@extends('layouts.employee')

@section('title', 'Edit User')
@section('page-title', 'Edit user')

@section('topbar-actions')
    <a href="{{ route('employee.users.index', array_filter(['q' => $search])) }}" class="btn btn-sm btn-outline-secondary">Back to users</a>
    @if ($user->isSeller() && $user->slug)
        <a href="{{ route('sellers.show', $user->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Public page</a>
    @endif
@endsection

@section('content')
@php
    $isSeller = old('user_type', $user->user_type?->value) === \App\Support\Enums\UserType::Seller->value;
@endphp

<form method="POST" action="{{ route('employee.users.update', $user) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @if ($search)
        <input type="hidden" name="q" value="{{ $search }}">
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="panel mb-3">
                <div class="panel-header">
                    <h2>Account</h2>
                    <span class="badge {{ $user->status?->badgeClass() ?? 'bg-secondary' }}">{{ $user->status?->label() ?? 'Active' }}</span>
                </div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full name</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="user_type" class="form-label">User type</label>
                            <select id="user_type" name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                @foreach ([\App\Support\Enums\UserType::Buyer, \App\Support\Enums\UserType::Seller] as $type)
                                    <option value="{{ $type->value }}" @selected(old('user_type', $user->user_type?->value) === $type->value)>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="plan" class="form-label">Plan</label>
                            <select id="plan" name="plan" class="form-select @error('plan') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\UserPlan::cases() as $plan)
                                    <option value="{{ $plan->value }}" @selected(old('plan', $user->plan?->value ?? 'free') === $plan->value)>
                                        {{ $plan->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Account status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\RecordStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected((int) old('status', $user->status?->value ?? 1) === $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">New password</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            <div class="form-text">Leave blank to keep the current password.</div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
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
                            <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $user->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input id="country" name="country" type="text" class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country', $user->country) }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel seller-profile-fields {{ $isSeller ? '' : 'd-none' }}">
                <div class="panel-header"><h2>Seller profile</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="tagline" class="form-label">Tagline</label>
                            <input id="tagline" name="tagline" type="text" class="form-control @error('tagline') is-invalid @enderror"
                                   value="{{ old('tagline', $user->tagline) }}">
                            @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="about" class="form-label">About</label>
                            <textarea id="about" name="about" rows="6" class="form-control @error('about') is-invalid @enderror">{{ old('about', $user->about) }}</textarea>
                            @error('about')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror"
                                   value="{{ old('city', $user->city) }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input id="website" name="website" type="url" class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $user->website) }}" placeholder="https://">
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $user->address) }}">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="year_established" class="form-label">Year established</label>
                            <input id="year_established" name="year_established" type="number" class="form-control"
                                   value="{{ old('year_established', $user->year_established) }}" min="1800" max="{{ date('Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="employees_range" class="form-label">Company size</label>
                            <select id="employees_range" name="employees_range" class="form-select">
                                <option value="">Select range</option>
                                @foreach (\App\Support\Enums\EmployeesRange::cases() as $range)
                                    <option value="{{ $range->value }}" @selected(old('employees_range', $user->employees_range?->value) === $range->value)>
                                        {{ $range->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Industries</label>
                            <div class="row g-2">
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="industries[]" value="{{ $type->value }}" id="ind_{{ $type->value }}"
                                                @checked(in_array($type->value, old('industries', $user->industries ?? []), true))>
                                            <label class="form-check-label" for="ind_{{ $type->value }}">{{ $type->label() }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel mb-3 seller-profile-fields {{ $isSeller ? '' : 'd-none' }}">
                <div class="panel-header"><h2>Media</h2></div>
                <div class="panel-body">
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        @if ($user->logoUrl())
                            <div class="mb-2"><img src="{{ $user->logoUrl() }}" alt="Logo" class="rounded border" style="height:64px;width:64px;object-fit:cover;"></div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                <label class="form-check-label small" for="remove_logo">Remove logo</label>
                            </div>
                        @endif
                        <input id="logo" name="logo" type="file" class="form-control @error('logo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="cover_image" class="form-label">Cover image</label>
                        @if ($user->coverImageUrl())
                            <div class="mb-2"><img src="{{ $user->coverImageUrl() }}" alt="Cover" class="rounded border w-100" style="height:96px;object-fit:cover;"></div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="remove_cover" value="1" id="remove_cover">
                                <label class="form-check-label small" for="remove_cover">Remove cover</label>
                            </div>
                        @endif
                        <input id="cover_image" name="cover_image" type="file" class="form-control @error('cover_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="panel mb-3 seller-profile-fields {{ $isSeller ? '' : 'd-none' }}">
                <div class="panel-header"><h2>Visibility</h2></div>
                <div class="panel-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_public" name="is_public" value="1"
                            @checked(old('is_public', $user->is_public))>
                        <label class="form-check-label" for="is_public">Publish public company page</label>
                    </div>
                    @if ($user->slug)
                        <div class="small text-muted mt-2">URL: <code>/sellers/{{ $user->slug }}</code></div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary-t4d w-100 py-2 mb-3">Save changes</button>
        </div>
    </div>
</form>

<div class="row g-4">
    <div class="col-lg-4 ms-lg-auto">
        <div class="panel border-danger">
            <div class="panel-header"><h2 class="text-danger">Danger zone</h2></div>
            <div class="panel-body">
                <p class="small text-muted mb-3">Soft-deletes this account. The user will no longer appear in this list.</p>
                <form method="POST" action="{{ route('employee.users.destroy', $user) }}" class="user-delete-form" data-user-name="{{ $user->name }}">
                    @csrf
                    @method('DELETE')
                    @if ($search)
                        <input type="hidden" name="q" value="{{ $search }}">
                    @endif
                    <button type="submit" class="btn btn-outline-danger w-100">Delete user</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const userTypeSelect = document.getElementById('user_type');
    const sellerSections = document.querySelectorAll('.seller-profile-fields');

    function toggleSellerFields() {
        const isSeller = userTypeSelect.value === 'seller';
        sellerSections.forEach(function (el) {
            el.classList.toggle('d-none', ! isSeller);
        });
    }

    userTypeSelect.addEventListener('change', toggleSellerFields);

    document.querySelectorAll('.user-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (! confirm('Delete ' + form.dataset.userName + '? This cannot be undone from this screen.')) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
