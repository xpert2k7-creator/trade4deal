<?php

declare(strict_types=1);

namespace Tests\Unit\Lead;

use App\Domains\Lead\Models\Lead;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LeadLocationLabelTest extends TestCase
{
    #[DataProvider('locationProvider')]
    public function test_location_label_ignores_placeholder_city_values(
        ?string $city,
        ?string $country,
        string $expected,
    ): void {
        $lead = new Lead([
            'city' => $city,
            'country' => $country,
        ]);

        $this->assertSame($expected, $lead->locationLabel());
    }

    /**
     * @return array<string, array{0: ?string, 1: ?string, 2: string}>
     */
    public static function locationProvider(): array
    {
        return [
            'city and country' => ['Muscat', 'Oman', 'Muscat, Oman'],
            'dash city' => ['-', 'Oman', 'Oman'],
            'em dash city' => ['—', 'Oman', 'Oman'],
            'na city' => ['N/A', 'Oman', 'Oman'],
            'empty city' => ['', 'Oman', 'Oman'],
            'null city' => [null, 'Oman', 'Oman'],
            'both missing' => [null, null, 'Global'],
            'both placeholders' => ['-', '-', 'Global'],
        ];
    }
}
