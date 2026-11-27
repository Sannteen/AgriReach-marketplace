<?php
// Set the page title and check whether a user is signed in.
$pageTitle = $pageTitle ?? 'AgriReach Marketplace';
$activePage = $activePage ?? '';
$user = currentUser();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AgriReach connects Jamaican farmers and buyers.">
    <title><?= e($pageTitle) ?> | AgriReach</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<!-- Display the same responsive navigation bar on every page. -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm" aria-label="Main navigation">
    <div class="container">

        <a class="navbar-brand" href="/index.php"><img src="/assets/img/logo.png" alt="AgriReach" class="brand-logo"></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'marketplace' ? 'active' : '' ?>" href="/marketplace.php">Marketplace</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'about' ? 'active' : '' ?>" href="/about.php">About Us</a></li>
                <?php if ($user && $user['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="/admin.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <?php if ($user && $user['role'] === 'farmer'): ?>
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="/dashboard.php">Farmer Dashboard</a></li>
                <?php endif; ?>
            </ul>

            <div class="d-flex gap-2 nav-actions align-items-center">
                <?php if ($user): ?>
                    <span class="small text-muted">Hi, <?= e($user['full_name']) ?></span>
                    <form method="post" action="/logout.php" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <button class="btn btn-outline-success" type="submit">Log Out</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-outline-success" href="/login.php">Log In</a>
                    <a class="btn btn-success" href="/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>

<main>
    <?php if ($notice = flash()): ?>
        <div class="container mt-3">
            <div class="alert alert-success" role="status"><?= e($notice) ?></div>
        </div>
    <?php endif; ?>