<?php
// Let a farmer edit only their own produce listing.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/image-upload.php';
$user = requireFarmer();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = ProductModel::ownedById($id ?: 0, (int)$user['id']);
if (!$product) { http_response_code(404); exit('Product not found.'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    require dirname(__DIR__) . '/product-validation.php';
    if (!$errors) {
        $newImage = null;
        try {
            // Keep the existing photo unless the farmer uploads a replacement.
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $newImage = saveProductImage($_FILES['product_image']);
            }
            $imagePath = $newImage ?? $product['image_path'];
            // Update only the listing owned by this farmer.
            ProductModel::update((int)$id, (int)$user['id'], $name, $description, $category, $price, $unit, $quantity, $delivery, $pickup, $status, $imagePath);
            // Remove the previous photo only after MySQL has saved the new one.
            if ($newImage) removeProductImage($product['image_path']);
            flash('Your product has been updated.');
            header('Location: /dashboard.php'); exit;
        } catch (Throwable $exception) {
            removeProductImage($newImage);
            error_log('Edit product error: ' . $exception->getMessage());
            $errors[] = $exception instanceof RuntimeException ? $exception->getMessage() : 'Could not update the product. Please try again.';
        }
    }
}
$pageTitle = 'Edit Product';
// Display the shared Bootstrap product form.
renderView('products/form', get_defined_vars());
