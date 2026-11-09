# Linkan.ID

**Linkan.ID** is an all-in-one Indonesian creator commerce platform featuring a modular microsite builder, click-tracked shortlinks, and instant digital product checkout via Midtrans.

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Tests Passing](https://img.shields.io/badge/Tests-159%20passed-success)](https://github.com/azhrielmuari/linkan-id)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**Status:** Actively Maintained · **Audience:** Indonesian creators, digital sellers, and developers seeking an extensible self-hosted link-in-bio and digital commerce stack.

---

## Overview

Linkan.ID unifies three fragmented tools into a single self-hosted application:
- **Modular Microsite Builder**: Create customizable `linkan.id/{username}` pages with text, image, video, social media, and divider elements.
- **Digital Product Commerce**: Sell e-books, templates, and courses with direct IDR payment gateways (QRIS, GoPay, Virtual Accounts via Midtrans Snap) and automated transactional email delivery.
- **Analytics & Shortlinks**: Generate branded shortlinks with click tracking, referer logging, device breakdown, and optional password protection.
- **Multi-Role Backoffice**: Dedicated portals for sellers (`/{locale}/admin/*`) and platform administrators (`/{locale}/platform-admin/*`).

---

## Why Linkan.ID?

| Feature / Axis | Linkan.ID | Linktree / Lynk.id | Bitly / Generic Shorteners |
| :--- | :--- | :--- | :--- |
| **Platform Commission** | **0%** (Direct to your Midtrans merchant account) | 5% – 12% revenue cut | N/A (Links only) |
| **Data Ownership** | **100% self-hosted** (SQLite/MySQL on your infra) | Proprietary cloud lock-in | Proprietary cloud lock-in |
| **All-in-One Engine** | Bio-link + Digital Downloads + Shortlinks | Separate subscriptions needed | Shortlinks only |
| **Payment Ecosystem** | Native Indonesian payments (QRIS, GoPay, VA) | Limited / overseas processors | None |
| **Queue & Throughput** | Background job fulfillment (`SendDigitalProductEmailJob`) | Closed blackbox | N/A |

> [!NOTE]
> **What Linkan.ID deliberately does NOT do:** It is not a generic blogging CMS or a general social network. It is laser-focused on creator bio-links, tracked shortlinks, and friction-free digital product checkout.

---

## Quickstart & Local Setup

### Prerequisites
- **PHP**: 8.2 or 8.3 with `pdo_sqlite` (or `pdo_mysql`), `mbstring`, `openssl`, `curl`, and `xml` extensions
- **Composer**: 2.x
- **Node.js**: >= 20.x & npm
- **Database**: SQLite (default local) or MySQL 8.0+

### 1-Minute Local Setup

Run the following commands in your terminal:

```bash
# 1. Clone the repository
git clone https://github.com/azhrielmuari/linkan-id.git && cd linkan-id

# 2. Install PHP and JavaScript dependencies
composer install
npm install

# 3. Configure environment variables
cp .env.example .env
php artisan key:generate
php artisan storage:link

# 4. Initialize database and run migrations
touch database/database.sqlite
php artisan migrate --seed

# 5. Build frontend assets and start local dev server
npm run build
php artisan serve
```

Once running, access the application in your browser at `http://localhost:8000/id`.

### Evaluation Credentials
When running with default seeders:
- **Default Seed User**: `test@example.com` / `password`
- **Registration**: Visit `http://localhost:8000/id/register` to register a new seller account.
- **Seller Dashboard**: `http://localhost:8000/id/admin/dashboard`
- **Platform Admin**: `http://localhost:8000/id/platform-admin/dashboard`

---

## Verification & Test Suites

The repository contains an automated safety net with 100% pass rates across backend and frontend suites.

```bash
# Run backend PHPUnit test suite (159 tests, 914 assertions)
./vendor/bin/phpunit

# Run frontend Vitest suite (17 tests)
npm run test:js

# Run End-to-End Playwright test suite (requires active server)
npm run test:perf
```

---

## Architecture & Key Seams

Linkan.ID follows John Ousterhout's philosophy of **Deep Modules**, isolating complexity behind minimal interfaces:

| Module / Seam | Location | Responsibility & Leverage |
| :--- | :--- | :--- |
| **Microsite Element Manager** | `app/Services/AdminSeller/MicrositeElementManager.php` | Deep module managing lifecycle, ordering, and payload validation for all 6 element types. |
| **Checkout & Fulfillment** | `app/Services/CheckoutService.php` | Coordinates order creation, Midtrans Snap token generation, and post-payment status updates. |
| **Asynchronous Delivery** | `app/Jobs/SendDigitalProductEmailJob.php` | Queued job with exponential backoff for transactional product delivery, decoupling SMTP latency. |
| **Request Correlation** | `app/Http/Middleware/AssignRequestId.php` | Assigns unique UUID to every HTTP request for end-to-end audit logging in `storage/logs/`. |
| **Bilingual Routing** | `routes/web/*.php` | Explicit `{locale}` route prefixes (`id` / `en`) with automatic fallback. |

---

## Project Status & Roadmap

- **Status**: Actively Maintained (`v1.0.0-dev`).
- **Stability Guarantee**: SemVer compatibility on all internal domain services and HTTP controller contracts.
- **Current Roadmap**:
  - [x] Asynchronous email delivery queue integration (`afterCommit`).
  - [x] Comprehensive technical SEO audit, dynamic XML sitemaps, and Schema.org JSON-LD.
  - [ ] Webhook signature verification for Midtrans production endpoints.
  - [ ] Multi-currency conversion for cross-border digital downloads.

---

## Documentation & Resources

- [Use Case Scenarios](docs/USE_CASE_SCENARIOS.md) - Detailed breakdown of all functional workflows and actor permissions.
- [Walkthrough Guide](walkthrough.md) - Architectural overview and test verification procedures.
- [Database Seeders](database/seeders/DatabaseSeeder.php) - Initial database configuration.

---

## Contributing & License

Distributed under the **MIT License**. See [LICENSE](LICENSE) for full details.

- **Maintainer**: Azhriel Muari / IndoBuzz Team
- **Contributions**: Contributions, bug reports, and pull requests are welcome. Please ensure existing test suites pass before submitting PRs.
