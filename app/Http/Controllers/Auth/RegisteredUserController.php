<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\Actions\RegisterUserAction;
use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Domains\Auth\Requests\RegisterUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(
        RegisterUserRequest $request,
        RegisterUserAction $registerUserAction,
    ): RedirectResponse {
        $user = $registerUserAction->execute(
            RegisterUserDTO::fromArray($request->validated())
        );

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
