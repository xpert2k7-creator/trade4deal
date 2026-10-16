# Trade4Deal project audit

Date: 16 October 2026 (Asia/Kolkata). Branch: `main`. Reviewed commit: `4703ef0`.

## Remediation status

Subsequent feature: [Razorpay Gold checkout](RAZORPAY.md) added at the confirmed USD 174 / six-month price. Final combined regression suite: **165 tests, 718 assertions**. Test credentials authenticated and the provider accepted a USD test order; no real payment was made. Local additive billing migrations were applied after a SQLite snapshot. Hosted checkout completion and webhook dashboard setup remain external rollout checks.

All 15 findings have corresponding repository fixes. The original findings and verification below describe the **pre-fix baseline**, retained for traceability.

| Finding | Implemented change |
| --- | --- |
| A01 | Removed the unconditional success-URL Gold grant; pending payments show a verification message. |
| A02 | One automatically discovered `WebhookHandled` listener; subscription access is derived from current Cashier records; separate `plan_override` stores explicit admin decisions; unsigned/unconfigured webhooks fail closed. |
| A03 | Default seed runs provision roles only; interactive `app:create-admin` has no preset credentials or password-reset side effects. |
| A04 | Public requirement form collects real email, country, and category instead of hidden substitute identities. |
| A05 | Updated compatible PHP packages/lockfile; Composer audit reports zero advisories. |
| A06 | Migrated Tailwind tooling to v4 with existing-template compatibility adjustments and patched remaining npm tooling; npm audit reports zero vulnerabilities. |
| A07 | Removed FTP deployment; production deployment uses SCP/SSH with pinned host keys. |
| A08 | Active status is checked at login and throughout existing web sessions. |
| A09 | Registration remains unverified until email confirmation; dashboard, checkout, seller and staff tools require verification. |
| A10 | Seller/admin updates validate normalized phone uniqueness; model saving keeps normalization synchronized; database conflicts become validation errors. |
| A11 | Shared file transaction stages uploads, cleans new files on save failure, and deletes superseded files after commit; applies to seller/admin profiles, lead images, product galleries, and specification files. Queued work waits for commit. |
| A12 | Geocoder connection/timeout errors fall back to local search or controlled reverse-lookup failure. |
| A13 | Public location suggestions/options use the public active-listing scope; bumped search cache version to avoid retaining old private suggestions. |
| A14 | CI gates deployment on tests, locked installs, security audits and builds; validated archives deploy to separate releases with a migration/health check and application-symlink rollback. |
| A15 | Seller dashboard uses SQL counts and five recent products; staff lists paginate with grouped totals and one selected-member chart; location options are capped at 100. |

**Final local verification:** 156 tests passed, 659 assertions; production frontend build passed; 213 PHP files parsed without syntax errors; Composer and npm audits both clean. Workflow YAML parsed (2 jobs, 11 shell commands), and shell commands passed syntax validation. Deployment simulation passed successful activation, migration failure, and health-check rollback. Git Bash lacks `flock`, so local simulation stubbed lock acquisition; Linux CI exercises the real command. Real SSH/hosting/payment/SMTP behavior has not been tested from this workstation. UI templates are covered by HTTP rendering tests; no browser visual comparison was performed for the Tailwind migration.

**Required production rollout:** configure the new SSH secrets and shared storage/document root, run the new `plan_override` migration, confirm working verification email and Stripe webhook delivery, rotate previously seeded credentials/revoke old sessions, and review legitimate legacy manual Gold grants. Old `users.plan=gold` alone is deliberately no longer proof of entitlement. Existing active subscriptions remain valid. The migration does not automatically trust/recreate legacy grants or alter existing email verification timestamps. See [deployment and rollout instructions](DEPLOY-GODADDY.md). No production deployment or credential rotation was performed locally.

Current raw verification: `storage/logs/fix-tests.txt`, `fix-junit.xml`, `fix-composer-audit.json`, and `fix-npm-audit.json`. Earlier `audit-*` outputs and defect probes are historical evidence, not post-fix expectations.

