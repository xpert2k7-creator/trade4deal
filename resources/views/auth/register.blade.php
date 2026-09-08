@extends('layouts.marketplace')

@section('title', 'Register — Trade4Deal')

@section('content')
<style>
    .register-panel {
        background: linear-gradient(160deg, #0B3A6E 0%, #082F58 55%, #0E7490 140%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        color: #fff;
        overflow: hidden;
    }

    .register-panel .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.14);
        color: #fff;
    }

    .register-panel .card-header h4,
    .register-panel .form-label {
        color: #FFFFFF !important;
    }

    .register-panel .card-header .text-muted,
    .register-panel .text-muted {
        color: rgba(255, 255, 255, 0.72) !important;
    }

    .register-panel a {
        color: #A5F3FC !important;
    }

    .register-panel .form-control,
    .register-panel .form-select {
        background-color: rgba(255, 255, 255, 0.12) !important;
        border-color: rgba(255, 255, 255, 0.28) !important;
        color: #FFFFFF !important;
        -webkit-text-fill-color: #FFFFFF;
        caret-color: #FFFFFF;
    }

    .register-panel .form-control::placeholder,
    .register-panel .form-select::placeholder {
        color: rgba(255, 255, 255, 0.55) !important;
    }

    .register-panel .form-control:hover,
    .register-panel .form-select:hover,
    .register-panel .form-control:focus,
    .register-panel .form-select:focus {
        background-color: rgba(255, 255, 255, 0.18) !important;
        border-color: #FFFFFF !important;
        color: #FFFFFF !important;
        -webkit-text-fill-color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.18) !important;
    }

    .register-panel .form-select option {
        color: #0F172A;
        background: #fff;
    }

    .register-panel .btn-primary-t4d {
        background: #FFFFFF;
        color: #0B3A6E !important;
    }

    .register-panel .btn-primary-t4d:hover {
        background: #F1F5F9;
        color: #082F58 !important;
    }
</style>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card register-panel">
                    <div class="card-header text-center py-4">
                        <h4 class="fw-bold mb-1">Create your Trade4Deal account</h4>
                        <p class="text-muted small mb-0">Join the global B2B marketplace as a buyer or seller</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Business Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="company_name" class="form-label fw-medium">Company Name</label>
                                <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror"
                                       name="company_name" value="{{ old('company_name') }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label fw-medium">Country</label>
                                    <input id="country" type="text" class="form-control @error('country') is-invalid @enderror"
                                           name="country" value="{{ old('country') }}" required>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-medium">Phone</label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="user_type" class="form-label fw-medium">Account Type</label>
                                <select id="user_type" name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                    <option value="">Select account type</option>
                                    <option value="buyer" @selected(old('user_type') === 'buyer')>Buyer — I want to source products</option>
                                    <option value="seller" @selected(old('user_type') === 'seller')>Seller — I want to sell products</option>
                                </select>
                                @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control"
                                       name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary-t4d text-white w-100 py-2">
                                Create Account
                            </button>

                            <p class="text-center text-muted small mt-3 mb-0">
                                Already registered?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Sign in</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
