<?php
require_once "config.php"; // Include PDO connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user details
    $query = "SELECT id, password FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("SQL error");
    }

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../../index.php");
        exit;
    } else {
        $error = "Invalid credentials! Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel="stylesheet" href="../style/form.css" />
    <link rel="stylesheet" href="../font/fontawesome-free-6.6.0-web/css/all.min.css"/>
    <title>Loruki | User Login</title>
  </head>
  <body>
    <div class="all">
      <form action="login.php" method="POST">
        <h1>Login</h1>
        <?php if(isset($error)){
          echo "<p style='color:red;'>$error</p>";
        }?>
        <div class="input">
          <input type="email" placeholder="email" name="email" required />
        </div>

        <div class="input">
          <input type="password" placeholder="password" name="password" required />
        </div>

        <div class="R-F">
          <label><input type="checkbox" title="Check" /> Remember me</label>
          <a href="#">Forgot Password</a>
        </div>

        <button type="submit" class="btn" name="submit">Login</button>
        <div class="register">
          <p>Don't have an account? <a href="signup.php">Sign Up <i class="fas fa-sign-in-alt"></i></a></p>
        </div>
      </form>
    </div>
  </body>
</html>