## Original assessment before fixes

The application has useful separation between requests, policies, domain services, and persistence, and existing tests cover several role boundaries. However, billing authorization currently permits an unpaid account to obtain Gold access. Address the billing defects before relying on paid lead access in production.

This repository review identified **15 findings: 1 critical, 5 high, 7 medium, and 2 low**. Severity reflects application impact and exposure; dependency registry severity is recorded separately and does not by itself prove an exploitable application path.

## Scope and limitations

Reviewed authentication and registration, route middleware, policies, user/staff management, lead creation/moderation/visibility, seller profiles, product validation and uploads, dynamic fields, migrations, notifications, Stripe integration, frontend templates/build configuration, dependency locks, and the GoDaddy deployment workflow. Ran the existing test suite, targeted local defect probes, a production frontend build, Composer validation/platform checks, and PHP parsing checks.

No production database, real payment, outgoing email, production account changes, or deployment was used. Runtime probes used SQLite in memory, fake HTTP responses, and test accounts. Production secrets, actual server document root, TLS configuration, backup restoration, and real Stripe/SMTP/queue operation remain unverified. This is a repository audit, not a production penetration test or a visual/mobile accessibility certification.

During the initial audit, application source and lockfiles were not changed. Two missing PHP dependencies were installed at their existing locked versions to prepare verification. Audit probes and raw outputs are in ignored `storage/logs/`. The subsequent remediation changes are summarized above.

## Findings

### A01 — Critical: visiting the checkout success URL grants Gold without payment

**Evidence:** `app/Http/Controllers/Web/PlanController.php:44–50`; `routes/web.php:71–74`; `tests/Feature/Plan/GoldPlanTest.php:test_success_route_upgrades_user_to_gold`.

Any authenticated account can request `GET /plans/gold/success`. The controller never validates a Checkout Session, customer ownership, payment status, or paid subscription. After subscription synchronization it forcibly sets `plan=gold`. The existing test explicitly expects this behavior without a session ID. A separate audit probe confirmed Gold with zero subscriptions.

**Impact:** bypass of paid lead visibility and other Gold benefits.

