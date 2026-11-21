<?php
// Connect to MySQL and load the session functions.
require __DIR__ . '/includes/data.php';
if (currentUser()) { header('Location: /index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the form token before checking login details.
    checkCsrf();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email and password.';
    } else {
        // Find the user account by email using a prepared statement.
        $statement = db()->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        $account = $statement->fetch();
        if (!$account || !password_verify($password, $account['password_hash'])) {
            $error = 'Incorrect email or password.';
        } else {
            // Start a fresh session ID to prevent session fixation attacks.
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => (int)$account['id'], 'full_name' => $account['full_name'],
                'email' => $account['email'], 'role' => $account['role'],
            ];
            flash('Welcome back to AgriReach!');
            header('Location: ' . ($account['role'] === 'farmer' ? '/dashboard.php' : '/marketplace.php'));
            exit;
        }
    }
}
$pageTitle = 'Log In'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="container"><div class="auth-card"><div class="text-center mb-4"><div class="auth-icon">🌱</div><h1 class="h2 fw-bold">Welcome back</h1><p class="text-muted">Log in to your AgriReach account.</p></div>
<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><?php endif; ?>
<!-- Send the email and password to PHP for verification. -->
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><div class="mb-3"><label for="email" class="form-label">Email address</label><input id="email" class="form-control" type="email" name="email" autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>" required></div><div class="mb-4"><label for="password" class="form-label">Password</label><input id="password" class="form-control" type="password" name="password" autocomplete="current-password" required></div><button class="btn btn-success w-100 btn-lg" type="submit">Log In</button></form><p class="text-center mt-4 mb-0">New to AgriReach? <a href="/register.php">Create an account</a></p></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
