<?php
require_once __DIR__ . '/../../config/strongMaple_config.php';
require_once __DIR__ . '/auth.php';

$currentPage = 'newProduct';
$msg = "";

/* ========================
   DELETE PRODUCT
======================== */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $pdo->prepare("DELETE FROM product_specs WHERE product_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM product_media WHERE product_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);

    header("Location: newProduct.php");
    exit;
}

/* ========================
   PRICE FORMAT
======================== */
function formatPriceDisplay(int $price): string
{
    if ($price >= 1_000_000) {
        $m = floor($price / 1_000_000);
        $k = floor(($price % 1_000_000) / 1_000);
        return $k ? "{$m}m{$k}k" : "{$m}m";
    }
    if ($price >= 1_000) {
        return floor($price / 1_000) . "k";
    }
    return (string)$price;
}

/* ========================
   ADD / EDIT PRODUCT
======================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {

    $id   = $_POST['product_id'] ?? null;
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $secondaryPage = !empty($_POST['secondary_page']) ? $_POST['secondary_page'] : null;

    $priceNumeric = (int)str_replace(',', '', $_POST['price_numeric']);
    $priceDisplay = formatPriceDisplay($priceNumeric);
    $categoryId = (int)($_POST['category_id'] ?? 0);

    /* ========================
       MAIN DISPLAY MEDIA (UPDATED)
    ======================== */
    $displayPath = $_POST['existing_media'] ?? null;

    if (!empty($_FILES['display_media']['tmp_name'])) {
        $tmp = $_FILES['display_media']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['display_media']['name'], PATHINFO_EXTENSION));
        $filename = time() . '.' . $ext;

        if (in_array($ext, ['mp4', 'webm'])) {
            $displayPath = 'video/' . $filename;
            move_uploaded_file($tmp, __DIR__ . '/../../assets/video/' . $filename);
        } else {
            $displayPath = 'img/' . $filename;
            move_uploaded_file($tmp, __DIR__ . '/../../assets/img/' . $filename);
        }
    }

    if ($id) {
        $stmt = $pdo->prepare("
            UPDATE products SET
                name=?, description=?, price=?, price_display=?,
                category_id=?, secondary_page=?, display_media=?
            WHERE id=?
        ");
        $stmt->execute([
            $name,
            $desc,
            $priceNumeric,
            $priceDisplay,
            $categoryId,
            $secondaryPage,
            $displayPath,
            $id
        ]);
        $productId = $id;
        $msg = "Product updated successfully";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO products
            (name, description, price, price_display, category_id, secondary_page, display_media)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $name,
            $desc,
            $priceNumeric,
            $priceDisplay,
            $categoryId,
            $secondaryPage,
            $displayPath
        ]);
        $productId = $pdo->lastInsertId();
        $msg = "Product added successfully";
    }

    /* ========================
       GALLERY MEDIA (UPDATED)
    ======================== */
    if (!empty($_FILES['gallery']['tmp_name'])) {
        foreach ($_FILES['gallery']['tmp_name'] as $i => $tmp) {
            if (!$tmp) continue;

            $ext = strtolower(pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION));
            $filename = time() . '_' . $i . '.' . $ext;

            if (in_array($ext, ['mp4', 'webm'])) {
                $path = 'video/' . $filename;
                move_uploaded_file($tmp, __DIR__ . '/../../assets/video/' . $filename);
            } else {
                $path = 'img/' . $filename;
                move_uploaded_file($tmp, __DIR__ . '/../../assets/img/' . $filename);
            }

            $pdo->prepare(
                "INSERT INTO product_media (product_id, media_path) VALUES (?, ?)"
            )->execute([$productId, $path]);
        }
    }

    /* ========================
       SPECS
    ======================== */
    foreach ($_POST['specs'] ?? [] as $spec) {
        if ($spec !== '') {
            $pdo->prepare(
                "INSERT INTO product_specs (product_id, spec_text) VALUES (?, ?)"
            )->execute([$productId, trim($spec)]);
        }
    }
}

