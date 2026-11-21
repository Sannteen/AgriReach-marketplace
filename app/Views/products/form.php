<?php
// This file is shared by the add and edit product pages.
$categories = ['Vegetables', 'Fruits', 'Ground Provisions'];
$units = ['lb', 'kg', 'each', 'dozen', 'bundle', 'bag'];
// Use submitted values after an error, otherwise use existing product values.
$values = $_POST ?: ($product ?? []);
// Format the current input value for safe display in a form field.
if (!function_exists('field')) { function field(array $values, string $name, $default = ''): string { return e($values[$name] ?? $default); } }
?>
<section class="section-space"><div class="container" style="max-width:820px"><a href="/dashboard.php" class="text-success">← Back to Dashboard</a><h1 class="h2 mt-3 mb-4"><?= e($pageTitle) ?></h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<!-- Save the product details through a protected POST request. -->
<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><div class="row g-3">
<div class="col-md-8"><label class="form-label" for="name">Produce name</label><input class="form-control" id="name" name="name" maxlength="150" value="<?= field($values, 'name') ?>" required></div>
<div class="col-md-4"><label class="form-label" for="category">Category</label><select class="form-select" id="category" name="category"><?php foreach ($categories as $cat): ?><option value="<?= e($cat) ?>" <?= ($values['category'] ?? '') === $cat ? 'selected' : '' ?>><?= e($cat) ?></option><?php endforeach; ?></select></div>
<div class="col-12"><label class="form-label" for="description">Description</label><textarea class="form-control" id="description" name="description" rows="4" maxlength="3000"><?= field($values, 'description') ?></textarea></div>
<div class="col-md-4"><label class="form-label" for="price">Price (JMD)</label><input class="form-control" id="price" name="price" type="number" step="0.01" min="0.01" max="99999999.99" value="<?= field($values, 'price') ?>" required></div>
<div class="col-md-4"><label class="form-label" for="unit">Unit</label><select class="form-select" id="unit" name="unit"><?php foreach ($units as $unit): ?><option value="<?= e($unit) ?>" <?= ($values['unit'] ?? 'lb') === $unit ? 'selected' : '' ?>><?= e($unit) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label" for="quantity">Quantity</label><input class="form-control" id="quantity" name="quantity" type="number" min="0" max="9999999" value="<?= field($values, 'quantity', 0) ?>" required></div>
<div class="col-md-6"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status"><option value="available" <?= ($values['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>Available</option><option value="sold_out" <?= ($values['status'] ?? '') === 'sold_out' ? 'selected' : '' ?>>Sold out</option></select></div>
<div class="col-md-6"><label class="form-label d-block">Fulfilment options</label><label class="me-3"><input type="checkbox" name="delivery_available" value="1" <?= !empty($values['delivery_available']) ? 'checked' : '' ?>> Delivery</label><label><input type="checkbox" name="pickup_available" value="1" <?= !empty($values['pickup_available']) ? 'checked' : '' ?>> Pickup</label></div>
</div>
<!-- Let the farmer add or replace a produce photo. -->
<div class="mt-3"><label for="product_image" class="form-label fw-semibold">Produce photo (optional)</label>
<input class="form-control" type="file" name="product_image" id="product_image" accept="image/jpeg,image/png,image/webp">
<div class="form-text">JPEG, PNG or WebP; maximum 3 MB. Leave blank when editing to keep the existing photo.</div>
<?php if (!empty($product['image_path']) && productImageUrl($product['image_path'])): ?>
<img class="mt-3 rounded border" style="max-width:200px;max-height:150px;object-fit:cover" src="<?= e(productImageUrl($product['image_path'])) ?>" alt="Current produce photo">
<?php endif; ?></div>
<button class="btn btn-success mt-4" type="submit">Save Product</button></form></div></section>
