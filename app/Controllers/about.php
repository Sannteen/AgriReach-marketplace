<?php
// Display information about the AgriReach marketplace.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
$pageTitle = 'About Us';
$activePage = 'about';



// Render the Bootstrap view after processing this request.
renderView('pages/about', get_defined_vars());
