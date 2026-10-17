<?php

use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-admin {email}', function () {
    $email = strtolower(trim($this->argument('email')));
    if (User::withTrashed()->where('email', $email)->exists()) {
        $this->error('An account already exists for this email. No changes made.');

        return 1;
    }

    $name = $this->ask('Administrator name');
    $password = $this->secret('Password (at least 12 characters)');
    $confirmation = $this->secret('Confirm password');
    $validator = Validator::make([
        'name' => $name, 'email' => $email, 'password' => $password,
        'password_confirmation' => $confirmation,
    ], ['name' => 'required|string|max:255', 'email' => 'required|email|max:255',
        'password' => 'required|string|min:12|confirmed']);
    if ($validator->fails()) {
        $this->error($validator->errors()->first());

        return 1;
    }

    DB::transaction(function () use ($name, $email, $password): void {
        Role::findOrCreate('admin', 'web');
        $user = User::create([
            'name' => $name, 'email' => $email, 'password' => $password,
            'user_type' => UserType::Admin,
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('admin');
    });
    $this->info('Administrator created. Sign in and verify the email address.');

    return 0;
})->purpose('Create an administrator interactively without default credentials');

Artisan::command('app:check-production', function () {
    $errors = [];
    if (! app()->environment('production') || config('app.debug')) {
        $errors[] = 'Set APP_ENV=production and APP_DEBUG=false.';
    }
    if (! config('session.secure')) {
        $errors[] = 'Set SESSION_SECURE_COOKIE=true for the HTTPS site.';
    }
    if (in_array(config('mail.default'), ['log', 'array'], true)) {
        $errors[] = 'Configure a delivery mailer for email verification.';
    }
    if (config('services.razorpay.key_id') &&
        (! str_starts_with(config('services.razorpay.key_id'), 'rzp_live_') ||
            ! config('services.razorpay.key_secret') || ! config('services.razorpay.webhook_secret'))) {
        $errors[] = 'Configure live Razorpay keys and a separate webhook signing secret for production.';
    }
    foreach ($errors as $error) {
        $this->error($error);
    }

    return $errors ? 1 : 0;
})->purpose('Validate production settings before activating a release');
