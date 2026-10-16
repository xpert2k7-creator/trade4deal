<?php

namespace App\Domains\Seller\Services;

use App\Models\User;
use App\Support\Storage\PublicFileTransaction;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateSellerProfile
{
    public function __construct(private PublicFileTransaction $files) {}

    public function execute(User $seller, FormRequest $request): void
    {
        try {
            $this->files->run(function () use ($seller, $request): void {
                $data = $request->safe()->except(['logo', 'cover_image', 'remove_logo', 'remove_cover']);
                foreach ([['logo', 'logo_path', 'remove_logo', 'sellers/logos'],
                    ['cover_image', 'cover_image_path', 'remove_cover', 'sellers/covers']] as [$field, $column, $remove, $directory]) {
                    if ($request->hasFile($field)) {
                        $data[$column] = $this->files->store($request->file($field), $directory, $field);
                        $this->files->removeAfterCommit($seller->{$column});
                    } elseif ($request->boolean($remove)) {
                        $data[$column] = null;
                        $this->files->removeAfterCommit($seller->{$column});
                    }
                }
                $seller->fill($data);
                if ($seller->isDirty('email')) {
                    $seller->email_verified_at = null;
                }
                $seller->save();
                $seller->ensureSellerSlug();
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->getMessage(), 'phone_normalized')) {
                throw ValidationException::withMessages(['phone' => 'This mobile number is already registered.']);
            }
            throw $exception;
        }
    }
}
