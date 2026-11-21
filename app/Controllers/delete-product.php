<?php
// Allow farmers to delete only their own products.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/image-upload.php';
$user = requireFarmer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
checkCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('Invalid product ID.'); }
// Find the owned product before deleting it so we can clean up its photo.
$product = ProductModel::ownedById($id, (int)$user['id']);
if ($product) {
    if (ProductModel::delete($id, (int)$user['id'])) removeProductImage($product['image_path']);
}
flash($product ? 'Product deleted.' : 'Product not found.');
header('Location: /dashboard.php'); exit;
