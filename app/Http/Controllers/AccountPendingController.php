<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Enums\RecordStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountPendingController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);

        if (! $user->isSeller()) {
            return redirect()->route('dashboard');
        }

        if ($user->status === RecordStatus::Active) {
            return redirect()->route('seller.dashboard');
        }

        return view('account.pending', [
            'user' => $user,
        ]);
    }
}
