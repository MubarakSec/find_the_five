Find The Five — Security Training App (UI Only)
================================================

Frontend-only PHP pages that illustrate five web vulnerabilities. Backend logic is intentionally missing so students can implement authentication, database access, and validation later.

Project layout
--------------
- `index.php` – login UI (guest)
- `register.php` – signup UI (guest)
- `dashboard.php` – achievements overview (user)
- `profile.php?id=?` – view profile (user)
- `update_profile.php` – edit bio + stored XSS lab
- `sqli_lab.php` – SQL injection lab
- `idor_lab.php` – IDOR lab
- `cookie_lab.php` – cookie tampering lab
- `privesc_lab.php` – privilege escalation lab
- `flag_lab.php` – final flag page
- `admin.php` – Security Supervisor Panel (mock)
- `logout.php` – logout placeholder
- `db.php` – future MySQL connection stub
- `assets/css/style.css` – custom styling
- `assets/js/app.js` – frontend helpers for flags/achievements

How to view
-----------
Open any PHP file in a local web server (e.g., `php -S localhost:8000`). All pages are static; achievements are stored in `localStorage` only.

Backend TODOs
-------------
Every PHP file starts with `session_start()` and contains comments like “TODO: Implement login logic”. No business logic, database code, or validation is present. `db.php` only outlines the planned table structures.

Labs and flags (client side)
----------------------------
- SQLi: classic `' OR '1'='1` bypass reveals flag.
- IDOR: change `?id=` in `idor_lab.php` to load another record.
- Stored XSS: inject `<script>` in the bio to trigger the flag.
- Cookie tampering: edit `access_level` cookie to `elite`/`admin`.
- Privilege escalation: set `"role": "admin"` in request payload.
- Final flag: enter the master code from the page source or finish all five achievements to reveal it.

Notes
-----
- Uses Bootstrap 5 + Font Awesome from CDN, custom styling in `assets/css/style.css`.
- All interactive actions are mock only; server-side validation will be added later.
- Footer branding: “Find The Five — Security Training App v1.0”.
