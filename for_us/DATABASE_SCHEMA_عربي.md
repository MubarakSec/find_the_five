دليل قاعدة البيانات (مبسط)
===========================

قاعدة البيانات: `find_the_five` (UTF8MB4). ثبّت بالترتيب: `schema.sql` ثم `data.sql` ثم `user.sql`.

الجداول (أعمدة أساسية)
----------------------
- `users`: `id`, `name`, `username` (فريد), `email` (فريد), `password_hash`, `role` (`user`/`admin`), تواريخ.
- `profiles`: `id`, `user_id` (FK إلى `users.id`، 1:1، حذف متتابع), `bio`, `avatar_url`, تواريخ.
- `achievements`: `id`, `user_id` (1:1), أعلام 0/1 لـ `sqli`, `idor`, `xss`, `cookie`, `privesc`, وحقل `final`, مع `completed_at` و`updated_at`.
- `audit_logs`: `id`, `user_id` (1:many), `event_type`, `event_context`, `ip_address`, `user_agent`, `created_at`.
- `flags`: `lab_key` (مفتاح أساسي: `sqli`, `idor`, `cookie`, `privesc`, `final_code`, `final_flag`), `flag_value`, `updated_at`.

العلاقات
--------
- `users` 1:1 `profiles`
- `users` 1:1 `achievements`
- `users` 1:many `audit_logs`

نصائح سريعة
-----------
- استخدم Prepared Statements دائمًا؛ `password_hash`/`password_verify` للمرور.
- تسجيل مستخدم: أنشئ `users` → `profiles` → `achievements` في معاملة واحدة.
- اربط كل استعلام بالجلسة (Session) لمنع IDOR، ولا تعتمد على الكوكيز للأدوار.
- دمّر الجلسة عند تسجيل الخروج.
