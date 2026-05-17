<?php
// Include configuration file (ensures database connection is established)
require_once 'config.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details (Username and Email) for display
try {
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching user details: " . $e->getMessage());
}

// Define error messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $plan_name = $_POST['plan_name'];
    $storage = $_POST['storage'];
    $bandwidth = $_POST['bandwidth'];
    $description = $_POST['description'];
    $add_ons = $_POST['add_ons'];
    $price = $_POST['price'];

    // Validate form data
    if (empty($plan_name) || empty($storage) || empty($bandwidth) || empty($description) || empty($price)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        try {
            // Prepare SQL query
            // MySQLi version (wrong):
            // $insert_query = "INSERT INTO user_plans (user_id, plan_name, storage, bandwidth, description, add_ons, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            // $stmt = $conn->prepare($insert_query);
            // $stmt->bind_param('isssssd', $user_id, $plan_name, $storage, $bandwidth, $description, $add_ons, $price);
            
            // Updated PDO version (correct):
            $insert_query = "INSERT INTO user_plans (user_id, plan_name, storage, bandwidth, description, add_ons, price) 
                             VALUES (:user_id, :plan_name, :storage, :bandwidth, :description, :add_ons, :price)";
            $stmt = $conn->prepare($insert_query);

            // Bind parameters
            $stmt->execute([
                ':user_id' => $user_id,
                ':plan_name' => $plan_name,
                ':storage' => $storage,
                ':bandwidth' => $bandwidth,
                ':description' => $description,
                ':add_ons' => $add_ons,
                ':price' => $price
            ]);

            $success_message = 'Plan created successfully.';
        } catch (PDOException $e) {
            $error_message = 'Error creating plan: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../font/fontawesome-free-6.6.0-web/css/all.min.css">
    <title>Loruki || User Setting__Create Plan</title>
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
    <p class="md"><?php echo htmlspecialchars($user['username']); ?></p>
    <p class="sm my-2"><?php echo htmlspecialchars($user['email']); ?></p>
</div>

<div class="ling">
    <div class="ling-link">
        <ul class="well">
            <li><a href="user.php">Dashboard</a></li>
            <li class="var"><a href="create_plan.php">Create Plan</a></li>
        </ul>
    </div>

    <div class="repo-form">
        <?php if (!empty($success_message)): ?>
            <p style="color:green;"><?php echo $success_message; ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="repo-div">
                <label for="plan_name">Plan Name:</label><br>
                <input type="text" id="plan_name" name="plan_name" required><br><br>
            </div>

            <div class="repo-div">
                <label for="storage">Storage (GB):</label><br>
                <input type="number" id="storage" name="storage" required><br><br>
            </div>

            <div class="repo-div">
                <label for="bandwidth">Bandwidth (GB):</label><br>
                <input type="number" id="bandwidth" name="bandwidth" required><br><br>
            </div>

            <div class="repo-div">
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" required></textarea><br><br>
            </div>

            <div class="repo-div">
                <label for="add_ons">Add-Ons:</label><br>
                <input type="text" id="add_ons" name="add_ons"><br><br>
            </div>

            <div class="repo-div">
                <label for="price">Price ($):</label><br>
                <input type="number" id="price" name="price" step="0.01" required><br><br>
            </div>

            <button type="submit" class="ref btn-outline">Create Plan</button>
        </form>
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
