<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$format = $_GET['format'] ?? 'csv';

// Get filter parameters (same as inquiries page)
$search = $_GET['search'] ?? '';
$country_filter = $_GET['country'] ?? '';

// Build query using PREPARED STATEMENTS (SECURE)
$conditions = [];
$params = [];
$types = "";

$query = "SELECT id, inquiry_number, name, email, phone, company, country, job_title, job_details, status, created_at FROM contact_inquiries WHERE 1=1";

if (!empty($search)) {
    $search_param = "%$search%";
    $conditions[] = "(name LIKE ? OR email LIKE ? OR company LIKE ?)";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($country_filter)) {
    $conditions[] = "country = ?";
    $params[] = $country_filter;
    $types .= "s";
}

if (!empty($conditions)) {
    $query .= " AND " . implode(" AND ", $conditions);
}

$query .= " ORDER BY created_at DESC";

// Execute with prepared statement
if (!empty($params)) {
    $stmt = $connection->prepare($query);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $inquiries = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $inquiries = [];
    }
} else {
    $result = $connection->query($query);
    $inquiries = $result->fetch_all(MYSQLI_ASSOC);
}

if ($format == 'csv') {
    // Export as CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="inquiries_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for special characters
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Headers
    fputcsv($output, ['ID', 'Inquiry No', 'Name', 'Email', 'Phone', 'Company', 'Country', 'Job Title', 'Job Details', 'Status', 'Date']);
    
    // Data
    foreach ($inquiries as $row) {
        fputcsv($output, [
            $row['id'],
            $row['inquiry_number'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['company'],
            $row['country'],
            $row['job_title'],
            $row['job_details'],
            $row['status'],
            date('Y-m-d H:i', strtotime($row['created_at']))
        ]);
    }
    
    fclose($output);
    
} elseif ($format == 'excel') {
    // Export as Excel (using HTML table method)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="inquiries_' . date('Y-m-d') . '.xls"');
    
    echo '<html>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
    echo '<body>';
    echo '<table border="1">';
    echo '<thead>';
    echo '<tr style="background-color: #007bff; color: white;">';
    echo '<th>ID</th>';
    echo '<th>Inquiry No</th>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Phone</th>';
    echo '<th>Company</th>';
    echo '<th>Country</th>';
    echo '<th>Job Title</th>';
    echo '<th>Job Details</th>';
    echo '<th>Status</th>';
    echo '<th>Date</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    foreach ($inquiries as $row) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['inquiry_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
        echo '<td>' . htmlspecialchars($row['company']) . '</td>';
        echo '<td>' . htmlspecialchars($row['country']) . '</td>';
        echo '<td>' . htmlspecialchars($row['job_title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['job_details']) . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '<td>' . date('Y-m-d H:i', strtotime($row['created_at'])) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</body>';
    echo '</html>';
}
?>