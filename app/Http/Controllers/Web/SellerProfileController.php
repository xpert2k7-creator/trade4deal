<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Seller\Notifications\SellerEnquiryNotification;
use App\Domains\Seller\Requests\ContactSellerRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class SellerProfileController extends Controller
{
    public function show(string $slug): View
    {
        $seller = User::query()
            ->where('slug', $slug)
            ->where('user_type', UserType::Seller)
            ->where('status', RecordStatus::Active)
            ->where('is_public', true)
            ->firstOrFail();

        $products = $seller->liveProducts()->paginate(8)->withQueryString()->fragment('products');

        return view('marketplace.sellers.show', [
            'seller' => $seller,
            'products' => $products,
        ]);
    }

    public function contact(ContactSellerRequest $request, string $slug): RedirectResponse
    {
        $seller = User::query()
            ->where('slug', $slug)
            ->where('user_type', UserType::Seller)
            ->where('status', RecordStatus::Active)
            ->where('is_public', true)
            ->firstOrFail();

        Notification::route('mail', $seller->email)
            ->notify(new SellerEnquiryNotification($seller, $request->validated()));

        return redirect()
            ->route('sellers.show', $seller->slug)
            ->with('success', 'Your enquiry has been sent to the seller. They can reply by email.');
    }
}
