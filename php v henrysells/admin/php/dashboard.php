<?php
require_once __DIR__ . '/../../config/strongMaple_config.php';
require_once __DIR__ . '/auth.php';

$currentPage = 'dashboard';

/* CSRF token */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* fetch reviews */
$stmt = $pdo->query(
    "SELECT id, review_text, created_at
     FROM reviews
     ORDER BY created_at DESC"
);
$reviews = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin • Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Styles -->
    <link rel="stylesheet" href="../css/dashboard.css" />

    <!-- Favicons -->
    <link rel="icon" href="../../assets/img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/img/apple-touch-icon.png" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap"
        rel="stylesheet" />
</head>

<body>

    <header class="admin-header">
        <div class="header-left">
            <h1>Henry Sells — Admin Dashboard</h1>
            <p class="subtitle">Review management</p>
        </div>

        <nav class="header-nav" id="adminNav">
            <a href="dashboard.php" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="orders.php" class="nav-link <?= $currentPage === 'orders' ? 'active' : '' ?>">Orders</a>
            <a href="newProduct.php" class="nav-link <?= $currentPage === 'newProduct' ? 'active' : '' ?>">New Product</a>

            <!-- desktop logout -->
            <form method="POST" action="../logout.php" class="desktop-only">
                <button type="submit" class="logout-btn">Logout</button>
            </form>

            <!-- mobile toggle -->
            <button class="nav-toggle" id="navToggle" aria-label="Open menu">☰</button>

            <!-- mobile dropdown -->
            <div class="dropdown">
                <a href="dashboard.php" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="orders.php" class="<?= $currentPage === 'orders' ? 'active' : '' ?>">Orders</a>
                <a href="newProduct.php" class="<?= $currentPage === 'newProduct' ? 'active' : '' ?>">New Product</a>
            </div>
        </nav>
    </header>

    <main class="dashboard-container">
        <section class="review-section">
            <h2 class="section-title">Submitted Reviews</h2>

            <?php if (empty($reviews)): ?>
                <p class="no-reviews">No reviews have been submitted yet.</p>
            <?php else: ?>
                <div class="reviews-grid">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <p class="review-date">
                                <?= htmlspecialchars(date('M j, Y — H:i', strtotime($review['created_at']))) ?>
                            </p>

                            <p class="review-text">
                                <?= nl2br(htmlspecialchars($review['review_text'])) ?>
                            </p>

                            <!-- ADMIN ONLY DELETE -->
                            <form method="POST" action="delete_review.php">
                                <input type="hidden" name="id" value="<?= (int)$review['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script>
        const nav = document.getElementById('adminNav');
        const toggle = document.getElementById('navToggle');

        toggle.addEventListener('click', () => {
            nav.classList.toggle('open');
        });

        document.addEventListener('click', e => {
            if (!nav.contains(e.target)) {
                nav.classList.remove('open');
            }
        });
    </script>

</body>

</html>