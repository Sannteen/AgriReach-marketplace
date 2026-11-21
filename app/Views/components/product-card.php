<?php
// Display one reusable product card with a real photo when available.
require_once dirname(__DIR__, 2) . '/image-upload.php';
?>
<div class="col-sm-6 col-lg-4"><article class="product-card h-100">
<a href="/product.php?id=<?= (int)$product['id'] ?>" class="product-image" aria-label="View <?= e($product['name']) ?>"><?php if ($photo = productImageUrl($product['image_path'] ?? null)): ?>
<img src="<?= e($photo) ?>" alt="<?= e($product['name']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
<?php else: ?><span class="product-emoji" aria-hidden="true"><?= produceEmoji($product['category']) ?></span><?php endif; ?><span class="category-pill"><?= e($product['category']) ?></span></a>
<div class="p-4"><div class="d-flex justify-content-between align-items-start gap-2"><h3 class="h5 mb-1"><a class="text-reset text-decoration-none" href="/product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3><span class="text-success fw-bold text-nowrap"><?= priceJmd($product['price']) ?></span></div>
<p class="text-muted small mb-2">per <?= e($product['unit']) ?></p><p class="small mb-2">👨‍🌾 <?= e($product['farmer']) ?></p>
<p class="small text-muted mb-3"><?= (int)$product['quantity'] ?> available · <?= $product['delivery_available'] ? 'Delivery offered' : 'Pickup available' ?></p>
<a class="btn btn-outline-success w-100" href="/product.php?id=<?= (int)$product['id'] ?>">View Details</a></div></article></div>
