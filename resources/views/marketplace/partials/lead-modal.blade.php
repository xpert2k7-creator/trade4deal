<style>
    .supplier-signup-modal .modal-content {
        border-radius: 8px;
        background: #fff;
        color: #020617;
    }

    .supplier-signup-modal .modal-dialog {
        max-width: min(1220px, calc(100vw - 2rem));
    }

    .supplier-signup-modal .btn-close-custom {
        top: 2rem;
        right: 2.1rem;
        border: 0;
        background: transparent;
        color: #475569;
        font-size: 1.15rem;
    }

    .supplier-modal-shell {
        display: grid;
        grid-template-columns: minmax(340px, 0.78fr) minmax(0, 1.22fr);
        min-height: 640px;
    }

    .supplier-modal-aside {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(145deg, rgba(232, 241, 251, 0.98), rgba(219, 231, 253, 0.9)),
            radial-gradient(circle at 12% 92%, rgba(245, 130, 32, 0.18), transparent 25%);
        padding: clamp(1.4rem, 3vw, 2.25rem);
    }

    .supplier-modal-aside::after {
        content: "";
        position: absolute;
        right: -80px;
        bottom: -90px;
        width: 310px;
        height: 310px;
        border: 3px solid rgba(6, 68, 117, 0.08);
        border-radius: 50%;
    }

    .supplier-brand,
    .supplier-aside-title,
    .supplier-benefits,
    .supplier-stat-card {
        position: relative;
        z-index: 1;
    }

    .supplier-brand {
        display: grid;
        gap: 0.35rem;
        width: fit-content;
        margin-bottom: 1.45rem;
    }

    .supplier-brand img {
        width: 135px;
        height: auto;
    }

    .supplier-brand span {
        color: #334155;
        font-size: 0.74rem;
        font-weight: 800;
    }

    .supplier-aside-title {
        margin: 0 0 1.25rem;
        color: #020617;
        font-size: clamp(1.25rem, 2.4vw, 1.55rem) !important;
        font-weight: 850;
        line-height: 1.25;
    }

    .supplier-benefits {
        display: grid;
        gap: 1.15rem;
    }

    .supplier-benefit {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.75rem;
        align-items: center;
        color: #111827;
        font-size: 1.02rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .supplier-benefit-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        background: #fff;
        color: #079455;
        font-size: 1.45rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .supplier-stat-card {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem 2rem;
        margin-top: 2.4rem;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.92);
        padding: 1.1rem 1.25rem;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.12);
    }

    .supplier-stat-card strong {
        display: block;
        color: #020617;
        font-size: 1.45rem;
        font-weight: 900;
        line-height: 1.1;
    }

    .supplier-stat-card span {
        display: block;
        margin-top: 0.22rem;
        color: #475569;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .supplier-modal-main {
        padding: clamp(1.4rem, 3.4vw, 2.6rem);
        overflow-y: auto;
    }

    .supplier-form-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.35rem;
        padding-right: 2rem;
    }

    .supplier-form-top h1 {
        margin: 0;
        color: #020617;
        font-size: clamp(1.55rem, 3vw, 2rem);
        font-weight: 850;
        line-height: 1.15;
    }

    .supplier-form-top p {
        margin: 0.35rem 0 0;
        color: #64748b;
        font-size: 0.9rem !important;
    }

    .auth-switch {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        background: #f1f5f9;
        padding: 0.72rem 0.85rem;
        color: #0f172a;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .auth-switch a {
        border: 1px solid #94a3b8;
        border-radius: 9px;
        background: #fff;
        color: #0f172a;
        padding: 0.45rem 0.85rem;
        text-decoration: none;
        font-weight: 850;
    }

    .supplier-register-form {
        display: grid;
        gap: 0.68rem;
    }

    .supplier-field {
        position: relative;
    }

    .supplier-field i {
        position: absolute;
        top: 50%;
        left: 0.95rem;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 1.1rem;
        pointer-events: none;
    }

    .supplier-field input,
    .supplier-field select {
        width: 100%;
        min-height: 47px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff !important;
        color: #0f172a !important;
        padding: 0 0.95rem 0 2.65rem;
        font-size: 0.95rem;
        -webkit-text-fill-color: #0f172a;
    }

    .supplier-field input::placeholder {
        color: #9ca3af;
    }

    .supplier-field input:focus,
    .supplier-field select:focus {
        border-color: #079455 !important;
        box-shadow: 0 0 0 3px rgba(7, 148, 85, 0.16) !important;
        outline: none;
    }

    .supplier-two-col {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.68rem;
    }

    .requirement-radio-group {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.68rem;
    }

    .requirement-radio {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.62rem;
        min-height: 47px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        color: #0f172a;
        padding: 0 0.95rem;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
    }

    .requirement-radio input {
        width: 18px;
        height: 18px;
        accent-color: #079455;
    }

    .terms-line {
        display: grid;
        grid-template-columns: 24px 1fr;
        gap: 0.55rem;
        align-items: start;
        margin-top: 0.25rem;
        color: #475569;
        font-size: 0.78rem;
        line-height: 1.45;
        cursor: pointer;
    }

    .terms-line input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .terms-check {
        width: 23px;
        height: 23px;
        border: 1px solid #079455;
        border-radius: 5px;
        display: grid;
        place-items: center;
        color: #079455;
        font-size: 0.95rem;
        font-weight: 900;
        transition: background 0.16s ease, color 0.16s ease;
    }

    .terms-line input:not(:checked) + .terms-check {
        background: #fff;
        color: transparent;
    }

    .terms-line input:focus-visible + .terms-check {
        box-shadow: 0 0 0 3px rgba(7, 148, 85, 0.18);
    }

    .terms-line a {
        color: #075985;
        text-decoration: none;
        font-weight: 800;
    }

    .supplier-submit {
        width: min(100%, 390px);
        min-height: 52px;
        margin: 0.25rem auto 0;
        border: 0;
        border-radius: 9px;
        background: #079455;
        color: #fff;
        font-size: 1rem;
        font-weight: 850;
        transition: transform 0.16s ease, background 0.16s ease;
    }

    .supplier-submit:hover {
        background: #067647;
        transform: translateY(-1px);
    }

    .or-divider {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 1rem;
        align-items: center;
        width: min(100%, 430px);
        margin: 0.6rem auto;
        color: #0f172a;
        font-weight: 800;
    }

    .or-divider::before,
    .or-divider::after {
        content: "";
        height: 1px;
        background: #d1d5db;
    }

    .google-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        width: min(100%, 390px);
        min-height: 52px;
        margin: 0 auto;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        color: #111827;
        font-weight: 850;
        text-decoration: none;
    }

    .google-placeholder:hover {
        border-color: #94a3b8;
        color: #111827;
    }

    .google-mark {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: #4285f4;
        font-weight: 900;
    }

    .supplier-signup-modal .invalid-feedback {
        display: block;
        margin: 0.25rem 0 0 0.2rem;
        color: #b91c1c;
        font-size: 0.78rem;
    }

    @media (max-width: 991px) {
        .supplier-modal-shell {
            grid-template-columns: 1fr;
            min-height: auto;
        }
    }

    @media (max-width: 575px) {
        .supplier-signup-modal .modal-dialog {
            max-width: calc(100vw - 1rem);
            margin: 0.5rem auto;
        }

        .supplier-modal-aside {
            padding: 0.95rem;
        }

        .supplier-modal-aside::after {
            width: 190px;
            height: 190px;
            right: -70px;
            bottom: -70px;
        }

        .supplier-brand {
            margin-bottom: 0.9rem;
        }

        .supplier-brand img {
            width: 92px;
        }

        .supplier-brand span {
            font-size: 0.62rem;
        }

        .supplier-aside-title {
            margin-bottom: 0.75rem;
            font-size: 1.04rem !important;
            line-height: 1.22;
        }

        .supplier-benefits {
            gap: 0.58rem;
        }

        .supplier-benefit {
            grid-template-columns: 34px 1fr;
            gap: 0.55rem;
            font-size: 0.78rem;
            line-height: 1.25;
        }

        .supplier-benefit-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 1rem;
        }

        .supplier-stat-card {
            gap: 0.8rem 1rem;
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: 10px;
        }

        .supplier-stat-card strong {
            font-size: 1.05rem;
        }

        .supplier-stat-card span {
            font-size: 0.66rem;
            line-height: 1.25;
        }

        .supplier-modal-main {
            padding: 1rem 0.85rem 1.1rem;
        }

        .supplier-form-top {
            margin-bottom: 0.78rem;
            padding-right: 1.75rem;
        }

        .supplier-form-top h1 {
            font-size: 1.25rem;
        }

        .supplier-form-top p {
            font-size: 0.74rem !important;
        }

        .supplier-signup-modal .btn-close-custom {
            top: 1rem;
            right: 1rem;
            font-size: 1rem;
        }

        .auth-switch {
            padding: 0.58rem 0.65rem;
            font-size: 0.72rem;
        }

        .auth-switch a {
            padding: 0.38rem 0.62rem;
            border-radius: 7px;
        }

        .supplier-register-form {
            gap: 0.52rem;
        }

        .supplier-two-col {
            grid-template-columns: 1fr;
        }

        .requirement-radio-group {
            grid-template-columns: 1fr;
            gap: 0.52rem;
        }

        .requirement-radio {
            min-height: 42px;
            border-radius: 8px;
            font-size: 0.78rem;
        }

        .supplier-field i {
            left: 0.78rem;
            font-size: 0.9rem;
        }

        .supplier-field input,
        .supplier-field select {
            min-height: 42px;
            border-radius: 8px;
            padding-left: 2.25rem;
            font-size: 0.78rem;
        }

        .terms-line {
            grid-template-columns: 20px 1fr;
            gap: 0.45rem;
            font-size: 0.62rem;
            line-height: 1.35;
        }

        .terms-check {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            font-size: 0.78rem;
        }

        .supplier-submit,
        .google-placeholder {
            min-height: 44px;
            width: 100%;
            border-radius: 8px;
            font-size: 0.82rem;
        }

        .or-divider {
            width: 100%;
            margin: 0.35rem auto;
            font-size: 0.72rem;
        }

        .google-mark {
            width: 18px;
            height: 18px;
        }
    }
