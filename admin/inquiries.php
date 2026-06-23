<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// ✅ AUTO-MARK AS READ: When user goes back to inquiries page
// Check if any inquiry was viewed and still marked as "new"
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'viewed_inquiry_') === 0 && $value === true) {
        $inquiry_id = str_replace('viewed_inquiry_', '', $key);
        
        // Update status to 'read' only if it's still 'new'
        $update_stmt = $connection->prepare("UPDATE contact_inquiries SET is_read = 1, status = 'read' WHERE id = ? AND status = 'new'");
        $update_stmt->bind_param("i", $inquiry_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Remove the session flag so it doesn't update again
        unset($_SESSION['viewed_inquiry_' . $inquiry_id]);
    }
}

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_action'])) {
    $ids = $_POST['ids'] ?? [];
    $action = $_POST['bulk_action'];
    
    if (!empty($ids)) {
        $ids_string = implode(',', array_map('intval', $ids));
        if ($action == 'delete') {
            $connection->query("DELETE FROM contact_inquiries WHERE id IN ($ids_string)");
            $_SESSION['inquiry_success'] = "Selected inquiries deleted successfully";
        } elseif ($action == 'archive') {
            $connection->query("UPDATE contact_inquiries SET status = 'archived' WHERE id IN ($ids_string)");
            $_SESSION['inquiry_success'] = "Selected inquiries archived successfully";
        } elseif ($action == 'mark_read') {
            $connection->query("UPDATE contact_inquiries SET status = 'read', is_read = 1 WHERE id IN ($ids_string)");
            $_SESSION['inquiry_success'] = "Selected inquiries marked as read";
        }
        header("Location: inquiries.php");
        exit();
    }
}

// Handle single delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $connection->prepare("DELETE FROM contact_inquiries WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['inquiry_success'] = "Inquiry deleted successfully";
    } else {
        $_SESSION['inquiry_error'] = "Failed to delete inquiry";
    }
    $stmt->close();
    header("Location: inquiries.php");
    exit();
}

// Get filter and pagination parameters
$search = $_GET['search'] ?? '';
$country_filter = $_GET['country'] ?? '';
$sort_by = $_GET['sort_by'] ?? '';
$sort_order = $_GET['sort_order'] ?? 'DESC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// If no sort_by is set, default to created_at DESC for data display
$display_sort_by = $sort_by ? $sort_by : 'created_at';
$display_sort_order = $sort_by ? $sort_order : 'DESC';

// Build WHERE clause
$where_clause = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $where_clause .= " AND (name LIKE ? OR email LIKE ? OR company LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($country_filter)) {
    $where_clause .= " AND country = ?";
    $params[] = $country_filter;
    $types .= "s";
}

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM contact_inquiries $where_clause";
$count_stmt = $connection->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Build main query with sorting
$valid_sort_columns = ['name', 'email', 'country', 'created_at'];
if (!in_array($display_sort_by, $valid_sort_columns)) {
    $display_sort_by = 'created_at';
}
$display_sort_order = strtoupper($display_sort_order) === 'ASC' ? 'ASC' : 'DESC';

$sql = "SELECT * FROM contact_inquiries $where_clause ORDER BY $display_sort_by $display_sort_order LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $connection->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$inquiries = $result->fetch_all(MYSQLI_ASSOC);

$countries_list = [
    "Afghanistan", "Albania", "Algeria", "Argentina", "Australia", "Austria",
    "Bangladesh", "Belgium", "Brazil", "Canada", "China", "Denmark", "Egypt",
    "Finland", "France", "Germany", "Greece", "India", "Indonesia", "Iran",
    "Ireland", "Israel", "Italy", "Japan", "Malaysia", "Mexico", "Nepal",
    "Netherlands", "New Zealand", "Nigeria", "Norway", "Pakistan", "Philippines",
    "Poland", "Portugal", "Russia", "Saudi Arabia", "Singapore", "South Africa",
    "South Korea", "Spain", "Sri Lanka", "Sweden", "Switzerland", "Thailand",
    "Turkey", "United Arab Emirates", "United Kingdom", "United States", "Vietnam"
];

