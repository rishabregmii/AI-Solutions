<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AI Solutions</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        
        <div class="login-header">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" class="logo-img">
            </div>
            <p>Admin Panel Login</p>
        </div>

        <!-- Display Combined Errors -->
        <?php if (isset($_SESSION['login_errors']) && !empty($_SESSION['login_errors'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['login_errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['login_errors']); ?>
        <?php endif; ?>

        <form action="login_action.php" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="username">Email or Username</label>
                <div class="input-icon">
                    <input type="text" id="username" name="username" 
                           placeholder="Enter your email or username" 
                           value="<?php echo isset($_SESSION['login_username']) ? htmlspecialchars($_SESSION['login_username']) : ''; ?>" 
                           required autocomplete="off">
                    <?php unset($_SESSION['login_username']); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <!-- reCAPTCHA -->
            <div class="form-group captcha-group">
                <div class="g-recaptcha" data-sitekey="6LftKo0sAAAAAMwUMY8C44Xkquhd_x5RYeuRK4Ce"></div>
            </div>

            <button type="submit" class="login-btn">Login</button>

            <div class="login-footer">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>

        </form>

    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>