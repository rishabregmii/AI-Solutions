<?php
session_start();

error_reporting(0);
ini_set('display_errors', 0);

include 'database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Get input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Combined validation array
$errors = [];

// Validation
if (empty($username)) {
    $errors[] = "Please fill in all fields";
}

if (empty($password)) {
    $errors[] = "Please fill in all fields";
}

// reCAPTCHA Verification
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptcha_response)) {
    $errors[] = "Please verify that you are not a robot";
} else {
    // Verify reCAPTCHA
    $recaptcha_secret = "6LftKo0sAAAAACBDGzwNT2u3HQShXil3fCTP_q0C";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $recaptcha_result = curl_exec($ch);
    curl_close($ch);

    $recaptcha_data = json_decode($recaptcha_result, true);

    if (!$recaptcha_data['success']) {
        $errors[] = "reCAPTCHA verification failed. Please try again.";
    }
}

// If there are validation errors, redirect back
if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    // Store username to repopulate the field
    if (!empty($username)) {
        $_SESSION['login_username'] = $username;
    }
    header("Location: login.php");
    exit();
}

// Check database connection
if (!$connection) {
    $_SESSION['login_errors'] = ["Something went wrong. Please try again."];
    header("Location: login.php");
    exit();
}

// Get user from database (search by both username AND email)
$stmt = $connection->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Generic error message - don't reveal if user exists or not
    $_SESSION['login_errors'] = ["Invalid username or password. Please try again."];
    if (!empty($username)) {
        $_SESSION['login_username'] = $username;
    }
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// Verify password (PLAIN TEXT comparison - NO HASHING)
if ($password !== $user['password']) {
    // Generic error message - don't reveal if username exists but password is wrong
    $_SESSION['login_errors'] = ["Invalid username or password. Please try again."];
    if (!empty($username)) {
        $_SESSION['login_username'] = $username;
    }
    header("Location: login.php");
    exit();
}

// Login successful
session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['logged_in'] = true;

// Clear any previous errors
unset($_SESSION['login_errors']);
unset($_SESSION['login_username']);

// Update last login time
$update_stmt = $connection->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
$update_stmt->bind_param("i", $user['id']);
$update_stmt->execute();

// Redirect to admin dashboard
header("Location: index.php");
exit();
?>