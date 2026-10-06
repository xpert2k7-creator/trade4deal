@extends('layouts.sourcing')

@section('title', 'Add buy lead')
@section('page-title', 'Add buy lead')

@section('topbar-actions')
    <a href="{{ route('sourcing.leads.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-secondary">My submissions</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>New buy lead</h2>
            </div>
            <div class="panel-body">
                <p class="text-muted small mb-4">
                    Submitted leads go to the employee review queue and go live only after approval.
                </p>

                <form method="POST" action="{{ route('sourcing.leads.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company name *</label>
                            <input id="company_name" name="company_name" type="text"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name') }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="contact_name" class="form-label">Contact person</label>
                            <input id="contact_name" name="contact_name" type="text"
                                   class="form-control @error('contact_name') is-invalid @enderror"
                                   value="{{ old('contact_name') }}">
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Contact number *</label>
                            <input id="phone" name="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_type" class="form-label">Product category *</label>
                            <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('product_type') === null)>Select category</option>
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('product_type') === $type->value)>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_interest" class="form-label">Product</label>
                            <input id="product_interest" name="product_interest" type="text"
                                   class="form-control @error('product_interest') is-invalid @enderror"
                                   value="{{ old('product_interest') }}" placeholder="Product name or description">
                            @error('product_interest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input id="city" name="city" type="text"
                                   class="form-control @error('city') is-invalid @enderror"
                                   value="{{ old('city') }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country *</label>
                            <input id="country" name="country" type="text"
                                   class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country') }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary-t4d">Submit buy lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
