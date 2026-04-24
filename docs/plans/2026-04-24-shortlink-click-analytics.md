# Shortlink Click Analytics Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add per-shortlink click analytics with traffic source breakdown and show it in the shortlink dashboard.

**Architecture:** Store shortlink click events in a dedicated table keyed to `shortlinks.id` and `users.id`, capture request metadata during public shortlink redirects, and compute aggregate totals plus grouped sources in the shortlink index query. Keep the existing public redirect behavior intact while extending the dashboard view with analytics columns and source summaries.

**Tech Stack:** Laravel, Eloquent, Blade, MySQL migrations, PHPUnit

---

### Task 1: Add failing analytics feature tests

**Files:**
- Create: `tests/Feature/ShortlinkAnalyticsTest.php`
- Modify: `tests/TestCase.php` only if app bootstrap support is missing

**Step 1: Write redirect analytics test**

Add a feature test that creates a user and a shortlink, requests the shortlink URL with a `Referer` header, and asserts a click analytics row is written with the right shortlink owner, shortlink ID, referer, and normalized source.

**Step 2: Run only the new test and verify failure**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: FAIL because analytics table or tracking code does not exist yet.

### Task 2: Add analytics storage

**Files:**
- Create: `database/migrations/2026_04_24_010000_create_shortlink_clicks_table.php`
- Create: `app/Models/ShortlinkClick.php`
- Modify: `app/Models/Shortlink.php`
- Modify: `app/Models/User.php`

**Step 1: Add migration**

Create `shortlink_clicks` with `shortlink_id`, `user_id`, `source`, `referer`, `ip_address`, `user_agent`, timestamps, and indexes for owner/date filtering.

**Step 2: Add model relations**

Add `Shortlink::clicks()` and `User::shortlinkClicks()` so analytics can be queried with Eloquent.

**Step 3: Re-run the test to confirm it still fails for missing behavior, not schema boot errors**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: FAIL because redirect still doesn't record analytics yet.

### Task 3: Record analytics during redirect

**Files:**
- Modify: `app/Http/Controllers/ShortlinkController.php`

**Step 1: Add source normalization helper**

Classify sources as:
- explicit `utm_source` query parameter when present
- `direct` when no referer header exists
- otherwise the referer host like `instagram.com`, `google.com`, etc.

**Step 2: Insert analytics row before redirect**

Persist one row per redirect event with referer, normalized source, IP, user agent, and authenticated owner via the shortlink relation.

**Step 3: Re-run the redirect analytics test and verify pass**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: PASS

### Task 4: Show totals and sources in shortlink dashboard

**Files:**
- Modify: `app/Http/Controllers/ShortlinkController.php`
- Modify: `resources/views/shortlink/create.blade.php`

**Step 1: Extend index query**

Load paginated shortlinks for the logged-in user with click counts and grouped source summaries.

**Step 2: Update dashboard table**

Add a total click column and a simple source breakdown cell such as `instagram.com (4), direct (2)` with an empty fallback.

**Step 3: Add a feature test for dashboard scoping**

Extend `tests/Feature/ShortlinkAnalyticsTest.php` so only the logged-in user's shortlink analytics appear on `/shortlink`.

**Step 4: Run the focused test file**

Run: `php artisan test tests/Feature/ShortlinkAnalyticsTest.php`
Expected: PASS

### Task 5: Verify the final implementation

**Files:**
- Modify: `.opencode/CHANGELOG.md`

**Step 1: Migration verification**

Run: `php artisan migrate --pretend`
Expected: migration SQL for `shortlink_clicks` is valid.

**Step 2: Full test run**

Run: `php artisan test`
Expected: all available tests pass.

**Step 3: Record summary**

Append a short summary to `.opencode/CHANGELOG.md`.
