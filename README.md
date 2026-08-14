# Haivora Logistics - Native WordPress Theme

**Company Branding:** Qidex Express LOGISTICS  
**Theme Name:** Haivora Logistics  
**Version:** 1.7.0 (Phase 7 Release)  
**License:** GPL v2 or later  

---

## 🚚 Overview

**Haivora Logistics** is a production-grade, high-performance native WordPress theme designed specifically for international logistics companies, freight forwarders, cargo ocean liners, air express couriers, and supply chain management platforms.

Phase 7 adds complete **API and Payment-Ready Architecture** with WordPress REST API endpoints (`haivora/v1`), carrier integration wrappers (DHL, FedEx, UPS, Aramex), multi-provider payment processing (Flutterwave, Paystack, Stripe), cryptographic webhook verification, and administrative financial transaction management.

---

## 🔌 WordPress REST API Architecture (`haivora/v1`)

The theme registers custom REST routes under namespace `haivora/v1` using `register_rest_route()`:

| Method | Endpoint | Description | Permission Callback |
| :--- | :--- | :--- | :--- |
| `GET` | `/wp-json/haivora/v1/shipments` | List all shipments | Admin (`manage_options`) / Logged-in User |
| `GET` | `/wp-json/haivora/v1/shipments/{id}` | Get single shipment details | Admin / Cargo Owner |
| `POST` | `/wp-json/haivora/v1/shipments` | Register & dispatch new shipment | Admin / Authorized Client |
| `PUT` | `/wp-json/haivora/v1/shipments/{id}` | Update shipment status/telemetry | Admin only (`manage_options`) |
| `GET` | `/wp-json/haivora/v1/track/{code}` | Public tracking query (PII Masked) | `__return_true` (Public) |
| `POST` | `/wp-json/haivora/v1/payments/initiate` | Initiate payment transaction token | Public / Client Validation |
| `GET` | `/wp-json/haivora/v1/payments/transactions` | Retrieve financial audit log | Admin only (`manage_options`) |
| `POST` | `/wp-json/haivora/v1/webhooks/payment` | Cryptographic Webhook Handler | Webhook Signature Check |

---

## 🚢 Carrier Logistics API Integration

**Status:** `READY FOR INTEGRATION`

The carrier layer (`inc/carrier-api.php`) provides unified abstractions for external logistics carrier APIs (DHL Express, FedEx Web Services, UPS Shipping, Aramex):

- `haivora_api_create_shipment($data)`
- `haivora_api_get_shipment($tracking_number)`
- `haivora_api_update_shipment($tracking_number, $data)`
- `haivora_api_track_shipment($tracking_number)`

To connect a live carrier account, define your carrier credentials in `wp-config.php` or environment variables:

```php
// wp-config.php
define('HAIVORA_CARRIER_PROVIDER', 'dhl_express');
define('HAIVORA_CARRIER_API_URL', 'https://express.api.dhl.com/v1');
define('HAIVORA_CARRIER_API_KEY', 'your_dhl_api_key');
define('HAIVORA_CARRIER_API_SECRET', 'your_dhl_api_secret');
define('HAIVORA_CARRIER_MODE', 'production');
```

---

## 💳 Payment Architecture & Webhooks

**Status:** `READY FOR INTEGRATION`

The payment module (`inc/payments.php`) supports multi-provider transactions across **Stripe**, **Flutterwave**, and **Paystack** for:
- Shipping payments
- Quote proposal payments
- Invoice payments

### Webhook Cryptographic Verification
Webhooks are received at `/wp-json/haivora/v1/webhooks/payment` and strictly validated before modifying transaction state:
- **Stripe:** Verifies `Stripe-Signature` header HMAC SHA256 against `HAIVORA_PAYMENT_WEBHOOK_SECRET`.
- **Paystack:** Verifies `X-Paystack-Signature` HMAC SHA512 against secret key.
- **Flutterwave:** Verifies `verif-hash` header match against `HAIVORA_PAYMENT_WEBHOOK_SECRET`.

> **Security Rule:** Transactions are NEVER marked as `Successful` based on frontend browser redirects alone. Payment confirmation is executed exclusively server-side upon cryptographic webhook validation.

### Payment Environment Configuration

```php
// wp-config.php
define('HAIVORA_PAYMENT_PROVIDER', 'stripe'); // 'stripe' | 'flutterwave' | 'paystack'
define('HAIVORA_PAYMENT_PUBLIC_KEY', 'pk_live_xxxxxxxxxxxx');
define('HAIVORA_PAYMENT_SECRET_KEY', 'sk_live_xxxxxxxxxxxx'); // SERVER ONLY - Never expose to frontend
define('HAIVORA_PAYMENT_WEBHOOK_SECRET', 'whsec_xxxxxxxxxxxx');
define('HAIVORA_PAYMENT_CURRENCY', 'USD');
define('HAIVORA_PAYMENT_MODE', 'live');
```

---

## ⚙️ Administrative Portal (`/shipment-admin`)

Navigate to `/shipment-admin` in your browser to access the complete dispatcher management suite:
1. **📦 Shipments:** Create, edit, and update live shipment telemetry and multi-milestone timeline events.
2. **📋 Quote Requests:** Review freight quote submissions, calculate rates, and send customer proposals.
3. **📥 Contact Inbox:** Read customer support messages and update response statuses.
4. **💳 Payments & Transactions:** Inspect financial transaction records, filter by gateway status, and issue refunds.
5. **🔑 API & Integrations:** Configure Carrier API endpoints and Payment Gateway credentials.
6. **💬 WhatsApp & Email:** Manage WhatsApp support floaters and audit live `wp_mail()` dispatches.

---

## 🔒 Security Best Practices

1. **Secret Key Isolation:** Secret keys (`HAIVORA_PAYMENT_SECRET_KEY`, `HAIVORA_CARRIER_API_SECRET`) are kept strictly server-side and never sent to client JS.
2. **Card Security:** No credit card or CVV details are ever stored or processed in theme database tables (PCI DSS compliance).
3. **PII Masking:** Public tracking queries on `/track/{code}` mask sensitive customer phone numbers and full addresses.
4. **Nonce & Sanitization:** All forms use WordPress nonces (`wp_nonce_field`) and strict input sanitization (`sanitize_text_field()`, `sanitize_email()`).

---

## 🗺️ Roadmap & Multi-Phase Progress

- ✅ **Phase 1:** Design System, WP Theme Core, Front Page Layout, Responsive Header & Footer.
- ✅ **Phase 2:** Shipment Custom Post Type (`shipment`), Custom Fields, Status Taxonomies, Meta Boxes.
- ✅ **Phase 3:** Interactive Tracking Search Engine, Status Timeline Component, PII Masking.
- ✅ **Phase 4:** Administrative Shipment Manager, Multi-milestone Event Repeater, Status Filters.
- ✅ **Phase 5:** Customer Authentication, Protected Dashboard, Real-time Status Notifications.
- ✅ **Phase 6:** Freight Quote Calculator, Contact Inbox, WhatsApp Widget, Email Audit Log (`wp_mail`).
- ✅ **Phase 7:** WordPress REST API (`haivora/v1`), Carrier API Architecture, Flutterwave/Paystack/Stripe Gateways, Cryptographic Webhook Handler.
- ⏳ **Phase 8:** Final Security Audit, Performance Optimization, XML Demo Content Import, Release Package.
