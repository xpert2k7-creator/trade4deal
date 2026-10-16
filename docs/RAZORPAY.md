# Razorpay Gold checkout

Gold costs **USD 174.00 for six months**, as confirmed for this integration. It is a one-time purchase with no automatic renewal. The server creates a Razorpay order for **17400 cents in USD**; request/browser amounts are never trusted. Enable USD/international payments on the Razorpay account for live use. Do not substitute an INR amount without a separate approved price.

## Configuration

Set these only in the local/server environment, never in source control:

```dotenv
RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
RAZORPAY_WEBHOOK_SECRET=
RAZORPAY_GOLD_AMOUNT=17400
RAZORPAY_GOLD_CURRENCY=USD
```

The supplied **test** key pair was stored in the ignored local `.env`; it is not included in this document or the example environment file. The checkout displays a test-mode banner when the key ID starts with `rzp_test_`. PHPUnit forces separate fake credentials and uses fake HTTP responses. No Razorpay PHP SDK is needed; the integration uses Laravel's HTTP client against the fixed official API endpoint.

Run `php artisan migrate` when releasing the code. This adds the purchase ledger and the earlier admin plan-override column. The verified local SQLite database was backed up before applying these two additive billing migrations; production data was not changed.

Configure automatic capture in the Razorpay Dashboard. Authorized-only payments do not grant access. The browser submits Razorpay's payment/order IDs and signature to the same application with CSRF protection. The server checks order ownership, verifies HMAC using the stored order ID, fetches the payment from Razorpay, and checks captured status, amount, currency and order association before granting six calendar months. Reloads and repeated callback/webhook delivery do not extend an existing grant. Expired purchases no longer grant Gold even if an old cached plan value says Gold.

## Webhooks and recovery

Add a Razorpay webhook URL for the accessible HTTPS site:

```text
https://YOUR_DOMAIN/razorpay/webhook
```

Subscribe to `payment.captured`, `order.paid`, `payment.refunded`, and `refund.processed`. Choose a **separate webhook signing secret** in the Razorpay Dashboard and set the same value in `RAZORPAY_WEBHOOK_SECRET`. This secret is different from the API key secret; it was not provided during integration, so no value was invented. Until configured, the endpoint returns 503 and fails closed. Direct browser verification works with the API key pair, but webhooks are needed to recover payments when the browser closes or a verification request times out.

The webhook verifies the signature over the raw request body, then retrieves current payment state from Razorpay. Unknown purchases are ignored. Duplicate events and old capture notifications cannot reactivate a purchase already marked refunded. Full refunds revoke that purchase's access; partial refunds retain access. Other paid subscriptions and explicit administrator grants remain independent. A Free administrator override blocks checkout and paid entitlements until support resolves the account restriction.

Production preflight rejects test keys or a missing API/webhook secret when Razorpay is enabled. Configure live keys and verified webhook delivery separately before accepting real payments. If environment config is cached after changing keys, rebuild the config cache as part of deployment. Existing Stripe checkout remains available when its price is configured; Razorpay is the primary button on `/plans`.

## Verification and limits

The supplied test credentials authenticated successfully via the official API. An actual USD 174 test order was accepted; no card payment or real charge was made. Automated tests cover server-owned pricing, owner-only checkout, signature rejection, mismatched payment data, capture, expiry, duplicate delivery, refunds, restricted/unverified accounts, and provider errors. Complete a payment in Razorpay's hosted test checkout and verify webhook delivery against an externally accessible URL before going live. Full end-to-end hosted payment completion has not been performed by this agent.

Provider references: [Standard Checkout integration](https://razorpay.com/docs/payments/payment-gateway/web-integration/standard/integration-steps/), [Orders API](https://razorpay.com/docs/api/orders/create/), [payment lookup](https://razorpay.com/docs/api/payments/fetch-with-id/), and [webhook validation](https://razorpay.com/docs/webhooks/validate-test/).
