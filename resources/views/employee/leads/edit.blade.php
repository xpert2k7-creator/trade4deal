@extends('layouts.employee')

@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')

@section('topbar-actions')
    <a href="{{ route('employee.leads.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-secondary">Back to queue</a>
@endsection

@section('content')
@php
    $statusLabel = $lead->status === \App\Support\Enums\RecordStatus::Inactive
        ? 'Rejected'
        : $lead->status->label();
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>{{ $lead->company_name }}</h2>
                <span class="badge {{ $lead->status->badgeClass() }}">{{ $statusLabel }}</span>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('employee.leads.update', $lead) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input id="company_name" name="company_name" type="text"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $lead->company_name) }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="contact_name" class="form-label">Contact Name</label>
                            <input id="contact_name" name="contact_name" type="text"
                                   class="form-control @error('contact_name') is-invalid @enderror"
                                   value="{{ old('contact_name', $lead->contact_name) }}" required>
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $lead->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $lead->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input id="country" name="country" type="text"
                                   class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country', $lead->country) }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="business_type" class="form-label">Business Type</label>
                            <select id="business_type" name="business_type" class="form-select @error('business_type') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\BusinessType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('business_type', $lead->business_type?->value) === $type->value)>
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
                                   value="{{ old('product_interest', $lead->product_interest) }}" required>
                            @error('product_interest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_type" class="form-label">Product Type</label>
                            <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('product_type', $lead->product_type?->value) === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="product_image" class="form-label">Product Image</label>
                            @if($lead->productImageUrl())
                                <div class="mb-2"><img src="{{ $lead->productImageUrl() }}" alt="Product" class="rounded" style="height:64px;width:64px;object-fit:cover;"></div>
                            @endif
                            <input id="product_image" name="product_image" type="file" class="form-control @error('product_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\Currency::cases() as $currency)
                                    <option value="{{ $currency->value }}" @selected(old('currency', $lead->currency?->value) === $currency->value)>{{ $currency->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="units" class="form-label">Units</label>
                            <select id="units" name="units" class="form-select @error('units') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\LeadUnit::cases() as $unit)
                                    <option value="{{ $unit->value }}" @selected(old('units', $lead->units?->value) === $unit->value)>{{ $unit->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Payment Methods</label>
                            <div class="row g-2">
                                @foreach (\App\Support\Enums\PaymentMethod::cases() as $method)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="payment_methods[]" value="{{ $method->value }}" id="emp_pay_{{ $method->value }}"
                                                @checked(in_array($method->value, old('payment_methods', $lead->payment_methods ?? []), true))>
                                            <label class="form-check-label" for="emp_pay_{{ $method->value }}">{{ $method->label() }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="4"
                                      class="form-control @error('message') is-invalid @enderror">{{ old('message', $lead->message) }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary-t4d">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel mb-3">
            <div class="panel-header"><h2>Moderation</h2></div>
            <div class="panel-body d-grid gap-2">
                @if ($lead->status !== \App\Support\Enums\RecordStatus::Active)
                    <form action="{{ route('employee.leads.approve', $lead) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-approve w-100 py-2">
                            <i class="bi bi-check-lg me-1"></i> Approve &amp; publish
                        </button>
                    </form>
                @else
                    <div class="alert alert-success small mb-0">This lead is live on the website.</div>
                @endif

                @if ($lead->status !== \App\Support\Enums\RecordStatus::Inactive)
                    <form action="{{ route('employee.leads.reject', $lead) }}" method="POST" onsubmit="return confirm('Reject this lead and email the submitter?');">
                        @csrf
                        <button type="submit" class="btn btn-reject w-100 py-2">
                            <i class="bi bi-x-lg me-1"></i> Reject lead
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h2>Meta</h2></div>
            <div class="panel-body small text-muted">
                <div class="mb-2"><strong class="text-dark">Published:</strong> {{ $lead->published_at?->format('M j, Y g:i A') ?? '—' }}</div>
                <div class="mb-2"><strong class="text-dark">Submitted:</strong> {{ $lead->created_at?->format('M j, Y g:i A') }}</div>
                <div class="mb-2"><strong class="text-dark">Updated:</strong> {{ $lead->updated_at?->format('M j, Y g:i A') }}</div>
                <div><strong class="text-dark">ID:</strong> <code class="small">{{ $lead->id }}</code></div>
            </div>
        </div>
    </div>
</div>
@endsection
