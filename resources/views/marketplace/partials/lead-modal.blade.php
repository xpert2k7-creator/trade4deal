<div class="modal fade modal-lead" id="leadModal" tabindex="-1" aria-labelledby="leadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content position-relative">
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="row g-0">
                <div class="col-md-4 d-none d-md-block">
                    <div class="modal-side h-100">
                        <div>
                            <div class="mb-3">
                                <span class="badge rounded-pill text-bg-light text-primary px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i> Reviewed before publish
                                </span>
                            </div>
                            <h3 class="fw-bold mb-3" style="font-size: 1.85rem;">
                                Submit your business lead
                            </h3>
                            <p class="mb-4 opacity-75" style="font-size: 0.95rem;">
                                Reach verified buyers and sellers worldwide. Our team reviews every submission before it goes live.
                            </p>

                            <ul class="list-unstyled small mb-0">
                                <li class="d-flex align-items-start gap-2 mb-3">
                                    <i class="bi bi-image mt-1"></i>
                                    <span>Add product image, type, currency &amp; payment terms</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-3">
                                    <i class="bi bi-globe2 mt-1"></i>
                                    <span>Gold members see new leads instantly on the homepage</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="bi bi-clock-history mt-1"></i>
                                    <span>Free members see leads 24 hours after publish</span>
                                </li>
                            </ul>
                        </div>

                        <div class="row g-2 mt-4">
                            <div class="col-6">
                                <div class="side-stat">
                                    <div class="fw-bold fs-5">{{ isset($leads) ? $leads->count() : '0' }}+</div>
                                    <div class="small opacity-75">Visible leads</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="side-stat">
                                    <div class="fw-bold fs-5">24h</div>
                                    <div class="small opacity-75">Free delay</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="modal-body">
                        <div class="mb-3">
                            <h4 class="fw-bold mb-1" id="leadModalLabel">Submit Business Lead</h4>
                            <p class="text-muted small mb-0">Complete product and trade details for faster matching.</p>
                        </div>

                        <form method="POST" action="{{ route('leads.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label fw-medium">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                           id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required>
                                    @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-medium">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()?->phone) }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label fw-medium">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                           id="country" name="country" value="{{ old('country', auth()->user()?->country) }}" required>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="business_type" class="form-label fw-medium">I am a <span class="text-danger">*</span></label>
                                    <select class="form-select @error('business_type') is-invalid @enderror" id="business_type" name="business_type" required>
                                        <option value="">Select type</option>
                                        @foreach(\App\Support\Enums\BusinessType::cases() as $type)
                                            <option value="{{ $type->value }}" @selected(old('business_type') === $type->value)>{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('business_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="product_type" class="form-label fw-medium">Product Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_type') is-invalid @enderror" id="product_type" name="product_type" required>
                                        <option value="">Select product type</option>
                                        @foreach(\App\Support\Enums\ProductType::cases() as $type)
                                            <option value="{{ $type->value }}" @selected(old('product_type') === $type->value)>{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="product_image" class="form-label fw-medium">Product Image</label>
                                    <input type="file" class="form-control @error('product_image') is-invalid @enderror"
                                           id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp">
                                    @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="product_interest" class="form-label fw-medium">Product / Service Interest <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_interest') is-invalid @enderror"
                                       id="product_interest" name="product_interest" value="{{ old('product_interest') }}" required>
                                @error('product_interest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="currency" class="form-label fw-medium">Currency <span class="text-danger">*</span></label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                        <option value="">Select currency</option>
                                        @foreach(\App\Support\Enums\Currency::cases() as $currency)
                                            <option value="{{ $currency->value }}" @selected(old('currency') === $currency->value)>{{ $currency->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="units" class="form-label fw-medium">Units <span class="text-danger">*</span></label>
                                    <select class="form-select @error('units') is-invalid @enderror" id="units" name="units" required>
                                        <option value="">Select units</option>
                                        @foreach(\App\Support\Enums\LeadUnit::cases() as $unit)
                                            <option value="{{ $unit->value }}" @selected(old('units') === $unit->value)>{{ $unit->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium d-block">Payment Methods <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach(\App\Support\Enums\PaymentMethod::cases() as $method)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('payment_methods') is-invalid @enderror"
                                                       type="checkbox" name="payment_methods[]" value="{{ $method->value }}"
                                                       id="pay_{{ $method->value }}"
                                                       @checked(is_array(old('payment_methods')) && in_array($method->value, old('payment_methods'), true))>
                                                <label class="form-check-label" for="pay_{{ $method->value }}">{{ $method->label() }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('payment_methods')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-medium">Message <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary-t4d text-white w-100 py-2">
                                <i class="bi bi-send-fill me-2"></i>Submit Lead for Review
                            </button>
                            <p class="text-center text-muted small mt-3 mb-0">
                                By submitting, you agree to Trade4Deal’s terms of use.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
