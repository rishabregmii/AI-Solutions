<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Solutions - Gallery</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/gallery.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Database Connection -->
    <?php include 'admin/database.php'; ?>

    <!-- Gallery Heading - TOP CENTER -->
    <section class="gallery-header">
        <div class="container">
            <h1 class="gallery-title">Gallery</h1>
        </div>
    </section>

    <!-- Description Paragraph -->
    <section class="gallery-description-section">
        <div class="container">
            <p class="gallery-description">Explore our moments captured through events, promotions, team activities, and award ceremonies.</p>
        </div>
    </section>

    <!-- Category Dropdown Section - Dynamic from Database -->
    <section class="category-section">
        <div class="container">
            <div class="category-wrapper">
                <span class="category-label">Categories:</span>
                <select id="galleryCategorySelect" class="category-dropdown">
                    <option value="all">All</option>
                    <?php
                    // Get unique categories from gallery
                    $categories_query = $connection->query("SELECT DISTINCT category FROM content_items WHERE content_type = 'gallery' AND category IS NOT NULL AND category != '' ORDER BY category ASC");
                    if ($categories_query && $categories_query->num_rows > 0) {
                        while ($cat = $categories_query->fetch_assoc()) {
                            $category_value = strtolower($cat['category']);
                            $category_label = ucfirst($cat['category']);
                            echo '<option value="' . htmlspecialchars($category_value) . '">' . htmlspecialchars($category_label) . '</option>';
                        }
                    } else {
                        // Default categories if no data
                        echo '<option value="events">Events</option>';
                        echo '<option value="promotions">Promotions</option>';
                        echo '<option value="team">Team</option>';
                        echo '<option value="awards">Awards</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </section>

    <!-- Gallery Grid - Dynamic from Database -->
    <section class="gallery-section">
        <div class="container">

            <div class="gallery-grid" id="galleryGrid">
                <?php
                // Fetch gallery images from database
                $gallery_query = $connection->query("SELECT * FROM content_items WHERE content_type = 'gallery' ORDER BY id ASC");
                
                if ($gallery_query && $gallery_query->num_rows > 0) {
                    while ($image = $gallery_query->fetch_assoc()) {
                        ?>
                        <div class="gallery-card" data-category="<?php echo strtolower($image['category']); ?>">
                            <?php if ($image['image'] && file_exists($image['image'])): ?>
                                <img src="<?php echo $image['image']; ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" class="gallery-image">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($image['title']); ?>" class="gallery-image">
                            <?php endif; ?>
                            <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($image['title']); ?></h3>
                                <p><?php echo htmlspecialchars($image['description']); ?></p>
                                <span class="gallery-category-tag"><?php echo ucfirst($image['category']); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Fallback static content if no data in database
                    ?>
                    <div class="gallery-card" data-category="events">
                        <img src="images/event1.png" alt="AI Summit 2025" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>AI Summit 2025</h3>
                            <p>Annual AI Technology Conference</p>
                            <span class="gallery-category-tag">Events</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="events">
                        <img src="images/event2.png" alt="Tech Expo London" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Tech Expo London</h3>
                            <p>Showcasing Latest AI Innovations</p>
                            <span class="gallery-category-tag">Events</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="promotions">
                        <img src="images/promo1.png" alt="Product Launch" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>AI Assistant Launch</h3>
                            <p>New AI Virtual Assistant Release</p>
                            <span class="gallery-category-tag">Promotions</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="promotions">
                        <img src="images/promo2.png" alt="Summer Campaign" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Summer Campaign 2025</h3>
                            <p>AI Solutions for Business</p>
                            <span class="gallery-category-tag">Promotions</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="team">
                        <img src="images/team1.jpg" alt="Team Meeting" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Weekly Team Huddle</h3>
                            <p>Collaborative Brainstorming Session</p>
                            <span class="gallery-category-tag">Team</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="team">
                        <img src="images/team2.png" alt="Company Outing" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Annual Company Outing</h3>
                            <p>Team Building Activity</p>
                            <span class="gallery-category-tag">Team</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="awards">
                        <img src="images/award1.png" alt="Innovation Award" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Best AI Innovation 2025</h3>
                            <p>UK Tech Awards Ceremony</p>
                            <span class="gallery-category-tag">Awards</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="awards">
                        <img src="images/award2.png" alt="Excellence Award" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Excellence in AI Award</h3>
                            <p>Global AI Summit Recognition</p>
                            <span class="gallery-category-tag">Awards</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="events">
                        <img src="images/event3.png" alt="Webinar Series" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>AI Webinar Series</h3>
                            <p>Digital Transformation Talks</p>
                            <span class="gallery-category-tag">Events</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="team">
                        <img src="images/team3.png" alt="Office Celebration" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Project Milestone Celebration</h3>
                            <p>Successful AI Deployment</p>
                            <span class="gallery-category-tag">Team</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="promotions">
                        <img src="images/promo3.png" alt="Partnership Announcement" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Strategic Partnership</h3>
                            <p>Collaboration with Microsoft</p>
                            <span class="gallery-category-tag">Promotions</span>
                        </div>
                    </div>
                    <div class="gallery-card" data-category="awards">
                        <img src="images/award3.png" alt="Leadership Award" class="gallery-image">
                        <div class="gallery-overlay">
                            <h3>Leadership Excellence Award</h3>
                            <p>CEO Recognition Ceremony</p>
                            <span class="gallery-category-tag">Awards</span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </section>

    <!-- Pagination -->
    <div class="pagination-section">
        <div class="container">
            <div class="pagination">
                <button class="page-btn prev-page" id="prevPageBtn" disabled>← Previous</button>
                <div class="page-numbers" id="pageNumbers"></div>
                <button class="page-btn next-page" id="nextPageBtn">Next →</button>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <?php include 'cta.php'; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>

</body>

</html>