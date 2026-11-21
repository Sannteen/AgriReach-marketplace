<?php
// Load the database connection and session functions.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/User.php';
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
            UserModel::create($name, $email, password_hash($password, PASSWORD_DEFAULT), $phone ?: null, $role);
            flash('Account created successfully. Please log in.');
            header('Location: /login.php'); exit;
        } catch (PDOException $exception) {
            // Show a friendly error if the email address is already registered.
            if ($exception->getCode() === '23000') $errors[] = 'An account with this email already exists.';
            else { error_log('Registration error: ' . $exception->getMessage()); $errors[] = 'Could not create your account right now.'; }
        }
    }
}
$pageTitle = 'Create Account'; 


// Render the Bootstrap view after processing this request.
renderView('auth/register', get_defined_vars());
