<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Notifications\ContactInquiryNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('marketplace.contact');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        $inquiry = $request->validated();

        Notification::route('mail', config('trade4deal.contact_email', 'info@trade4deal.com'))
            ->notify(new ContactInquiryNotification($inquiry));

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you. Your message has been sent to the Trade4Deal team.');
    }
}
