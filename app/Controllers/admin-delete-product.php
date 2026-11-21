<?php
// Allow an administrator to remove a product through a protected form.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/image-upload.php';
$user = requireLogin();
if ($user['role'] !== 'admin') { http_response_code(403); exit('Administrator access required.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
checkCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('Invalid product ID.'); }
// Read the photo path before removing the listing from MySQL.
$product = ProductModel::imageById($id);
if ($product) {
    if (ProductModel::delete($id)) removeProductImage($product['image_path']);
}
flash($product ? 'Listing removed by administrator.' : 'Listing not found.');
header('Location: /admin.php'); exit;
