# Shortlink User Ownership Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add `user_id` ownership to shortlinks so each authenticated user only sees and creates their own shortlinks in the shortlink menu.

**Architecture:** Add a foreign key from `shortlinks` to `users`, persist the current authenticated user's ID when creating a shortlink, and scope the shortlink index query to `auth()->id()`. Keep the public redirect route unchanged so existing short URLs still resolve by slug.

**Tech Stack:** Laravel, Eloquent, Blade, MySQL migrations

---

### Task 1: Add shortlink ownership at database level

**Files:**
- Create: `database/migrations/2026_04_24_000000_add_user_id_to_shortlinks_table.php`
- Modify: `app/Models/Shortlink.php`
- Modify: `app/Models/User.php`

**Step 1: Add migration**

Create a migration that adds nullable `user_id` to `shortlinks`, backfills existing rows if needed later, then makes it non-nullable only if the existing data allows it. For this repo, keep it nullable to avoid breaking existing rows that already exist without owners.

**Step 2: Add model relations**

Add `user_id` to `Shortlink::$fillable`, add `Shortlink::user()` belongsTo relation, and add `User::shortlinks()` hasMany relation.

**Step 3: Verify schema assumptions**

Run: `php artisan migrate --pretend`
Expected: migration SQL adds `user_id` and foreign key on `shortlinks`

### Task 2: Scope shortlink create and listing to current user

**Files:**
- Modify: `app/Http/Controllers/ShortlinkController.php`
- Modify: `resources/views/shortlink/create.blade.php`

**Step 1: Persist authenticated owner**

Update `store()` so new rows include `auth()->id()`.

**Step 2: Filter index**

Update `index()` so it only paginates shortlinks for the logged-in user, newest first.

**Step 3: Make empty state clear in UI**

Show a simple empty-state message when the authenticated user has no shortlinks yet.

### Task 3: Verify

**Files:**
- No new files required unless current test suite already has feature tests for shortlinks.

**Step 1: Framework checks**

Run: `php artisan migrate --pretend`
Expected: no migration syntax errors

Run: `php artisan test`
Expected: relevant application tests still pass, or report existing unrelated failures clearly

**Step 2: Manual behavior check**

1. Login as user A, create a shortlink, confirm it appears in `/shortlink`.
2. Login as user B, confirm user A's shortlink does not appear in `/shortlink`.
3. Open the public slug URL and confirm redirect still works.
