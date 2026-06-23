<?php
session_start();

include 'admin/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $job_details = trim($_POST['job_details'] ?? '');
    
    // Validation - ALL fields now required with field-specific errors
    $errors = [];
    
    // Name validation
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    
    // Email validation
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    // Phone validation
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required";
    }
    
    // Company validation
    if (empty($company)) {
        $errors['company'] = "Company name is required";
    }
    
    // Country validation
    if (empty($country)) {
        $errors['country'] = "Country is required";
    }
    
    // Job Title validation
    if (empty($job_title)) {
        $errors['job_title'] = "Job title is required";
    }
    
    // Job Details validation
    if (empty($job_details)) {
        $errors['job_details'] = "Job details are required";
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        
        // Generate inquiry number
        $year = date('Y');
        $month = date('m');
        $query = "SELECT inquiry_number FROM contact_inquiries WHERE inquiry_number LIKE 'INQ-$year-$month-%' ORDER BY id DESC LIMIT 1";
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            $last = $result->fetch_assoc()['inquiry_number'];
            $last_num = intval(substr($last, -4));
            $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $new_num = '0001';
        }
        
        $inquiry_number = "INQ-$year-$month-$new_num";
        
        // Insert into database
        $stmt = $connection->prepare("INSERT INTO contact_inquiries (inquiry_number, name, email, phone, company, country, job_title, job_details, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW())");
        $stmt->bind_param("ssssssss", $inquiry_number, $name, $email, $phone, $company, $country, $job_title, $job_details);
        
        if ($stmt->execute()) {
            $_SESSION['contact_success'] = "Thank you for contacting us! Your inquiry (Ref: $inquiry_number) has been submitted. We will get back to you within 24 hours.";
        } else {
            $_SESSION['contact_error'] = "Something went wrong. Please try again later.";
        }
        
        $stmt->close();
        
    } else {
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['contact_data'] = $_POST;
    }
    
    header("Location: contact.php");
    exit();
    
} else {
    header("Location: contact.php");
    exit();
}
?>