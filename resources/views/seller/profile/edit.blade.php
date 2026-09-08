@extends('layouts.seller')

@section('title', 'Company Profile')
@section('page-title', 'Company Profile')

@section('topbar-actions')
    @if ($seller->slug)
        <a href="{{ route('sellers.show', $seller->slug) }}" target="_blank" class="btn btn-sm btn-outline-t4d">View public page</a>
    @endif
@endsection

@section('content')
<form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel mb-3">
                <div class="panel-header"><h2>Brand & story</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="company_name" class="form-label">Company name</label>
                            <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $seller->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="tagline" class="form-label">Tagline</label>
                            <input id="tagline" name="tagline" type="text" class="form-control @error('tagline') is-invalid @enderror"
                                   value="{{ old('tagline', $seller->tagline) }}" placeholder="e.g. Trusted global textile exporters since 1998">
                            @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="about" class="form-label">About us</label>
                            <textarea id="about" name="about" rows="7" class="form-control @error('about') is-invalid @enderror"
                                      placeholder="Tell buyers who you are, what you supply, and why partners trust you…">{{ old('about', $seller->about) }}</textarea>
                            @error('about')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Industries</label>
                            <div class="row g-2">
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="industries[]" value="{{ $type->value }}" id="ind_{{ $type->value }}"
                                                @checked(in_array($type->value, old('industries', $seller->industries ?? []), true))>
                                            <label class="form-check-label" for="ind_{{ $type->value }}">{{ $type->label() }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h2>Contact & location</h2></div>
                <div class="panel-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input id="country" name="country" type="text" class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country', $seller->country) }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror"
                                   value="{{ old('city', $seller->city) }}">
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $seller->address) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input id="website" name="website" type="url" class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $seller->website) }}" placeholder="https://">
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone <span class="text-muted fw-normal">(private)</span></label>
                            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $seller->phone) }}">
                            <div class="form-text">Not shown on your public page. Buyers contact you via enquiry form.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="year_established" class="form-label">Year established</label>
                            <input id="year_established" name="year_established" type="number" class="form-control"
                                   value="{{ old('year_established', $seller->year_established) }}" min="1800" max="{{ date('Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="employees_range" class="form-label">Company size</label>
                            <select id="employees_range" name="employees_range" class="form-select">
                                <option value="">Select range</option>
                                @foreach (\App\Support\Enums\EmployeesRange::cases() as $range)
                                    <option value="{{ $range->value }}" @selected(old('employees_range', $seller->employees_range?->value) === $range->value)>
                                        {{ $range->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel mb-3">
                <div class="panel-header"><h2>Media</h2></div>
                <div class="panel-body">
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        @if ($seller->logoUrl())
                            <div class="mb-2"><img src="{{ $seller->logoUrl() }}" alt="Logo" class="rounded border" style="height:64px;width:64px;object-fit:cover;"></div>
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
                        @if ($seller->coverImageUrl())
                            <div class="mb-2"><img src="{{ $seller->coverImageUrl() }}" alt="Cover" class="rounded border w-100" style="height:96px;object-fit:cover;"></div>
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

            <div class="panel mb-3">
                <div class="panel-header"><h2>Visibility</h2></div>
                <div class="panel-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_public" name="is_public" value="1"
                            @checked(old('is_public', $seller->is_public))>
                        <label class="form-check-label" for="is_public">Publish public company page</label>
                    </div>
                    @if ($seller->slug)
                        <div class="small text-muted mt-2">
                            URL: <code>/sellers/{{ $seller->slug }}</code>
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary-t4d w-100 py-2">Save profile</button>
        </div>
    </div>
</form>
@endsection
