<?php
session_start();
include 'db.php';

if (!empty($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}
$errors = [];
$old = ['name' => '', 'username' => '', 'email' => ''];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old['name'] = trim($_POST['name'] ?? '');
  $old['email'] = trim($_POST['email'] ?? '');
  $old['username'] = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';
  
  if (empty($old['name']) || empty($old['username']) || empty($old['email']) || empty($password) || empty($confirm_password)) {
    $errors[] = "all fields are required.";
  }
  if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match, try again.";
  }

  if (empty($errors)) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $old['username'], $old['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $errors[] = "Username or email already exists.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      // use password_hash column per schema
      $stmt = $connection->prepare("INSERT INTO users (name, username, email, password_hash, role) VALUES (?, ?, ?, ?, 'user')");
      $stmt->bind_param("ssss", $old['name'], $old['username'], $old['email'], $hashed_password);
      if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['role'] = 'user';
        header('Location: dashboard.php');
        exit;
      } else {
        $errors[] = "Registration failed, please try again.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Register</title>
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
          <li class="nav-item"><a class="nav-link" href="index.php" data-i18n="nav_login">Login</a></li>
          <li class="nav-item ms-2"><a class="btn btn-primary" href="register.php" data-i18n="nav_register">Register</a></li>
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
      <div class="col-lg-5">
        <div class="pill mb-3" data-i18n="register_badge"><i class="fa-solid fa-user-plus"></i> Create your lab account</div>
        <h1 class="mb-3" data-i18n="register_title">Join the security challenge</h1>
        <p class="muted" data-i18n="register_subtitle">Progress, achievements, and leaderboard are simulated locally. Backend will be implemented later by students.</p>
        <ul class="lab-steps mt-3">
          <li data-i18n-html="register_tip1">Complete 5 labs to earn <span class="mini-flag">Certified Hacker</span></li>
          <li data-i18n="register_tip2">Use only your browser — no external interceptors</li>
          <li data-i18n="register_tip3">All data is placeholder; feel free to experiment</li>
        </ul>
      </div>
      <div class="col-lg-7 mt-4 mt-lg-0">
        <div class="card p-4 hero-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="register_form_title">Register</h5>
            <span class="chip" data-i18n="ui_only_chip">Live database</span>
          </div>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $err): ?>
                <div><?php echo htmlspecialchars($err, ENT_QUOTES); ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <form method="POST" action="register.php" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" data-i18n="field_full_name">Full Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($old['name'] ?? '', ENT_QUOTES); ?>" placeholder="Ada Lovelace" data-i18n-placeholder="placeholder_full_name">
              </div>
              <div class="col-md-6">
                <label class="form-label" data-i18n="field_username">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($old['username'] ?? '', ENT_QUOTES); ?>" placeholder="ada" data-i18n-placeholder="placeholder_username">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label" data-i18n="field_email">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES); ?>" placeholder="you@example.com" data-i18n-placeholder="placeholder_email">
            </div>
            <div class="row g-3 mt-3">
              <div class="col-md-6">
                <label class="form-label" data-i18n="field_password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="••••••••" data-i18n-placeholder="placeholder_password">
              </div>
              <div class="col-md-6">
                <label class="form-label" data-i18n="field_confirm_password">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="••••••••" data-i18n-placeholder="placeholder_password">
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4" data-i18n="register_button">Create account</button>
          </form>
          <div class="text-center mt-3">
            <small class="muted" data-i18n-html="register_switch">Already registered? <a href="index.php">Login</a></small>
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
