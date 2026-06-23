<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Determine which content type to manage
$content_type = $_GET['type'] ?? 'solution';

// Pagination variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // 10 records per page
$offset = ($page - 1) * $limit;

// Get total count for pagination
$total_query = $connection->query("SELECT COUNT(*) as total FROM content_items WHERE content_type = '$content_type'");
$total_rows = $total_query->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Get data based on content type with pagination
$items = $connection->query("SELECT * FROM content_items WHERE content_type = '$content_type' ORDER BY id ASC LIMIT $limit OFFSET $offset");

// Handle file upload
function uploadImage($file, $existing_image = '') {
    if ($file['error'] == UPLOAD_ERR_NO_FILE) {
        return $existing_image;
    }
    
    $target_dir = "../images/";
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_types)) {
        return $existing_image;
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return 'images/' . $new_filename;
    }
    
    return $existing_image;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $image = isset($_FILES['image']) ? uploadImage($_FILES['image'], '') : '';
    
    // For events, include event_time
    if ($content_type == 'event') {
        $stmt = $connection->prepare("INSERT INTO content_items (content_type, title, description, image, category, tag, rating, publish_date, event_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssiss", $content_type, $_POST['title'], $_POST['description'], $image, $_POST['category'], $_POST['tag'], $_POST['rating'], $_POST['publish_date'], $_POST['event_time']);
    } else {
        $stmt = $connection->prepare("INSERT INTO content_items (content_type, title, description, image, category, tag, rating, publish_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $content_type, $_POST['title'], $_POST['description'], $image, $_POST['category'], $_POST['tag'], $_POST['rating'], $_POST['publish_date']);
    }
    
    if ($stmt->execute()) {
        $_SESSION['content_success'] = "Item added successfully";
    } else {
        $_SESSION['content_error'] = "Failed to add item";
    }
    $stmt->close();
    
    header("Location: content.php?type=" . $content_type . "&page=" . $page);
    exit();
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $image = isset($_FILES['image']) ? uploadImage($_FILES['image'], $_POST['existing_image']) : $_POST['existing_image'];
    
    // For events, include event_time
    if ($content_type == 'event') {
        $stmt = $connection->prepare("UPDATE content_items SET title = ?, description = ?, image = ?, category = ?, tag = ?, rating = ?, publish_date = ?, event_time = ? WHERE id = ?");
        $stmt->bind_param("ssssssssi", $_POST['title'], $_POST['description'], $image, $_POST['category'], $_POST['tag'], $_POST['rating'], $_POST['publish_date'], $_POST['event_time'], $id);
    } else {
        $stmt = $connection->prepare("UPDATE content_items SET title = ?, description = ?, image = ?, category = ?, tag = ?, rating = ?, publish_date = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $_POST['title'], $_POST['description'], $image, $_POST['category'], $_POST['tag'], $_POST['rating'], $_POST['publish_date'], $id);
    }
    
    if ($stmt->execute()) {
        $_SESSION['content_success'] = "Item updated successfully";
    } else {
        $_SESSION['content_error'] = "Failed to update item";
    }
    $stmt->close();
    
    header("Location: content.php?type=" . $content_type . "&page=" . $page);
    exit();
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $img_result = $connection->query("SELECT image FROM content_items WHERE id = $delete_id");
    if ($img_result && $img_result->num_rows > 0) {
        $img_row = $img_result->fetch_assoc();
        if ($img_row['image'] && file_exists("../" . $img_row['image'])) {
            unlink("../" . $img_row['image']);
        }
    }
    
    $stmt = $connection->prepare("DELETE FROM content_items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    $_SESSION['content_success'] = "Item deleted successfully";
    header("Location: content.php?type=" . $content_type . "&page=" . $page);
    exit();
}

// Define columns based on content type
if ($content_type == 'solution') {
    $columns = ['SN', 'Title', 'Description', 'Image'];
} elseif ($content_type == 'case_study') {
    $columns = ['SN', 'Title', 'Category', 'Description', 'Image'];
} elseif ($content_type == 'article') {
    $columns = ['SN', 'Title', 'Category', 'Description', 'Image', 'Publish Date'];
} elseif ($content_type == 'testimonial') {
    $columns = ['SN', 'Name', 'Image', 'Position/Company', 'Content', 'Rating', 'Category'];
} elseif ($content_type == 'gallery') {
    $columns = ['SN', 'Title', 'Category', 'Description', 'Image'];
} elseif ($content_type == 'training') {
    $columns = ['SN', 'Title', 'Category', 'Duration', 'Description', 'Image', 'Publish Date'];
} elseif ($content_type == 'event') {
    $columns = ['SN', 'Title', 'Category', 'Format', 'Time', 'Description', 'Image', 'Event Date'];
} else {
    $columns = ['SN', 'Title', 'Description', 'Image'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/content.css">
</head>
<body>

<div class="page-container">
    <div class="top-bar">
        <a href="index.php" class="back-to-dashboard">← Back to Dashboard</a>
        <h1>Content Management</h1>
    </div>

    <main class="page-content">
        
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['content_success'])): ?>
            <div class="alert-success-custom">
                <?php 
                    echo $_SESSION['content_success'];
                    unset($_SESSION['content_success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['content_error'])): ?>
            <div class="alert-error-custom">
                <?php 
                    echo $_SESSION['content_error'];
                    unset($_SESSION['content_error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Content Type Navigation -->
        <div class="content-nav">
            <a href="?type=solution&page=1" class="content-nav-item <?php echo $content_type == 'solution' ? 'active' : ''; ?>">Solutions</a>
            <a href="?type=case_study&page=1" class="content-nav-item <?php echo $content_type == 'case_study' ? 'active' : ''; ?>">Case Studies</a>
            <a href="?type=article&page=1" class="content-nav-item <?php echo $content_type == 'article' ? 'active' : ''; ?>">Articles</a>
            <a href="?type=testimonial&page=1" class="content-nav-item <?php echo $content_type == 'testimonial' ? 'active' : ''; ?>">Testimonials</a>
            <a href="?type=gallery&page=1" class="content-nav-item <?php echo $content_type == 'gallery' ? 'active' : ''; ?>">Gallery</a>
            <a href="?type=training&page=1" class="content-nav-item <?php echo $content_type == 'training' ? 'active' : ''; ?>">Training</a>
            <a href="?type=event&page=1" class="content-nav-item <?php echo $content_type == 'event' ? 'active' : ''; ?>">Events</a>
        </div>

        <!-- Add New Item Form -->
        <div class="form-card">
            <h3>Add New <?php echo ucfirst(str_replace('_', ' ', $content_type)); ?></h3>
            <form method="POST" enctype="multipart/form-data" class="content-form">
                
                <?php if ($content_type == 'solution'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., Featured, Enterprise, Automation">
                        </div>
                        <div class="form-group">
                            <label>Tag</label>
                            <input type="text" name="tag" placeholder="e.g., 24/7 Support, DEX, Fast Track">
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'case_study'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., Healthcare, Finance">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'article'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., AI Trends, Chatbots">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Publish Date</label>
                            <input type="date" name="publish_date">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'testimonial'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="image" accept="image/*">
                            <small>Upload person's photo (optional)</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Position / Company</label>
                            <input type="text" name="tag" placeholder="e.g., CEO, Company Name">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., Healthcare, Finance">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" value="5">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Testimonial Content <span class="required">*</span></label>
                            <textarea name="description" rows="4" required></textarea>
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'gallery'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., Events, Promotions">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description</label>
                            <textarea name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Image <span class="required">*</span></label>
                            <input type="file" name="image" accept="image/*" required>
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'training'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category (Level)</label>
                            <input type="text" name="category" placeholder="e.g., Beginner, Intermediate, Advanced">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" name="tag" placeholder="e.g., 2 Days, 3 Days">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Publish Date</label>
                            <input type="date" name="publish_date">
                        </div>
                    </div>
                    
                <?php elseif ($content_type == 'event'): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category (Type)</label>
                            <input type="text" name="category" placeholder="e.g., Conference, Webinar, Workshop, Meetup">
                        </div>
                        <div class="form-group">
                            <label>Format</label>
                            <input type="text" name="tag" placeholder="e.g., In-person, Online, Hybrid">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Event Time <span class="required">*</span></label>
                            <input type="text" name="event_time" placeholder="e.g., 10:00 AM " required>
                        </div>
                        <div class="form-group">
                            <label>Event Date</label>
                            <input type="date" name="publish_date">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                    </div>
                <?php endif; ?>
                
                <button type="submit" name="add_item" class="btn-primary">Add <?php echo ucfirst(str_replace('_', ' ', $content_type)); ?></button>
            </form>
        </div>

        <!-- Items Table -->
        <div class="users-table-container">
            <h3>All <?php echo ucfirst(str_replace('_', ' ', $content_type)); ?></h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <th><?php echo $col; ?></th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = $offset + 1;
                        while ($row = $items->fetch_assoc()): 
                        ?>
                        <tr>
                            <td class="sn-column"><?php echo $sn++; ?></td>
                            <?php if ($content_type == 'solution'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 80)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                            <?php elseif ($content_type == 'case_study'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                            <?php elseif ($content_type == 'article'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                                <td><?php echo $row['publish_date']; ?></td>
                            <?php elseif ($content_type == 'testimonial'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="40" height="40" style="object-fit:cover; border-radius:50%;">'; else echo '—'; ?></td>
                                <td><?php echo $row['tag'] ?: '—'; ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php echo str_repeat('⭐', $row['rating']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <?php elseif ($content_type == 'gallery'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                            <?php elseif ($content_type == 'training'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['tag']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                                <td><?php echo $row['publish_date']; ?></td>
                            <?php elseif ($content_type == 'event'): ?>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['tag']); ?></td>
                                <td><?php echo htmlspecialchars($row['event_time'] ?? 'TBD'); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . '...'; ?></td>
                                <td><?php if($row['image']) echo '<img src="../' . $row['image'] . '" width="50" height="40" style="object-fit:cover; border-radius:6px;">'; else echo '—'; ?></td>
                                <td><?php echo $row['publish_date']; ?></td>
                            <?php endif; ?>
                            <td class="action-icons">
                                <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">✏️ Edit</button>
                                <a href="?type=<?php echo $content_type; ?>&delete_id=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" class="delete-btn" onclick="return confirm('Delete this item?')">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($total_rows == 0): ?>
                        <tr>
                            <td colspan="<?php echo count($columns) + 1; ?>" style="text-align: center;">No items found</td>
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
                    <a href="?type=<?php echo $content_type; ?>&page=<?php echo $page - 1; ?>" class="page-link prev">← Previous</a>
                <?php else: ?>
                    <span class="page-link disabled">← Previous</span>
                <?php endif; ?>
                
                <div class="page-numbers">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="page-num active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?type=<?php echo $content_type; ?>&page=<?php echo $i; ?>" class="page-num"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?type=<?php echo $content_type; ?>&page=<?php echo $page + 1; ?>" class="page-link next">Next →</a>
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

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit <?php echo ucfirst(str_replace('_', ' ', $content_type)); ?></h3>
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" enctype="multipart/form-data" class="edit-form">
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="existing_image" id="edit_existing_image">
            
            <?php if ($content_type == 'solution'): ?>
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" id="edit_category">
                </div>
                <div class="form-group">
                    <label>Tag</label>
                    <input type="text" name="tag" id="edit_tag">
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    <div id="edit_image_preview"></div>
                    <input type="file" name="image" accept="image/*">
                    <small>Leave empty to keep current image</small>
                </div>
                
            <?php elseif ($content_type == 'training'): ?>
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Category (Level)</label>
                    <input type="text" name="category" id="edit_category">
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="tag" id="edit_tag">
                </div>
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="date" name="publish_date" id="edit_publish_date">
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    <div id="edit_image_preview"></div>
                    <input type="file" name="image" accept="image/*">
                    <small>Leave empty to keep current image</small>
                </div>
                
            <?php elseif ($content_type == 'event'): ?>
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Category (Type)</label>
                    <input type="text" name="category" id="edit_category">
                </div>
                <div class="form-group">
                    <label>Format</label>
                    <input type="text" name="tag" id="edit_tag">
                </div>
                <div class="form-group">
                    <label>Event Time <span class="required">*</span></label>
                    <input type="text" name="event_time" id="edit_event_time" placeholder="e.g., 10:00 AM " required>
                </div>
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="publish_date" id="edit_publish_date">
                </div>
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    <div id="edit_image_preview"></div>
                    <input type="file" name="image" accept="image/*">
                    <small>Leave empty to keep current image</small>
                </div>
                
            <?php else: ?>
                <!-- Other content types fields -->
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    <div id="edit_image_preview"></div>
                    <input type="file" name="image" accept="image/*">
                    <small>Leave empty to keep current image</small>
                </div>
            <?php endif; ?>
            
            <div class="modal-buttons">
                <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                <button type="submit" name="edit_item" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(item) {
        document.getElementById('edit_id').value = item.id;
        document.getElementById('edit_existing_image').value = item.image || '';
        
        if (document.getElementById('edit_title')) 
            document.getElementById('edit_title').value = item.title || '';
        
        if (document.getElementById('edit_description')) 
            document.getElementById('edit_description').value = item.description || '';
        
        if (document.getElementById('edit_category')) 
            document.getElementById('edit_category').value = item.category || '';
        
        if (document.getElementById('edit_tag')) 
            document.getElementById('edit_tag').value = item.tag || '';
        
        if (document.getElementById('edit_rating')) 
            document.getElementById('edit_rating').value = item.rating || 5;
        
        if (document.getElementById('edit_publish_date')) 
            document.getElementById('edit_publish_date').value = item.publish_date || '';
        
        if (document.getElementById('edit_event_time')) 
            document.getElementById('edit_event_time').value = item.event_time || '';
        
        // Show image preview
        const previewDiv = document.getElementById('edit_image_preview');
        if (previewDiv && item.image) {
            const imgSize = '<?php echo $content_type; ?>' === 'testimonial' ? '80' : '60';
            const borderRadius = '<?php echo $content_type; ?>' === 'testimonial' ? '50%' : '6px';
            previewDiv.innerHTML = `<img src="../${item.image}" width="80" height="${imgSize}" style="object-fit:cover; border-radius:${borderRadius}; margin-bottom:10px;">`;
        } else if (previewDiv) {
            previewDiv.innerHTML = '';
        }
        
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