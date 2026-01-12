Database Schema (MySQL)
=======================

Use `find_the_five` (UTF8MB4). Import `schema.sql`, then `data.sql`, then `user.sql` for seeds.

Tables (key columns only)
-------------------------
- `users`: `id` PK, `name`, `username` UNIQUE, `email` UNIQUE, `password_hash`, `role` ENUM('user','admin'), `created_at`, `updated_at`.
- `profiles`: `id` PK, `user_id` FK→`users.id` (1:1, cascade), `bio`, `avatar_url`, timestamps.
- `achievements`: `id` PK, `user_id` FK→`users.id` (1:1, cascade), `sqli`, `idor`, `xss`, `cookie`, `privesc`, `final` (TINYINT), `completed_at`, `updated_at`.
- `audit_logs`: `id` PK, `user_id` FK→`users.id` (many:1), `event_type`, `event_context`, `ip_address`, `user_agent`, `created_at`.
- `flags`: `lab_key` PK (e.g., `sqli`, `idor`, `cookie`, `privesc`, `final_code`, `final_flag`), `flag_value`, `updated_at`.

Relationships
-------------
- `users` 1:1 `profiles`
- `users` 1:1 `achievements`
- `users` 1:many `audit_logs`

Backend notes
-------------
- Parameterize all queries; use `password_hash`/`password_verify`.
- Registration flow: create `users` → `profiles` → `achievements` in one transaction.
- Enforce session-based access to profiles/achievements to prevent IDOR.
- Destroy sessions server-side on logout; never trust cookies for roles.
