<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Product\Models\Product;
use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use App\Support\Location;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplacePageController extends Controller
{
    public function show(string $page, Request $request, LeadService $leadService): View
    {
        $pages = $this->pages();

        abort_unless(array_key_exists($page, $pages), 404);

        if ($page === 'privacy-policy') {
            return view('marketplace.privacy-policy', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'terms-of-use') {
            return view('marketplace.terms-of-use', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'about-us') {
            return view('marketplace.about-us', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'help') {
            return view('marketplace.help', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'feedback') {
            return view('marketplace.feedback', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'customer-care') {
            return view('marketplace.customer-care', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'live-leads') {
            $viewer = auth()->user();
            $perPage = min(60, max(12, (int) $request->query('per_page', 24)));

            return view('marketplace.live-leads', [
                'page' => $pages[$page],
                'leads' => $leadService->paginateLeadsForViewer($viewer, $perPage),
                'viewerPlan' => $leadService->viewerPlan($viewer),
                'isStaffViewer' => $viewer?->canModerateLeads() ?? false,
            ]);
        }

        if ($page === 'sell-on-trade4deal') {
            return view('marketplace.sell-on-trade4deal', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'latest-trade-leads') {
            return view('marketplace.latest-trade-leads', [
                'page' => $pages[$page],
            ]);
        }

        if ($page === 'product-directory') {
            $categories = collect(ProductType::cases());
            $filters = [
                'search' => mb_substr(trim((string) $request->query('search', '')), 0, 120),
                'category' => (string) $request->query('category', ''),
                'location_id' => mb_substr(trim((string) $request->query('location_id', '')), 0, 191),
                'location_label' => mb_substr(trim((string) $request->query('location_label', '')), 0, 180),
            ];

            if (! Location::isValidId($filters['location_id'])) {
                $filters['location_id'] = '';
                $filters['location_label'] = '';
            }

            $publicSellerScope = function ($query): void {
                $query->where('user_type', UserType::Seller)
                    ->where('status', RecordStatus::Active)
                    ->where('is_public', true)
                    ->whereNotNull('slug');
            };

            $productsQuery = Product::query()
                ->with('user:id,company_name,slug,city,state,country,user_type,status,is_public')
                ->where('status', RecordStatus::Active)
                ->whereHas('user', $publicSellerScope);

            if ($filters['search'] !== '') {
                $search = $filters['search'];
                $productsQuery->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('location_city', 'like', '%'.$search.'%')
                        ->orWhere('location_state', 'like', '%'.$search.'%')
                        ->orWhere('location_country', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($sellerQuery) use ($search): void {
                            $sellerQuery->where('company_name', 'like', '%'.$search.'%');
                        });
                });
            }

            if (ProductType::tryFrom($filters['category']) !== null) {
                $productsQuery->where('product_type', $filters['category']);
            }

            if ($filters['location_id'] !== '') {
                $productsQuery->where(function ($query) use ($filters): void {
                    $query->where('location_id', $filters['location_id'])
                        ->orWhereNull('location_id');
                });
            }

            $locationOptions = Product::query()
                ->whereNotNull('location_id')
                ->whereNotNull('location_city')
                ->whereNotNull('location_country')
                ->select('location_id', 'location_city', 'location_state', 'location_country')
                ->distinct()
                ->orderBy('location_city')
                ->get()
                ->map(fn (Product $product): array => [
                    'id' => $product->location_id,
                    'label' => $product->locationLabel(),
                ])
                ->sortBy('label')
                ->values();

            if ($filters['location_id'] !== '') {
                $matchedLocation = $locationOptions->firstWhere('id', $filters['location_id']);
                if ($matchedLocation !== null) {
                    $filters['location_label'] = $matchedLocation['label'];
                } elseif ($filters['location_label'] === '') {
                    $filters['location_label'] = 'selected location';
                }
            }

            $products = $productsQuery
                ->latest()
                ->paginate(30)
                ->withQueryString();

            return view('marketplace.product-directory', [
                'page' => $pages[$page],
                'products' => $products,
                'categories' => $categories,
                'locationOptions' => $locationOptions,
                'filters' => $filters,
            ]);
        }

        if ($page === 'lead-board') {
            return view('marketplace.lead-board', [
                'page' => $pages[$page],
            ]);
        }

        return view('marketplace.page', [
            'page' => $pages[$page],
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function pages(): array
    {
        $defaults = [
            'kpis' => [
                ['value' => 'B2B', 'label' => 'Marketplace Focus'],
                ['value' => '24/7', 'label' => 'Lead Access'],
                ['value' => 'Global', 'label' => 'Trade Reach'],
            ],
            'cta_label' => 'Join Trade4Deal',
            'cta_route' => 'register',
            'secondary_label' => 'Submit Requirement',
            'secondary_target' => '#leadModal',
        ];

        $pages = [
            'about-us' => [
                'title' => 'About Trade4Deal',
                'eyebrow' => 'Company',
                'icon' => 'bi-buildings',
                'summary' => 'Trade4Deal is a global B2B marketplace built to help buyers discover suppliers, suppliers showcase products, and businesses convert trade interest into real conversations.',
                'sections' => [
                    ['title' => 'Built for practical B2B trade', 'body' => 'Trade4Deal keeps buyer requirements, seller profiles, product listings, and enquiries in one focused marketplace experience.'],
                    ['title' => 'For buyers and suppliers', 'body' => 'Buyers can explore categories and submit requirements. Suppliers can publish products, review leads, and connect with relevant business opportunities.'],
                    ['title' => 'Trade-first experience', 'body' => 'Every page is designed around sourcing clarity: product type, company details, payment preferences, location, and direct contact actions.'],
                ],
            ],
            'help' => [
                'title' => 'Trade4Deal Help',
                'eyebrow' => 'Help & Support',
                'icon' => 'bi-life-preserver',
                'summary' => 'Find quick guidance for using Trade4Deal as a buyer, supplier, or marketplace member.',
                'sections' => [
                    ['title' => 'Create your account', 'body' => 'Register as a buyer or seller, verify your email, and complete your profile so other businesses can trust your details.'],
                    ['title' => 'Browse marketplace data', 'body' => 'Use categories, product listings, and live business leads to find relevant trade opportunities.'],
                    ['title' => 'Contact support', 'body' => 'For account, lead, or listing questions, reach the Trade4Deal team through the contact page.'],
                ],
                'secondary_label' => 'Contact Support',
                'secondary_route' => 'contact',
            ],
            'feedback' => [
                'title' => 'Share Feedback',
                'eyebrow' => 'Help & Support',
                'icon' => 'bi-chat-square-text',
                'summary' => 'Tell Trade4Deal what can be improved across leads, seller profiles, product discovery, and marketplace workflows.',
                'sections' => [
                    ['title' => 'Product feedback', 'body' => 'Share suggestions about search, category pages, lead cards, seller profiles, and enquiry flows.'],
                    ['title' => 'Business feedback', 'body' => 'Tell us what buyers or suppliers need before making a trade decision.'],
                    ['title' => 'Support feedback', 'body' => 'Help us improve response quality for onboarding, customer care, and marketplace assistance.'],
                ],
                'cta_label' => 'Send Feedback',
                'cta_route' => 'contact',
            ],
            'customer-care' => [
                'title' => 'Customer Care',
                'eyebrow' => 'Help & Support',
                'icon' => 'bi-headset',
                'summary' => 'Get help with buyer requirements, seller onboarding, profile updates, product listings, and lead visibility.',
                'sections' => [
                    ['title' => 'Buyer support', 'body' => 'Get assistance with submitting requirements, browsing suppliers, and contacting marketplace businesses.'],
                    ['title' => 'Seller support', 'body' => 'Get help creating a storefront, adding products, and understanding Trade4Deal lead access.'],
                    ['title' => 'Account support', 'body' => 'Contact Trade4Deal for login, verification, plan, and profile questions.'],
                ],
                'cta_label' => 'Contact Customer Care',
                'cta_route' => 'contact',
            ],
            'live-leads' => [
                'title' => 'Live Business Leads',
                'eyebrow' => 'Marketplace Leads',
                'icon' => 'bi-broadcast',
                'summary' => 'Discover active buyer and supplier opportunities published on Trade4Deal after review.',
                'sections' => [
                    ['title' => 'Fresh business opportunities', 'body' => 'Live leads include product interest, country, category, trade role, payment method, and contact options.'],
                    ['title' => 'Plan-based visibility', 'body' => 'Gold members can see new leads instantly. Free accounts see eligible leads after the configured visibility delay.'],
                    ['title' => 'Direct action', 'body' => 'Each lead page gives visitors a clear way to view details and contact the business.'],
                ],
                'cta_label' => 'View Leads',
                'cta_route' => 'home',
                'cta_fragment' => 'leads',
            ],
            'sell-on-trade4deal' => [
                'title' => 'Sell on Trade4Deal',
                'eyebrow' => 'Suppliers Tool Kit',
                'icon' => 'bi-shop-window',
                'summary' => 'Create a public seller profile, add products, and make your business easier for buyers to discover.',
                'sections' => [
                    ['title' => 'Public storefront', 'body' => 'Show company details, industries, location, contact options, and product listings in one profile.'],
                    ['title' => 'Product catalog', 'body' => 'Add product names, categories, pricing range, units, and images to support buyer evaluation.'],
                    ['title' => 'Lead access', 'body' => 'Use the lead board to spot relevant buyer requirements and start conversations.'],
                ],
            ],
            'latest-trade-leads' => [
                'title' => 'Latest Trade Leads',
                'eyebrow' => 'Suppliers Tool Kit',
                'icon' => 'bi-lightning-charge',
                'summary' => 'Review newly published Trade4Deal leads and respond to relevant buyer requirements.',
                'sections' => [
                    ['title' => 'Lead cards', 'body' => 'Each lead card highlights the product, company, category, country, trade role, and payment method.'],
                    ['title' => 'Supplier workflow', 'body' => 'Shortlist the right opportunities, open the lead detail, and contact the business with context.'],
                    ['title' => 'Upgrade when timing matters', 'body' => 'Gold access helps suppliers view fresh opportunities as soon as they go live.'],
                ],
                'cta_label' => 'Browse Latest Leads',
                'cta_route' => 'home',
                'cta_fragment' => 'leads',
            ],
            'product-directory' => [
                'title' => 'Product Directory',
                'eyebrow' => 'Suppliers Tool Kit',
                'icon' => 'bi-grid-3x3-gap',
                'summary' => 'Explore Trade4Deal product categories and seller catalogs across industrial, agricultural, electronics, textile, construction, medical, and other sectors.',
                'sections' => [
                    ['title' => 'Category browsing', 'body' => 'Products are grouped by marketplace categories to help buyers scan faster.'],
                    ['title' => 'Seller context', 'body' => 'Product cards connect back to public seller profiles so buyers can review the business behind the listing.'],
                    ['title' => 'Trade details', 'body' => 'Pricing labels, units, images, and product categories support quick comparison.'],
                ],
                'cta_label' => 'Open Directory',
                'cta_route' => 'home',
                'cta_fragment' => 'category-products',
            ],
            'lead-board' => [
                'title' => 'Lead Board',
                'eyebrow' => 'Suppliers Tool Kit',
                'icon' => 'bi-kanban',
                'summary' => 'Use the Trade4Deal lead board to scan opportunities, identify matching product needs, and connect with interested companies.',
                'sections' => [
                    ['title' => 'Scan faster', 'body' => 'Grid cards make it easier to compare products, countries, categories, and payment terms.'],
                    ['title' => 'Open details', 'body' => 'Each opportunity has a detail page with contact action and business information.'],
                    ['title' => 'Find matches', 'body' => 'Suppliers can compare leads with their industries and product catalog.'],
                ],
                'cta_label' => 'View Lead Board',
                'cta_route' => 'home',
                'cta_fragment' => 'leads',
            ],
            'submit-requirement' => [
                'title' => 'Submit Requirement',
                'eyebrow' => 'Buyers Tool Kit',
                'icon' => 'bi-send',
                'summary' => 'Share your product requirement with Trade4Deal so suitable suppliers can discover and respond to your business need.',
                'sections' => [
                    ['title' => 'Add clear product details', 'body' => 'Mention the product, category, units, payment preference, country, and message.'],
                    ['title' => 'Review process', 'body' => 'Submitted requirements are reviewed before becoming visible as marketplace leads.'],
                    ['title' => 'Better supplier responses', 'body' => 'Detailed requirements help suppliers understand your need and respond with relevant information.'],
                ],
                'cta_label' => 'Submit Requirement',
                'cta_target' => '#leadModal',
            ],
            'search-products' => [
                'title' => 'Search Products',
                'eyebrow' => 'Buyers Tool Kit',
                'icon' => 'bi-search',
                'summary' => 'Search Trade4Deal products, categories, and seller companies from the marketplace homepage.',
                'sections' => [
                    ['title' => 'Marketplace search', 'body' => 'Use product names, category labels, or seller company names to jump to relevant listings.'],
                    ['title' => 'Category sections', 'body' => 'Browse category blocks when you want to compare related products side by side.'],
                    ['title' => 'Supplier profiles', 'body' => 'Open a product card to reach the seller profile and start an enquiry.'],
                ],
                'cta_label' => 'Search Products',
                'cta_route' => 'home',
                'cta_fragment' => 'category-products',
            ],
            'payment-safety' => [
                'title' => 'Payment Safety',
                'eyebrow' => 'Buyers Tool Kit',
                'icon' => 'bi-shield-check',
                'summary' => 'Trade4Deal encourages clear payment terms, verified business details, and careful communication before any transaction.',
                'sections' => [
                    ['title' => 'Check business details', 'body' => 'Review seller profile information, contact details, location, and product information before proceeding.'],
                    ['title' => 'Use clear terms', 'body' => 'Discuss currency, units, quantity, payment method, delivery, and documentation before finalizing.'],
                    ['title' => 'Stay cautious', 'body' => 'Avoid sending sensitive payment data until you have verified the business and agreed terms through trusted channels.'],
                ],
                'cta_label' => 'View Plans',
                'cta_route' => 'plans.index',
            ],
            'seller-verification' => [
                'title' => 'Seller Verification',
                'eyebrow' => 'Buyers Tool Kit',
                'icon' => 'bi-patch-check',
                'summary' => 'Trade4Deal seller profiles help buyers evaluate company information, public storefront details, and product listings before contact.',
                'sections' => [
                    ['title' => 'Profile completeness', 'body' => 'Complete seller details such as company name, location, industries, logo, and description build buyer confidence.'],
                    ['title' => 'Public visibility', 'body' => 'Only public active seller profiles with slugs appear in marketplace product discovery.'],
                    ['title' => 'Buyer checks', 'body' => 'Buyers should review the seller profile and confirm business details before placing orders.'],
                ],
                'cta_label' => 'Join as Seller',
                'cta_route' => 'register',
            ],
            'global-buyers' => [
                'title' => 'Global Buyers',
                'eyebrow' => 'Trade4Deal Coverage',
                'icon' => 'bi-globe2',
                'summary' => 'Trade4Deal supports buyer discovery across international markets and business categories.',
                'sections' => [
                    ['title' => 'Buyer requirements', 'body' => 'Buyers can publish product needs with category, country, units, and payment preferences.'],
                    ['title' => 'Supplier discovery', 'body' => 'Suppliers can review visible buyer requirements and respond where they match capability.'],
                    ['title' => 'Global reach', 'body' => 'The marketplace is designed for cross-border B2B discovery and lead generation.'],
                ],
            ],
            'verified-suppliers' => [
                'title' => 'Verified Suppliers',
                'eyebrow' => 'Trade4Deal Coverage',
                'icon' => 'bi-award',
                'summary' => 'Trade4Deal gives suppliers a structured profile to present their business, products, and contact information.',
                'sections' => [
                    ['title' => 'Seller profiles', 'body' => 'Public profiles show company identity, location, industries, and products.'],
                    ['title' => 'Product listings', 'body' => 'Active seller products appear in marketplace categories when the seller profile is public.'],
                    ['title' => 'Trust signals', 'body' => 'Consistent business details, product images, and complete profiles help buyers evaluate suppliers.'],
                ],
            ],
            'marketplace-leads' => [
                'title' => 'Marketplace Leads',
                'eyebrow' => 'Trade4Deal Coverage',
                'icon' => 'bi-megaphone',
                'summary' => 'Marketplace leads connect product requirements with suppliers who can respond to relevant opportunities.',
                'sections' => [
                    ['title' => 'Structured details', 'body' => 'Leads include product interest, business type, category, payment methods, country, and message.'],
                    ['title' => 'Moderated publishing', 'body' => 'Leads are reviewed before they appear publicly in the marketplace.'],
                    ['title' => 'Easy contact', 'body' => 'Lead detail pages provide a focused contact action for business follow-up.'],
                ],
                'cta_label' => 'View Marketplace Leads',
                'cta_route' => 'home',
                'cta_fragment' => 'leads',
            ],
            'categories' => [
                'title' => 'Trade4Deal Categories',
                'eyebrow' => 'Business Tools',
                'icon' => 'bi-tags',
                'summary' => 'Explore marketplace categories that organize products and leads for faster sourcing decisions.',
                'sections' => [
                    ['title' => 'Industrial and machinery', 'body' => 'Find equipment, manufacturing inputs, and industrial trade requirements.'],
                    ['title' => 'Electronics, textiles, agriculture', 'body' => 'Browse high-demand categories used by buyers and sellers across Trade4Deal.'],
                    ['title' => 'Construction, medical, food, and more', 'body' => 'Use category filters to discover relevant leads and supplier products.'],
                ],
                'cta_label' => 'Browse Categories',
                'cta_route' => 'home',
                'cta_fragment' => 'categories',
            ],
            'enquiries' => [
                'title' => 'Trade Enquiries',
                'eyebrow' => 'Business Tools',
                'icon' => 'bi-chat-dots',
                'summary' => 'Trade4Deal enquiry flows help buyers and suppliers begin a focused business conversation from a lead or seller profile.',
                'sections' => [
                    ['title' => 'Lead enquiries', 'body' => 'Contact a published lead when your business can support the requirement.'],
                    ['title' => 'Seller enquiries', 'body' => 'Contact public seller profiles to ask about products, pricing, samples, or capability.'],
                    ['title' => 'Useful context', 'body' => 'Enquiries work best when you include product, quantity, delivery, and payment expectations.'],
                ],
                'cta_label' => 'Start an Enquiry',
                'cta_target' => '#leadModal',
            ],
            'terms-of-use' => [
                'title' => 'Terms of Use',
                'eyebrow' => 'Legal',
                'icon' => 'bi-file-earmark-text',
                'summary' => 'These marketplace terms explain how Trade4Deal should be used by buyers, sellers, and visitors.',
                'sections' => [
                    ['title' => 'Marketplace use', 'body' => 'Use Trade4Deal for lawful B2B discovery, product listing, lead submission, and business communication.'],
                    ['title' => 'User responsibility', 'body' => 'Users are responsible for the accuracy of their company, product, requirement, and contact details.'],
                    ['title' => 'Business decisions', 'body' => 'Buyers and sellers should verify details independently before entering commercial agreements.'],
                ],
                'cta_label' => 'Contact Trade4Deal',
                'cta_route' => 'contact',
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'eyebrow' => 'Legal',
                'icon' => 'bi-lock',
                'summary' => 'Trade4Deal uses business information to support account access, marketplace listings, leads, enquiries, and customer support.',
                'sections' => [
                    ['title' => 'Information we use', 'body' => 'Account, company, contact, product, and lead information may be used to operate the marketplace.'],
                    ['title' => 'Marketplace visibility', 'body' => 'Public seller profiles and approved leads may be visible to visitors according to platform settings.'],
                    ['title' => 'Support communication', 'body' => 'Contact details may be used to respond to enquiries, support requests, and marketplace communications.'],
                ],
                'cta_label' => 'Contact Support',
                'cta_route' => 'contact',
            ],
        ];

        return array_map(
            fn (array $page): array => $page + $defaults,
            $pages,
        );
    }
}

