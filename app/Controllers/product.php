<?php
// Load one available product and the farmer's public contact information.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/image-upload.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = ProductModel::publicById($id ?: 0);
if (!$product) {
    // Show a proper 404 page using the shared Bootstrap layout.
    http_response_code(404);
    $pageTitle = 'Product Not Found';
    renderView('products/not-found', get_defined_vars());
    exit;
}
$pageTitle = $product['name']; $activePage = 'marketplace'; 


// Render the Bootstrap view after processing this request.
renderView('products/product', get_defined_vars());
