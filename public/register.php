<?php
// Load the database connection and session functions.
require __DIR__ . '/includes/data.php';
if (currentUser()) { header('Location: /index.php'); exit; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the security token before creating a new account.
    checkCsrf();
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $role = (string)($_POST['role'] ?? 'buyer');
    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) $errors[] = 'Enter a name between 2 and 100 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) $errors[] = 'Enter a valid email address.';
    if (strlen($phone) > 20) $errors[] = 'Phone number is too long.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if ($password !== (string)($_POST['confirm_password'] ?? '')) $errors[] = 'Passwords do not match.';
    if (!in_array($role, ['farmer', 'buyer'], true)) $errors[] = 'Choose a valid account type.';
    if (!$errors) {
        try {
            // Hash the password before saving the account to MySQL.
            $statement = db()->prepare('INSERT INTO users (full_name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)');
            $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $phone ?: null, $role]);
            flash('Account created successfully. Please log in.');
            header('Location: /login.php'); exit;
        } catch (PDOException $exception) {
            // Show a friendly error if the email address is already registered.
            if ($exception->getCode() === '23000') $errors[] = 'An account with this email already exists.';
            else { error_log('Registration error: ' . $exception->getMessage()); $errors[] = 'Could not create your account right now.'; }
        }
    }
}
$pageTitle = 'Create Account'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="container"><div class="auth-card auth-card-wide"><div class="text-center mb-4"><div class="auth-icon">🌿</div><h1 class="h2 fw-bold">Join AgriReach</h1><p class="text-muted">Create a buyer or farmer account.</p></div>
<?php if ($errors): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<!-- Validate the registration fields and save a new user account. -->
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><div class="row g-3">
<div class="col-md-6"><label class="form-label" for="full_name">Full name</label><input class="form-control" id="full_name" name="full_name" maxlength="100" value="<?= e($_POST['full_name'] ?? '') ?>" required></div>
<div class="col-md-6"><label class="form-label" for="phone">Phone number</label><input class="form-control" id="phone" name="phone" type="tel" maxlength="20" value="<?= e($_POST['phone'] ?? '') ?>"></div>
<div class="col-12"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" maxlength="150" value="<?= e($_POST['email'] ?? '') ?>" required></div>
<div class="col-md-6"><label class="form-label" for="password">Password</label><input class="form-control" id="password" name="password" type="password" minlength="8" required></div>
<div class="col-md-6"><label class="form-label" for="confirm_password">Confirm password</label><input class="form-control" id="confirm_password" name="confirm_password" type="password" minlength="8" required></div>
<div class="col-12"><label class="form-label" for="role">Account type</label><select class="form-select" id="role" name="role"><option value="buyer">Buyer — browse local produce</option><option value="farmer" <?= ($_POST['role'] ?? '') === 'farmer' ? 'selected' : '' ?>>Farmer — list and manage produce</option></select></div>
</div><button class="btn btn-success btn-lg w-100 mt-4" type="submit">Create Account</button></form><p class="text-center mt-4 mb-0">Already registered? <a href="/login.php">Log in</a></p></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
