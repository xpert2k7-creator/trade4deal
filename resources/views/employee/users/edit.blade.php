@extends('layouts.employee')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('topbar-actions')
    <a href="{{ route('employee.users.index') }}" class="btn btn-sm btn-outline-secondary">Back to users</a>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('employee.users.update', $managedUser) }}">
            @csrf
            @method('PUT')

            <div class="panel mb-3">
                <div class="panel-header"><h2>Account</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $managedUser->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $managedUser->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $managedUser->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="user_type" class="form-label">Account type</label>
                            <select id="user_type" name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                @foreach ([\App\Support\Enums\UserType::Buyer, \App\Support\Enums\UserType::Seller] as $type)
                                    <option value="{{ $type->value }}" @selected(old('user_type', $managedUser->user_type?->value) === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="plan" class="form-label">Plan</label>
                            <select id="plan" name="plan" class="form-select @error('plan') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\UserPlan::cases() as $plan)
                                    <option value="{{ $plan->value }}" @selected(old('plan', $managedUser->plan?->value ?? 'free') === $plan->value)>{{ $plan->label() }}</option>
                                @endforeach
                            </select>
                            @error('plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\RecordStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected((int) old('status', $managedUser->status?->value ?? 1) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel mb-3">
                <div class="panel-header"><h2>Company</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company name</label>
                            <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $managedUser->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input id="country" name="country" type="text" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $managedUser->country) }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $managedUser->city) }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input id="website" name="website" type="text" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $managedUser->website) }}" placeholder="https://">
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel mb-3">
                <div class="panel-header"><h2>Reset password (optional)</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New password</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-t4d px-4">Save user</button>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="panel border-danger mb-3">
            <div class="panel-header"><h2 class="text-danger">Remove user</h2></div>
            <div class="panel-body">
                <p class="small text-muted mb-3">Soft-deletes this account, hides their products, and removes uploaded seller images from storage.</p>
                @include('employee.users.partials.delete-user-form', ['user' => $managedUser, 'class' => 'd-grid', 'buttonClass' => 'btn btn-danger w-100', 'label' => 'Delete user'])
            </div>
        </div>
    </div>
</div>
@endsection
