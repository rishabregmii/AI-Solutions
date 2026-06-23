<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inquiry_id = $_POST['inquiry_id'];
    $reply_subject = $_POST['reply_subject'];
    $reply_message = $_POST['reply_message'];
    
    // Get inquiry details
    $stmt = $connection->prepare("SELECT * FROM contact_inquiries WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);
    $stmt->execute();
    $inquiry = $stmt->get_result()->fetch_assoc();
    
    if ($inquiry) {
        $to_email = $inquiry['email'];
        $to_name = $inquiry['name'];
        
        // Email body
        $email_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #007bff; color: white; padding: 15px; text-align: center; }
                .content { padding: 20px; border: 1px solid #ddd; background: #f9f9f9; }
                .footer { font-size: 12px; text-align: center; padding: 10px; color: #888; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>AI Solutions</h2>
                </div>
                <div class='content'>
                    <p>Dear <strong>" . htmlspecialchars($to_name) . "</strong>,</p>
                    <p>Thank you for contacting AI Solutions. Here is our response to your inquiry:</p>
                    <hr>
                    <p>" . nl2br(htmlspecialchars($reply_message)) . "</p>
                    <hr>
                    <p>Reference: <strong>" . htmlspecialchars($inquiry['inquiry_number']) . "</strong></p>
                    <br>
                    <p>Best regards,<br><strong>AI Solutions Team</strong></p>
                </div>
                <div class='footer'>
                    <p>© 2026 AI Solutions. All rights reserved.</p>
                    <p>This is an automated response, please do not reply directly to this email.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Create PHPMailer instance
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';        // SMTP server (change for your email)
            $mail->SMTPAuth   = true;
            $mail->Username   = 'regmirishab05@gmail.com';  // Your email (change this)
            $mail->Password   = 'yomo aibt oial jpet';     // Your app password (change this)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            
            // Recipients
            $mail->setFrom('info@aisolutions.com', 'AI Solutions');
            $mail->addAddress($to_email, $to_name);
            $mail->addReplyTo('info@aisolutions.com', 'AI Solutions');
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $reply_subject;
            $mail->Body    = $email_body;
            $mail->AltBody = strip_tags($reply_message);
            
            $mail->send();
            $_SESSION['reply_success'] = "Reply sent successfully to " . htmlspecialchars($to_email);
            
            // Update inquiry status
            $update_stmt = $connection->prepare("UPDATE contact_inquiries SET status = 'read', is_read = 1 WHERE id = ?");
            $update_stmt->bind_param("i", $inquiry_id);
            $update_stmt->execute();
            $update_stmt->close();
            
        } catch (Exception $e) {
            $_SESSION['reply_error'] = "Failed to send email: " . $mail->ErrorInfo;
        }
        
        $stmt->close();
    }
    
    header("Location: view_inquiry.php?id=" . $inquiry_id);
    exit();
}
?>