<?php

declare(strict_types=1);

require_once __DIR__ . '/config/strongMaple_config.php';

/* =========================
   1. HELPER FUNCTIONS
========================= */

function renderProductCard(array $p): void
{
    $media = $p['display_media'] ?? '';
    if ($media === '') {
        $mediaUrl = '/assets/img/placeholder.png';
        $isVideo = false;
    } elseif (str_starts_with($media, 'video/')) {
        $mediaUrl = '/assets/' . $media;
        $isVideo = true;
    } else {
        $mediaUrl = '/assets/' . $media;
        $isVideo = false;
    }
?>
    <div class="product-card" data-product="<?= (int)$p['id'] ?>">
        <div class="product-container">
            <?php if ($isVideo): ?>
                <video class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" muted loop playsinline preload="metadata"></video>
            <?php else: ?>
                <img class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <?php endif; ?>

            <div class="price-tag">
                <span class="currency">₦</span>
                <span class="amount"><?= htmlspecialchars($p['price_display']) ?></span>
            </div>
        </div>

        <div class="product-info">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p><?= htmlspecialchars($p['category_label'] ?? 'General') ?></p>
            <button class="place-order-btn">Place Order</button>
        </div>
    </div>
<?php
}

/**
 * Fetches products for specific sections.
 */
