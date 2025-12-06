Database Schema Overview
========================

This document describes the MySQL tables that back the “Find The Five” training app. The current project is UI-only; these tables will be used later when server-side logic is implemented.

Database
--------
- `find_the_five`: main database (UTF8MB4).

Tables
------
- `users`
  - Purpose: store authentication and roles.
  - Key columns:
    - `id` INT UNSIGNED PK.
    - `name` VARCHAR(120), `username` VARCHAR(60) UNIQUE, `email` VARCHAR(120) UNIQUE.
    - `password_hash` VARCHAR(255) for hashed passwords (e.g., bcrypt).
    - `role` ENUM('user','admin') to gate admin panel.
    - `created_at`, `updated_at` timestamps.
- `profiles`
  - Purpose: hold user profile content (bio + avatar).
  - Key columns:
    - `id` INT UNSIGNED PK.
    - `user_id` INT UNSIGNED FK → `users.id` (1:1), ON DELETE CASCADE.
    - `bio` TEXT (sanitize/escape on output to prevent XSS).
    - `avatar_url` VARCHAR(255).
    - `created_at`, `updated_at` timestamps.
- `achievements`
  - Purpose: track lab completion per user.
  - Key columns:
    - `id` INT UNSIGNED PK.
    - `user_id` INT UNSIGNED FK → `users.id` (1:1), ON DELETE CASCADE.
    - `sqli`, `idor`, `xss`, `cookie`, `privesc` TINYINT(1) flags (0/1).
    - `completed_at` DATETIME when all five are done (nullable).
    - `updated_at` TIMESTAMP for last change.

Relationships
-------------
- `users` 1:1 `profiles` (unique `user_id`).
- `users` 1:1 `achievements` (unique `user_id`).

Usage notes (future backend)
----------------------------
- Use parameterized queries for all reads/writes.
- Set `password_hash` via `password_hash()` (PHP) and verify with `password_verify()`.
- On registration: create `users`, then `profiles`, then `achievements` rows in a transaction.
- On logout: destroy session server-side; never trust cookies for roles.
- Prevent IDOR by checking `user_id` from session before loading profiles/achievements.