/* ========================
   EDIT MODE
======================== */
$editProduct = null;
$specs = [];

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();

    if ($editProduct) {
        $s = $pdo->prepare("SELECT spec_text FROM product_specs WHERE product_id=?");
        $s->execute([$_GET['edit']]);
        $specs = $s->fetchAll(PDO::FETCH_COLUMN);
    }
}

$products = $pdo->query("
    SELECT p.*, c.parent, c.label
    FROM products p
    JOIN product_categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • Products</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/newProduct.css">

    <!-- Favicons -->
    <link rel="icon" href="../../assets/img/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/img/apple-touch-icon.png" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
</head>

<body>

    <header class="admin-header">
        <div class="header-left">
            <h1>Henry Sells — Admin Products</h1>
            <p class="subtitle">Product creation & management</p>
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

    <div class="admin-wrap">

        <h2>📦 Product Manager</h2>

        <?php if ($msg): ?>
            <div class="success" id="successMsg"><?= htmlspecialchars($msg) ?></div>
            <script>
                setTimeout(() => document.getElementById('successMsg')?.classList.add('fade'), 1500);
            </script>
        <?php endif; ?>

        <button id="toggleForm">+ Add Product</button>

        <form id="productForm" class="product-form <?= $editProduct ? 'show' : '' ?>"
            method="POST" enctype="multipart/form-data">

            <input type="hidden" name="save_product">
            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?? '' ?>">
            <input type="hidden" name="existing_media" value="<?= $editProduct['display_media'] ?? '' ?>">

            <label>Product Name</label>
            <input name="name" required value="<?= htmlspecialchars($editProduct['name'] ?? '') ?>">

            <label>Description</label>
            <input name="description" required value="<?= htmlspecialchars($editProduct['description'] ?? '') ?>">

            <label>Category</label>
            <select name="category_id" required>
                <option value="">— Select Category —</option>
                <?php
                foreach ($pdo->query("SELECT id,parent,label FROM product_categories ORDER BY parent,label") as $c):
                ?>
                    <option value="<?= $c['id'] ?>"
                        <?= ($editProduct['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= ucfirst($c['parent']) ?> — <?= ucfirst($c['label']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Price (numbers only)</label>
            <input name="price_numeric" placeholder="130000"
                value="<?= $editProduct['price'] ?? '' ?>" required>

            <label>Also show on</label>
            <select name="secondary_page">
                <option value="">— None —</option>
                <option value="accessories" <?= ($editProduct['secondary_page'] ?? '') === 'accessories' ? 'selected' : '' ?>>Accessories</option>
                <option value="phones" <?= ($editProduct['secondary_page'] ?? '') === 'phones' ? 'selected' : '' ?>>Phones</option>
                <option value="laptops" <?= ($editProduct['secondary_page'] ?? '') === 'laptops' ? 'selected' : '' ?>>Laptops</option>
            </select>

            <label>Main Display Image / Video</label>
            <input type="file" name="display_media" accept="image/*,video/mp4,video/webm">

            <label>Gallery / Specification Media</label>
            <input type="file" name="gallery[]" multiple
                accept="image/*,video/mp4,video/webm">

            <label>Specifications</label>
            <div id="specs">
                <?php foreach ($specs ?: [''] as $s): ?>
                    <input name="specs[]" value="<?= htmlspecialchars($s) ?>">
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addSpec()">+ Add Spec</button>
            <button type="submit">Save Product</button>
        </form>

        <hr>

        <h3>Existing Products</h3>
        <?php foreach ($products as $p): ?>
            <div class="product-row">
                <b><?= htmlspecialchars($p['name']) ?></b>
                <span>₦<?= htmlspecialchars($p['price_display']) ?></span>
                <a href="?edit=<?= $p['id'] ?>"><i class="fa fa-pen"></i></a>
                <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete product?')">
                    <i class="fa fa-trash"></i></a>
            </div>
        <?php endforeach; ?>

    </div>

    <script>
        document.getElementById('toggleForm').onclick = () =>
            document.getElementById('productForm').classList.toggle('show');

        function addSpec() {
            const i = document.createElement('input');
            i.name = 'specs[]';
            document.getElementById('specs').appendChild(i);
        }
    </script>

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