**Remediation:** remove the unconditional grant. Grant paid entitlements only from verified server-side payment/subscription state, check session ownership if displaying checkout completion, and handle delayed webhook delivery without granting access speculatively. Replace the existing test with unpaid/foreign-session rejection and verified-payment cases. See [Stripe fulfillment guidance](https://docs.stripe.com/checkout/fulfillment).

### A02 — High: subscription synchronization runs before Cashier updates its records

**Evidence:** `app/Listeners/SyncUserPlanFromStripeWebhook.php:13–44`; `app/Providers/DomainServiceProvider.php:55–58`; `app/Models/User.php:133–151`. Installed Cashier dispatches `WebhookReceived` before invoking its subscription handler.

The listener computes the plan from the previous subscription state. A probe changed an active subscription to `unpaid` through the actual webhook route: Cashier stored `unpaid`, `subscribed('gold')` became false, but `userPlan()` still returned Gold because the cached `users.plan` remained Gold. Initial subscription activation can similarly leave the persisted plan behind. `php artisan event:list` also showed this listener registered twice, through explicit registration and automatic discovery.

**Remediation:** synchronize after Cashier handles the event, register the listener once, distinguish manual Gold grants from subscription-derived access, and test activation, unpaid state, expiration, cancellation, and duplicate delivery. Also enforce a configured webhook signing secret before enabling billing: Cashier only attaches its signature middleware when that secret is present. No claim is made that the production secret is missing. See [Cashier webhooks](https://laravel.com/docs/12.x/billing#handling-stripe-webhooks).

### A03 — High: default seeding creates privileged accounts with fixed passwords

**Evidence:** `database/seeders/DatabaseSeeder.php:23–66`.

The default seeder creates admin/employee accounts using publicly readable fixed passwords. One employee account uses `updateOrCreate`, so rerunning that section can reset its password to the fixed value. Production exposure depends on whether these seed credentials have been used or retained; no production login was attempted.

**Remediation:** separate demo/test seeders from production bootstrap. Use an explicit one-time admin provisioning process with a supplied/generated secret and avoid resetting existing credentials during seeding. Rotate any deployed seeded credentials.

### A04 — High: the public requirement form saves the platform inbox as the submitter's email

**Evidence:** `resources/views/marketplace/partials/lead-modal.blade.php:620–627`; `app/Http/Controllers/Web/LeadController.php:53–54`; lead notification listeners.

The main requirement form collects no email. It posts the same hidden platform inbox for every submitter and silently defaults country to India and category to `other`. Enquiries and approval/rejection notifications use the saved lead email, so this path sends them to the platform rather than the business owner. Category matching and country information are also inaccurate unless staff correct them.

**Remediation:** collect the actual business email and relevant country/category, or visibly confirm prefilled account details. If the platform is intentionally brokering enquiries, store the requester identity separately and implement an explicit forwarding workflow.

### A05 — High: PHP lockfile contains known security advisories

`composer audit --locked --format=json` returned **17 advisories across 6 packages**:

| Package | Locked version | Advisories |
| --- | --- | ---: |
| laravel/framework | 12.64.0 | 1 |
| league/commonmark | 2.8.3 | 12 |
| league/flysystem | 3.35.2 | 1 |
| livewire/livewire | 4.3.3 | 1 |
| symfony/http-foundation | 7.4.14 | 1 |
| symfony/yaml | 7.4.14 (development dependency) | 1 |

Reported classes include XSS, Markdown denial of service, request-header parsing denial of service, and path normalization issues. Reachability varies: some CommonMark reports require optional extensions or attacker-controlled Markdown, while the Symfony header advisory concerns request processing. Registry counts are not counts of independently proven application exploits.

**Remediation:** update affected packages within supported compatible releases, review advisory-specific conditions, rerun the test suite, and repeat the audit. Do not blindly perform major upgrades. Raw advisory IDs, affected ranges, titles, and links: `storage/logs/audit-composer.json`. Example primary advisory: [Symfony HTTP Foundation](https://symfony.com/cve-2026-108082).

### A06 — Medium: npm development tools have security advisories

`npm audit --json` reported **10 affected package entries: 2 critical, 6 high, 2 moderate**. Critical entries are `shell-quote` and its dependent `concurrently`; the remaining entries include `braces`, `chokidar`, `micromatch`, `fast-glob`, `source-map-js`, and Tailwind-related dependencies. These are development dependencies, and this audit did not establish a production request path into the affected parsers. Dependent package entries can refer to the same underlying advisory.

**Remediation:** update tooling and transitive dependencies deliberately, retain lockfile reproducibility, and validate CSS/build output. The registry suggests a major Tailwind upgrade for some chains, which needs separate compatibility work. Do not use a forced automatic fix as a substitute for review. Raw evidence: `storage/logs/audit-npm.json`.

### A07 — High: production deployment uses plaintext FTP

**Evidence:** `.github/workflows/deploy-godaddy.yml:40–50` sets `protocol: ftp` and port 21.

The workflow does not select encrypted FTP transport. Credentials and uploaded application files are exposed to interception on the transport path.

**Remediation:** configure the host's supported encrypted transport, such as FTPS with the current action or an SFTP/SSH deployment mechanism, and verify certificate/host identity. Confirm actual provider capabilities before changing the workflow.

### A08 — Medium: inactive accounts can authenticate

**Evidence:** `app/Http/Requests/Auth/LoginRequest.php:45`; no account-status middleware is installed in `bootstrap/app.php`.

Login checks only email/password. A local test successfully authenticated an account whose status was `RecordStatus::Inactive`. Role middleware does not enforce this status. This is distinct from soft deletion, which uses Eloquent's deleted-row filtering.

**Remediation:** include active-account checks during authentication and on authenticated requests so an already established session also stops working after account deactivation.

### A09 — Medium: email verification is bypassed by design

**Evidence:** `app/Domains/Auth/Services/AuthService.php:31–32`; `routes/web.php` authenticated route groups.

Registration sets `email_verified_at=now()` without proving ownership, and protected marketplace/dashboard routes do not require `verified`. The source comment says this was done to allow access without SMTP. Consequently, verification status cannot currently be treated as evidence that a business owns an email address.

**Remediation:** send the verification notification, leave verification null until the signed link is used, and decide which sensitive actions require a verified address. Keep any local SMTP bypass limited to an explicit local/testing setting.

### A10 — Medium: seller phone updates break normalized uniqueness

**Evidence:** `app/Domains/Seller/Requests/UpdateSellerProfileRequest.php:39`; `app/Http/Controllers/Seller/SellerDashboardController.php:111–115`; the equivalent admin seller-update flow; `app/Domains/Auth/Requests/RegisterUserRequest.php:43–66`.

Registration checks uniqueness against `phone_normalized`, but seller/admin profile updates change `phone` and country without updating that field or validating normalized uniqueness. A local probe confirmed that changing the visible phone leaves the previous normalized number stored. This can block registration for a released number and permit duplicate visible numbers.

**Remediation:** centralize normalization whenever phone/country changes, validate the normalized value while excluding the current account, retain the database unique constraint, and handle conflicts as validation errors.

### A11 — Medium: replacing uploads can delete the old file before the new save succeeds

**Evidence:** `app/Http/Controllers/Seller/SellerDashboardController.php:81–108`; `app/Http/Controllers/Employee/UserManagementController.php:79–106`; `app/Domains/Product/Services/ProductDynamicFieldService.php:syncFileField`; `app/Domains/Product/Services/ProductImageStorage.php:mergeUploadedImagesIntoProductData`; lead image replacement in `LeadModerationController`.

Several update paths delete existing files before storing replacements or committing model changes. On disk failure or a later database rollback, records can still reference deleted files. The dynamic-file path even returns silently after a failed replacement, leaving the old database path behind.

**Remediation:** store new files first, persist changes successfully, then remove superseded files after commit. Clean up newly written files on failure. Add storage-failure and database-rollback coverage.

### A12 — Medium: geocoder network failures cause HTTP 500

**Evidence:** `app/Http/Controllers/Web/LocationController.php:336–349`.

The controller handles non-OK HTTP responses, but not connection/timeout exceptions from `Http::get()`. A simulated provider outage returned 500 from location search. `.env.example` enables the external provider, so the failure can affect ordinary searches where it is configured.

**Remediation:** catch connection failures, preserve curated/local results for search, return a controlled unavailable response for reverse lookup, and log provider failure without exposing internal exception details.

### A13 — Low: public location suggestions include private/draft product locations

**Evidence:** `app/Http/Controllers/Web/LocationController.php:32–47`; `app/Http/Controllers/Web/MarketplacePageController.php:136–143`.

The location queries omit the active-product/public-active-seller filters used for product listings. A local probe retrieved the city of a draft product owned by a nonpublic seller through the public search endpoint. This reveals location vocabulary, not the full private listing or seller identity.

**Remediation:** reuse the public listing scope for location suggestions/options, unless location aggregation from private inventory is explicitly intended.

### A14 — Medium: deployment has no automated test gate and updates files in place

**Evidence:** `.github/workflows/deploy-godaddy.yml`; `docs/DEPLOY-GODADDY.md`.

Every push to main builds and deploys without running the existing PHP tests. It installs dependencies with `npm install` instead of `npm ci`. FTP updates the live directory file by file and `cancel-in-progress: true` can interrupt a deployment. Database migration/cache steps are documented as manual follow-up, so code can become live before its schema is ready. No atomic release/rollback mechanism is present in this repository.

**Remediation:** add a separate test job with needed PHP extensions before deployment, use the npm lockfile deterministically, serialize deployment safely, and define an appropriate migration/maintenance/rollback process for the host. Confirm the server serves only Laravel's `public` directory; the current `public_html` example does not prove the real document-root configuration. See [Laravel deployment guidance](https://laravel.com/docs/12.x/deployment).

### A15 — Low: several dashboard/options queries grow without bounds

**Evidence:** `app/Http/Controllers/Seller/SellerDashboardController.php:39`; employee/sourcing team controller `index()` methods; `app/Http/Controllers/Web/MarketplacePageController.php:136–149`.

The seller dashboard loads every product to compute counts and show five recent products. Team pages load all members and execute four aggregate queries per member. The product directory loads all distinct locations into memory on each request. These are maintainability/performance risks as inventory and staff counts grow; no production load benchmark was run.

**Remediation:** compute counts in SQL, fetch recent products with a limit, paginate staff, group counts across members, and cap/cache or search location options.

## Baseline verification results before fixes

- `npm run build`: **passed**, Vite built 59 modules and emitted the production manifest/CSS/JS.
- `composer validate --no-check-publish`: **passed**.
- `composer check-platform-reqs`: **passed** for the installed vendor set; this does not check optional extensions required only by tests.
- Parsed **205 PHP files** under app/bootstrap/config/database/routes/tests with `TOKEN_PARSE`: **no syntax errors**.
- `php artisan route:list --except-vendor --json`: **passed**, 73 application routes inspected.
- Six targeted defect probes: **6 reproduced, 20 assertions**. They assert observed defective behavior; a passing probe is evidence of the defect, not a security pass.
- Existing test suite after environment preparation: **141 passed, 605 assertions**, 29.267 seconds; no errors or failures. Run with `php -d extension=gd vendor/phpunit/phpunit/phpunit --do-not-cache-result --log-junit storage/logs/audit-junit.xml`, a separate compiled-view directory, and normal local filesystem permissions.

Initial suite runs encountered missing locked phone-number dependencies, a disabled GD extension, and Windows sandbox permissions on existing compiled views/test-upload directories. All were resolved for the final run: Composer completed installation/autoload generation, GD was enabled per command, and tests ran with normal local filesystem permissions. No system PHP configuration was edited. These initial failures were local verification constraints rather than application defects. Test databases use SQLite `:memory:` from `phpunit.xml`.

The green existing suite does not invalidate the security findings: it currently expects the unsafe checkout-success upgrade, and does not cover the six negative scenarios exercised by the separate audit probes.

## Controls observed working

The reviewed paths use validated request data and explicit policies for sensitive user/product operations. Tests exercise several guest/buyer/employee/admin boundaries and product ownership checks. Password hashing is configured on the User model. CSRF validation remains enabled for web routes, with the Stripe webhook explicitly exempted; upload requests constrain MIME types and sizes. No real `.env` or private-key file was found in the current tracked-file inventory. Git history was not scanned for historical secrets.

Local raw evidence: `storage/logs/audit-probes.txt`, `AuditChecksTest.php`, `audit-tests.txt`, `audit-junit.xml`, `audit-npm.json`, `audit-composer.json`, `audit-events.txt`, and `audit-routes.json`.

## Suggested order of work

1. Correct A01/A02 and add negative billing tests. Remove production dependence on seeded credentials (A03).
2. Correct requirement contact capture (A04), then update vulnerable PHP dependencies and deployment transport (A05/A07).
3. Correct phone/account verification rules and upload failure handling; add a CI test gate before further releases.
4. Fix geocoder resilience, narrow location queries, and improve bounded query patterns/tooling.

The initial audit was read-only apart from its report and verification setup. Repository fixes have since been implemented as recorded in the remediation status above; production deployment remains a separate rollout.
