# Deploy Trade4Deal to GoDaddy

The workflow validates pull requests and main pushes before building a release. Deployment uses verified SSH/SCP, keeps shared data outside release directories, and switches a `current` symlink after migrations succeed. Interrupted or failed validation cannot upload application files. Deployment jobs queue instead of canceling an active release.

## Required host setup

This workflow requires Linux SSH access, PHP 8.2+, `bash`, `tar`, `flock`, `curl`, and GNU `mv -T`. If the hosting plan supports only FTP, enable/migrate to an SSH-capable plan before deploying; plaintext FTP is no longer supported.

Choose a dedicated application directory outside the public web root, for example `/home/account/apps/trade4deal`. Create `shared/.env` there with mode 600. Copy the existing application's storage into `shared/storage`, preserving uploads, before the first migration. Configure the domain document root as `/home/account/apps/trade4deal/current/public`. Never serve the repository root. The web user must be able to write shared storage and release `bootstrap/cache`.

Production settings must include `APP_ENV=production`, `APP_DEBUG=false`, the correct HTTPS `APP_URL`, `SESSION_SECURE_COOKIE=true`, a stable `APP_KEY`, working database settings, and a delivery mailer (SMTP/API). The deploy preflight rejects debug/log-only mail configurations. Configure and supervise a queue worker against `current/artisan queue:work`; the release script restarts workers after activation. Configure cron if scheduled tasks are added.

If the domain document root cannot follow a symlink, adapt the hosting setup before enabling this workflow. Do not copy the repository into `public_html` as a substitute.

## GitHub production environment secrets

Create a `production` environment with deployment restrictions appropriate to the repository, and add:

| Secret | Value |
| --- | --- |
| `DEPLOY_HOST` | SSH server hostname |
| `DEPLOY_USER` | Dedicated deployment account |
| `DEPLOY_PORT` | SSH port (defaults to 22) |
| `DEPLOY_SSH_KEY` | Private key authorized for that account |
| `DEPLOY_KNOWN_HOSTS` | Pinned known-hosts entry independently verified with the host/provider; include `[host]:port` for a custom port |
| `DEPLOY_ROOT` | Absolute application root such as `/home/account/apps/trade4deal` |
| `DEPLOY_HEALTH_URL` | HTTPS application health URL, such as `https://trade4deal.com/up` |

The workflow accepts simple paths/hostnames and an HTTPS health URL without query parameters. It fails closed if required secrets are absent. Old `FTP_*` secrets are unused and can be retired after migration. SSH host verification is mandatory.

## Release process and rollback

CI runs locked dependency installs, both security audits, the frontend build, the PHP suite (including GD/SQLite), and deployment-script syntax checks. Only validated main releases enter the production job. The archive contains production PHP dependencies and compiled frontend assets; it excludes credentials, tests, local uploads, caches, and development dependencies.

On the host, the script serializes deployments, extracts into a new release, links `.env`/storage, validates production configuration, caches routes/views, puts the old app into maintenance, runs `migrate --force`, switches `current` atomically, restores availability, checks HTTPS health, and restarts workers. The old release is retained. On activation/health failure it restores the previous application symlink and exits maintenance.

Back up the database before releases. Code rollback does **not** reverse schema/data migrations: deploy backward-compatible migrations and review schema changes before release. The initial conversion from an existing installation to shared storage/current is a host setup operation, not performed automatically by this script. No live deployment or host configuration has been performed from the local fix session.

For a deliberate code rollback, point `current` atomically at a retained compatible release, run its `artisan up`, and restart queue workers. Verify the database remains compatible. Retain and prune old releases according to your backup policy; the script does not automatically delete release directories or shared data.

## Security-fix rollout

Run the new `2026_10_16_000001_add_plan_override_to_users_table` migration as part of release. Subscription entitlements use Cashier records, while explicit administrator grants use `plan_override`. Old `users.plan=gold` values are not trusted because the former success URL could set them without payment. Review legacy manual Gold accounts and reapply legitimate grants in the admin UI. Existing active subscriptions continue to determine access.

Configure `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`, and `STRIPE_GOLD_PRICE_ID` together. Webhooks fail closed without a signing secret; checkout remains unavailable until configured. Keep the Stripe endpoint at `/stripe/webhook` and test signed activation, unpaid state, and cancellation using Stripe test mode before enabling live sales.

Razorpay checkout is also available for USD 174 / six months. Follow [Razorpay configuration and webhook instructions](RAZORPAY.md), including the purchase-ledger migration, automatic capture setting, live credentials for production and a separate webhook signing secret. Local test keys are not shipped by the workflow.

Default database seeding now creates roles only. For a new administrator use `php artisan app:create-admin admin@example.com` and enter a strong password interactively. Existing accounts are never reset by seeding. Rotate any previously deployed seeded admin/staff passwords and revoke their existing sessions. This rotation needs authorized server access and was not performed locally.

New registrations must verify email before dashboard, seller/staff tools, or checkout access. Configure delivery and test verification/reset emails. Existing previously auto-verified accounts are not proof of past email ownership; review/reverify them according to the business rollout policy rather than silently changing live accounts in a migration.
