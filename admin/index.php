<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$user_id = $_SESSION['user_id'];
$stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-container">
    <!-- Sidebar (Only on Dashboard) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" class="logo-img">
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="analytics.php" class="nav-item">
                <span class="nav-icon">📈</span>
                <span>Analytics</span>
            </a>
            <a href="inquiries.php" class="nav-item">
                <span class="nav-icon">📧</span>
                <span>Inquiries</span>
            </a>
            <a href="content.php" class="nav-item">
                <span class="nav-icon">📝</span>
                <span>Content</span>
            </a>
            <a href="accounts.php" class="nav-item">
                <span class="nav-icon">👥</span>
                <span>Accounts</span>
            </a>
            <a href="logout.php" class="nav-item logout">
                <span class="nav-icon">🚪</span>
                <span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="welcome-box">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Analytics Overview</h3>
            </div>
            <div class="card-body">
                <p>View overall analytics of the company</p>
                <a href="analytics.php" class="card-btn">Go →</a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Inquiry Management</h3>
            </div>
            <div class="card-body">
                <p>Manage incoming inquiries</p>
                <a href="inquiries.php" class="card-btn">Go →</a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Content Management</h3>
            </div>
            <div class="card-body">
                <p>Manage solutions, case studies, articles, testimonials, gallery & industries</p>
                <a href="content.php" class="card-btn">Go →</a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Manage Accounts</h3>
            </div>
            <div class="card-body">
                <p>Manage accounts of users of company</p>
                <a href="accounts.php" class="card-btn">Go →</a>
            </div>
        </div>

        <div class="dashboard-card logout-card">
            <div class="card-header">
                <h3>Logout</h3>
            </div>
            <div class="card-body">
                <p>Securely logout from your account</p>
                <a href="logout.php" class="card-btn logout-btn">Logout →</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>