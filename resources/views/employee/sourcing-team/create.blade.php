@extends('layouts.employee')

@section('title', 'Create sourcing login')
@section('page-title', 'Create sourcing login')

@section('topbar-actions')
    <a href="{{ route('verification.sourcing-team.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-header">
                <h2>Account details</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('verification.sourcing-team.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email (login)</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary-t4d">Create login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
