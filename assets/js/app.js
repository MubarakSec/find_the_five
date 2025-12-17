// Frontend-only helpers for the Find The Five UI
(function () {
  const STORAGE_KEY = 'ftf_achievements';
  const LANG_KEY = 'ftf_lang';
  const ACHIEVEMENTS = ['sqli', 'idor', 'xss', 'cookie', 'privesc'];
  const FLAG_VALUES = {
    sqli: 'FLAG{SQLI_BYPASS_MASTER}',
    idor: 'FLAG{IDOR_UNLOCKED_PROFILE}',
    xss: 'FLAG{STORED_XSS_OWNED}',
    cookie: 'FLAG{COOKIE_TRUST_IS_BAD}',
    privesc: 'FLAG{ROLE_TAMPERING_SUCCESS}',
    final: 'FLAG{FIND_THE_FIVE_COMPLETE}',
    profile: 'FLAG{PROFILE_VIEW_ONLY}'
  };
  const MASTER_CODE = 'FTF-MASTER-KEY-204';

  const translations = {
    en: {
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
      ui_only_badge: 'Connected — backend pending',
      mysql_later_badge: 'MySQL schema will be added later',
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
      register_tip3: 'All data is placeholder; feel free to experiment',
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
      dash_subtitle: 'Each lab is intentionally misconfigured. Exploit the weakness to reveal its flag, submit it, and watch your rank improve. All progress is simulated on the frontend for now.',
      progress_label: 'Progress',
      progress_hint: 'Unlock each flag to level up',
      final_flag_link: 'Final Flag',
      achievements_title: 'Achievements',
      achievements_note: 'Progress now backed by MySQL',
      ach_sqli_title: 'SQL Injection (sqli_lab.php)',
      ach_sqli_desc: 'Bypass the login/search query to leak flag',
      ach_idor_title: 'IDOR (idor_lab.php)',
      ach_idor_desc: 'Change the URL id to access hidden profile section',
      ach_xss_title: 'Stored XSS (update_profile.php)',
      ach_xss_desc: 'Inject script into bio to trigger the flag',
      ach_cookie_title: 'Cookie Tampering (cookie_lab.php)',
      ach_cookie_desc: 'Edit your cookie to elevate privileges',
      ach_privesc_title: 'Privilege Escalation (privesc_lab.php)',
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
      sqli_placeholder_user: "admin' OR '1'='1",
      sqli_placeholder_pass: 'not needed when injecting',
      sqli_run_btn: 'Run query',
      sqli_hint: "Hint: Classic payloads like <code>' OR '1'='1 --</code> should break the WHERE clause.",
      sqli_result_title: 'Query output',
      sqli_result_chip: 'Simulated DB',
      sqli_result_empty: 'No records yet.',
      sqli_submit_btn: 'Submit SQLi solution',
      idor_badge: 'Insecure Direct Object Reference',
      idor_title: 'Change the ID in the URL',
      idor_subtitle: "This page fetches profile details solely by the <code>id</code> parameter with no authorization. Modify the id to read another user's data and uncover the flag.",
      idor_chip: 'Access control missing',
      idor_form_title: 'Profile payload',
      idor_form_subtitle: 'Query string driven',
      idor_alert: "Try <code>?id=2</code> or <code>?id=admin</code> to fetch another user's record. In a real app this would be blocked server-side.",
      idor_current: 'Current id:',
      idor_loading: 'Loading profile...',
      idor_flag_title: 'Unauthorized data',
      idor_flag_chip: 'Sensitive',
      idor_flag_desc: "If you can read another user's profile or progress without permission, you've exploited the IDOR.",
      idor_submit_btn: 'Submit flag (frontend)',
      cookie_badge: 'Cookie tampering lab',
      cookie_title: 'Privilege stored in a cookie',
      cookie_subtitle: 'The application trusts the <code>access_level</code> cookie. Edit it to escalate your privileges and expose the admin-only flag.',
      cookie_chip: 'Client-side trust',
      cookie_form_title: 'Cookie inspector',
      cookie_form_subtitle: 'Default: learner',
      cookie_current_label: 'Current cookies',
      cookie_refresh_btn: 'Refresh cookie view',
      cookie_goal_title: 'Goal',
      cookie_goal_text: 'Set <code>access_level=elite</code> or <code>access_level=admin</code> then refresh. You can use browser devtools to edit cookies.',
      cookie_flag_title: 'Admin flag',
      cookie_flag_chip: 'Hidden',
      cookie_flag_desc: 'When the cookie indicates admin access, the restricted flag appears.',
      cookie_submit_btn: 'Submit flag (frontend)',
      privesc_badge: 'Privilege Escalation lab',
      privesc_title: 'Override the requested role',
      privesc_subtitle: 'The backend trusts a client-supplied role field. Modify the payload to promote yourself to admin and expose the flag.',
      privesc_chip: 'No server-side validation',
      privesc_form_title: 'Role request',
      privesc_form_subtitle: 'Editable JSON',
      privesc_role_label: 'Role',
      privesc_role_user: 'User',
      privesc_role_analyst: 'Analyst',
      privesc_role_viewer: 'Viewer',
      privesc_payload_label: 'Intercepted request body',
      privesc_send_btn: 'Send request',
      privesc_hint_title: 'Hint',
      privesc_hint_text: 'Change <code>role</code> to <code>admin</code> in the JSON or via the select dropdown then send. No verification happens server-side.',
      privesc_result_title: 'Escalation result',
      privesc_result_chip: 'Simulated',
      privesc_result_empty: 'Awaiting request...',
      privesc_submit_btn: 'Submit flag (frontend)',
      xss_badge: 'Stored XSS lab',
      xss_title: 'Update your bio (and break it)',
      xss_subtitle: 'The preview below renders unescaped HTML. Insert a <script> tag to simulate a stored cross-site scripting payload and expose the flag.',
      xss_target_title: 'Target',
      xss_step1: 'Write a script tag inside your bio',
      xss_step2: 'Save and reload the preview',
      xss_step3: 'The script executes and reveals the flag',
      xss_form_title: 'Edit bio',
      xss_form_chip: 'Unsanitized',
      xss_field_bio: 'Bio text',
      xss_placeholder_bio: 'Write about yourself...',
      xss_save_btn: 'Save bio',
      xss_hint: 'Psst: Stored XSS means the script will live in the database and run for anyone viewing your profile.',
      xss_preview_title: 'Live preview',
      xss_preview_chip: 'Bio output',
      xss_preview_empty: 'Your bio will appear here.',
      xss_submit_btn: 'Submit flag (frontend)',
      profile_badge: 'Profile viewer',
      profile_title: 'Security Trainee Profile',
      profile_subtitle: 'Profile data is static for now — backend to be wired later.',
      profile_edit_btn: 'Edit profile',
      profile_about_title: 'About',
      profile_about_note: 'Editable in update_profile.php',
      profile_bio_text: "Hi! I'm exploring web security. This bio is editable and intentionally unfiltered in the XSS lab.",
      profile_recent_title: 'Recent labs',
      profile_view_all: 'View all',
      profile_recent_sqli: 'SQL Injection lab',
      profile_recent_sqli_hint: "Try `' OR '1'='1`",
      profile_recent_xss: 'Stored XSS lab',
      profile_recent_xss_hint: 'Update your bio unsafely',
      final_badge: 'Final flag',
      final_title: 'Finish line',
      final_subtitle: 'You should now have 5 lab flags. Combine your knowledge or inspect the page to discover the master code that reveals the final trophy flag.',
      final_progress_title: 'Completion check',
      final_progress_note: 'Progress is stored locally in your browser for demo purposes.',
      final_form_title: 'Submit master code',
      final_form_chip: 'Client-side leak',
      final_form_desc: "Somewhere in the client (source, devtools, JS) there's a hardcoded master code. Enter it below to unlock the final flag. Completing all 5 achievements also auto-unlocks.",
      final_field_label: 'Master code',
      final_field_placeholder: 'FTF-????-????',
      final_reveal_btn: 'Reveal final flag',
      final_flag_title: 'Final flag',
      final_flag_chip: 'Trophy',
      final_submit_btn: 'Submit flag (frontend)',
      final_note_title: 'Note',
      final_note_text: 'This submission is cosmetic. Backend validation will be added later.',
      logout_return: 'Return to login',
      logout_badge: 'Logout placeholder',
      logout_title: 'Session cleared',
      logout_subtitle: 'In the real build, this page will destroy the PHP session and remove cookies, then redirect to the login page.',
      logout_back: 'Back to login',
      logout_cancel: 'Cancel',
      admin_badge: 'Security Supervisor Panel',
      admin_title: 'User management (mock)',
      admin_subtitle: 'This panel is for admins only. Buttons are placeholders until backend logic is added.',
      admin_chip: 'Admin view',
      admin_table_title: 'Users',
      admin_reset_all: 'Reset all progress',
      admin_export: 'Export CSV',
      admin_col_user: 'User',
      admin_col_email: 'Email',
      admin_col_role: 'Role',
      admin_col_achievements: 'Achievements',
      admin_col_actions: 'Actions',
      admin_reset_btn: 'Reset progress',
      admin_delete_btn: 'Delete',
      rank_casual: 'Casual User',
      rank_curious: 'Curious Brain',
      rank_nearly: 'Nearly Hacker',
      rank_certified: 'Certified Hacker 😈🔥'
    },
    ar: {
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
      ui_only_badge: 'متصل — المنطق الخادمي لاحقاً',
      mysql_later_badge: 'سيتم ربط MySQL لاحقاً',
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
      register_tip3: 'البيانات تجريبية؛ جرّب كما تشاء',
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
      dash_subtitle: 'كل مختبر به ثغرة متعمدة. استغلها لكشف العلم، أرسله، وشاهد رتبتك ترتفع. التقدم محلي حالياً.',
      progress_label: 'التقدم',
      progress_hint: 'افتح كل علم لترتقي',
      final_flag_link: 'العلم النهائي',
      achievements_title: 'الإنجازات',
      achievements_note: 'التقدم مدعوم الآن بقاعدة البيانات',
      ach_sqli_title: 'حقن SQL (sqli_lab.php)',
      ach_sqli_desc: 'تجاوز استعلام الدخول/البحث لكشف العلم',
      ach_idor_title: 'IDOR (idor_lab.php)',
      ach_idor_desc: 'غيّر معرّف الرابط للوصول لقسم مخفي',
      ach_xss_title: 'XSS مخزّن (update_profile.php)',
      ach_xss_desc: 'حقن سكربت في النبذة لإظهار العلم',
      ach_cookie_title: 'تلاعب بالكوكيز (cookie_lab.php)',
      ach_cookie_desc: 'حرّر الكوكي لرفع الصلاحيات',
      ach_privesc_title: 'تصعيد صلاحيات (privesc_lab.php)',
      ach_privesc_desc: 'عدّل حقل الدور لفتح العلم الأخير',
      start_lab_title: 'ابدأ مختبراً',
      lab_sqli_title: 'حقن SQL',
      lab_sqli_desc: 'سلسلة استعلام ضعيفة',
      lab_idor_title: 'IDOR',
      lab_idor_desc: 'معرّفات مستخدم مكشوفة',
      lab_xss_title: 'XSS مخزّن',
      lab_xss_desc: 'نبذة غير معقمة',
      lab_cookie_title: 'تلاعب بالكوكيز',
      lab_cookie_desc: 'صلاحيات داخل الكوكيز',
      lab_privesc_title: 'تصعيد صلاحيات',
      lab_privesc_desc: 'تجاوز الدور',
      lab_final_title: 'العلم النهائي',
      lab_final_desc: 'خط النهاية',
      sqli_badge: 'مختبر حقن SQL',
      sqli_title: 'اكسر استعلام الدخول',
      sqli_subtitle: 'الاستعلام يدمج مدخلات المستخدم مباشرة. استخدم حقن منطقي لتجاوز التحقق والحصول على علم المدير.',
      sqli_form_title: 'بحث ضعيف',
      sqli_form_chip: 'جمع نصي مباشر',
      sqli_field_user: 'اسم المستخدم أو البريد',
      sqli_placeholder_user: "admin' OR '1'='1",
      sqli_placeholder_pass: 'غير مطلوب عند الحقن',
      sqli_run_btn: 'تنفيذ الاستعلام',
      sqli_hint: "تلميح: حمولة مثل <code>' OR '1'='1 --</code> تكسر شرط WHERE.",
      sqli_result_title: 'مخرجات الاستعلام',
      sqli_result_chip: 'قاعدة بيانات وهمية',
      sqli_result_empty: 'لا توجد سجلات بعد.',
      sqli_submit_btn: 'إرسال حل SQLi',
      idor_badge: 'مرجع مباشر غير آمن',
      idor_title: 'غيّر المعرّف في الرابط',
      idor_subtitle: 'هذه الصفحة تجلب الملف بالاعتماد على <code>id</code> فقط دون تفويض. عدّل المعرّف لقراءة بيانات مستخدم آخر وكشف العلم.',
      idor_chip: 'التحكم بالوصول مفقود',
      idor_form_title: 'حمولة الملف',
      idor_form_subtitle: 'يعتمد على الاستعلام',
      idor_alert: 'جرّب <code>?id=2</code> أو <code>?id=admin</code> لجلب سجل مستخدم آخر. في تطبيق حقيقي سيُمنع ذلك.',
      idor_current: 'المعرف الحالي:',
      idor_loading: 'يتم تحميل الملف...',
      idor_flag_title: 'بيانات غير مصرّح بها',
      idor_flag_chip: 'حساسة',
      idor_flag_desc: 'إذا استطعت قراءة ملف أو تقدم مستخدم آخر بدون إذن فقد استغليت ثغرة IDOR.',
      idor_submit_btn: 'إرسال العلم (واجهة)',
      cookie_badge: 'مختبر تلاعب الكوكيز',
      cookie_title: 'الصلاحية مخزنة في كوكي',
      cookie_subtitle: 'التطبيق يثق في كوكي <code>access_level</code>. عدّلها لرفع صلاحياتك وإظهار علم المدير.',
      cookie_chip: 'ثقة على جانب العميل',
      cookie_form_title: 'عارض الكوكيز',
      cookie_form_subtitle: 'الافتراضي: متعلّم',
      cookie_current_label: 'الكوكيز الحالية',
      cookie_refresh_btn: 'تحديث عرض الكوكيز',
      cookie_goal_title: 'الهدف',
      cookie_goal_text: 'عيّن <code>access_level=elite</code> أو <code>access_level=admin</code> ثم حدّث. يمكنك استخدام أدوات المتصفح لتعديل الكوكيز.',
      cookie_flag_title: 'علم المدير',
      cookie_flag_chip: 'مخفي',
      cookie_flag_desc: 'عندما تشير الكوكيز لصلاحية مدير سيظهر العلم المقيد.',
      cookie_submit_btn: 'إرسال العلم (واجهة)',
      privesc_badge: 'مختبر تصعيد الصلاحيات',
      privesc_title: 'تجاوز الدور المطلوب',
      privesc_subtitle: 'الواجهة الخلفية تثق بحقل الدور المرسل من العميل. عدّل الحمولة لترقية نفسك لمدير وكشف العلم.',
      privesc_chip: 'لا تحقق خادمي',
      privesc_form_title: 'طلب الدور',
      privesc_form_subtitle: 'JSON قابل للتعديل',
      privesc_role_label: 'الدور',
      privesc_role_user: 'مستخدم',
      privesc_role_analyst: 'محلل',
      privesc_role_viewer: 'عارض',
      privesc_payload_label: 'هيكل الطلب المعترض',
      privesc_send_btn: 'إرسال الطلب',
      privesc_hint_title: 'تلميح',
      privesc_hint_text: 'غيّر <code>role</code> إلى <code>admin</code> في JSON أو من القائمة ثم أرسل. لا يوجد تحقق خادمي.',
      privesc_result_title: 'نتيجة التصعيد',
      privesc_result_chip: 'محاكاة',
      privesc_result_empty: 'بانتظار الطلب...',
      privesc_submit_btn: 'إرسال العلم (واجهة)',
      xss_badge: 'مختبر XSS مخزّن',
      xss_title: 'حدّث النبذة (واكسرها)',
      xss_subtitle: 'المعاينة تعرض HTML غير معقم. أضف وسم <script> لمحاكاة هجوم XSS مخزّن وكشف العلم.',
      xss_target_title: 'الهدف',
      xss_step1: 'اكتب وسم سكربت داخل النبذة',
      xss_step2: 'احفظ وأعد تحميل المعاينة',
      xss_step3: 'سيُنفذ السكربت ويظهر العلم',
      xss_form_title: 'تحرير النبذة',
      xss_form_chip: 'غير معقمة',
      xss_field_bio: 'نص النبذة',
      xss_placeholder_bio: 'اكتب عن نفسك...',
      xss_save_btn: 'حفظ النبذة',
      xss_hint: 'ملاحظة: XSS المخزّن يعني أن السكربت سيعيش في القاعدة ويُشغل لكل من يرى ملفك.',
      xss_preview_title: 'معاينة مباشرة',
      xss_preview_chip: 'مخرج النبذة',
      xss_preview_empty: 'ستظهر نبذتك هنا.',
      xss_submit_btn: 'إرسال العلم (واجهة)',
      profile_badge: 'عارض الملف',
      profile_title: 'ملف متدرب الأمان',
      profile_subtitle: 'بيانات الملف ثابتة حالياً — سيتم ربط الخلفية لاحقاً.',
      profile_edit_btn: 'تعديل الملف',
      profile_about_title: 'نبذة',
      profile_about_note: 'قابلة للتعديل في update_profile.php',
      profile_bio_text: 'مرحباً! أستكشف أمن الويب. هذه النبذة قابلة للتعديل وغير معقمة في مختبر XSS.',
      profile_recent_title: 'المختبرات الأخيرة',
      profile_view_all: 'عرض الكل',
      profile_recent_sqli: 'مختبر حقن SQL',
      profile_recent_sqli_hint: "جرّب `' OR '1'='1`",
      profile_recent_xss: 'مختبر XSS مخزّن',
      profile_recent_xss_hint: 'حدّث نبذتك بشكل غير آمن',
      final_badge: 'العلم النهائي',
      final_title: 'خط النهاية',
      final_subtitle: 'يجب أن تكون لديك 5 أعلام. اجمع معرفتك أو تفحص الصفحة لاكتشاف الرمز الرئيسي الذي يكشف العلم النهائي.',
      final_progress_title: 'فحص الاكتمال',
      final_progress_note: 'التقدم مخزن محلياً في المتصفح لأغراض العرض.',
      final_form_title: 'أدخل الرمز الرئيسي',
      final_form_chip: 'تسريب من جهة العميل',
      final_form_desc: 'يوجد الرمز الرئيسي داخل العميل (المصدر أو أدوات المطور). أدخله هنا لفتح العلم النهائي. إكمال جميع الإنجازات يفتحه تلقائياً.',
      final_field_label: 'الرمز الرئيسي',
      final_field_placeholder: 'FTF-????-????',
      final_reveal_btn: 'إظهار العلم النهائي',
      final_flag_title: 'العلم النهائي',
      final_flag_chip: 'جائزة',
      final_submit_btn: 'إرسال العلم (واجهة)',
      final_note_title: 'ملاحظة',
      final_note_text: 'هذا الإرسال تجميلي. سيتم إضافة تحقق خادمي لاحقاً.',
      logout_return: 'العودة لتسجيل الدخول',
      logout_badge: 'صفحة خروج تجريبية',
      logout_title: 'تم مسح الجلسة',
      logout_subtitle: 'في النسخة النهائية ستُلغى جلسة PHP وتُحذف الكوكيز ثم إعادة التوجيه لصفحة الدخول.',
      logout_back: 'العودة للدخول',
      logout_cancel: 'إلغاء',
      admin_badge: 'لوحة المشرف الأمني',
      admin_title: 'إدارة المستخدمين (تجريبية)',
      admin_subtitle: 'هذه اللوحة للمشرفين فقط. الأزرار تجريبية حتى إضافة المنطق الخلفي.',
      admin_chip: 'عرض المشرف',
      admin_table_title: 'المستخدمون',
      admin_reset_all: 'إعادة تعيين كل التقدم',
      admin_export: 'تصدير CSV',
      admin_col_user: 'المستخدم',
      admin_col_email: 'البريد الإلكتروني',
      admin_col_role: 'الدور',
      admin_col_achievements: 'الإنجازات',
      admin_col_actions: 'الإجراءات',
      admin_reset_btn: 'إعادة التقدم',
      admin_delete_btn: 'حذف',
      rank_casual: 'مستخدم عادي',
      rank_curious: 'عقل فضولي',
      rank_nearly: 'قريب من الهاكر',
      rank_certified: 'هاكر معتمد 😈🔥'
    }
  };

  let currentLang = localStorage.getItem(LANG_KEY) || 'en';

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

  const saveState = (state) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  };

  const showMessage = (text, type = 'success') => {
    let placeholder = document.getElementById('alertPlaceholder');
    if (!placeholder) {
      placeholder = document.createElement('div');
      placeholder.id = 'alertPlaceholder';
      placeholder.className = 'position-fixed top-0 start-50 translate-middle-x p-3';
      placeholder.style.zIndex = '1080';
      document.body.appendChild(placeholder);
    }
    placeholder.innerHTML = `<div class="alert alert-${type} shadow mb-2">${text}</div>`;
    setTimeout(() => {
      if (placeholder) placeholder.innerHTML = '';
    }, 3000);
  };

  const rankForCount = (count) => {
    const dict = translations[currentLang] || translations.en;
    if (count === 0) return dict.rank_casual;
    if (count <= 2) return dict.rank_curious;
    if (count <= 4) return dict.rank_nearly;
    return dict.rank_certified;
  };

  const applyTranslations = () => {
    const dict = translations[currentLang] || translations.en;
    document.documentElement.lang = currentLang;
    document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.dataset.i18n;
      if (!dict[key]) return;
      const iconChildren = el.querySelectorAll('i');
      if (iconChildren.length > 0) {
        const icons = Array.from(iconChildren)
          .map((n) => n.outerHTML)
          .join(' ');
        el.innerHTML = `${icons} ${dict[key]}`;
      } else {
        el.textContent = dict[key];
      }
    });
    document.querySelectorAll('[data-i18n-html]').forEach((el) => {
      const key = el.dataset.i18nHtml;
      if (dict[key]) el.innerHTML = dict[key];
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
    applyTranslations();
    syncAchievements();
  };

  const syncAchievements = () => {
    const state = loadState();
    const unlockedCount = ACHIEVEMENTS.filter((k) => state[k]).length;
    const percent = Math.round((unlockedCount / ACHIEVEMENTS.length) * 100);

    document.querySelectorAll('[data-achievement]').forEach((node) => {
      const key = node.dataset.achievement;
      if (!key) return;
      const unlocked = Boolean(state[key]);
      node.classList.toggle('unlocked', unlocked);
      node.classList.toggle('locked', !unlocked);
      const status = node.querySelector('[data-status]');
      if (status) {
        status.textContent = unlocked ? 'Unlocked' : 'Locked';
        status.className = unlocked
          ? 'badge bg-success-subtle text-success'
          : 'badge bg-light text-dark';
      }
    });

    const bar = document.getElementById('achievementProgressBar');
    if (bar) bar.style.width = `${percent}%`;

    const text = document.getElementById('achievementProgressText');
    if (text) text.textContent = `${unlockedCount}/5 achievements`;

    const rankLabel = document.getElementById('rankLabel');
    if (rankLabel) rankLabel.textContent = rankForCount(unlockedCount);

    const finalBar = document.getElementById('finalProgressBar');
    if (finalBar) finalBar.style.width = `${percent}%`;
  };

  const markAchievement = (key) => {
    const state = loadState();
    if (!ACHIEVEMENTS.includes(key)) return;
    if (!state[key]) {
      state[key] = true;
      saveState(state);
      syncAchievements();
    }
  };

  const revealFlag = (selector, key) => {
    const flag = document.querySelector(selector);
    if (!flag) return;
    const flagKey = key || flag.dataset.flagKey;
    if (flagKey && FLAG_VALUES[flagKey]) {
      flag.textContent = FLAG_VALUES[flagKey];
    }
    flag.classList.remove('hidden-flag');
  };

  // SQLi lab
  const initSqliLab = () => {
    const form = document.getElementById('sqliForm');
    if (!form) return;
    const input = document.getElementById('sqliInput');
    const resultBox = document.getElementById('sqliResult');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const payload = (input.value || '').toLowerCase();
      const looksInjected =
        payload.includes("' or") ||
        payload.includes('" or') ||
        payload.includes('1=1') ||
        payload.includes('--') ||
        payload.includes(' or 1');
      if (looksInjected) {
        resultBox.innerHTML =
          "<div class='text-success fw-semibold'>Welcome back, admin!</div><div class='small muted'>You bypassed the WHERE clause.</div>";
        revealFlag('#sqliFlag', 'sqli');
        showMessage('Injection worked — flag revealed.', 'success');
      } else {
        resultBox.innerHTML =
          "<div class='text-danger fw-semibold'>0 rows returned.</div><div class='small muted'>Try a boolean-based payload.</div>";
      }
    });
  };

  // IDOR lab
  const initIdorLab = () => {
    const recordBox = document.getElementById('idorRecord');
    if (!recordBox) return;
    const idLabel = document.getElementById('idorCurrentId');
    const id = new URLSearchParams(window.location.search).get('id') || '1';
    if (idLabel) idLabel.textContent = id;

    if (id !== '1') {
      recordBox.innerHTML = `
        <div class="fw-semibold">Admin profile (id: ${id})</div>
        <div class="muted small">Achievements: 5/5, Email: root@example.com</div>
        <div class="mini-flag mt-2">Unauthorized data exposure!</div>
      `;
      revealFlag('#idorFlag', 'idor');
      showMessage('You accessed another profile — IDOR achieved.', 'success');
    } else {
      recordBox.innerHTML = `
        <div class="fw-semibold">Your profile (id: 1)</div>
        <div class="muted small">Achievements: 0/5, Email: you@example.com</div>
        <div class="mt-2">Try changing <code>?id=2</code>.</div>
      `;
    }
  };

  // XSS lab
  const initXssLab = () => {
    const form = document.getElementById('bioForm');
    if (!form) return;
    const input = document.getElementById('bioInput');
    const preview = document.getElementById('bioPreview');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const value = input.value || '';
      preview.innerHTML = value;
      if (/<script/i.test(value)) {
        revealFlag('#xssFlag', 'xss');
        showMessage('Script executed in profile preview — stored XSS achieved.', 'success');
      } else {
        showMessage('Saved (client-side). Try injecting a script tag to exploit.', 'info');
      }
    });
  };

  // Cookie lab
  const initCookieLab = () => {
    const cookieBox = document.getElementById('cookieBox');
    if (!cookieBox) return;
    const ensureCookie = () => {
      const hasCookie = document.cookie.includes('access_level');
      if (!hasCookie) document.cookie = 'access_level=learner; path=/';
    };
    ensureCookie();
    const refresh = () => {
      cookieBox.value = document.cookie || 'No cookies found.';
      if (
        document.cookie.includes('access_level=elite') ||
        document.cookie.includes('access_level=admin')
      ) {
        revealFlag('#cookieFlag', 'cookie');
        showMessage('Cookie elevated — flag unlocked.', 'success');
      }
    };
    refresh();
    const btn = document.getElementById('refreshCookies');
    if (btn) btn.addEventListener('click', refresh);
  };

  // Privilege escalation lab
  const initPrivescLab = () => {
    const roleSelect = document.getElementById('roleSelect');
    const payloadArea = document.getElementById('rolePayload');
    const resultBox = document.getElementById('roleResult');
    const sendBtn = document.getElementById('sendRoleRequest');
    if (!roleSelect || !payloadArea || !sendBtn || !resultBox) return;

    roleSelect.addEventListener('change', () => {
      const payload = { id: 1, role: roleSelect.value, note: 'promotion-request' };
      payloadArea.value = JSON.stringify(payload, null, 2);
    });

    sendBtn.addEventListener('click', () => {
      let role = roleSelect.value;
      try {
        const parsed = JSON.parse(payloadArea.value);
        if (parsed.role) role = parsed.role;
      } catch (err) {
        // keep role from select if JSON fails
      }
      resultBox.innerHTML = `<div class="fw-semibold">Requested role: ${role}</div>`;
      if (String(role).toLowerCase() === 'admin') {
        resultBox.innerHTML += `<div class="mini-flag mt-2">Server accepted elevated role.</div>`;
        revealFlag('#privescFlag', 'privesc');
        showMessage('Privilege escalation simulated. Flag unlocked.', 'success');
      } else {
        resultBox.innerHTML += `<div class="muted small">No change. Try setting role to "admin".</div>`;
      }
    });
  };

  // Final flag page
  const initFinalFlag = () => {
    const finalFlag = document.getElementById('finalFlag');
    if (!finalFlag) return;
    const form = document.getElementById('finalFlagForm');
    const submitBtn = document.getElementById('finalFlagSubmit');
    const secret = MASTER_CODE;

    const checkCompletion = () => {
      const state = loadState();
      const unlockedCount = ACHIEVEMENTS.filter((k) => state[k]).length;
      if (unlockedCount === ACHIEVEMENTS.length) {
        revealFlag('#finalFlag', 'final');
        showMessage('All achievements done. Final flag revealed.', 'success');
      }
    };

    if (form) {
      form.addEventListener('submit', (e) => {
        const clientOnly = form.dataset.clientOnly === 'true';
        if (!clientOnly) {
          return; // allow the server to process the POST so PHP messages show up
        }
        e.preventDefault();
        const code = (document.getElementById('finalCodeInput').value || '').trim();
        if (code.toUpperCase() === secret.toUpperCase()) {
          revealFlag('#finalFlag', 'final');
          showMessage('Correct master code. Final flag unlocked.', 'success');
        } else {
          showMessage('Incorrect code. Inspect the client for clues.', 'danger');
        }
      });
    }

    if (submitBtn) {
      submitBtn.addEventListener('click', () => {
        if (finalFlag.classList.contains('hidden-flag')) {
          showMessage('Unlock the flag first, then submit.', 'warning');
        } else {
          showMessage('Final flag submitted (frontend only).', 'success');
        }
      });
    }

    checkCompletion();
  };

  const initLanguageToggle = () => {
    document.querySelectorAll('[data-lang-select]').forEach((btn) => {
      btn.addEventListener('click', () => setLanguage(btn.dataset.langSelect));
    });
    applyTranslations();
  };

  // Flag submit buttons
  const initFlagSubmitButtons = () => {
    document.querySelectorAll('.flag-submit').forEach((btn) => {
      btn.addEventListener('click', () => {
        const targetSelector = btn.dataset.flagTarget;
        const achievement = btn.dataset.achievement;
        const target = targetSelector ? document.querySelector(targetSelector) : null;
        if (target && target.classList.contains('hidden-flag')) {
          showMessage('Trigger the vulnerability to reveal the flag first.', 'warning');
          return;
        }
        if (achievement) {
          markAchievement(achievement);
        }
        showMessage('Flag submitted. Backend validation is pending.', 'success');
      });
    });
  };

  // Profile helper
  const initProfilePage = () => {
    const label = document.getElementById('profileIdLabel');
    if (!label) return;
    const id = new URLSearchParams(window.location.search).get('id') || '1';
    label.textContent = `Profile ID: ${id}`;
  };

  document.addEventListener('DOMContentLoaded', () => {
    applyTranslations();
    syncAchievements();
    initFlagSubmitButtons();
    initSqliLab();
    initIdorLab();
    initXssLab();
    initCookieLab();
    initPrivescLab();
    initFinalFlag();
    initProfilePage();
    initLanguageToggle();
  });
})();
