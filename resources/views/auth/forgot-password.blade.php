<x-guest-layout>
    <h4 class="fw-bold mb-2">Forgot password</h4>
    <p class="text-muted small mb-3">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="d-grid">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    <p class="text-center text-muted small mt-3 mb-0">
        <a href="{{ route('login') }}" class="text-primary text-decoration-none">← Back to sign in</a>
    </p>
</x-guest-layout>