// Sort URL function for headers
function sort_url($column, $current_sort, $current_order) {
    $new_order = ($current_sort == $column && $current_order == 'ASC') ? 'DESC' : 'ASC';
    $params = $_GET;
    $params['sort_by'] = $column;
    $params['sort_order'] = $new_order;
    $params['page'] = 1;
    return '?' . http_build_query($params);
}

// Sort icon function
function sort_icon($column, $current_sort, $current_order) {
    if ($current_sort != $column) return '↕️';
    return $current_order == 'ASC' ? '↑' : '↓';
}

// Reset URL function
function reset_url() {
    return '?' . http_build_query(['page' => 1]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Management - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="page-container">
    <!-- Back Button -->
    <div class="top-bar">
        <a href="index.php" class="back-to-dashboard">← Back to Dashboard</a>
        <h1>Inquiry Management</h1>
    </div>

    <main class="page-content">
        
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['inquiry_success'])): ?>
            <div class="alert-success-custom">
                <?php 
                    echo $_SESSION['inquiry_success'];
                    unset($_SESSION['inquiry_success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['inquiry_error'])): ?>
            <div class="alert-error-custom">
                <?php 
                    echo $_SESSION['inquiry_error'];
                    unset($_SESSION['inquiry_error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Export and Search Bar -->
        <div class="action-bar">
            <div class="export-buttons">
                <a href="export_inquiries.php?format=csv" class="btn-secondary">Export CSV</a>
                <a href="export_inquiries.php?format=excel" class="btn-secondary">Export Excel</a>
            </div>
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by Name, Email, Company" value="<?php echo htmlspecialchars($search); ?>">
                <select name="country">
                    <option value="">All Countries</option>
                    <?php foreach ($countries_list as $country): ?>
                        <option value="<?php echo $country; ?>" <?php echo $country_filter == $country ? 'selected' : ''; ?>>
                            <?php echo $country; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-primary">Filter</button>
                <a href="<?php echo reset_url(); ?>" class="btn-reset">Reset</a>
            </form>
        </div>

        <!-- Inquiries Table -->
        <form method="POST" id="bulkForm">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th><a href="<?php echo sort_url('name', $sort_by, $sort_order); ?>" class="sort-link">Name <?php echo sort_icon('name', $sort_by, $sort_order); ?></a></th>
                            <th><a href="<?php echo sort_url('email', $sort_by, $sort_order); ?>" class="sort-link">Email <?php echo sort_icon('email', $sort_by, $sort_order); ?></a></th>
                            <th>Company</th>
                            <th><a href="<?php echo sort_url('country', $sort_by, $sort_order); ?>" class="sort-link">Country <?php echo sort_icon('country', $sort_by, $sort_order); ?></a></th>
                            <th>Job Title</th>
                            <th><a href="<?php echo sort_url('created_at', $sort_by, $sort_order); ?>" class="sort-link">Date <?php echo sort_icon('created_at', $sort_by, $sort_order); ?></a></th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $inquiry): ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?php echo $inquiry['id']; ?>"></td>
                            <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['company']) ?: '—'; ?></td>
                            <td><?php echo htmlspecialchars($inquiry['country']) ?: '—'; ?></td>
                            <td><?php echo htmlspecialchars($inquiry['job_title']) ?: '—'; ?></td>
                            <td><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></td>
                            <td><span class="status-badge <?php echo $inquiry['status']; ?>"><?php echo ucfirst($inquiry['status']); ?></span></td>
                            <td class="action-buttons-cell">
                                <a href="view_inquiry.php?id=<?php echo $inquiry['id']; ?>" class="btn-view">View</a>
                                <a href="?delete_id=<?php echo $inquiry['id']; ?>" class="btn-delete-row" onclick="return confirm('Delete this inquiry?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($inquiries)): ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No inquiries found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="bulk-actions">
                <select name="bulk_action">
                    <option value="">Bulk Actions</option>
                    <option value="mark_read">Mark as Read</option>
                    <option value="archive">Archive</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn-secondary">Apply</button>
            </div>
        </form>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="page-link prev">← Previous</a>
                <?php else: ?>
                    <span class="page-link disabled">← Previous</span>
                <?php endif; ?>
                
                <div class="page-numbers">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="page-num active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="page-num"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="page-link next">Next →</a>
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

<script>
    document.getElementById('selectAll')?.addEventListener('click', function(e) {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
</body>
</html>