# Changelog

## 2026-04-24

- Added `user_id` ownership to `shortlinks` via new migration.
- Scoped shortlink creation and listing to the authenticated user.
- Added Eloquent relations between `User` and `Shortlink`.
- Added an empty state in the shortlink history UI for users without data.
- Saved implementation plan to `docs/plans/2026-04-24-shortlink-user-ownership.md`.
- Added shortlink click analytics with per-link totals and source breakdowns.
- Added source tracking from `utm_source` or HTTP referer during shortlink redirects.
- Added guards to duplicate legacy migrations so the test database can migrate cleanly.
- Saved implementation plan to `docs/plans/2026-04-24-shortlink-click-analytics.md`.
- Added a dedicated analytics page and chart endpoint for each shortlink.
- Added `Lihat Analitik` access from the shortlink index and hid inline source summaries there.
- Saved implementation plan to `docs/plans/2026-04-24-shortlink-analytics-page.md`.
- Restored inline shortlink source summaries in the shortlink index.
- Switched shortlink feature tests to database transactions instead of migrate-fresh style resets.
- Added a source traffic chart below the daily clicks chart on the shortlink analytics page.
