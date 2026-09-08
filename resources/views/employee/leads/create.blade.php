@extends('layouts.employee')

@section('title', 'Submit Lead')
@section('page-title', 'Submit Lead')

@section('topbar-actions')
    <a href="{{ route('employee.leads.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-secondary">Back to queue</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>New business lead</h2>
            </div>
            <div class="panel-body">
                <p class="text-muted small mb-4">
                    Submit a lead on behalf of a business. It will enter the pending queue for review before going live.
                </p>

                <form method="POST" action="{{ route('employee.leads.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input id="company_name" name="company_name" type="text"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name') }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="contact_name" class="form-label">Contact Name</label>
                            <input id="contact_name" name="contact_name" type="text"
                                   class="form-control @error('contact_name') is-invalid @enderror"
                                   value="{{ old('contact_name') }}" required>
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input id="country" name="country" type="text"
                                   class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country') }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="business_type" class="form-label">Business Type</label>
                            <select id="business_type" name="business_type" class="form-select @error('business_type') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('business_type') === null)>Select type</option>
                                @foreach (\App\Support\Enums\BusinessType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('business_type') === $type->value)>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="product_interest" class="form-label">Product Interest</label>
                            <input id="product_interest" name="product_interest" type="text"
                                   class="form-control @error('product_interest') is-invalid @enderror"
                                   value="{{ old('product_interest') }}" required>
                            @error('product_interest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_type" class="form-label">Product Type</label>
                            <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('product_type') === null)>Select category</option>
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('product_type') === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_image" class="form-label">Product Image</label>
                            <input id="product_image" name="product_image" type="file" class="form-control @error('product_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                            @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('currency') === null)>Select currency</option>
                                @foreach (\App\Support\Enums\Currency::cases() as $currency)
                                    <option value="{{ $currency->value }}" @selected(old('currency') === $currency->value)>{{ $currency->label() }}</option>
                                @endforeach
                            </select>
                            @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="units" class="form-label">Units</label>
                            <select id="units" name="units" class="form-select @error('units') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('units') === null)>Select units</option>
                                @foreach (\App\Support\Enums\LeadUnit::cases() as $unit)
                                    <option value="{{ $unit->value }}" @selected(old('units') === $unit->value)>{{ $unit->label() }}</option>
                                @endforeach
                            </select>
                            @error('units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Payment Methods</label>
                            <div class="row g-2">
                                @foreach (\App\Support\Enums\PaymentMethod::cases() as $method)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="payment_methods[]" value="{{ $method->value }}" id="create_pay_{{ $method->value }}"
                                                @checked(in_array($method->value, old('payment_methods', []), true))>
                                            <label class="form-check-label" for="create_pay_{{ $method->value }}">{{ $method->label() }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('payment_methods')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="4"
                                      class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary-t4d">Submit lead</button>
                        <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h2>Notes</h2></div>
            <div class="panel-body small text-muted">
                <p class="mb-2">Leads submitted here follow the same review workflow as public submissions.</p>
                <p class="mb-0">After submission, approve the lead from the pending queue to publish it on the homepage.</p>
            </div>
        </div>
    </div>
</div>
@endsection
