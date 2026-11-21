<?php // Render the register page using the existing Bootstrap layout. ?>
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