function getProductsForSection(PDO $pdo, string $sectionName): array
{
    try {
        $query = "
            SELECT p.*, c.label AS category_label
            FROM products p
            JOIN product_categories c ON p.category_id = c.id
            WHERE c.parent = :section 
               OR p.secondary_page = :section
            ORDER BY p.id DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['section' => $sectionName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // If secondary_page column doesn't exist yet, fallback to just category parent
        $query = "
            SELECT p.*, c.label AS category_label
            FROM products p
            JOIN product_categories c ON p.category_id = c.id
            WHERE c.parent = :section
            ORDER BY p.id DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['section' => $sectionName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/* =========================
   2. DATA FETCHING
========================= */

$accessoriesList = getProductsForSection($pdo, 'accessories');
$laptopsList     = getProductsForSection($pdo, 'laptops');
$phonesList      = getProductsForSection($pdo, 'phones');

/* =========================
   3. REVIEWS (AJAX)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    header('Content-Type: application/json');
    $review = trim($_POST['review']);

    if ($review === '' || mb_strlen($review) > 1200) {
        http_response_code(422);
        echo json_encode(['success' => false]);
        exit;
    }

    try {
        $stmtR = $pdo->prepare("INSERT INTO reviews (review_text) VALUES (?)");
        $stmtR->execute([$review]);
        echo json_encode(['success' => true]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['success' => false]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic page info -->
    <title>strongMaple — Curated, Genuine Tech Products</title>
    <!-- =========================
      PRIMARY META
========================= -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Indexing -->
    <meta name="robots" content="index, follow" />

    <meta
        name="description"
        content="strongMaple — trusted seller of high-quality phones, laptops, and accessories in Nigeria. Genuine products, fair pricing, and a seamless shopping experience." />

    <meta
        name="keywords"
        content="Strong Maple, strongMaple, Strong Maple Nigeria, strongMaple UNN, strongMaple phones, buy phones Nigeria, authentic gadgets, tech shop Nigeria" />
    <meta name="author" content="strongMaple" />

    <!-- Canonical -->
    <link rel="canonical" href="https://strongMaple.com" />

    <!-- Theme Color -->
    <meta name="theme-color" content="#f68b1e" />

    <!-- =========================
      OPEN GRAPH (Facebook / Instagram / LinkedIn)
      — Uses GitHub-hosted preview image
========================= -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="strongMaple" />

    <meta
        property="og:title"
        content="strongMaple — Genuine Phones & Quality Tech" />

    <meta
        property="og:description"
        content="Shop high-quality phones, laptops, and accessories from strongMaple — trusted, verified, and affordable." />

    <meta property="og:url" content="https://strongMaple.com" />

    <!-- GitHub image for all non-WhatsApp platforms -->
    <meta
        property="og:image"
        content="" />
    <meta
        property="og:image:secure_url"
        content="" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta
        property="og:image:alt"
        content="strongMaple – Trusted Phone Seller in Nigeria" />

    <!-- =========================
      WHATSApp SPECIAL PREVIEW IMAGE
========================= -->
    <meta
        property="og:image"
        content="https://strongMaple.com/og-image.png"
        data-platform="whatsapp" />

    <!-- =========================
      TWITTER / X (uses GitHub image)
========================= -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta
        name="twitter:title"
        content="strongMaple — Genuine Phones & Quality Tech" />
    <meta
        name="twitter:description"
        content="Discover quality phones, laptops, and accessories at fair prices from strongMaple." />
    <meta
        name="twitter:image"
        content="" />
    <meta name="twitter:site" content="@strongMaple07" />
    <meta name="twitter:creator" content="@strongMaple07" />

    <!-- =========================
      ICONS
========================= -->
    <link rel="icon" href="./assets/img/favicon.ico" />
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="./assets/img/apple-touch-icon.png" />

    <!-- =========================
      ORGANIZATION SCHEMA
========================= -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "strongMaple",
            "alternateName": "Strong Maple",
            "url": "https://strongMaple.com",
            "logo": "https://strongMaple.com/og-image.png",
            "sameAs": [
                "#",
                "#"
            ],
            "contactPoint": [{
                "@type": "ContactPoint",
                "telephone": "+2348160813334",
                "contactType": "customer service",
                "areaServed": "NG"
            }]
        }
    </script>

    <!-- =========================
      PERSON SCHEMA
========================= -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Person",
            "name": "Strong Maple",
            "alternateName": "strongMaple",
            "url": "https://strongMaple.com",
            "jobTitle": "Tech Product Seller",
            "image": "https://strongMaple.com/og-image.png",
            "sameAs": [
                "#",
                "#"
            ]
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Righteous&display=swap"
        rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/index.css" />

    <!-- Font Awesome -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"
        crossorigin="anonymous"
        defer></script>
</head>

<body>
    <div id="preloader" class="preloader">
        <div class="preloader-content">
            <span class="fa-stack fa-2x logo-stack">
                <i class="fa-regular fa-circle fa-stack-2x circle-glow"></i>
                <i class="fa-solid fa-star fa-stack-1x star-spin"></i>
            </span>
            <div class="loading-text">Loading...</div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div>
            <a href="#accessory-deals">
                <span class="fa-stack fa-1g">
                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                    <i class="fa-solid fa-star fa-stack-1x"></i>
                </span>
                Buy from me
            </a>
        </div>

        <div>
            <p>Strong Maple</p>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="wrapper">
            <div class="logo">
                Henry<b>Sells</b><span class="fa-stack fa-1g"><i class="fa-regular fa-circle fa-stack-2x"></i><i class="fa-solid fa-star fa-stack-1x"></i></span>
            </div>

            <div class="search-bar">
                <input
                    type="search"
                    placeholder="Search products, brands and categories" />
                <button class="search-btn">Search</button>
            </div>

            <div class="about-me more-info-btn" data-target="card1">
                <i class="fa-regular fa-user"></i>About
            </div>

            <div class="help more-info-btn" data-target="card2">
                <i class="fa-regular fa-circle-question"></i>Help
            </div>

            <div class="contact more-info-btn" data-target="card3">
                <i class="fa-solid fa-location-dot"></i>Contact
            </div>
        </div>

        <!-- Overlay (All Cards) -->
        <div class="overlay" id="overlay">
            <!-- Card 1 -->
            <div class="card" id="card1">
                <i class="fa-solid fa-xmark close-btn"></i>
                <h2>About Strong Maple</h2>
                <p>
                    Strong Maple delivers curated, high-quality phones, laptops, and
                    accessories — offering genuine products, fair pricing, and a refined
                    shopping experience.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="card" id="card2">
                <i class="fa-solid fa-xmark close-btn"></i>
                <h2>Help & Support</h2>
                <p>
                    <strong>Need Help?</strong><br /><br />
                    I personally handle every order, inquiry, and customer request.
                    Whether you're interested in a phone, laptop, or accessory, I’m here
                    to guide you through a smooth buying process.<br /><br />

                    <strong>How I Support You:</strong><br />
                    • Product recommendations<br />
                    • Price inquiries<br />
                    • Availability checks<br />
                    • Order confirmation<br />
                    • Delivery information<br />
                    • Warranty or after-sale support
                </p>
            </div>

            <!-- Card 3 -->
            <div class="card" id="card3">
                <i class="fa-solid fa-xmark close-btn"></i>
                <h2>Find Me Here</h2>
                <p>
                    I'm a UNN student & verified gadget supplier, offering nationwide
                    delivery with reliability you can trust. Whether you're around
                    campus or anywhere in Nigeria, I handle every order personally,
                    quickly, and professionally.
                </p>

                <strong>My Location:</strong>
                <div
                    style="
              width: 100%;
              height: 300px;
              border-radius: 10px;
              overflow: hidden;
              margin-top: 0.5rem;
            ">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.8545325745925!2d7.41252897373187!3d6.857341118966986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10439d148c7e8b1d%3A0xc5d481f5cd6243ad!2sUniversity%20of%20Nigeria%2C%20Nsukka!5e0!3m2!1sen!2sng!4v1700000000000"
                        width="100%"
                        height="100%"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </nav>

    <!-- Navigation Path -->
    <div class="navigation">
        <p class="nav">
            <span id="current">Accessories</span>
            <span id="nav-toggle">›</span>
            <span id="nav-links" class="hidden">
                <a href="#laptops" data-name="Laptops">Laptops</a>
                <a href="#phones" data-name="Phones">Phones</a>
                <a href="#accessories" data-name="Accessories">Accessories</a>
            </span>
        </p>
    </div>

    <section class="showcase"></section>

    <!-- ACCESSORIES -->

    <section id="accessories">
        <div class="page-head nav current">
            <h1>Accessories</h1>
        </div>

        <!-- Category Filter -->
        <section class="accessories-categories">
            <div class="ac-item">
                <img src="./assets/img/phone_accessories.png" alt="Phone Accessories" />
                <p>Phone Accessories <span>(Power Banks, Chargers & Gadgets)</span></p>
            </div>

            <div class="ac-item">
                <img src="./assets/img/laptop_accessories.png" alt="Wireless Devices" />
                <p>Laptop Accessories <span>(Routers, Mouse & Keyboards)</span></p>
            </div>
        </section>



        <!-- Accessories - Deals -->
        <section class="deals" id="accessory-deals">
            <?php foreach ($accessoriesList as $p): ?>
                <?php
                $media = $p['display_media'] ?? '';
                $mediaUrl = ($media === '') ? '/assets/img/placeholder.png' : '/assets/' . $media;
                $isVideo = str_starts_with($media, 'video/');
                ?>
                <div class="product-card" data-product="<?= (int)$p['id'] ?>">
                    <div class="product-container">
                        <?php if ($isVideo): ?>
                            <video class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" muted loop playsinline preload="metadata"></video>
                        <?php else: ?>
                            <img class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                        <?php endif; ?>

                        <!-- Menu Icon / Options -->
                        <div class="menu-icon" title="Options">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </div>
                        <div class="menu-options">
                            <a href="product.php?id=<?= (int)$p['id'] ?>" class="preview-btn">Preview</a>
                            <button class="select-btn" data-product="<?= (int)$p['id'] ?>">Select</button>
                        </div>

                        <div class="selection-overlay"></div>

                        <div class="price-tag">
                            <span class="currency">₦</span>
                            <span class="amount"><?= htmlspecialchars($p['price_display']) ?></span>
                        </div>
                    </div>

                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p><?= htmlspecialchars($p['category_label'] ?? '') ?></p>
                        <button class="place-order-btn">Place Order</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </section>


    <!-- LAPTOPS -->
    <section id="laptops">
        <div class="page-head nav current">
            <h1>Laptops</h1>
        </div>

        <!-- Category Filter -->
        <section class="categories">
            <div>
                <img src="./assets/img/macbook.jpg" alt="MacBook-print" width="145px" />
                <p>MacBook <span>(Apple)</span></p>
            </div>

            <div>
                <img src="./assets/img/dell.png" alt="Dell-print" width="145px" />
                <p>Dell <span>(XPS, Inspiron)</span></p>
            </div>

            <div>
                <img src="./assets/img/hp.png" alt="HP-print" width="145px" />
                <p>HP <span>(x360's, Spectre, Pavilion)</span></p>
            </div>

            <div>
                <img src="./assets/img/Lenovo.png" alt="Lenovo-print" width="145px" />
                <p>Lenovo <span>(Thinkpad, Yoga)</span></p>
            </div>

            <div>
                <img src="./assets/img/asus.png" alt="Asus-print" width="145px" />
                <p>Asus <span>(ZenBook, ROG)</span></p>
            </div>

            <div>
                <img src="./assets/img/acer.png" alt="Acer-print" width="145px" />
                <p>Acer <span>(Predator, Swift)</span></p>
            </div>
        </section>



        <!-- Laptops - Deals -->
        <section class="deals" id="laptop-deals">
            <?php foreach ($laptopsList as $p): ?>
                <?php
                $media = $p['display_media'] ?? '';
                $mediaUrl = ($media === '') ? '/assets/img/placeholder.png' : '/assets/' . $media;
                $isVideo = str_starts_with($media, 'video/');
                ?>
                <div class="product-card" data-product="<?= (int)$p['id'] ?>">
                    <div class="product-container">
                        <?php if ($isVideo): ?>
                            <video class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" muted loop playsinline preload="metadata"></video>
                        <?php else: ?>
                            <img class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                        <?php endif; ?>

                        <!-- Menu Icon / Options -->
                        <div class="menu-icon" title="Options">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </div>
                        <div class="menu-options">
                            <a href="product.php?id=<?= (int)$p['id'] ?>" class="preview-btn">Preview</a>
                            <button class="select-btn" data-product="<?= (int)$p['id'] ?>">Select</button>
                        </div>

                        <div class="selection-overlay"></div>

                        <div class="price-tag">
                            <span class="currency">₦</span>
                            <span class="amount"><?= htmlspecialchars($p['price_display']) ?></span>
                        </div>
                    </div>

                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p><?= htmlspecialchars($p['category_label'] ?? '') ?></p>
                        <button class="place-order-btn">Place Order</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </section>


    <!-- PHONES -->
    <section id="phones">
        <div class="page-head nav current">
            <h1>Phones</h1>
        </div>

        <section class="categories">
            <div>
                <img src="./assets/img/iPhone.png" alt="Iphone-print" width="145px" />
                <p>iPhone <span>(Apple)</span></p>
            </div>

            <div>
                <img src="./assets/img/Samsung.jpg" alt="Samsung-print" width="145px" />
                <p>Samsung</p>
            </div>

            <div>
                <img src="./assets/img/Xiaomi.jpeg" alt="Xiaomi" width="145px" />
                <p>Xiaomi</p>
            </div>

            <div>
                <img src="./assets/img/OnePlus.jpeg" alt="OnePlus-print" width="145px" />
                <p>OnePlus</p>
            </div>

            <div>
                <img src="./assets/img/oppo.jpeg" alt="Oppo-print" width="145px" />
                <p>Oppo</p>
            </div>

            <div>
                <img src="./assets/img/pixel.jpeg" alt="Pixel-print" width="145px" />
                <p>Pixel <span>(Google)</span></p>
            </div>
        </section>


        <!-- Phones - Deals -->
        <section class="deals" id="phone-deals">
            <?php foreach ($phonesList as $p): ?>
                <?php
                $media = $p['display_media'] ?? '';
                $mediaUrl = ($media === '') ? '/assets/img/placeholder.png' : '/assets/' . $media;
                $isVideo = str_starts_with($media, 'video/');
                ?>
                <div class="product-card" data-product="<?= (int)$p['id'] ?>">
                    <div class="product-container">
                        <?php if ($isVideo): ?>
                            <video class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" muted loop playsinline preload="metadata"></video>
                        <?php else: ?>
                            <img class="product-image" src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                        <?php endif; ?>

                        <!-- Menu Icon / Options -->
                        <div class="menu-icon" title="Options">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </div>
                        <div class="menu-options">
                            <a href="product.php?id=<?= (int)$p['id'] ?>" class="preview-btn">Preview</a>
                            <button class="select-btn" data-product="<?= (int)$p['id'] ?>">Select</button>
                        </div>

                        <div class="selection-overlay"></div>

                        <div class="price-tag">
                            <span class="currency">₦</span>
                            <span class="amount"><?= htmlspecialchars($p['price_display']) ?></span>
                        </div>
                    </div>

                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p><?= htmlspecialchars($p['category_label'] ?? '') ?></p>
                        <button class="place-order-btn">Place Order</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </section>

    <!-- Floating Buy Button -->
    <button class="order-selected-btn">Order Selected</button>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <!-- Review Card -->
            <div class="review-card" role="form" aria-labelledby="reviewTitle">
                <div class="left">
                    <div>
                        <div class="title" id="reviewTitle">
                            <i class="fa-solid fa-star"></i>
                            Leave a review
                        </div>
                        <div class="subtitle">
                            I appreciate every message—short or long. Your feedback helps me make this better.
                        </div>
                    </div>

                    <div class="review-input">
                        <label class="visually-hidden" for="review-text">Your review</label>
                        <textarea
                            id="review-text"
                            placeholder="Share what you enjoyed or what I can improve."
                            maxlength="1200"
                            aria-describedby="charHint"></textarea>

                        <div class="meta-row">
                            <div class="meta-pill">Text only</div>
                            <div class="meta-pill" id="charHint">1200 max</div>
                        </div>
                    </div>

                    <div class="legal">
                        By submitting, you agree to the community guidelines.
                    </div>
                </div>

                <div class="right">
                    <div class="star-row">
                        <div class="star">
                            <i class="fa-solid fa-star" style="font-size: 20px"></i>
                        </div>
                    </div>
                    <button class="submit-btn" type="button" aria-label="Submit review" id="reviewSubmit">
                        <i class="fa-solid fa-pen-nib"></i>Submit review
                    </button>
                    <div class="submit-caption">
                        Secure • Private • Reviewed by one human
                    </div>
                    <div class="helper">
                        Only text is supported right now—keeping it simple and focused.
                    </div>
                </div>
            </div>

            <script>
                (() => {
                    const textarea = document.getElementById("review-text");
                    const charHint = document.getElementById("charHint");
                    const submitBtn = document.getElementById("reviewSubmit");

                    const MAX = 1200;

                    textarea.addEventListener("input", () => {
                        const remaining = MAX - textarea.value.length;
                        charHint.textContent = remaining + " left";
                        charHint.style.color =
                            remaining <= 20 ? "#ff6b6b" :
                            remaining <= 100 ? "#ffb347" : "";
                    });

                    submitBtn.addEventListener("click", async () => {
                        const review = textarea.value.trim();
                        if (!review) {
                            alert("Please write something before submitting.");
                            return;
                        }

                        submitBtn.disabled = true;
                        submitBtn.textContent = "Submitting...";

                        try {
                            const res = await fetch(window.location.href, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: new URLSearchParams({
                                    review
                                })
                            });

                            const data = await res.json();

                            if (!res.ok || !data.success) throw new Error();

                            textarea.value = "";
                            charHint.textContent = "1200 max";
                            alert("Thanks for your review!");
                        } catch {
                            alert("Could not submit review. Please try again.");
                        }

                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa-solid fa-pen-nib"></i>Submit review';
                    });
                })();
            </script>

            <!-- Brand / Socials -->
            <div class="footer-brand">
                <div class="logo">
                    Henry<b>Sells</b>
                    <span class="fa-stack fa-1g">
                        <i class="fa-regular fa-circle fa-stack-2x"></i>
                        <i class="fa-solid fa-star fa-stack-1x"></i>
                    </span>
                </div>

                <div class="socials">
                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer nofollow"
                        aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer nofollow"
                        aria-label="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>

                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer nofollow"
                        aria-label="Telegram">
                        <i class="fa-brands fa-telegram"></i>
                    </a>

                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer nofollow"
                        aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>

                    <a
                        href="mailto:strongMaple@proton.me"
                        aria-label="Email"
                        rel="nofollow">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr />

        <!-- Footer Buttons -->
        <div class="footer-links">
            <button class="footer-btn" data-popup="about">About</button>
            <button class="footer-btn" data-popup="help">Help</button>
            <button class="footer-btn" data-popup="contact">Contact</button>
        </div>

        <p class="copyright">
            &copy; <span id="year"></span> Henry Sell |
            <a
                href="#"
                target="_blank"
                rel="noopener noreferrer">
                Henify
            </a>
        </p>

        <!-- Popup Modal -->
        <div class="popup" id="popup">
            <div class="popup-content">
                <span class="close-btn">&times;</span>
                <h2 id="popup-title"></h2>
                <p id="popup-text"></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script type="module" src="./assets/js/main.js"></script>
    <script type="module" src="./assets/js/search.js"></script>
    <script type="module" src="./assets/js/handle.js"></script>

</body>

</html>