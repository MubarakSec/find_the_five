// Frontend-only helpers for the Find The Five UI
(function () {
  const STORAGE_KEY = 'ftf_achievements';
  const LANG_KEY = 'ftf_lang';
  const ACHIEVEMENTS = ['sqli', 'idor', 'xss', 'cookie', 'privesc'];

  const translations = {
    en: {
      status_unlocked: 'Unlocked',
      status_locked: 'Locked',
      achievements_label: 'achievements',
      profile_id_label: 'Profile ID:',
      brand: 'Find The Five',
      nav_dashboard: 'Dashboard',
      nav_profile: 'Profile',
      nav_admin: 'Admin',
      nav_logout: 'Logout',
      nav_login: 'Login',
      nav_register: 'Register',
      login_badge: 'Browser-only security labs',
      login_title: 'Sign in to hunt all five flags',
      login_subtitle: 'Discover vulnerabilities, unlock achievements, and level up your security mindset. No external tools required.',
      ui_only_badge: 'Backend connected',
      mysql_later_badge: 'Live MySQL',
      login_form_title: 'Login',
      login_chip: 'Guest access',
      field_email: 'Email',
      field_password: 'Password',
      placeholder_email: 'you@example.com',
      placeholder_password: '••••••••',
      login_button: 'Login',
      login_switch: 'No account? <a href="register.php">Register here</a>',
      register_badge: 'Create your lab account',
      register_title: 'Join the security challenge',
      register_subtitle: 'Sign up and store your account securely in the database.',
      register_tip1: 'Complete 5 labs to earn <span class="mini-flag">Certified Hacker</span>',
      register_tip2: 'Use only your browser — no external interceptors',
      register_form_title: 'Register',
      ui_only_chip: 'Live database',
      field_full_name: 'Full Name',
      field_username: 'Username',
      field_confirm_password: 'Confirm Password',
      placeholder_full_name: 'Ada Lovelace',
      placeholder_username: 'ada',
      register_button: 'Create account',
      register_switch: 'Already registered? <a href="index.php">Login</a>',
      dash_badge: 'Track your lab achievements',
      dash_title: 'Find all five vulnerabilities',
      dash_subtitle: 'Each lab is intentionally misconfigured. Exploit the weakness, submit flags, and watch your rank improve. Progress now saves to MySQL.',
      progress_label: 'Progress',
      progress_hint: 'Unlock each flag to level up',
      final_flag_link: 'Final Flag',
      achievements_title: 'Achievements',
      achievements_note: 'Progress now backed by MySQL',
      ach_sqli_title: 'SQL Injection',
      ach_sqli_desc: 'Bypass the login/search query to leak flag',
      ach_idor_title: 'IDOR',
      ach_idor_desc: 'Change the URL id to access hidden profile section',
      ach_xss_title: 'Stored XSS',
      ach_xss_desc: 'Inject script into bio to trigger the flag',
      ach_cookie_title: 'Cookie Tampering',
      ach_cookie_desc: 'Edit your cookie to elevate privileges',
      ach_privesc_title: 'Privilege Escalation',
      ach_privesc_desc: 'Modify the role request to unlock the last flag',
      start_lab_title: 'Start a lab',
      lab_sqli_title: 'SQL Injection',
      lab_sqli_desc: 'Weak query string',
      lab_idor_title: 'IDOR',
      lab_idor_desc: 'User IDs exposed',
      lab_xss_title: 'Stored XSS',
      lab_xss_desc: 'Unescaped bio',
      lab_cookie_title: 'Cookie Tampering',
      lab_cookie_desc: 'Privilege in cookies',
      lab_privesc_title: 'Privilege Escalation',
      lab_privesc_desc: 'Role override',
      lab_final_title: 'Final Flag',
      lab_final_desc: 'Finish line',
      sqli_badge: 'SQL Injection lab',
      sqli_title: 'Break the login query',
      sqli_subtitle: 'The query concatenates user input directly. Use a classic boolean-based injection to bypass the check and return the hidden admin flag.',
      sqli_form_title: 'Vulnerable search',
      sqli_form_chip: 'String concatenation',
      sqli_field_user: 'Username or email',
      sqli_placeholder_user: 'Username or email',
      sqli_placeholder_pass: 'Password',
      sqli_run_btn: 'Run query',
      sqli_hint: 'Hint: The query is concatenated. Try changing the WHERE logic or commenting out the rest.',
      sqli_result_title: 'Query output',
      sqli_result_chip: 'Live DB',
      sqli_result_empty: 'No records yet.',
      sqli_submit_btn: 'Submit SQLi solution',
      hint_toggle: 'Show hint',
      answer_toggle: 'Show answer',
      answer_title: 'Answer',
      sqli_answer: '<ol class="mb-0 ps-3"><li>Use <code>&#39; OR &#39;1&#39;=&#39;1 --</code> in the username/email field.</li><li>Leave the password blank (or any value) and run the query.</li><li>The admin row appears and the flag unlocks.</li></ol>',
      idor_badge: 'Insecure Direct Object Reference',
      idor_title: 'Change the ID in the URL',
      idor_subtitle: "This page fetches profile details solely by the <code>id</code> parameter with no authorization. Modify the id to read another user's data and uncover the flag.",
      idor_chip: 'Access control missing',
      idor_form_title: 'Profile payload',
      idor_form_subtitle: 'Query string driven',
      idor_alert: 'Try changing the <code>id</code> value in the URL to a different user. In a real app this would be blocked server-side.',
      idor_current: 'Current id:',
      idor_loading: 'Loading profile...',
      idor_flag_title: 'Unauthorized data',
      idor_flag_chip: 'Sensitive',
      idor_flag_desc: "If you can read another user's profile or progress without permission, you've exploited the IDOR.",
      idor_submit_btn: 'Submit flag',
      idor_answer: '<ol class="mb-0 ps-3"><li>Change the URL to another id (for example <code>?id=2</code>).</li><li>Reload the page to view another user&#39;s profile.</li><li>The flag appears in the unauthorized data panel.</li></ol>',
      cookie_badge: 'Cookie tampering lab',
      cookie_title: 'Privilege stored in a cookie',
      cookie_subtitle: 'The application trusts the <code>access_level</code> cookie. Edit it to escalate your privileges and expose the admin-only flag.',
      cookie_chip: 'Client-side trust',
      cookie_form_title: 'Cookie inspector',
      cookie_form_subtitle: 'Default: learner',
      cookie_current_label: 'Current cookies',
      cookie_refresh_btn: 'Refresh cookie view',
      cookie_goal_title: 'Goal',
      cookie_goal_text: 'Change the <code>access_level</code> cookie to a higher privilege value, then refresh. You can use browser devtools to edit cookies.',
      cookie_flag_title: 'Admin flag',
      cookie_flag_chip: 'Hidden',
      cookie_flag_desc: 'When the cookie indicates admin access, the restricted flag appears.',
      cookie_answer: '<ol class="mb-0 ps-3"><li>Open browser devtools and edit the <code>access_level</code> cookie.</li><li>Set it to <code>admin</code> (or another elevated value) and refresh.</li><li>The admin flag appears.</li></ol>',
      privesc_badge: 'Privilege Escalation lab',
      privesc_title: 'Override the requested role',
      privesc_subtitle: 'The backend trusts a client-supplied role field. Modify the payload to promote yourself to admin and expose the flag.',
      privesc_chip: 'No server-side validation',
      privesc_form_title: 'Role request',
      privesc_form_subtitle: 'Editable JSON',
      privesc_payload_label: 'Intercepted request body',
      privesc_send_btn: 'Send request',
      privesc_hint_title: 'Hint',
      privesc_hint_text: 'Modify the <code>role</code> field in the JSON payload to a higher-privilege value and submit. No verification happens server-side.',
      privesc_result_title: 'Escalation result',
      privesc_result_chip: 'Server response',
      privesc_result_empty: 'Awaiting request...',
      privesc_submit_btn: 'Submit flag',
      privesc_answer: '<ol class="mb-0 ps-3"><li>Edit the JSON payload so <code>role</code> is <code>admin</code>.</li><li>Submit the request.</li><li>The flag appears in the result panel.</li></ol>',
      xss_badge: 'Stored XSS lab',
      xss_title: 'Update your bio (and break it)',
      xss_subtitle: 'Your bio is stored and rendered without sanitization. Insert a <script> tag to simulate stored XSS and unlock the flag.',
      xss_target_title: 'Target',
      xss_step1: 'Write a script tag inside your bio',
      xss_step2: 'Save the bio',
      xss_step3: 'The flag appears after saving',
      xss_form_title: 'Edit bio',
      xss_form_chip: 'Unsanitized',
      xss_field_bio: 'Bio text',
      xss_placeholder_bio: 'Write about yourself...',
      xss_save_btn: 'Save bio',
      xss_hint: 'Psst: Stored XSS means the script will live in the database and run for anyone viewing your profile.',
      xss_answer: '<ol class="mb-0 ps-3"><li>Paste <code>&lt;script&gt;alert(1)&lt;/script&gt;</code> into the bio.</li><li>Save the bio.</li><li>The flag appears below.</li></ol>',
      profile_badge: 'Profile viewer',
      profile_title: 'Security Trainee Profile',
      profile_subtitle: 'Profile data is static for now — backend to be wired later.',
      profile_edit_btn: 'Edit profile',
      profile_access_note: 'Real profile access is restricted to you. The IDOR lab is separate and intentionally vulnerable.',
      profile_about_title: 'About',
      profile_about_note: 'Editable in update_profile.php',
      profile_bio_text: "Hi! I'm exploring web security. This bio is editable and intentionally unfiltered in the XSS lab.",
      profile_bio_empty: 'No bio yet. Edit your profile to add one.',
      profile_bio_script_warning: 'Bio contains a script tag and will execute on view.',
      final_badge: 'Final flag',
      final_title: 'Finish line',
      final_subtitle: 'Complete all five labs or enter the master code to unlock the final flag.',
      final_progress_title: 'Completion check',
      final_progress_note: 'Progress is stored in MySQL and reflected here.',
      final_form_title: 'Submit master code',
      final_form_chip: 'Master code',
      final_form_desc: 'Enter the master code for this lab, or complete all five achievements to unlock the final flag automatically.',
      final_field_label: 'Master code',
      final_field_placeholder: 'FTF-????-????',
      final_reveal_btn: 'Reveal final flag',
      final_flag_title: 'Final flag',
      final_flag_chip: 'Trophy',
      final_submit_btn: 'Submit flag',
      final_answer_label: 'Enter this master code:',
      final_answer_alt: 'Or complete all five labs to unlock it.',
      final_note_title: 'Note',
      final_note_text: 'This submission is cosmetic. Backend validation will be added later.',
      logout_return: 'Return to login',
      logout_badge: 'Logout placeholder',
      logout_title: 'Session cleared',
      logout_subtitle: 'In the real build, this page will destroy the PHP session and remove cookies, then redirect to the login page.',
      logout_back: 'Back to login',
      logout_cancel: 'Cancel',
      admin_badge: 'Security Supervisor Panel',
      admin_title: 'User management',
      admin_subtitle: 'Manage users and lab progress.',
      admin_chip: 'Admin view',
      admin_table_title: 'Users',
      admin_reset_all: 'Reset all progress',
      admin_export: 'Export CSV',
      admin_col_user: 'User',
      admin_col_email: 'Email',
      admin_col_role: 'Role',
      admin_col_achievements: 'Achievements',
      admin_col_final: 'Final',
      admin_col_actions: 'Actions',
      admin_reset_btn: 'Reset progress',
      admin_delete_btn: 'Delete',
      rank_casual: 'Casual User',
      rank_curious: 'Curious Brain',
      rank_nearly: 'Nearly Hacker',
      rank_certified: 'Certified Hacker 😈🔥'
    },
    ar: {
      status_unlocked: 'مفتوح',
      status_locked: 'مقفل',
      achievements_label: 'إنجازات',
      profile_id_label: 'رقم الملف:',
      brand: 'اعثر على الخمسة',
      nav_dashboard: 'لوحة التحكم',
      nav_profile: 'الملف الشخصي',
      nav_admin: 'الإدارة',
      nav_logout: 'تسجيل الخروج',
      nav_login: 'تسجيل الدخول',
      nav_register: 'إنشاء حساب',
      login_badge: 'مختبرات أمنية بالمتصفح فقط',
      login_title: 'سجّل دخولك للعثور على خمس ثغرات',
      login_subtitle: 'اكتشف الثغرات، افتح الإنجازات، وطور مهاراتك الأمنية. بدون أدوات خارجية.',
      ui_only_badge: 'متصل بالخادم',
      mysql_later_badge: 'MySQL مباشر',
      login_form_title: 'تسجيل الدخول',
      login_chip: 'وصول زائر',
      field_email: 'البريد الإلكتروني',
      field_password: 'كلمة المرور',
      placeholder_email: 'you@example.com',
      placeholder_password: '••••••••',
      login_button: 'تسجيل الدخول',
      login_switch: 'لا تملك حساباً؟ <a href="register.php">سجّل هنا</a>',
      register_badge: 'أنشئ حساب المختبر',
      register_title: 'انضم لتحدي الأمان',
      register_subtitle: 'سجّل حسابك ليُخزَّن بأمان في قاعدة البيانات.',
      register_tip1: 'أكمل 5 مختبرات لتحصل على <span class="mini-flag">مخترق معتمد</span>',
      register_tip2: 'استخدم المتصفح فقط — بلا أدوات اعتراض خارجية',
      register_form_title: 'تسجيل',
      ui_only_chip: 'متصل بقاعدة البيانات',
      field_full_name: 'الاسم الكامل',
      field_username: 'اسم المستخدم',
      field_confirm_password: 'تأكيد كلمة المرور',
      placeholder_full_name: 'Ada Lovelace',
      placeholder_username: 'ada',
      register_button: 'إنشاء حساب',
      register_switch: 'لديك حساب؟ <a href="index.php">سجّل الدخول</a>',
      dash_badge: 'تتبع إنجازات المختبر',
      dash_title: 'اعثر على الثغرات الخمس',
      dash_subtitle: 'كل مختبر به ثغرة متعمدة. استغلها لكشف العلم، أرسله، وشاهد رتبتك ترتفع. التقدم يُحفَظ في MySQL الآن.',
      progress_label: 'التقدم',
      progress_hint: 'افتح كل علم لترتقي',
      final_flag_link: 'العلم النهائي',
      achievements_title: 'الإنجازات',
      achievements_note: 'التقدم مدعوم الآن بقاعدة البيانات',
      ach_sqli_title: 'حقن SQL [SQL Injection]',
      ach_sqli_desc: 'تجاوز استعلام الدخول/البحث لكشف العلم',
      ach_idor_title: 'مرجع مباشر غير آمن [IDOR]',
      ach_idor_desc: 'غيّر معرّف الرابط للوصول لقسم مخفي',
      ach_xss_title: 'XSS مخزّن [Stored XSS]',
      ach_xss_desc: 'حقن سكربت في النبذة لإظهار العلم',
      ach_cookie_title: 'تلاعب بالكوكيز [Cookie Tampering]',
      ach_cookie_desc: 'حرّر الكوكي لرفع الصلاحيات',
      ach_privesc_title: 'تصعيد صلاحيات [Privilege Escalation]',
      ach_privesc_desc: 'عدّل حقل الدور لفتح العلم الأخير',
      start_lab_title: 'ابدأ مختبراً',
      lab_sqli_title: 'حقن SQL [SQL Injection]',
      lab_sqli_desc: 'سلسلة استعلام ضعيفة',
      lab_idor_title: 'مرجع مباشر غير آمن [IDOR]',
      lab_idor_desc: 'معرّفات مستخدم مكشوفة',
      lab_xss_title: 'XSS مخزّن [Stored XSS]',
      lab_xss_desc: 'نبذة غير معقمة',
      lab_cookie_title: 'تلاعب بالكوكيز [Cookie Tampering]',
      lab_cookie_desc: 'صلاحيات داخل الكوكيز',
      lab_privesc_title: 'تصعيد صلاحيات [Privilege Escalation]',
      lab_privesc_desc: 'تجاوز الدور',
      lab_final_title: 'العلم النهائي',
      lab_final_desc: 'خط النهاية',
      sqli_badge: 'مختبر حقن SQL [SQL Injection]',
      sqli_title: 'اكسر استعلام الدخول',
      sqli_subtitle: 'الاستعلام يدمج مدخلات المستخدم مباشرة. استخدم حقن منطقي لتجاوز التحقق والحصول على علم المدير.',
      sqli_form_title: 'بحث ضعيف',
      sqli_form_chip: 'جمع نصي مباشر',
      sqli_field_user: 'اسم المستخدم أو البريد',
      sqli_placeholder_user: 'اسم المستخدم أو البريد',
      sqli_placeholder_pass: 'كلمة المرور',
      sqli_run_btn: 'تنفيذ الاستعلام',
      sqli_hint: 'تلميح: الاستعلام مبني بالدمج. جرّب تغيير منطق <code>WHERE</code> أو تعليق بقية الاستعلام.',
      sqli_result_title: 'مخرجات الاستعلام',
      sqli_result_chip: 'قاعدة بيانات مباشرة',
      sqli_result_empty: 'لا توجد سجلات بعد.',
      sqli_submit_btn: 'إرسال حل [SQLi]',
      hint_toggle: 'عرض التلميح',
      answer_toggle: 'عرض الإجابة',
      answer_title: 'الإجابة',
      sqli_answer: '<ol class="mb-0 ps-3"><li>استخدم <code>&#39; OR &#39;1&#39;=&#39;1 --</code> في حقل المستخدم/البريد.</li><li>اترك كلمة المرور فارغة (أو أي قيمة) ثم نفّذ الاستعلام.</li><li>سيظهر سجل المدير ويتم فتح العلم.</li></ol>',
      idor_badge: 'مرجع مباشر غير آمن [IDOR]',
      idor_title: 'غيّر المعرّف في الرابط',
      idor_subtitle: 'هذه الصفحة تجلب الملف بالاعتماد على <code>id</code> فقط دون تفويض. عدّل المعرّف لقراءة بيانات مستخدم آخر وكشف العلم.',
      idor_chip: 'التحكم بالوصول مفقود',
      idor_form_title: 'حمولة الملف',
      idor_form_subtitle: 'يعتمد على الاستعلام',
      idor_alert: 'جرّب تغيير قيمة <code>id</code> في الرابط للوصول لمستخدم آخر. في تطبيق حقيقي سيُمنع ذلك.',
      idor_current: 'المعرف الحالي:',
      idor_loading: 'يتم تحميل الملف...',
      idor_flag_title: 'بيانات غير مصرّح بها',
      idor_flag_chip: 'حساسة',
      idor_flag_desc: 'إذا استطعت قراءة ملف أو تقدم مستخدم آخر بدون إذن فقد استغليت ثغرة [IDOR].',
      idor_submit_btn: 'إرسال العلم',
      idor_answer: '<ol class="mb-0 ps-3"><li>غيّر الرابط إلى معرّف آخر (مثلاً <code>?id=2</code>).</li><li>أعد تحميل الصفحة لعرض ملف مستخدم آخر.</li><li>سيظهر العلم في لوحة البيانات غير المصرّح بها.</li></ol>',
      cookie_badge: 'مختبر تلاعب الكوكيز [Cookie Tampering]',
      cookie_title: 'الصلاحية مخزنة في كوكي',
      cookie_subtitle: 'التطبيق يثق في كوكي <code>access_level</code>. عدّلها لرفع صلاحياتك وإظهار علم المدير.',
      cookie_chip: 'ثقة على جانب العميل',
      cookie_form_title: 'عارض الكوكيز',
      cookie_form_subtitle: 'الافتراضي: متعلّم',
      cookie_current_label: 'الكوكيز الحالية',
      cookie_refresh_btn: 'تحديث عرض الكوكيز',
      cookie_goal_title: 'الهدف',
      cookie_goal_text: 'عدّل كوكي <code>access_level</code> إلى قيمة بصلاحية أعلى ثم حدّث الصفحة. يمكنك استخدام أدوات المتصفح لتعديل الكوكيز.',
      cookie_flag_title: 'علم المدير',
      cookie_flag_chip: 'مخفي',
      cookie_flag_desc: 'عندما تشير الكوكيز لصلاحية مدير سيظهر العلم المقيد.',
      cookie_answer: '<ol class="mb-0 ps-3"><li>افتح أدوات المتصفح وعدّل كوكي <code>access_level</code>.</li><li>اجعلها <code>admin</code> (أو قيمة أعلى) ثم حدّث الصفحة.</li><li>سيظهر علم المدير.</li></ol>',
      privesc_badge: 'مختبر تصعيد الصلاحيات [Privilege Escalation]',
      privesc_title: 'تجاوز الدور المطلوب',
      privesc_subtitle: 'الواجهة الخلفية تثق بحقل الدور المرسل من العميل. عدّل الحمولة لترقية نفسك لمدير وكشف العلم.',
      privesc_chip: 'لا تحقق خادمي',
      privesc_form_title: 'طلب الدور',
      privesc_form_subtitle: 'JSON قابل للتعديل',
      privesc_payload_label: 'هيكل الطلب المعترض',
      privesc_send_btn: 'إرسال الطلب',
      privesc_hint_title: 'تلميح',
      privesc_hint_text: 'عدّل قيمة <code>role</code> في حمولة JSON إلى صلاحية أعلى ثم أرسل. لا يوجد تحقق خادمي.',
      privesc_result_title: 'نتيجة التصعيد',
      privesc_result_chip: 'استجابة الخادم',
      privesc_result_empty: 'بانتظار الطلب...',
      privesc_submit_btn: 'إرسال العلم',
      privesc_answer: '<ol class="mb-0 ps-3"><li>عدّل حمولة JSON بحيث تصبح قيمة <code>role</code> هي <code>admin</code>.</li><li>أرسل الطلب.</li><li>سيظهر العلم في لوحة النتيجة.</li></ol>',
      xss_badge: 'مختبر XSS مخزّن [Stored XSS]',
      xss_title: 'حدّث النبذة (واكسرها)',
      xss_subtitle: 'تُحفَظ النبذة وتُعرض بدون تعقيم. أضف وسم <script> لمحاكاة [Stored XSS] وفتح العلم.',
      xss_target_title: 'الهدف',
      xss_step1: 'اكتب وسم سكربت داخل النبذة',
      xss_step2: 'احفظ النبذة',
      xss_step3: 'سيظهر العلم بعد الحفظ',
      xss_form_title: 'تحرير النبذة',
      xss_form_chip: 'غير معقمة',
      xss_field_bio: 'نص النبذة',
      xss_placeholder_bio: 'اكتب عن نفسك...',
      xss_save_btn: 'حفظ النبذة',
      xss_hint: 'ملاحظة: هجوم XSS المخزّن [Stored XSS] يعني أن السكربت سيعيش في القاعدة ويُشغل لكل من يرى ملفك.',
      xss_answer: '<ol class="mb-0 ps-3"><li>الصق <code>&lt;script&gt;alert(1)&lt;/script&gt;</code> داخل النبذة.</li><li>احفظ النبذة.</li><li>سيظهر العلم بالأسفل.</li></ol>',
      profile_badge: 'عارض الملف',
      profile_title: 'ملف متدرب الأمان',
      profile_subtitle: 'بيانات الملف ثابتة حالياً — سيتم ربط الخلفية لاحقاً.',
      profile_edit_btn: 'تعديل الملف',
      profile_access_note: 'الوصول لملفك الحقيقي مقيد بك فقط. مختبر [IDOR] منفصل ومقصود أن يكون ضعيفاً.',
      profile_about_title: 'نبذة',
      profile_about_note: 'قابلة للتعديل في update_profile.php',
      profile_bio_text: 'مرحباً! أستكشف أمن الويب. هذه النبذة قابلة للتعديل وغير معقمة في مختبر [Stored XSS].',
      profile_bio_empty: 'لا توجد نبذة بعد. عدّل ملفك لإضافة نبذة.',
      profile_bio_script_warning: 'النبذة تحتوي وسم سكربت وسيُنفذ عند العرض.',
      final_badge: 'العلم النهائي',
      final_title: 'خط النهاية',
      final_subtitle: 'أكمل المختبرات الخمسة أو أدخل الرمز الرئيسي لفتح العلم النهائي.',
      final_progress_title: 'فحص الاكتمال',
      final_progress_note: 'التقدم مخزن في MySQL ويظهر هنا.',
      final_form_title: 'أدخل الرمز الرئيسي',
      final_form_chip: 'الرمز الرئيسي',
      final_form_desc: 'أدخل الرمز الرئيسي الخاص بالمختبر، أو أكمل جميع الإنجازات الخمسة لفتح العلم النهائي تلقائياً.',
      final_field_label: 'الرمز الرئيسي',
      final_field_placeholder: 'FTF-????-????',
      final_reveal_btn: 'إظهار العلم النهائي',
      final_flag_title: 'العلم النهائي',
      final_flag_chip: 'جائزة',
      final_submit_btn: 'إرسال العلم',
      final_answer_label: 'أدخل الرمز الرئيسي التالي:',
      final_answer_alt: 'أو أكمل المختبرات الخمسة لفتحه.',
      final_note_title: 'ملاحظة',
      final_note_text: 'هذا الإرسال تجميلي. سيتم إضافة تحقق خادمي لاحقاً.',
      logout_return: 'العودة لتسجيل الدخول',
      logout_badge: 'صفحة خروج تجريبية',
      logout_title: 'تم مسح الجلسة',
      logout_subtitle: 'في النسخة النهائية ستُلغى جلسة PHP وتُحذف الكوكيز ثم إعادة التوجيه لصفحة الدخول.',
      logout_back: 'العودة للدخول',
      logout_cancel: 'إلغاء',
      admin_badge: 'لوحة المشرف الأمني',
      admin_title: 'إدارة المستخدمين',
      admin_subtitle: 'إدارة المستخدمين وتقدم المختبرات.',
      admin_chip: 'عرض المشرف',
      admin_table_title: 'المستخدمون',
      admin_reset_all: 'إعادة تعيين كل التقدم',
      admin_export: 'تصدير CSV',
      admin_col_user: 'المستخدم',
      admin_col_email: 'البريد الإلكتروني',
      admin_col_role: 'الدور',
      admin_col_achievements: 'الإنجازات',
      admin_col_final: 'العلم النهائي',
      admin_col_actions: 'الإجراءات',
      admin_reset_btn: 'إعادة التقدم',
      admin_delete_btn: 'حذف',
      rank_casual: 'مستخدم عادي',
      rank_curious: 'عقل فضولي',
      rank_nearly: 'قريب من الهاكر',
      rank_certified: 'هاكر معتمد 😈🔥'
    }
  };

  const cookieMatch = document.cookie.match(/(?:^|; )ftf_lang=([^;]+)/);
  let currentLang = cookieMatch ? decodeURIComponent(cookieMatch[1]) : localStorage.getItem(LANG_KEY) || 'en';
  if (!translations[currentLang]) currentLang = 'en';

  const loadState = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return ACHIEVEMENTS.reduce((acc, key) => {
        acc[key] = Boolean(parsed[key]);
        return acc;
      }, {});
    } catch (err) {
      console.warn('Could not read achievements', err);
      return ACHIEVEMENTS.reduce((acc, key) => ({ ...acc, [key]: false }), {});
    }
  };

  const formatInlineBidi = (text) => {
    if (currentLang !== 'ar' || !text.includes('[') || !text.includes(']')) return text;
    return text.replace(/\[([^\]]+)\]/g, (_, term) => `[<bdi dir="ltr">${term}</bdi>]`);
  };

  const rankForCount = (count) => {
    const dict = translations[currentLang] || translations.en;
    if (count === 0) return dict.rank_casual;
    if (count <= 2) return dict.rank_curious;
    if (count <= 4) return dict.rank_nearly;
    return dict.rank_certified;
  };

  const appendBidiText = (el, text) => {
    const regex = /\[([^\]]+)\]|([A-Za-z0-9_]+(?:-[A-Za-z0-9_]+)*)/g;
    let lastIndex = 0;
    let match;
    while ((match = regex.exec(text)) !== null) {
      if (match.index > lastIndex) {
        el.appendChild(document.createTextNode(text.slice(lastIndex, match.index)));
      }
      if (match[1] !== undefined) {
        el.appendChild(document.createTextNode('['));
        const bdi = document.createElement('bdi');
        bdi.setAttribute('dir', 'ltr');
        bdi.textContent = match[1];
        el.appendChild(bdi);
        el.appendChild(document.createTextNode(']'));
      } else if (match[2] !== undefined) {
        const bdi = document.createElement('bdi');
        bdi.setAttribute('dir', 'ltr');
        bdi.textContent = match[2];
        el.appendChild(bdi);
      }
      lastIndex = regex.lastIndex;
    }
    if (lastIndex < text.length) {
      el.appendChild(document.createTextNode(text.slice(lastIndex)));
    }
  };

  const hasArabic = (text) => /[\u0600-\u06FF]/.test(text);

  const setTranslatedText = (el, text) => {
    const iconNodes = Array.from(el.querySelectorAll('i')).map((node) =>
      node.cloneNode(true)
    );
    el.textContent = '';
    if (iconNodes.length) {
      iconNodes.forEach((node) => el.appendChild(node));
      el.appendChild(document.createTextNode(' '));
    }
    if (currentLang === 'ar' && hasArabic(text)) {
      el.setAttribute('dir', 'rtl');
    } else {
      el.setAttribute('dir', 'ltr');
    }
    if (currentLang === 'ar' && /[A-Za-z]/.test(text)) {
      appendBidiText(el, text);
    } else {
      el.appendChild(document.createTextNode(text));
    }
  };

  const applyTranslations = () => {
    const dict = translations[currentLang] || translations.en;
    document.documentElement.lang = currentLang;
    // Keep layout LTR even in Arabic to avoid flipping the nav; change only text.
    document.documentElement.dir = 'ltr';
    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.dataset.i18n;
      if (!dict[key]) return;
      setTranslatedText(el, dict[key]);
    });
    document.querySelectorAll('[data-i18n-html]').forEach((el) => {
      const key = el.dataset.i18nHtml;
      if (!dict[key]) return;
      el.innerHTML = formatInlineBidi(dict[key]);
      if (currentLang === 'ar' && hasArabic(dict[key])) {
        el.setAttribute('dir', 'rtl');
      } else {
        el.setAttribute('dir', 'ltr');
      }
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
      const key = el.dataset.i18nPlaceholder;
      if (dict[key]) el.setAttribute('placeholder', dict[key]);
    });
    document.querySelectorAll('[data-lang-select]').forEach((btn) => {
      const isActive = btn.dataset.langSelect === currentLang;
      btn.classList.toggle('btn-primary', isActive);
      btn.classList.toggle('btn-outline-secondary', !isActive);
    });
  };

  const setLanguage = (lang) => {
    currentLang = translations[lang] ? lang : 'en';
    localStorage.setItem(LANG_KEY, currentLang);
    document.cookie = `ftf_lang=${encodeURIComponent(currentLang)}; path=/; max-age=31536000`;
    applyTranslations();
    syncAchievements();
  };

  const syncAchievements = () => {
    const state = loadState();
    const unlockedCount = ACHIEVEMENTS.filter((k) => state[k]).length;
    const percent = Math.round((unlockedCount / ACHIEVEMENTS.length) * 100);
    const dict = translations[currentLang] || translations.en;

    document.querySelectorAll('[data-achievement]').forEach((node) => {
      const key = node.dataset.achievement;
      if (!key) return;
      const unlocked = Boolean(state[key]);
      node.classList.toggle('unlocked', unlocked);
      node.classList.toggle('locked', !unlocked);
      const status = node.querySelector('[data-status]');
      if (status) {
        status.textContent = unlocked ? dict.status_unlocked : dict.status_locked;
        status.className = unlocked
          ? 'badge bg-success-subtle text-success'
          : 'badge bg-light text-dark';
      }
    });

    const bar = document.getElementById('achievementProgressBar');
    if (bar) bar.style.width = `${percent}%`;

    const text = document.getElementById('achievementProgressText');
    if (text) text.textContent = `${unlockedCount}/5 ${dict.achievements_label}`;

    const rankLabel = document.getElementById('rankLabel');
    if (rankLabel) rankLabel.textContent = rankForCount(unlockedCount);

    const finalBar = document.getElementById('finalProgressBar');
    if (finalBar) finalBar.style.width = `${percent}%`;
  };

  const initLanguageToggle = () => {
    document.querySelectorAll('[data-lang-select]').forEach((btn) => {
      btn.addEventListener('click', () => setLanguage(btn.dataset.langSelect));
    });
    applyTranslations();
  };

  // Profile helper
  const initProfilePage = () => {
    const label = document.getElementById('profileIdLabel');
    if (!label) return;
    const dict = translations[currentLang] || translations.en;
    const id = new URLSearchParams(window.location.search).get('id') || '1';
    label.textContent = `${dict.profile_id_label} ${id}`;
  };

  document.addEventListener('DOMContentLoaded', () => {
    applyTranslations();
    syncAchievements();
    initProfilePage();
    initLanguageToggle();
  });
})();
