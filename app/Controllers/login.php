<?php
// Connect to MySQL and load the session functions.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/Core/view.php';
require_once dirname(__DIR__) . '/Models/User.php';
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
        $account = UserModel::byEmail($email);
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
$pageTitle = 'Log In'; 


// Render the Bootstrap view after processing this request.
renderView('auth/login', get_defined_vars());
