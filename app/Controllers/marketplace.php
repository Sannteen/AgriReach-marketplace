<?php
// Load the database connection and read the marketplace filters.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
$pageTitle = 'Browse Marketplace'; $activePage = 'marketplace';
$search = trim((string)($_GET['search'] ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$categories = ['Vegetables', 'Fruits', 'Ground Provisions', 'Eggs'];
if (strlen($search) > 100) $search = substr($search, 0, 100);
if ($category !== '' && !in_array($category, $categories, true)) $category = '';

// Ask the Product model to safely search the database.
$products = ProductModel::search($search, $category);



// Render the Bootstrap view after processing this request.
renderView('products/marketplace', get_defined_vars());
