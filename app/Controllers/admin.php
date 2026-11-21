<?php
// Restrict the administration dashboard to existing admin accounts.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/Product.php';
require_once dirname(__DIR__) . '/Models/User.php';
$user = requireLogin();
if ($user['role'] !== 'admin') { http_response_code(403); exit('Administrator access required.'); }
// Count users and listings to provide an overview of the marketplace.
$usersCount = UserModel::countAll();
$adminCounts = ProductModel::adminCounts();
$productsCount = $adminCounts['products'];
$availableCount = $adminCounts['available'];
$products = ProductModel::adminRecent();
$pageTitle = 'Admin Dashboard';
$activePage = 'admin';



// Render the Bootstrap view after processing this request.
renderView('admin/admin', get_defined_vars());
