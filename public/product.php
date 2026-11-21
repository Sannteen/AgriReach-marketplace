<?php
// Load one available product and the farmer's public contact information.
require __DIR__ . '/includes/data.php';
require_once dirname(__DIR__) . '/app/image-upload.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = db()->prepare("SELECT p.*, u.full_name AS farmer, u.email AS farmer_email, u.phone AS farmer_phone FROM products p JOIN users u ON u.id = p.farmer_id WHERE p.id = ? AND p.status = 'available' LIMIT 1");
$stmt->execute([$id ?: 0]); $product = $stmt->fetch();
if (!$product) { http_response_code(404); $pageTitle = 'Product Not Found'; require __DIR__ . '/includes/header.php'; echo '<section class="section-space container"><h1>Product not found</h1><a href="/marketplace.php">Back to Marketplace</a></section>'; require __DIR__ . '/includes/footer.php'; exit; }
$pageTitle = $product['name']; $activePage = 'marketplace'; require __DIR__ . '/includes/header.php';
?>
<section class="section-space"><div class="container"><a href="/marketplace.php" class="text-success text-decoration-none">← Back to Marketplace</a><div class="row g-5 mt-1"><div class="col-lg-6"><div class="product-detail-art"><?php if ($photo = productImageUrl($product['image_path'] ?? null)): ?>
<img src="<?= e($photo) ?>" alt="<?= e($product['name']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
<?php else: ?><span aria-hidden="true"><?= produceEmoji($product['category']) ?></span><?php endif; ?></div></div><div class="col-lg-6"><span class="eyebrow"><?= e($product['category']) ?></span><h1 class="display-5 fw-bold mt-2"><?= e($product['name']) ?></h1><p class="detail-price"><?= priceJmd($product['price']) ?> <small>/ <?= e($product['unit']) ?></small></p><p class="lead"><?= nl2br(e($product['description'])) ?></p><hr><div class="detail-facts"><p><strong>Farmer:</strong> <?= e($product['farmer']) ?></p><p><strong>Quantity available:</strong> <?= (int)$product['quantity'] ?> <?= e($product['unit']) ?></p><p><strong>Delivery:</strong> <?= $product['delivery_available'] ? 'Available — confirm with farmer' : 'Not offered' ?></p><p><strong>Pickup:</strong> <?= $product['pickup_available'] ? 'Available — confirm with farmer' : 'Not offered' ?></p></div>
<!-- Let buyers contact the farmer directly without processing payments. -->
<div class="alert alert-info mt-4"><strong>Contact farmer:</strong> <a href="mailto:<?= e($product['farmer_email']) ?>?subject=AgriReach%20Produce%20Enquiry"><?= e($product['farmer_email']) ?></a><?php if ($product['farmer_phone']): ?><div>Phone: <?= e($product['farmer_phone']) ?></div><?php endif; ?></div>
<a class="btn btn-success btn-lg" href="/marketplace.php">Browse More Produce</a></div></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
