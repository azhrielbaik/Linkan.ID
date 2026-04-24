# Shortlink Analytics Page Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add a dedicated analytics page for each shortlink with a click chart, hide sources from the shortlink index, and add a button from the index to open that analytics page.

**Architecture:** Extend the shortlink controller with an owner-scoped analytics detail page and a chart-data endpoint keyed to a single shortlink. Reuse the existing dashboard chart pattern with a lightweight Blade page, keep redirect tracking unchanged, and simplify the shortlink index table by removing inline source summaries.

**Tech Stack:** Laravel, Blade, Chart.js, PHPUnit

---

### Task 1: Add failing feature tests

**Files:**
- Modify: `tests/Feature/ShortlinkAnalyticsTest.php`

**Step 1: Add index expectations**

Assert the shortlink index no longer shows inline source values and instead shows a `Lihat Analitik` button or link for the owner's shortlink.

**Step 2: Add analytics page expectations**

Assert `/shortlink/{shortlink}/analytics` shows only the owner's shortlink analytics, includes total clicks, source summary, and returns chart JSON from a dedicated endpoint.

**Step 3: Run test file and verify failure**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: FAIL because the routes and analytics page do not exist yet.

### Task 2: Add owner-scoped analytics endpoints

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/ShortlinkController.php`

**Step 1: Add routes**

Add authenticated routes for analytics page and analytics chart data.

**Step 2: Add analytics page query**

Load one shortlink owned by the logged-in user, compute total clicks, grouped sources, and the default 7-day range.

**Step 3: Add chart-data endpoint**

Return daily click totals for the selected shortlink with optional start/end date filters.

### Task 3: Build the analytics page and simplify the index

**Files:**
- Modify: `resources/views/shortlink/create.blade.php`
- Create: `resources/views/shortlink/analytics.blade.php`

**Step 1: Remove source column from index**

Hide source summaries from the list page.

**Step 2: Add analytics button**

Add a link/button in each row to open the per-shortlink analytics page.

**Step 3: Build analytics page**

Show shortlink metadata, total clicks, source summary, and a Chart.js daily clicks graph with date filters.

### Task 4: Verify

**Files:**
- Modify: `.opencode/CHANGELOG.md`

**Step 1: Run focused tests**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: PASS

**Step 2: Run full tests**

Run: `php artisan test`
Expected: all tests pass

**Step 3: Update changelog**

Append a concise summary of the analytics page changes.
