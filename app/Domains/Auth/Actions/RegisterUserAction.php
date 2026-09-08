<?php

declare(strict_types=1);

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Domains\Auth\Services\AuthService;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterUserAction
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function execute(RegisterUserDTO $dto): User
    {
        $user = $this->authService->registerUser($dto);

        event(new Registered($user));

        return $user;
    }
}
