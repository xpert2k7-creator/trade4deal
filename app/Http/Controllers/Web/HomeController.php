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
        $perPage = min(50, max(5, (int) $request->input('per_page', 10)));

        $leads = $leadService->paginateLeadsForViewer($viewer, $perPage);
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

        $productsByCategory = (clone $publicProductQuery)
            ->latest()
            ->limit(72)
            ->get()
            ->groupBy(fn (Product $product): string => $product->product_type?->value ?? 'other');

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
            'perPage',
            'categories',
            'productsByCategory',
            'marketplaceStats',
        ));
    }
}
