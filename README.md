# Find The Five — Security Training App

PHP front-end labs for five core web vulns. Built as a university assignment to demonstrate backend/security fundamentals; backend hooks are stubbed so you can plug in real auth/DB/validation.

## Run it
- PHP 8+; from project root: `php -S localhost:8000`
- Go to `http://localhost:8000/index.php`

## Labs
- `sqli_lab.php` — SQL injection bypass (`' OR '1'='1`)
- `idor_lab.php` — change `?id=` to access another record
- `update_profile.php` — stored XSS in the bio field
- `cookie_lab.php` — tamper `access_level` → `elite`/`admin`
- `privesc_lab.php` — forged `"role": "admin"` payload
- `flag_lab.php` — final flag via master code or all achievements

## Files at a glance
- UIs: `index.php`, `register.php`, `dashboard.php`
- Labs: `sqli_lab.php`, `idor_lab.php`, `update_profile.php`, `cookie_lab.php`, `privesc_lab.php`, `flag_lab.php`
- Support: `admin.php`, `db.php`, `helpers.php`
- Data: `for_us/` (`schema.sql`, `data.sql`, `user.sql`, docs)
- Assets: `assets/css/style.css`, `assets/js/app.js`, `assets/screenshots/`

## Screenshots
![Login]([./assets/screenshots/login.png](https://raw.githubusercontent.com/USERNAME/REPO/main/assets/screenshots/login.png))
![Dashboard]([./assets/screenshots/dashboard.png](https://raw.githubusercontent.com/USERNAME/REPO/main/assets/screenshots/dashboard.png))
![SQLi Lab]([./assets/screenshots/sqli-lab.png](https://raw.githubusercontent.com/USERNAME/REPO/main/assets/screenshots/sqli-lab.png))

## Backend next steps
- Real auth/authorization + sessions
- DB migrations/queries using `for_us/`
- Input validation/output encoding everywhere
- Server-backed flags/achievements (replace `localStorage`)
