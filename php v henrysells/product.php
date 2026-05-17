<?php
require_once __DIR__ . '/./config/strongMaple_config.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
  http_response_code(404);
  exit('No Product found');
}

$media = $pdo->prepare("SELECT media_path FROM product_media WHERE product_id=?");
$media->execute([$id]);
$gallery = $media->fetchAll(PDO::FETCH_COLUMN);

$specs = $pdo->prepare("SELECT spec_text FROM product_specs WHERE product_id=?");
$specs->execute([$id]);
$specs = $specs->fetchAll(PDO::FETCH_COLUMN);

function media($path)
{
  return '../../admin/' . $path;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />

  <!-- Responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- ===========================
        BASIC SEO
  ============================ -->
  <title><?= htmlspecialchars($p['name']) ?> • strongMaple</title>
  <meta name="description" content="<?= htmlspecialchars($p['description']) ?>">

  <meta
    id="meta-description"
    name="description"
    content="View product details, full specifications, pricing, and high-quality media for items available on strongMaple." />

  <!-- Canonical URL -->
  <link id="meta-canonical" rel="canonical" href="" />

  <!-- Robots -->
  <meta name="robots" content="index, follow" />

  <!-- ===========================
        OPEN GRAPH (WhatsApp, FB)
  ============================ -->
  <meta property="og:type" content="product" />
  <meta id="og-title" property="og:title" content="strongMaple Product" />
  <meta
    id="og-desc"
    property="og:description"
    content="View product details, specifications, price, and media." />
  <meta id="og-image" property="og:image" content="" />
  <meta id="og-url" property="og:url" content="" />
  <meta property="og:site_name" content="strongMaple" />

  <!-- ===========================
        TWITTER CARD
  ============================ -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta id="tw-title" name="twitter:title" content="strongMaple Product" />
  <meta
    id="tw-desc"
    name="twitter:description"
    content="Full product details with price, images, and specifications." />
  <meta id="tw-image" name="twitter:image" content="" />

  <!-- =========================
        ICONS
  ========================= -->
  <link rel="icon" href="../img/favicon.ico" />
  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="../img/apple-touch-icon.png" />

  <!-- ===========================
        JSON-LD STRUCTURED PRODUCT DATA
        (Google Rich Snippets)
  ============================ -->
  <script id="json-ld" type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "",
      "image": [],
      "description": "",
      "brand": {
        "@type": "Brand",
        "name": "strongMaple"
      },
      "offers": {
        "@type": "Offer",
        "url": "",
        "priceCurrency": "NGN",
        "price": "",
        "availability": "https://schema.org/InStock"
      }
    }
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Righteous&display=swap"
    rel="stylesheet" />

  <!-- CSS -->
  <link rel="stylesheet" href="../css/index.css" />

  <!-- Font Awesome -->
  <script
    defer
    src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"
    crossorigin="anonymous"></script>
</head>

<body style="background: rgba(0, 0, 0, 0.7)">

  <div class="preview-overlay active">
    <div class="preview-content">

      <span class="close-preview" onclick="history.back()">&times;</span>

      <div class="carousel">
        <?php if ($p['display_media']): ?>
          <img src="<?= media($p['display_media']) ?>">
        <?php endif; ?>

        <?php foreach ($gallery as $g): ?>
          <img src="<?= media($g) ?>">
        <?php endforeach; ?>
      </div>

      <div class="specs-card">
        <h3>Specifications</h3>
        <ul>
          <?php foreach ($specs as $s): ?>
            <li><?= htmlspecialchars($s) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  </div>

</body>

</html>