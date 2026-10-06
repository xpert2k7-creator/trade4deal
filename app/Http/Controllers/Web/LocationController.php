<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Support\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
        ]);

        $query = trim((string) ($data['q'] ?? ''));

        if ($query !== '' && mb_strlen($query) < 2) {
            return response()->json(['locations' => []]);
        }

        $cacheKey = 'locations.search.v3.'.sha1(mb_strtolower($query));

        $locations = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query): array {
            $local = Product::query()
                ->whereNotNull('location_id')
                ->whereNotNull('location_city')
                ->whereNotNull('location_country')
                ->when($query !== '', function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('location_city', 'like', '%'.$query.'%')
                            ->orWhere('location_state', 'like', '%'.$query.'%')
                            ->orWhere('location_country', 'like', '%'.$query.'%');
                    });
                })
                ->select('location_id', 'location_city', 'location_state', 'location_country')
                ->distinct()
                ->orderBy('location_city')
                ->limit(12)
                ->get()
                ->map(fn (Product $product): array => $this->locationPayload(
                    $product->location_city,
                    $product->location_state,
                    $product->location_country,
                    'local',
                    $product->location_id,
                ))
                ->filter(fn (array $location): bool => $location['id'] !== null)
                ->values()
                ->all();

            return collect($local)
                ->merge($this->curatedSearch($query))
                ->merge($this->providerSearch($query))
                ->unique('id')
                ->take(12)
                ->values()
                ->all();
        });

        return response()->json(['locations' => $locations]);
    }

    public function reverse(Request $request): JsonResponse
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if (config('services.location_geocoder.provider') !== 'nominatim') {
            return response()->json([
                'message' => 'Reverse geocoding provider is not configured.',
            ], 503);
        }

        $lat = round((float) $data['lat'], 3);
        $lng = round((float) $data['lng'], 3);
        $cacheKey = 'locations.reverse.'.sha1($lat.','.$lng);

        $location = Cache::remember($cacheKey, now()->addDays(7), function () use ($lat, $lng): ?array {
            $response = $this->geocoderRequest('reverse', [
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'zoom' => 10,
            ]);

            if (! $response?->ok()) {
                return null;
            }

            return $this->payloadFromAddress((array) $response->json('address', []), 'detected');
        });

        if ($location === null) {
            return response()->json(['message' => 'Location could not be resolved.'], 422);
        }

        return response()->json(['location' => $location]);
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function curatedSearch(string $query): array
    {
        $normalizedQuery = mb_strtolower($query);

        return collect($this->curatedLocations())
            ->filter(function (array $location) use ($normalizedQuery): bool {
                if ($normalizedQuery === '') {
                    return true;
                }

                return str_contains($this->curatedSearchText($location), $normalizedQuery);
            })
            ->map(fn (array $location): array => $this->locationPayload(
                $location['city'],
                $location['state'],
                $location['country'],
                'curated',
            ))
            ->values()
            ->all();
    }

    /**
     * @param  array{city: string, state: string, country: string}  $location
     */
    private function curatedSearchText(array $location): string
    {
        $aliases = match ($location['country']) {
            'United Arab Emirates' => ' uae emirates',
            'United States' => ' usa us america',
            'United Kingdom' => ' uk britain england',
            'Saudi Arabia' => ' ksa',
            'South Korea' => ' korea',
            'Hong Kong' => ' china',
            default => '',
        };

        return mb_strtolower(Location::label(
            $location['city'],
            $location['state'],
            $location['country'],
        ).$aliases);
    }

    /**
     * @return array<int, array{city: string, state: string, country: string}>
     */
    private function curatedLocations(): array
    {
        return [
            ['city' => 'Delhi', 'state' => 'Delhi', 'country' => 'India'],
            ['city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India'],
            ['city' => 'Noida', 'state' => 'Uttar Pradesh', 'country' => 'India'],
            ['city' => 'Greater Noida', 'state' => 'Uttar Pradesh', 'country' => 'India'],
            ['city' => 'Gurugram', 'state' => 'Haryana', 'country' => 'India'],
            ['city' => 'Ghaziabad', 'state' => 'Uttar Pradesh', 'country' => 'India'],
            ['city' => 'Faridabad', 'state' => 'Haryana', 'country' => 'India'],
            ['city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India'],
            ['city' => 'Pune', 'state' => 'Maharashtra', 'country' => 'India'],
            ['city' => 'Bengaluru', 'state' => 'Karnataka', 'country' => 'India'],
            ['city' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India'],
            ['city' => 'Chennai', 'state' => 'Tamil Nadu', 'country' => 'India'],
            ['city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India'],
            ['city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India'],
            ['city' => 'Surat', 'state' => 'Gujarat', 'country' => 'India'],
            ['city' => 'Jaipur', 'state' => 'Rajasthan', 'country' => 'India'],
            ['city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'country' => 'India'],
            ['city' => 'Kanpur', 'state' => 'Uttar Pradesh', 'country' => 'India'],
            ['city' => 'Nagpur', 'state' => 'Maharashtra', 'country' => 'India'],
            ['city' => 'Indore', 'state' => 'Madhya Pradesh', 'country' => 'India'],
            ['city' => 'Bhopal', 'state' => 'Madhya Pradesh', 'country' => 'India'],
            ['city' => 'Chandigarh', 'state' => 'Chandigarh', 'country' => 'India'],
            ['city' => 'Dubai', 'state' => 'Dubai', 'country' => 'United Arab Emirates'],
            ['city' => 'Abu Dhabi', 'state' => 'Abu Dhabi', 'country' => 'United Arab Emirates'],
            ['city' => 'Sharjah', 'state' => 'Sharjah', 'country' => 'United Arab Emirates'],
            ['city' => 'Riyadh', 'state' => 'Riyadh Province', 'country' => 'Saudi Arabia'],
            ['city' => 'Jeddah', 'state' => 'Makkah Province', 'country' => 'Saudi Arabia'],
            ['city' => 'Doha', 'state' => 'Doha', 'country' => 'Qatar'],
            ['city' => 'Kuwait City', 'state' => 'Al Asimah', 'country' => 'Kuwait'],
            ['city' => 'Muscat', 'state' => 'Muscat', 'country' => 'Oman'],
            ['city' => 'Manama', 'state' => 'Capital Governorate', 'country' => 'Bahrain'],
            ['city' => 'London', 'state' => 'England', 'country' => 'United Kingdom'],
            ['city' => 'Manchester', 'state' => 'England', 'country' => 'United Kingdom'],
            ['city' => 'Birmingham', 'state' => 'England', 'country' => 'United Kingdom'],
            ['city' => 'Paris', 'state' => 'Ile-de-France', 'country' => 'France'],
            ['city' => 'Berlin', 'state' => 'Berlin', 'country' => 'Germany'],
            ['city' => 'Hamburg', 'state' => 'Hamburg', 'country' => 'Germany'],
            ['city' => 'Frankfurt', 'state' => 'Hesse', 'country' => 'Germany'],
            ['city' => 'Amsterdam', 'state' => 'North Holland', 'country' => 'Netherlands'],
            ['city' => 'Rotterdam', 'state' => 'South Holland', 'country' => 'Netherlands'],
            ['city' => 'Milan', 'state' => 'Lombardy', 'country' => 'Italy'],
            ['city' => 'Rome', 'state' => 'Lazio', 'country' => 'Italy'],
            ['city' => 'Madrid', 'state' => 'Community of Madrid', 'country' => 'Spain'],
            ['city' => 'Barcelona', 'state' => 'Catalonia', 'country' => 'Spain'],
            ['city' => 'Zurich', 'state' => 'Zurich', 'country' => 'Switzerland'],
            ['city' => 'Istanbul', 'state' => 'Istanbul', 'country' => 'Turkey'],
            ['city' => 'Moscow', 'state' => 'Moscow', 'country' => 'Russia'],
            ['city' => 'New York', 'state' => 'New York', 'country' => 'United States'],
            ['city' => 'Los Angeles', 'state' => 'California', 'country' => 'United States'],
            ['city' => 'Chicago', 'state' => 'Illinois', 'country' => 'United States'],
            ['city' => 'Houston', 'state' => 'Texas', 'country' => 'United States'],
            ['city' => 'San Francisco', 'state' => 'California', 'country' => 'United States'],
            ['city' => 'Dallas', 'state' => 'Texas', 'country' => 'United States'],
            ['city' => 'Atlanta', 'state' => 'Georgia', 'country' => 'United States'],
            ['city' => 'Miami', 'state' => 'Florida', 'country' => 'United States'],
            ['city' => 'Toronto', 'state' => 'Ontario', 'country' => 'Canada'],
            ['city' => 'Vancouver', 'state' => 'British Columbia', 'country' => 'Canada'],
            ['city' => 'Montreal', 'state' => 'Quebec', 'country' => 'Canada'],
            ['city' => 'Mexico City', 'state' => 'Mexico City', 'country' => 'Mexico'],
            ['city' => 'Sao Paulo', 'state' => 'Sao Paulo', 'country' => 'Brazil'],
            ['city' => 'Rio de Janeiro', 'state' => 'Rio de Janeiro', 'country' => 'Brazil'],
            ['city' => 'Buenos Aires', 'state' => 'Buenos Aires', 'country' => 'Argentina'],
            ['city' => 'Santiago', 'state' => 'Santiago Metropolitan', 'country' => 'Chile'],
            ['city' => 'Singapore', 'state' => 'Singapore', 'country' => 'Singapore'],
            ['city' => 'Hong Kong', 'state' => 'Hong Kong', 'country' => 'Hong Kong'],
            ['city' => 'Shanghai', 'state' => 'Shanghai', 'country' => 'China'],
            ['city' => 'Shenzhen', 'state' => 'Guangdong', 'country' => 'China'],
            ['city' => 'Guangzhou', 'state' => 'Guangdong', 'country' => 'China'],
            ['city' => 'Beijing', 'state' => 'Beijing', 'country' => 'China'],
            ['city' => 'Yiwu', 'state' => 'Zhejiang', 'country' => 'China'],
            ['city' => 'Tokyo', 'state' => 'Tokyo', 'country' => 'Japan'],
            ['city' => 'Osaka', 'state' => 'Osaka', 'country' => 'Japan'],
            ['city' => 'Seoul', 'state' => 'Seoul', 'country' => 'South Korea'],
            ['city' => 'Busan', 'state' => 'Busan', 'country' => 'South Korea'],
            ['city' => 'Bangkok', 'state' => 'Bangkok', 'country' => 'Thailand'],
            ['city' => 'Jakarta', 'state' => 'Jakarta', 'country' => 'Indonesia'],
            ['city' => 'Kuala Lumpur', 'state' => 'Kuala Lumpur', 'country' => 'Malaysia'],
            ['city' => 'Ho Chi Minh City', 'state' => 'Ho Chi Minh City', 'country' => 'Vietnam'],
            ['city' => 'Hanoi', 'state' => 'Hanoi', 'country' => 'Vietnam'],
            ['city' => 'Manila', 'state' => 'Metro Manila', 'country' => 'Philippines'],
            ['city' => 'Dhaka', 'state' => 'Dhaka', 'country' => 'Bangladesh'],
            ['city' => 'Chittagong', 'state' => 'Chittagong', 'country' => 'Bangladesh'],
            ['city' => 'Karachi', 'state' => 'Sindh', 'country' => 'Pakistan'],
            ['city' => 'Lahore', 'state' => 'Punjab', 'country' => 'Pakistan'],
            ['city' => 'Colombo', 'state' => 'Western Province', 'country' => 'Sri Lanka'],
            ['city' => 'Kathmandu', 'state' => 'Bagmati Province', 'country' => 'Nepal'],
            ['city' => 'Sydney', 'state' => 'New South Wales', 'country' => 'Australia'],
            ['city' => 'Melbourne', 'state' => 'Victoria', 'country' => 'Australia'],
            ['city' => 'Brisbane', 'state' => 'Queensland', 'country' => 'Australia'],
            ['city' => 'Auckland', 'state' => 'Auckland', 'country' => 'New Zealand'],
            ['city' => 'Johannesburg', 'state' => 'Gauteng', 'country' => 'South Africa'],
            ['city' => 'Cape Town', 'state' => 'Western Cape', 'country' => 'South Africa'],
            ['city' => 'Lagos', 'state' => 'Lagos', 'country' => 'Nigeria'],
            ['city' => 'Nairobi', 'state' => 'Nairobi County', 'country' => 'Kenya'],
            ['city' => 'Cairo', 'state' => 'Cairo Governorate', 'country' => 'Egypt'],
            ['city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'country' => 'Morocco'],
        ];
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function providerSearch(string $query): array
    {
        if ($query === '' || config('services.location_geocoder.provider') !== 'nominatim') {
            return [];
        }

        $response = $this->geocoderRequest('search', [
            'q' => $query,
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'limit' => 5,
        ]);

        if (! $response?->ok()) {
            return [];
        }

        return collect($response->json())
            ->map(fn (array $result): ?array => $this->payloadFromAddress((array) ($result['address'] ?? []), 'provider'))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $address
     * @return array<string, string|null>|null
     */
    private function payloadFromAddress(array $address, string $source): ?array
    {
        $city = $address['city']
            ?? $address['town']
            ?? $address['municipality']
            ?? $address['village']
            ?? $address['county']
            ?? null;
        $state = $address['state'] ?? $address['region'] ?? null;
        $country = $address['country'] ?? null;

        $payload = $this->locationPayload(
            is_string($city) ? $city : null,
            is_string($state) ? $state : null,
            is_string($country) ? $country : null,
            $source,
        );

        return $payload['id'] === null ? null : $payload;
    }

    /**
     * @return array<string, string|null>
     */
    private function locationPayload(?string $city, ?string $state, ?string $country, string $source, ?string $id = null): array
    {
        $id ??= Location::id($city, $state, $country);

        return [
            'id' => $id,
            'city' => Location::clean($city),
            'state' => Location::clean($state),
            'country' => Location::clean($country),
            'label' => Location::cityLabel($city),
            'full_label' => Location::label($city, $state, $country),
            'source' => $source,
        ];
    }

    /**
     * @param  array<string, mixed>  $query
     */
    private function geocoderRequest(string $path, array $query): ?\Illuminate\Http\Client\Response
    {
        $baseUrl = rtrim((string) config('services.location_geocoder.base_url'), '/');

        if ($baseUrl === '') {
            return null;
        }

        return Http::timeout(4)
            ->acceptJson()
            ->withHeaders([
                'User-Agent' => (string) config('app.name', 'Trade4Deal').' location lookup',
            ])
            ->get($baseUrl.'/'.$path, $query);
    }
}
