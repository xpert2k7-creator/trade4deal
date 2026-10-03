@php
    /** @var \App\Domains\Lead\Models\Lead|null $lead */
    $lead = $lead ?? null;
@endphp

<div class="col-12">
    <hr class="my-1">
    <div class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing:0.05em;">Trade &amp; packaging</div>
</div>
<div class="col-12">
    <label for="packaging_requirement" class="form-label">Packaging requirement</label>
    <textarea id="packaging_requirement" name="packaging_requirement" rows="3"
              class="form-control @error('packaging_requirement') is-invalid @enderror">{{ old('packaging_requirement', $lead?->packaging_requirement) }}</textarea>
    @error('packaging_requirement')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="required_quantity" class="form-label">Required quantity <span class="text-danger">*</span></label>
    <input id="required_quantity" name="required_quantity" type="number" step="0.001" min="0.001"
           class="form-control @error('required_quantity') is-invalid @enderror"
           value="{{ old('required_quantity', $lead?->required_quantity) }}" required>
    @error('required_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="packaging_size" class="form-label">Packaging size</label>
    <input id="packaging_size" name="packaging_size" type="text"
           class="form-control @error('packaging_size') is-invalid @enderror"
           value="{{ old('packaging_size', $lead?->packaging_size) }}" placeholder="e.g. 25 kg bags">
    @error('packaging_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="target_price" class="form-label">Target price</label>
    <input id="target_price" name="target_price" type="number" step="0.01" min="0"
           class="form-control @error('target_price') is-invalid @enderror"
           value="{{ old('target_price', $lead?->target_price) }}">
    @error('target_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="preferred_incoterm" class="form-label">Preferred incoterm <span class="text-danger">*</span></label>
    <select id="preferred_incoterm" name="preferred_incoterm" class="form-select @error('preferred_incoterm') is-invalid @enderror" required>
        <option value="" disabled @selected(old('preferred_incoterm', $lead?->preferred_incoterm?->value) === null)>Select incoterm</option>
        @foreach (\App\Support\Enums\Incoterm::cases() as $incoterm)
            <option value="{{ $incoterm->value }}" @selected(old('preferred_incoterm', $lead?->preferred_incoterm?->value) === $incoterm->value)>{{ $incoterm->label() }}</option>
        @endforeach
    </select>
    @error('preferred_incoterm')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="port_of_loading" class="form-label">Port of loading</label>
    <input id="port_of_loading" name="port_of_loading" type="text"
           class="form-control @error('port_of_loading') is-invalid @enderror"
           value="{{ old('port_of_loading', $lead?->port_of_loading) }}">
    @error('port_of_loading')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="destination_port" class="form-label">Destination port</label>
    <input id="destination_port" name="destination_port" type="text"
           class="form-control @error('destination_port') is-invalid @enderror"
           value="{{ old('destination_port', $lead?->destination_port) }}">
    @error('destination_port')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6">
    <label for="payment_terms" class="form-label">Payment terms <span class="text-danger">*</span></label>
    <select id="payment_terms" name="payment_terms" class="form-select @error('payment_terms') is-invalid @enderror" required>
        <option value="" disabled @selected(old('payment_terms', $lead?->payment_terms?->value) === null)>Select payment terms</option>
        @foreach (\App\Support\Enums\LeadPaymentTerm::cases() as $term)
            <option value="{{ $term->value }}" @selected(old('payment_terms', $lead?->payment_terms?->value) === $term->value)>{{ $term->label() }}</option>
        @endforeach
    </select>
    @error('payment_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
