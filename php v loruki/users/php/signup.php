<!-- PHP CODE here 👇👇 -->
<?php
require_once "config.php"; // Include PDO connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
   
    if ($stmt->rowCount() > 0) {
        die("Email is already registered.");
    }

    // Insert new user
    $insert_query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($insert_query);

    if (!$stmt) {
        die("SQL error");
    }

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Automatically log the user in
        $_SESSION['user_id'] = $conn->lastInsertId();
        header("Location: ../../index.php");
        exit;
    } else {
        die("Error during signup.");
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
    <title>Loruki | Sign Up</title>
  </head>
  <body>
    <div class="all">
      <form action="signup.php" method="POST">
        <h1>Sign Up</h1>
        <?php if(isset($error)){
          echo "<p style='color:red;'>$error</p>";
        }?>
        <div class="input">
          <input type="name" name="username" placeholder="username" required />
        </div>

        <div class="input">
          <input type="email" name="email" placeholder="email" required />
        </div>

        <div class="input">
          <input type="password" name="password" placeholder="password" required />
        </div>

        <div class="R-F">
          <label><input type="checkbox" title="Check" /> Remember me</label>
        </div>

        <button type="submit" name="submit" class="btn">Sign Up</button>
        <div class="register">
          <p>Already have an account? <a href="login.php">Login <i class="fas fa-sign-in-alt"></i></a></p>
        </div>
      </form>
    </div>
  </body>
</html>

