<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../config/strongMaple_config.php';
secure_session_start();

/* ==========================
   ALREADY LOGGED IN (SESSION)
========================== */
if (is_admin_logged_in()) {
  header("Location: php/dashboard.php");
  exit;
}

/* ==========================
   AUTO LOGIN VIA REMEMBER ME
========================== */
if (!empty($_COOKIE[REMEMBER_COOKIE])) {
  $parts = explode(':', $_COOKIE[REMEMBER_COOKIE], 2);

  if (count($parts) === 2) {
    [$adminId, $token] = $parts;

    $stmt = $pdo->prepare(
      "SELECT remember_token FROM admins WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$adminId]);
    $row = $stmt->fetch();

    if ($row && !empty($row['remember_token']) && hash_equals($row['remember_token'], hash('sha256', $token))) {
      session_regenerate_id(true);
      $_SESSION['admin_id'] = (int)$adminId;
      header("Location: php/dashboard.php");
      exit;
    }
  }
}

/* ==========================
   LOGIN HANDLER
========================== */
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $remember = !empty($_POST['remember']);

  $stmt = $pdo->prepare(
    "SELECT id, password_hash FROM admins WHERE email = ? LIMIT 1"
  );
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($password, $admin['password_hash'])) {
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];

    if ($remember) {
      $token = bin2hex(random_bytes(32));
      $hashedToken = hash('sha256', $token);

      $pdo->prepare(
        "UPDATE admins SET remember_token = ? WHERE id = ?"
      )->execute([$hashedToken, $admin['id']]);

      setcookie(
        REMEMBER_COOKIE,
        $admin['id'] . ':' . $token,
        [
          'expires'  => time() + REMEMBER_EXPIRY,
          'path'     => '/',
          'secure'   => APP_ENV === 'production',
          'httponly' => true,
          'samesite' => 'Lax'
        ]
      );
    }

    header("Location: php/dashboard.php");
    exit;
  }

  $error = 'Invalid email or password';
}
?>
<!DOCTYPE html>
<htmlP lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>

    <link rel="stylesheet" href="./css/login.css" />
    <link rel="icon" href="/../assets/img/favicon.ico" />
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="/../assets/img/apple-touch-icon.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Righteous&display=swap"
      rel="stylesheet" />
  </head>

  <body>
    <div class="login-wrapper">
      <form class="login-card" method="POST">
        <h2 class="title">Admin Access</h2>
        <p class="subtitle">Restricted Area — Authorized Personnel Only</p>

        <div class="input-group">
          <label>Email</label>
          <input type="email" name="email" required />
        </div>

        <div class="input-group">
          <label>Password</label>
          <input type="password" name="password" required />
        </div>

        <div class="remember-row">
          <input type="checkbox" name="remember" id="rememberMe" />
          <label for="rememberMe">Remember Me</label>
        </div>

        <?php if ($error): ?>
          <div class="error" style="display:block">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>
  </body>
</htmlP>