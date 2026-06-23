<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Include PHPMailer for sending account credentials
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $personal_email = trim($_POST['personal_email']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $errors = [];
    
    if (empty($full_name)) $errors[] = "Full name is required";
    if (empty($username)) $errors[] = "Username is required";
    if (empty($personal_email)) $errors[] = "Personal email is required";
    if (empty($email)) $errors[] = "Company email is required";
    if (empty($password)) $errors[] = "Password is required";
    
    if (empty($errors)) {
        $check = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check_result = $check->get_result();
        
        if ($check_result->num_rows > 0) {
            $_SESSION['accounts_error'] = "Username or email already exists";
        } else {
            $stmt = $connection->prepare("INSERT INTO users (full_name, username, email, personal_email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $full_name, $username, $email, $personal_email, $password, $role);
            if ($stmt->execute()) {
                // --- Send credentials to personal email ---
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'regmirishab05@gmail.com';
                    $mail->Password   = 'yomo aibt oial jpet';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;
                    
                    // Recipients
                    $mail->setFrom('info@aisolutions.com', 'AI Solutions');
                    $mail->addAddress($personal_email, $full_name);
                    
                    // Email content
                    $login_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php";
                    $email_body = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background: #007bff; color: white; padding: 15px; text-align: center; }
                            .content { padding: 20px; border: 1px solid #ddd; background: #f9f9f9; }
                            .credentials { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 15px 0; }
                            .credentials p { margin: 8px 0; }
                            .footer { font-size: 12px; text-align: center; padding: 10px; color: #888; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h2>AI Solutions Account Created</h2>
                            </div>
                            <div class='content'>
                                <p>Dear <strong>" . htmlspecialchars($full_name) . "</strong>,</p>
                                <p>An account has been created for you on the AI Solutions Admin Panel.</p>
                                <div class='credentials'>
                                    <p><strong>Full Name:</strong> " . htmlspecialchars($full_name) . "</p>
                                    <p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>
                                    <p><strong>Login Email:</strong> " . htmlspecialchars($email) . "</p>
                                    <p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>
                                </div>
                                <p>You can log in using the link below:</p>
                                <p><a href='" . $login_url . "'>" . $login_url . "</a></p>
                                <p>For security reasons, please change your password after your first login.</p>
                                <br>
                                <p>Best regards,<br><strong>AI Solutions Team</strong></p>
                            </div>
                            <div class='footer'>
                                <p>© 2026 AI Solutions. All rights reserved.</p>
                                <p>This is an automated message, please do not reply directly.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";
                    
                    $mail->isHTML(true);
                    $mail->Subject = 'Your AI Solutions Account Credentials';
                    $mail->Body    = $email_body;
                    $mail->AltBody = "Dear $full_name,\n\nAn account has been created for you.\nFull Name: $full_name\nUsername: $username\nLogin Email: $email\nPassword: $password\n\nLogin URL: $login_url\n\nBest regards,\nAI Solutions Team";
                    
                    $mail->send();
                    $_SESSION['accounts_success'] = "User added successfully. Credentials sent to " . htmlspecialchars($personal_email);
                } catch (Exception $e) {
                    $_SESSION['accounts_success'] = "User added successfully, but failed to send credentials email: " . $mail->ErrorInfo;
                }
            } else {
                $_SESSION['accounts_error'] = "Failed to add user";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $_SESSION['accounts_error'] = implode(", ", $errors);
    }
    header("Location: accounts.php");
    exit();
}

// Handle Edit User - ✅ NOW SENDS EMAIL NOTIFICATION
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $password_changed = !empty($password);
    
    // First, get the user's current personal_email before update
    $user_query = $connection->prepare("SELECT personal_email FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    $personal_email = $user_data['personal_email'];
    $user_query->close();
    
    // Update the user
    if ($password_changed) {
        $stmt = $connection->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $full_name, $username, $email, $password, $role, $user_id);
    } else {
        $stmt = $connection->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $full_name, $username, $email, $role, $user_id);
    }
    
    if ($stmt->execute()) {
        // ✅ Send email notification about the update
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'regmirishab05@gmail.com';
            $mail->Password   = 'yomo aibt oial jpet';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            
            // Recipients
            $mail->setFrom('info@aisolutions.com', 'AI Solutions');
            $mail->addAddress($personal_email, $full_name);
            
            // Email content
            $login_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php";
            
            $password_message = $password_changed ? "<p><strong>New Password:</strong> " . htmlspecialchars($password) . "</p>" : "<p><em>Password was not changed.</em></p>";
            
            $email_body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #28a745; color: white; padding: 15px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; background: #f9f9f9; }
                    .credentials { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 15px 0; }
                    .credentials p { margin: 8px 0; }
                    .footer { font-size: 12px; text-align: center; padding: 10px; color: #888; }
                    .warning { color: #dc3545; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>🔐 Your Account Has Been Updated</h2>
                    </div>
                    <div class='content'>
                        <p>Dear <strong>" . htmlspecialchars($full_name) . "</strong>,</p>
                        <p>Your account details on the AI Solutions Admin Panel have been updated by an administrator.</p>
                        <div class='credentials'>
                            <p><strong>Full Name:</strong> " . htmlspecialchars($full_name) . "</p>
                            <p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>
                            <p><strong>Login Email:</strong> " . htmlspecialchars($email) . "</p>
                            <p><strong>Role:</strong> " . htmlspecialchars($role) . "</p>
                            " . $password_message . "
                        </div>
                        <p>You can log in using the link below:</p>
                        <p><a href='" . $login_url . "'>" . $login_url . "</a></p>
                        " . ($password_changed ? "<p class='warning'>⚠️ Your password has been changed. Please use the new password above to login.</p>" : "") . "
                        <p>If you did not request these changes, please contact the system administrator immediately.</p>
                        <br>
                        <p>Best regards,<br><strong>AI Solutions Team</strong></p>
                    </div>
                    <div class='footer'>
                        <p>© 2026 AI Solutions. All rights reserved.</p>
                        <p>This is an automated message, please do not reply directly.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->isHTML(true);
            $mail->Subject = 'Your AI Solutions Account Has Been Updated';
            $mail->Body    = $email_body;
            $mail->AltBody = "Dear $full_name,\n\nYour account details have been updated.\nFull Name: $full_name\nUsername: $username\nLogin Email: $email\nRole: $role\n" . ($password_changed ? "New Password: $password\n" : "Password was not changed.\n") . "\nLogin URL: $login_url\n\nBest regards,\nAI Solutions Team";
            
            $mail->send();
            $_SESSION['accounts_success'] = "User updated successfully. Notification email sent to " . htmlspecialchars($personal_email);
            
        } catch (Exception $e) {
            $_SESSION['accounts_success'] = "User updated successfully, but failed to send notification email: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['accounts_error'] = "Failed to update user";
    }
    $stmt->close();
    header("Location: accounts.php");
    exit();
}

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Get user's personal email before deleting
    $user_query = $connection->prepare("SELECT personal_email, full_name FROM users WHERE id = ?");
    $user_query->bind_param("i", $delete_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    $user_query->close();
    
    $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Send email notification about account deletion
        if ($user_data) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'regmirishab05@gmail.com';
                $mail->Password   = 'yomo aibt oial jpet';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                
                $mail->setFrom('info@aisolutions.com', 'AI Solutions');
                $mail->addAddress($user_data['personal_email'], $user_data['full_name']);
                
                $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
                        .content { padding: 20px; border: 1px solid #ddd; background: #f9f9f9; }
                        .footer { font-size: 12px; text-align: center; padding: 10px; color: #888; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>⚠️ Your Account Has Been Deleted</h2>
                        </div>
                        <div class='content'>
                            <p>Dear <strong>" . htmlspecialchars($user_data['full_name']) . "</strong>,</p>
                            <p>Your account on the AI Solutions Admin Panel has been deleted by an administrator.</p>
                            <p>If you believe this was a mistake, please contact the system administrator.</p>
                            <br>
                            <p>Best regards,<br><strong>AI Solutions Team</strong></p>
                        </div>
                        <div class='footer'>
                            <p>© 2026 AI Solutions. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $mail->isHTML(true);
                $mail->Subject = 'Your AI Solutions Account Has Been Deleted';
                $mail->Body    = $email_body;
                $mail->send();
            } catch (Exception $e) {
                // Silent fail for deletion email
            }
        }
        
        $_SESSION['accounts_success'] = "User deleted successfully";
        if ($delete_id == $_SESSION['user_id']) {
            session_destroy();
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['accounts_error'] = "Failed to delete user";
    }
    $stmt->close();
    header("Location: accounts.php");
    exit();
}

// Pagination for users table
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_query = $connection->query("SELECT COUNT(*) as total FROM users");
$total_rows = $total_query->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$users_result = $connection->query("SELECT id, full_name, username, email, personal_email, role, created_at, last_login FROM users ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$users = $users_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Management - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="page-container">
    <div class="top-bar">
        <a href="index.php" class="back-to-dashboard">← Back to Dashboard</a>
        <h1>Accounts Management</h1>
    </div>

    <main class="page-content">
        
        <?php if (isset($_SESSION['accounts_success'])): ?>
            <div class="alert-success-custom">
                <?php echo $_SESSION['accounts_success']; unset($_SESSION['accounts_success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['accounts_error'])): ?>
            <div class="alert-error-custom">
                <?php echo $_SESSION['accounts_error']; unset($_SESSION['accounts_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Add New User Card -->
        <div class="form-card">
            <h3>Add New User</h3>
            <form method="POST" class="accounts-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" placeholder="Enter full name" required>
                    </div>
                    <div class="form-group">
                        <label>Username <span class="required">*</span></label>
                        <input type="text" name="username" placeholder="Enter username" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Personal Email <span class="required">*</span></label>
                        <input type="email" name="personal_email" placeholder="Enter personal email" required>
                    </div>
                    <div class="form-group">
                        <label>Company Email <span class="required">*</span></label>
                        <input type="email" name="email" placeholder="Enter company email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role">
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="add_user" class="btn-primary">Add User</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="users-table-container">
            <h3>All Users</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Company Email</th>
                            <th>Personal Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = $offset + 1;
                        foreach ($users as $user): 
                        ?>
                        <tr>
                            <td class="sn-column"><?php echo $sn++; ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['personal_email']); ?></td>
                            <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td><?php echo $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                            <td class="action-icons">
                                <button class="edit-btn" onclick='openEditModal(<?php echo json_encode($user); ?>)'>✏️ Edit</button>
                                <a href="?delete_id=<?php echo $user['id']; ?>&page=<?php echo $page; ?>" class="delete-btn" onclick="return confirm('Delete this user? This action cannot be undone.')">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if ($total_rows == 0): ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No users found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="page-link prev">← Previous</a>
                <?php else: ?>
                    <span class="page-link disabled">← Previous</span>
                <?php endif; ?>
                
                <div class="page-numbers">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="page-num active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="page-num"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="page-link next">Next →</a>
                <?php else: ?>
                    <span class="page-link disabled">Next →</span>
                <?php endif; ?>
            </div>
            <div class="pagination-info">
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_rows); ?> of <?php echo $total_rows; ?> entries
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" class="edit-form">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="full_name" id="edit_full_name" required>
            </div>
            <div class="form-group">
                <label>Username <span class="required">*</span></label>
                <input type="text" name="username" id="edit_username" required>
            </div>
            <div class="form-group">
                <label>Company Email <span class="required">*</span></label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="viewer">Viewer</option>
                </select>
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" placeholder="Enter new password">
                <small>Only enter if you want to change the password</small>
            </div>
            <div class="modal-buttons">
                <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                <button type="submit" name="edit_user" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('edit_user_id').value = user.id;
        document.getElementById('edit_full_name').value = user.full_name;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('editModal').style.display = 'flex';
    }
    
    function closeEditModal() { 
        document.getElementById('editModal').style.display = 'none'; 
    }
    
    window.onclick = function(event) { 
        const modal = document.getElementById('editModal'); 
        if (event.target == modal) modal.style.display = 'none'; 
    }
</script>

</body>
</html>