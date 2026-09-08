<?php

declare(strict_types=1);

namespace App\Domains\Auth\DTOs;

use App\Support\Enums\UserType;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone,
        public string $companyName,
        public string $country,
        public UserType $userType,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            phone: $data['phone'] ?? null,
            companyName: $data['company_name'],
            country: $data['country'],
            userType: UserType::from($data['user_type']),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'company_name' => $this->companyName,
            'country' => $this->country,
            'user_type' => $this->userType,
        ];
    }
}
