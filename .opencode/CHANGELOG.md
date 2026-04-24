# Changelog

## 2026-04-24

- Added `user_id` ownership to `shortlinks` via new migration.
- Scoped shortlink creation and listing to the authenticated user.
- Added Eloquent relations between `User` and `Shortlink`.
- Added an empty state in the shortlink history UI for users without data.
- Saved implementation plan to `docs/plans/2026-04-24-shortlink-user-ownership.md`.
