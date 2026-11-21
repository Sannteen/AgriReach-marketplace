<?php
// Load the database connection and read the marketplace filters.
require __DIR__ . '/includes/data.php';
$pageTitle = 'Browse Marketplace'; $activePage = 'marketplace';
$search = trim((string)($_GET['search'] ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$categories = ['Vegetables', 'Fruits', 'Ground Provisions'];
if (strlen($search) > 100) $search = substr($search, 0, 100);
if ($category !== '' && !in_array($category, $categories, true)) $category = '';
// Use prepared SQL parameters to search real products safely.
$sql = "SELECT p.*, u.full_name AS farmer FROM products p JOIN users u ON u.id = p.farmer_id WHERE p.status = 'available'";
$params = [];
if ($search !== '') { $sql .= ' AND (p.name LIKE ? OR p.description LIKE ? OR u.full_name LIKE ?)'; $term = '%' . $search . '%'; $params = [$term, $term, $term]; }
if ($category !== '') { $sql .= ' AND p.category = ?'; $params[] = $category; }
$sql .= ' ORDER BY p.created_at DESC LIMIT 100';
$stmt = db()->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner"><div class="container"><span class="eyebrow">THE AGRIREACH MARKETPLACE</span><h1>Explore Fresh Produce</h1><p>Browse local harvests and discover the farmers behind them.</p></div></section>
<section class="section-space"><div class="container">
<!-- Allow buyers to search products by name, description, or farmer. -->
<form class="filter-bar row g-3 align-items-end mb-4" method="get" action="/marketplace.php"><div class="col-md-7"><label class="form-label fw-semibold" for="search">Search produce</label><input class="form-control form-control-lg" id="search" name="search" value="<?= e($search) ?>" placeholder="Try tomatoes, bananas, yams..."></div><div class="col-md-3"><label class="form-label fw-semibold" for="category">Category</label><select class="form-select form-select-lg" id="category" name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?></select></div><div class="col-md-2"><button class="btn btn-success btn-lg w-100" type="submit">Search</button></div></form>
<div class="d-flex justify-content-between align-items-center mb-4"><h2 class="h5 mb-0"><?= count($products) ?> listings found</h2><a href="/marketplace.php" class="text-success">Clear filters</a></div>
<!-- Show live product cards or a helpful message when no products match. -->
<?php if ($products): ?><div class="row g-4"><?php foreach ($products as $product): include __DIR__ . '/includes/product-card.php'; endforeach; ?></div><?php else: ?><div class="empty-state"><div class="display-4">🔎</div><h3>No produce found</h3><p>Try another search, or ask a farmer to create the first listing.</p><a class="btn btn-success" href="/marketplace.php">Show All Products</a></div><?php endif; ?>
</div></section><?php require __DIR__ . '/includes/footer.php'; ?>