</style>

<div class="modal fade modal-lead supplier-signup-modal" id="leadModal" tabindex="-1" aria-labelledby="leadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content position-relative">
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="supplier-modal-shell">
                <aside class="supplier-modal-aside">
                    <div class="supplier-brand">
                        <x-brand-logo :height="44" />
                        <span>Global B2B Marketplace</span>
                    </div>

                    <h2 class="supplier-aside-title">Submit Your Business Requirement</h2>

                    <div class="supplier-benefits">
                        <div class="supplier-benefit">
                            <span class="supplier-benefit-icon"><i class="bi bi-list-check"></i></span>
                            <span>Connect with Verified Buyers and Daily Buying Leads</span>
                        </div>
                        <div class="supplier-benefit">
                            <span class="supplier-benefit-icon"><i class="bi bi-check-circle"></i></span>
                            <span>Find, connect and reply to interested buyers faster</span>
                        </div>
                        <div class="supplier-benefit">
                            <span class="supplier-benefit-icon"><i class="bi bi-diagram-3"></i></span>
                            <span>Save time with product listings and seller profile tools</span>
                        </div>
                    </div>

                    <div class="supplier-stat-card">
                        <div>
                            <strong>Global</strong>
                            <span>Buyer reach</span>
                        </div>
                        <div>
                            <strong>B2B</strong>
                            <span>Trade focused</span>
                        </div>
                        <div>
                            <strong>Live</strong>
                            <span>Buying leads</span>
                        </div>
                        <div>
                            <strong>200+</strong>
                            <span>Countries and regions</span>
                        </div>
                    </div>
                </aside>

                <div class="supplier-modal-main">
                    <div class="supplier-form-top">
                        <div>
                            <h1 id="leadModalLabel">Submit Requirement</h1>
                            <p>Share your business details and our team will review your requirement.</p>
                        </div>
                    </div>

                    <form class="supplier-register-form" method="POST" action="{{ route('leads.store') }}">
                        @csrf
                        <input type="hidden" name="currency" value="{{ old('currency', 'INR') }}">
                        <input type="hidden" name="units" value="{{ old('units', 'pieces') }}">
                        <input type="hidden" name="payment_methods[]" value="wire_transfer">

                        <div class="supplier-field">
                            <i class="bi bi-building"></i>
                            <input id="modal_company_name" type="text" class="@error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Bussiness name" required autofocus>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="supplier-two-col">
                            <div class="supplier-field">
                                <i class="bi bi-person"></i>
                                <input id="modal_contact_name" type="text" class="@error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name') }}" placeholder="Bussiness Owner Name" required>
                                @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="supplier-field">
                                <i class="bi bi-telephone"></i>
                                <input id="modal_phone" type="text" class="@error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Mobile Number" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="supplier-two-col">
                            <div class="supplier-field">
                                <i class="bi bi-envelope"></i>
                                <input id="modal_email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Business Email" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="supplier-field">
                                <i class="bi bi-geo-alt"></i>
                                <input id="modal_country" type="text" class="@error('country') is-invalid @enderror" name="country" value="{{ old('country', 'India') }}" placeholder="Country" required>
                                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="supplier-field">
                            <i class="bi bi-grid"></i>
                            <select id="modal_product_type" name="product_type" class="@error('product_type') is-invalid @enderror" required>
                                <option value="" disabled @selected(old('product_type') === null)>Product Category</option>
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('product_type') === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="supplier-field">
                            <i class="bi bi-box-seam"></i>
                            <input id="modal_product_interest" type="text" class="@error('product_interest') is-invalid @enderror" name="product_interest" value="{{ old('product_interest') }}" placeholder="Product / Requirement Details" required>
                            @error('product_interest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="requirement-radio-group" aria-label="Business type">
                            <label class="requirement-radio" for="modal_business_type_buyer">
                                <input id="modal_business_type_buyer" type="radio" name="business_type" value="buyer" @checked(old('business_type', 'buyer') === 'buyer') required>
                                <span>Buyer</span>
                            </label>
                            <label class="requirement-radio" for="modal_business_type_supplier">
                                <input id="modal_business_type_supplier" type="radio" name="business_type" value="seller" @checked(old('business_type') === 'seller') required>
                                <span>Supplier</span>
                            </label>
                            @error('business_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <label class="terms-line" for="modal_terms_accept">
                            <input id="modal_terms_accept" type="checkbox" required>
                            <span class="terms-check" aria-hidden="true"><i class="bi bi-check-lg"></i></span>
                            <span>
                                By submitting, I agree to Trade4Deal
                                <a href="{{ route('marketplace.page', ['page' => 'terms-of-use']) }}">Terms of Use</a>,
                                <a href="{{ route('marketplace.page', ['page' => 'privacy-policy']) }}">Privacy Policy</a>
                                and receive business/service communication about my requirement.
                            </span>
                        </label>

                        <button type="submit" class="supplier-submit">Submit Requirement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
