@extends('layouts.marketplace')

@section('title', 'Sign In — Trade4Deal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card card-t4d">
                    <div class="card-header text-center py-4">
                        <h4 class="fw-bold mb-1">Welcome back</h4>
                        <p class="text-muted small mb-0">Sign in to your Trade4Deal account</p>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success small">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small" for="remember">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">Forgot password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary-t4d text-white w-100 py-2">Sign In</button>

                            <p class="text-center text-muted small mt-3 mb-0">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-medium">Register free</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
