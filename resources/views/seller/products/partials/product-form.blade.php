@php
    $isEdit = isset($product);
    $action = $isEdit ? route('seller.products.update', $product) : route('seller.products.store');
    $initialProductType = old('product_type', $product->product_type->value ?? \App\Support\Enums\ProductType::Textiles->value);
    $dynamicFieldValues = $dynamicFieldValues ?? old('dynamic_fields', []);
@endphp
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="product-form">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-12">
            <label for="name" class="form-label">Product name</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $product->name ?? '') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="product_type" class="form-label">Category</label>
            <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                    <option value="{{ $type->value }}" @selected($initialProductType === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
            @error('product_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="{{ \App\Support\Enums\RecordStatus::Active->value }}" @selected((int) old('status', $product->status->value ?? 1) === 1)>Live</option>
                <option value="{{ \App\Support\Enums\RecordStatus::Inactive->value }}" @selected((int) old('status', $product->status->value ?? 1) === 0)>Draft</option>
            </select>
        </div>
        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <hr class="my-1 text-muted">
            <h3 class="h6 fw-semibold mb-0">Category Specifications</h3>
            <p class="small text-muted mb-2">Fields below depend on the product category selected above.</p>
            <div id="dynamic-fields-root" class="row g-3" data-initial-type="{{ $initialProductType }}"></div>
        </div>

        <div class="col-md-6">
            <label for="currency" class="form-label">Currency</label>
            <select id="currency" name="currency" class="form-select" required>
                @foreach (\App\Support\Enums\Currency::cases() as $currency)
                    <option value="{{ $currency->value }}" @selected(old('currency', $product->currency->value ?? 'USD') === $currency->value)>{{ $currency->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="units" class="form-label">Units</label>
            <select id="units" name="units" class="form-select" required>
                @foreach (\App\Support\Enums\LeadUnit::cases() as $unit)
                    <option value="{{ $unit->value }}" @selected(old('units', $product->units->value ?? 'pieces') === $unit->value)>{{ $unit->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="min_order_qty" class="form-label">Minimum qty</label>
            <input id="min_order_qty" name="min_order_qty" type="text" class="form-control @error('min_order_qty') is-invalid @enderror"
                   value="{{ old('min_order_qty', $product->min_order_qty ?? '') }}" placeholder="e.g. 100">
            @error('min_order_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="price_from" class="form-label">Price from</label>
            <input id="price_from" name="price_from" type="number" step="0.01" min="0" class="form-control @error('price_from') is-invalid @enderror"
                   value="{{ old('price_from', $product->price_from ?? '') }}">
            @error('price_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="price_to" class="form-label">Price to</label>
            <input id="price_to" name="price_to" type="number" step="0.01" min="0" class="form-control @error('price_to') is-invalid @enderror"
                   value="{{ old('price_to', $product->price_to ?? '') }}">
            @error('price_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @include('seller.products.partials.product-images-fields', ['product' => $product ?? null])
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary-t4d">{{ $isEdit ? 'Save changes' : 'Create product' }}</button>
        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-t4d">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
(function () {
    const root = document.getElementById('dynamic-fields-root');
    const typeSelect = document.getElementById('product_type');
    if (!root || !typeSelect) return;

    const fieldsUrl = @json(route('seller.products.category-fields'));
    const savedValues = @json($dynamicFieldValues);
    const serverErrors = @json($errors->getMessages());
    const uploadsRouteTemplate = @json(route('uploads.public', ['path' => '__RELATIVE_PATH__']));
    function publicUploadUrl(relativePath) {
        return uploadsRouteTemplate.replace('__RELATIVE_PATH__', String(relativePath).replace(/^\/+/, ''));
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function fieldError(fieldId) {
        const key = 'dynamic_fields.' + fieldId;
        const messages = serverErrors[key];
        if (!messages || !messages.length) return '';
        return '<div class="invalid-feedback d-block">' + escapeHtml(messages[0]) + '</div>';
    }

    function inputClasses(fieldId) {
        return serverErrors['dynamic_fields.' + fieldId] ? 'form-control is-invalid' : 'form-control';
    }

    function selectClasses(fieldId) {
        return serverErrors['dynamic_fields.' + fieldId] ? 'form-select is-invalid' : 'form-select';
    }

    function savedValue(fieldId) {
        if (Object.prototype.hasOwnProperty.call(savedValues, fieldId)) {
            return savedValues[fieldId];
        }
        if (Object.prototype.hasOwnProperty.call(savedValues, String(fieldId))) {
            return savedValues[String(fieldId)];
        }
        return '';
    }

    function renderField(field) {
        const id = field.id;
        const name = 'dynamic_fields[' + id + ']';
        const label = escapeHtml(field.field_name);
        const required = field.is_required ? ' required' : '';
        const value = savedValue(id);
        const unitHint = field.unit ? '<span class="small text-muted ms-1">' + escapeHtml(field.unit) + '</span>' : '';
        let control = '';

        switch (field.field_type) {
            case 'textarea':
                control = '<textarea id="df-' + id + '" name="' + name + '" rows="3" class="' + inputClasses(id) + '"' + required + '>' + escapeHtml(value) + '</textarea>';
                break;
            case 'number':
                control = '<input id="df-' + id + '" name="' + name + '" type="number" step="any" class="' + inputClasses(id) + '" value="' + escapeHtml(value) + '"' + required + '>' + unitHint;
                break;
            case 'date':
                control = '<input id="df-' + id + '" name="' + name + '" type="date" class="' + inputClasses(id) + '" value="' + escapeHtml(value) + '"' + required + '>';
                break;
            case 'select':
                control = '<select id="df-' + id + '" name="' + name + '" class="' + selectClasses(id) + '"' + required + '><option value="">— Select —</option>';
                field.options.forEach(function (opt) {
                    const selected = String(value) === String(opt.value) ? ' selected' : '';
                    control += '<option value="' + escapeHtml(opt.value) + '"' + selected + '>' + escapeHtml(opt.label) + '</option>';
                });
                control += '</select>';
                break;
            case 'boolean':
                control = '<select id="df-' + id + '" name="' + name + '" class="' + selectClasses(id) + '"' + required + '>' +
                    '<option value="">— Select —</option>' +
                    '<option value="1"' + (String(value) === '1' ? ' selected' : '') + '>Yes</option>' +
                    '<option value="0"' + (String(value) === '0' ? ' selected' : '') + '>No</option>' +
                    '</select>';
                break;
            case 'file':
                let existing = '';
                if (value) {
                    const href = publicUploadUrl(value);
                    existing = '<p class="small mb-1"><a href="' + escapeHtml(href) + '" target="_blank" rel="noopener">View uploaded file</a></p>';
                }
                control = existing + '<input id="df-' + id + '" name="' + name + '" type="file" class="' + inputClasses(id) + '" accept=".pdf,image/jpeg,image/png,image/webp"' + required + '>';
                break;
            default:
                control = '<input id="df-' + id + '" name="' + name + '" type="text" class="' + inputClasses(id) + '" value="' + escapeHtml(value) + '"' + required + '>' + unitHint;
        }

        return '<div class="col-12 col-md-6">' +
            '<label class="form-label" for="df-' + id + '">' + label + '</label>' +
            control + fieldError(id) +
            '</div>';
    }

    function loadFields(productType) {
        root.innerHTML = '<div class="col-12"><span class="small text-muted">Loading specifications…</span></div>';
        const url = fieldsUrl + '?product_type=' + encodeURIComponent(productType);
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                const fields = data.fields || [];
                if (!fields.length) {
                    root.innerHTML = '<div class="col-12"><span class="small text-muted">No category specifications for this category.</span></div>';
                    return;
                }
                root.innerHTML = fields.map(renderField).join('');
            })
            .catch(function () {
                root.innerHTML = '<div class="col-12"><span class="small text-danger">Could not load category specifications. Please try again.</span></div>';
            });
    }

    typeSelect.addEventListener('change', function () {
        loadFields(typeSelect.value);
    });

    loadFields(typeSelect.value || root.dataset.initialType);
})();
</script>
@endpush
