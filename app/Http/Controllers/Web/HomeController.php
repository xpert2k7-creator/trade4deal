<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Lead\Services\LeadService;
use App\Domains\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, LeadService $leadService): View
    {
        $viewer = auth()->user();
        $perPage = max(1, min(24, $request->integer('per_page', 10)));
        $leadSearch = mb_substr(trim((string) $request->query('lead_search', '')), 0, 120);
        $leads = $leadService->paginateLeadsForViewer(
            $viewer,
            $perPage,
            $leadSearch !== '' ? $leadSearch : null,
        );
        $viewerPlan = $leadService->viewerPlan($viewer);
        $isStaffViewer = $viewer?->canModerateLeads() ?? false;
        $categories = collect(ProductType::cases());
        $publicProductQuery = Product::query()
            ->with('user:id,company_name,slug,user_type,status,is_public')
            ->where('status', RecordStatus::Active)
            ->whereHas('user', function ($query): void {
                $query->where('user_type', UserType::Seller)
                    ->where('status', RecordStatus::Active)
                    ->where('is_public', true)
                    ->whereNotNull('slug');
            });

        // Load up to 12 newest public products per category so each slider
        // is filled when stock exists (instead of one global newest-72 pool).
        $perCategoryLimit = 12;
        $productsByCategory = $categories->mapWithKeys(
            function (ProductType $category) use ($publicProductQuery, $perCategoryLimit) {
                return [
                    $category->value => (clone $publicProductQuery)
                        ->where('product_type', $category->value)
                        ->latest()
                        ->limit($perCategoryLimit)
                        ->get(),
                ];
            }
        );

        $marketplaceStats = [
            'visible_leads' => $leads->total(),
            'categories' => $categories->count(),
            'products' => (clone $publicProductQuery)->count(),
            'sellers' => User::query()
                ->where('user_type', UserType::Seller)
                ->where('status', RecordStatus::Active)
                ->where('is_public', true)
                ->whereNotNull('slug')
                ->count(),
        ];

        return view('marketplace.home', compact(
            'leads',
            'viewerPlan',
            'isStaffViewer',
            'categories',
            'productsByCategory',
            'marketplaceStats',
            'leadSearch',
        ));
    }
}
