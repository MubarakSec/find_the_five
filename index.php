<?php
// index.php
session_start();

if (!empty($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}

// اتصال بقاعدة البيانات
include 'db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($email === '' || $password === '') {
    $errors[] = 'Email and password are required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
  }

  $stmt = $connection->prepare('SELECT id, password_hash, role FROM users WHERE email = ? LIMIT 1');
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result ? $result->fetch_assoc() : null;

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['role'] = $user['role'] ?? 'user';



    // إعادة توجيه بدون تحقق
    header("Location: profile.php");
    exit;
  } else {
    // رسالة خطأ عامة تكشف معلومات
    $errors[] = 'Invalid email or password.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="dashboard.php" data-i18n="brand">Find The Five</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="register.php" data-i18n="nav_register">Register</a></li>
          <li class="nav-item ms-2"><a class="btn btn-primary" href="index.php" data-i18n="nav_login">Login</a></li>
        </ul>
        <div class="ms-3 d-flex gap-1">
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="en">EN</button>
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="ar">ع</button>
        </div>
      </div>
    </div>
  </nav>

  <main class="container" style="max-width: 960px;">
    <div class="row align-items-center page-hero">
      <div class="col-lg-6">
        <div class="pill mb-3" data-i18n="login_badge"><i class="fa-solid fa-shield-halved"></i> Browser-only security labs</div>
        <h1 class="mb-3" data-i18n="login_title">Sign in to hunt all five flags</h1>
        <p class="muted" data-i18n="login_subtitle">Discover vulnerabilities, unlock achievements, and level up your security mindset. No external tools required.</p>
        <div class="d-flex gap-3 mt-4">
          <div class="badge-lock unlocked" data-i18n="ui_only_badge"><i class="fa-solid fa-lock-open"></i> UI only — backend placeholder</div>
          <div class="badge-lock" data-i18n="mysql_later_badge"><i class="fa-solid fa-database"></i> MySQL schema will be added later</div>
        </div>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card p-4 hero-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="login_form_title">Login</h5>
            <span class="chip" data-i18n="login_chip">Guest access</span>
          </div>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $err): ?>
                <div><?php echo htmlspecialchars($err, ENT_QUOTES); ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <form method="POST" action="index.php" novalidate>
            <div class="mb-3">
              <label class="form-label" data-i18n="field_email">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com" data-i18n-placeholder="placeholder_email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" placeholder="you@example.com" data-i18n-placeholder="placeholder_email" required>
            </div>
            <div class="mb-3">
              <label class="form-label" data-i18n="field_password">Password</label>
              <input type="password" class="form-control" placeholder="••••••••" data-i18n-placeholder="placeholder_password" name="password">
            </div>
            <button type="submit" class="btn btn-primary w-100" data-i18n="login_button" name="sign">Login</button>
          </form>
          <div class="text-center mt-3">
            <small class="muted" data-i18n-html="login_switch">No account? <a href="register.php">Register here</a></small>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer text-center">
    <div class="container">
      Find The Five — Security Training App v1.0
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>

</html>