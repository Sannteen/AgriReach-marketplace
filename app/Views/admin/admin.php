<?php // Render the admin page using the existing Bootstrap layout. ?>
<section class="page-banner"><div class="container"><span class="eyebrow">MARKETPLACE MANAGEMENT</span><h1>Admin Dashboard</h1><p>Review listings and remove inappropriate products.</p></div></section>
<section class="section-space"><div class="container">
<div class="row g-3 mb-4">
<div class="col-md-4"><div class="stat-card"><span>Registered Users</span><strong><?= $usersCount ?></strong></div></div>
<div class="col-md-4"><div class="stat-card"><span>All Listings</span><strong><?= $productsCount ?></strong></div></div>
<div class="col-md-4"><div class="stat-card"><span>Available Listings</span><strong><?= $availableCount ?></strong></div></div>
</div>
<h2 class="h4 mb-3">Recent Product Listings</h2>
<div class="table-responsive"><table class="table table-hover bg-white align-middle">
<thead><tr><th>Product</th><th>Farmer</th><th>Category</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php foreach ($products as $product): ?>
<tr><td><a href="/product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></td>
<td><?= e($product['farmer']) ?></td><td><?= e($product['category']) ?></td>
<td><?= priceJmd($product['price']) ?></td><td><?= e($product['status']) ?></td>
<td><form method="post" action="/admin-delete-product.php" onsubmit="return confirm('Remove this listing permanently?')">
<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
<input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
<button class="btn btn-sm btn-outline-danger" type="submit">Remove</button></form></td></tr>
<?php endforeach; ?>
<?php if (!$products): ?><tr><td colspan="6" class="text-center py-4">No listings yet.</td></tr><?php endif; ?>
</tbody></table></div>
<p class="text-muted small">Showing the 100 most recent listings. Admin access is assigned manually; visitors cannot register as admins.</p>
</div></section>
