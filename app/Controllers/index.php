<?php
// Load the newest available produce listings from MySQL.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
$products = ProductModel::featured();
$pageTitle = 'Fresh Local Produce';
$activePage = 'home';



// Render the Bootstrap view after processing this request.
renderView('pages/index', get_defined_vars());
