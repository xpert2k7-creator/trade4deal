<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Enums\RecordStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerIsApproved
{
    /**
     * Block non-Active sellers from the seller app. They can still log in
     * and see the account-pending page. Admins are not affected.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user !== null
            && $user->isSeller()
            && ! $user->isAdmin()
            && $user->status !== RecordStatus::Active
        ) {
            return redirect()->route('account.pending');
        }

        return $next($request);
    }
}
