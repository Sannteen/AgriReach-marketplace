<?php
// Allow farmers to create produce listings with optional photographs.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/image-upload.php';
$user = requireFarmer();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the security token and all product form fields.
    checkCsrf();
    require dirname(__DIR__) . '/product-validation.php';
    $imagePath = null;
    if (!$errors) {
        try {
            // Save a photo only when the farmer selected one.
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $imagePath = saveProductImage($_FILES['product_image']);
            }
            // Insert the product and its image path into MySQL.
            ProductModel::create((int)$user['id'], $name, $description, $category, $price, $unit, $quantity, $delivery, $pickup, $status, $imagePath);
            flash('Your product has been added.');
            header('Location: /dashboard.php'); exit;
        } catch (Throwable $exception) {
            // Remove a newly uploaded file if saving the database record fails.
            removeProductImage($imagePath);
            error_log('Add product error: ' . $exception->getMessage());
            $errors[] = $exception instanceof RuntimeException ? $exception->getMessage() : 'Could not save the product. Please try again.';
        }
    }
}
$pageTitle = 'Add Product';
// Display the shared Bootstrap product form.
renderView('products/form', get_defined_vars());
