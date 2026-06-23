<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$id = $_GET['id'] ?? 0;

// ✅ Get the inquiry data
$stmt = $connection->prepare("SELECT * FROM contact_inquiries WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$inquiry = $stmt->get_result()->fetch_assoc();

if (!$inquiry) {
    header("Location: inquiries.php");
    exit();
}

// Handle manual actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    // ✅ Manual "Mark as Read" button
    if ($action == 'mark_read') {
        $connection->query("UPDATE contact_inquiries SET status = 'read', is_read = 1 WHERE id = $id");
        header("Location: view_inquiry.php?id=" . $id);
        exit();
        
    } elseif ($action == 'archive') {
        $connection->query("UPDATE contact_inquiries SET status = 'archived' WHERE id = $id");
        header("Location: inquiries.php");
        exit();
        
    } elseif ($action == 'delete') {
        $connection->query("DELETE FROM contact_inquiries WHERE id = $id");
        $_SESSION['inquiry_success'] = "Inquiry deleted successfully";
        header("Location: inquiries.php");
        exit();
    }
}

// ✅ Set session flag to indicate this inquiry was viewed
// This will trigger auto-mark on the inquiries page when user goes back
$_SESSION['viewed_inquiry_' . $id] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Details - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="page-container">
    <!-- Top Bar with Back Button -->
    <div class="top-bar">
        <a href="inquiries.php" class="back-to-dashboard">← Back to Inquiries</a>
        <h1>Inquiry Details</h1>
    </div>

    <main class="page-content">
        
        <!-- Reply Success/Error Messages -->
        <?php if (isset($_SESSION['reply_success'])): ?>
            <div class="alert-success-custom">
                <?php 
                    echo $_SESSION['reply_success'];
                    unset($_SESSION['reply_success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['reply_error'])): ?>
            <div class="alert-error-custom">
                <?php 
                    echo $_SESSION['reply_error'];
                    unset($_SESSION['reply_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="inquiry-detail-card">
            
            <!-- Header with Inquiry Number and Status -->
            <div class="inquiry-header">
                <div class="inquiry-number">
                    <h2><?php echo htmlspecialchars($inquiry['inquiry_number']); ?></h2>
                </div>
                <div class="header-actions">
                    <span class="status-badge <?php echo $inquiry['status']; ?>">
                        <?php echo ucfirst($inquiry['status']); ?>
                    </span>
                </div>
            </div>

            <!-- Action Buttons Row -->
            <div class="action-buttons-row">
                <!-- ✅ "Mark as Read" button - ONLY shows when status is 'new' -->
                <?php if ($inquiry['status'] == 'new'): ?>
                    <a href="?id=<?php echo $id; ?>&action=mark_read" class="action-btn-mark">Mark as Read</a>
                <?php endif; ?>
                
                <?php if ($inquiry['status'] != 'archived'): ?>
                    <a href="?id=<?php echo $id; ?>&action=archive" class="action-btn-archive">Archive</a>
                <?php endif; ?>
                
                <a href="?id=<?php echo $id; ?>&action=delete" class="action-btn-delete" onclick="return confirm('Delete this inquiry?')">Delete</a>
            </div>

            <!-- Details Side by Side Layout -->
            <div class="details-side-by-side">
                <div class="details-row">
                    <div class="details-left">
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($inquiry['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($inquiry['email']); ?></p>
                        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($inquiry['phone']) ?: '—'; ?></p>
                        <p><strong>Company Name:</strong> <?php echo htmlspecialchars($inquiry['company']) ?: '—'; ?></p>
                    </div>
                    <div class="details-right">
                        <p><strong>Country:</strong> <?php echo htmlspecialchars($inquiry['country']) ?: '—'; ?></p>
                        <p><strong>Job Title:</strong> <?php echo htmlspecialchars($inquiry['job_title']) ?: '—'; ?></p>
                    </div>
                </div>
                <div class="details-full-width">
                    <p><strong>Job Details:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($inquiry['job_details'])) ?: '—'; ?></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="inquiry-footer">
                <div class="footer-info">
                    <span class="info-label">Submitted:</span>
                    <span><?php echo date('F d, Y \a\t h:i A', strtotime($inquiry['created_at'])); ?></span>
                </div>
            </div>

            <!-- Reply Section -->
            <div class="reply-section">
                <h3>Reply via Email</h3>
                <form method="POST" action="send_reply.php" class="reply-form-simple">
                    <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                    <div class="form-group">
                        <label>Subject:</label>
                        <input type="text" name="reply_subject" value="Re: <?php echo htmlspecialchars($inquiry['inquiry_number']); ?>" class="reply-subject" required>
                    </div>
                    <div class="form-group">
                        <label>Message:</label>
                        <textarea name="reply_message" rows="5" placeholder="Type your reply here..." required></textarea>
                    </div>
                    <button type="submit" class="btn-send">Send Reply</button>
                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html>