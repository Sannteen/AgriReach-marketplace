<?php
// Only a logged-in farmer can view this dashboard.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
$user = requireFarmer();
// Count this farmer's products to show real dashboard statistics.
$counts = ProductModel::farmerStats((int)$user['id']);
$products = ProductModel::farmerProducts((int)$user['id']);
$pageTitle = 'Farmer Dashboard'; $activePage = 'dashboard'; 


// Render the Bootstrap view after processing this request.
renderView('products/dashboard', get_defined_vars());
