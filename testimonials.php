<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Solutions - Testimonials</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/testimonials.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Database Connection -->
    <?php include 'admin/database.php'; ?>

    <!-- Testimonials Heading - TOP CENTER -->
    <section class="testimonials-header">
        <div class="container">
            <h1 class="testimonials-title">Testimonials</h1>
        </div>
    </section>

    <!-- Description Paragraph -->
    <section class="testimonials-description-section">
        <div class="container">
            <p class="testimonials-description">What our clients say about our AI-powered solutions and digital workplace transformations.</p>
        </div>
    </section>

    <!-- Category Dropdown Section - Dynamic from Database -->
    <section class="category-section">
        <div class="container">
            <div class="category-wrapper">
                <span class="category-label">Categories:</span>
                <select id="testimonialCategorySelect" class="category-dropdown">
                    <option value="all">All</option>
                    <?php
                    // Get unique categories from testimonials
                    $categories_query = $connection->query("SELECT DISTINCT category FROM content_items WHERE content_type = 'testimonial' AND category IS NOT NULL AND category != '' ORDER BY category ASC");
                    if ($categories_query && $categories_query->num_rows > 0) {
                        while ($cat = $categories_query->fetch_assoc()) {
                            $category_value = strtolower($cat['category']);
                            $category_label = ucfirst($cat['category']);
                            echo '<option value="' . htmlspecialchars($category_value) . '">' . htmlspecialchars($category_label) . '</option>';
                        }
                    } else {
                        // Default categories if no data
                        echo '<option value="healthcare">Healthcare</option>';
                        echo '<option value="finance">Finance</option>';
                        echo '<option value="retail">Retail</option>';
                        echo '<option value="manufacturing">Manufacturing</option>';
                        echo '<option value="education">Education</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </section>

    <!-- Testimonials Grid - Dynamic from Database -->
    <section class="testimonials-section">
        <div class="container">

            <div class="testimonials-grid" id="testimonialsGrid">
                <?php
                // Fetch testimonials from database
                $testimonials_query = $connection->query("SELECT * FROM content_items WHERE content_type = 'testimonial' ORDER BY id ASC");
                
                if ($testimonials_query && $testimonials_query->num_rows > 0) {
                    while ($testimonial = $testimonials_query->fetch_assoc()) {
                        $rating = (int)$testimonial['rating'];
                        $filled_stars = str_repeat('★', $rating);
                        $empty_stars = str_repeat('★', 5 - $rating);
                        ?>
                        <div class="testimonial-card" data-category="<?php echo strtolower($testimonial['category']); ?>">
                            <!-- Stars at the TOP -->
                            <div class="stars">
                                <span class="star filled"><?php echo $filled_stars; ?></span>
                                <?php if ($empty_stars): ?>
                                    <span class="star empty"><?php echo $empty_stars; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Content / Testimonial Text -->
                            <div class="testimonial-icon">“</div>
                            <p class="testimonial-text"><?php echo htmlspecialchars($testimonial['description']); ?></p>
                            
                            <!-- Avatar and Name at the BOTTOM -->
                            <div class="testimonial-footer">
                                <?php if ($testimonial['image'] && file_exists($testimonial['image'])): ?>
                                    <img src="<?php echo $testimonial['image']; ?>" alt="<?php echo htmlspecialchars($testimonial['title']); ?>" class="testimonial-avatar">
                                <?php endif; ?>
                                <div class="testimonial-author-info">
                                    <h4><?php echo htmlspecialchars($testimonial['title']); ?></h4>
                                    <p><?php echo htmlspecialchars($testimonial['tag']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Fallback static content if no data in database
                    ?>
                    <div class="testimonial-card" data-category="healthcare">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">AI-Solutions helped us reduce patient wait time by 65% with their intelligent triage system. Our staff now focuses on critical cases while AI handles routine inquiries.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Dr. Sarah Johnson</h4>
                                <p>Chief Medical Officer, NHS Trust</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="finance">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">The predictive analytics solution saved us over £15M in potential fraud losses. Real-time detection has been a game changer for our security team.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Michael Chen</h4>
                                <p>Head of Risk, Barclays</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="retail">
                        <div class="stars">
                            <span class="star filled">★★★★</span>
                            <span class="star empty">★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">AI demand forecasting reduced our food waste by 35% while improving stock availability. The ROI was visible within 3 months of implementation.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Emma Watson</h4>
                                <p>Supply Chain Director, Tesco</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="manufacturing">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">Predictive maintenance saved us £50M in unplanned downtime. The AI system alerts us before failures happen, keeping our production running.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>James Rodriguez</h4>
                                <p>Operations Manager, Rolls-Royce</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="healthcare">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">The AI drug discovery platform cut our research timeline from 5 years to 18 months. We're now helping rare disease patients faster than ever.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Dr. Emily Park</h4>
                                <p>Research Director, Mayo Clinic</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="finance">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">Risk assessment automation reduced our loan processing from 2 weeks to 48 hours. Our clients love the speed and accuracy.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>David Kim</h4>
                                <p>VP of Operations, Goldman Sachs</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="education">
                        <div class="stars">
                            <span class="star filled">★★★★</span>
                            <span class="star empty">★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">The AI virtual assistant transformed our student support system. Response time dropped from hours to seconds.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Prof. Lisa Brown</h4>
                                <p>Dean of Technology, University of London</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="retail">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">Personalized recommendations increased our conversion rate by 45%. Customers love the tailored shopping experience.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Mark Taylor</h4>
                                <p>E-commerce Director, ASOS</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card" data-category="manufacturing">
                        <div class="stars">
                            <span class="star filled">★★★★★</span>
                        </div>
                        <div class="testimonial-icon">“</div>
                        <p class="testimonial-text">Quality control AI reduced defect rates by 60% on our production line. The system catches issues human inspectors miss.</p>
                        <div class="testimonial-footer">
                            <div class="testimonial-author-info">
                                <h4>Anna Schmidt</h4>
                                <p>Quality Assurance Lead, Siemens</p>
                            </div>
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