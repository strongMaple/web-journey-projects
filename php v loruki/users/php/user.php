<?php
include "config.php";
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details using PDO
try {
    $user_query = "SELECT username, email FROM users WHERE id = :id";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $username = $user['username'];
        $email = $user['email'];
    } else {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("SQL error: " . $e->getMessage());
}

// Handle deletion of a plan
if (isset($_POST['delete_plan'])) {
    $plan_id = $_POST['plan_id'];
    try {
        $delete_query = "DELETE FROM user_plans WHERE id = :plan_id AND user_id = :user_id";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
        $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $delete_stmt->execute();
        
        header("Location: user.php");
        exit;
    } catch (PDOException $e) {
        die("Error deleting plan: " . $e->getMessage());
    }
}

// Fetch user plans using PDO
try {
    $plans_query = "SELECT id, plan_name, storage, bandwidth, description, add_ons, price FROM user_plans WHERE user_id = :user_id";
    $plans_stmt = $conn->prepare($plans_query);
    $plans_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $plans_stmt->execute();
    $plans = $plans_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("SQL error (fetch plans): " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel="stylesheet" href="../style/style.css" />
    <link rel="stylesheet" href="../font/fontawesome-free-6.6.0-web/css/all.min.css"/>
    <title>Loruki || User Setting__Dashboard</title>
</head>
<body>
    <!-- NavBar -->
    <div class="navbar">
        <div class="container flex">
            <h1 class="logo">Loruki</h1>
            <nav>
                <ul>
                    <li><a href="../../index.php">Home</a></li>
                    <li><a href="features.php">Features</a></li>
                    <li><a href="docs.php">Docs</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- User Directory -->
    <section class="fill">
        <div class="perk my-2">
            <p class="me"><i class="fas fa-user-circle"></i></p>
            <p class="md"><?php echo htmlspecialchars($username); ?></p>
            <p class="sm my-1"><?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="ling">
            <div class="ling-link">
                <ul class="well">
                    <li class="var"><a href="user.php">Dashboard</a></li>
                    <li><a href="create_plan.php">Create Plan</a></li>
                </ul>
            </div>

            <div class="ling-grid">
                <?php if (!empty($plans)): ?>
                    <?php foreach ($plans as $plan): ?>
                        <div class="grid-repo">
                            <p><strong>Plan:</strong> <?php echo htmlspecialchars($plan['plan_name']); ?></p>
                            <p><strong>Price:</strong> $<?php echo htmlspecialchars($plan['price']); ?></p>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                                <button type="submit" name="delete_plan">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You have no plans yet. Go to Create Plan!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer bg-dark py-5">
        <div class="container grid grid-3">
            <div>
                <h1>Loruki</h1>
                <p>Copyright &copy; 2025</p>
            </div>
            <nav>
                <ul>
                    <li><a href="../../index.php">Home</a></li>
                    <li><a href="features.php">Features</a></li>
                    <li><a href="docs.php">Docs</a></li>
                </ul>
            </nav>
        </div>
    </footer>

    <!-- User Info_Icon -->
    <div class="login">
        <a href="logout.php" title="Log Out" class="eek">Log Out</a>
    </div>
</body>
</html>
