<?php
// solutions.php - Main page file
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Solutions - Our Solutions</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/solutions.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Database Connection -->
    <?php include 'admin/database.php'; ?>

    <!-- ================= SOLUTIONS HEADING ================= -->
    <section class="solutions-header">
        <div class="container">
            <h1 class="solutions-title">Our Solutions</h1>
        </div>
    </section>

    <!-- Description Paragraph -->
    <section class="solutions-description-section">
        <div class="container">
            <p class="solutions-description">We provide AI-powered digital workplace solutions that help businesses improve productivity, automate workflows, and enhance decision-making processes.</p>
        </div>
    </section>

    <!-- Category Filter Row -->
    <div class="filter-row-main">
        <div class="filter-group">
            <span class="filter-label">Filter by:</span>
            <select id="globalFilterSelect" class="global-filter-dropdown">
                <option value="all">All Content</option>
                <option value="solution">Solutions</option>
                <option value="training">Training</option>
                <option value="event">Events</option>
            </select>
        </div>
    </div>

    <!-- ================= SOLUTIONS SECTION ================= -->
    <section class="solutions-section">
        <div class="container">
            <h2 class="section-title">What We Offer</h2>
            <div class="solutions-grid" id="solutionsGrid">
                <?php
                $solutions_query = $connection->query("SELECT * FROM content_items WHERE content_type = 'solution' ORDER BY id ASC");
                if ($solutions_query && $solutions_query->num_rows > 0) {
                    while ($solution = $solutions_query->fetch_assoc()) {
                        ?>
                        <div class="solution-card" data-type="solution" data-id="<?php echo $solution['id']; ?>">
                            <?php if ($solution['image'] && file_exists($solution['image'])): ?>
                                <img src="<?php echo $solution['image']; ?>" alt="<?php echo htmlspecialchars($solution['title']); ?>">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($solution['title']); ?>">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($solution['title']); ?></h3>
                            <p><?php echo htmlspecialchars($solution['description']); ?></p>
                            <a href="details.php?id=<?php echo $solution['id']; ?>&type=solution">Learn more →</a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- ================= TRAINING SECTION ================= -->
    <section class="training-section">
        <div class="container">
            <h2 class="section-title">Training We Offer</h2>
            <div class="training-grid" id="trainingGrid">
                <?php
                $trainings = $connection->query("SELECT * FROM content_items WHERE content_type = 'training' ORDER BY id ASC");
                if ($trainings && $trainings->num_rows > 0) {
                    while ($training = $trainings->fetch_assoc()) {
                        ?>
                        <div class="training-card" data-type="training" data-id="<?php echo $training['id']; ?>">
                            <?php if ($training['image'] && file_exists($training['image'])): ?>
                                <img src="<?php echo $training['image']; ?>" alt="<?php echo htmlspecialchars($training['title']); ?>" class="training-image">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($training['title']); ?>" class="training-image">
                            <?php endif; ?>
                            <div class="training-tag"><?php echo ucfirst($training['category']); ?></div>
                            <div class="training-duration"><?php echo htmlspecialchars($training['tag']); ?></div>
                            <h3><?php echo htmlspecialchars($training['title']); ?></h3>
                            <p><?php echo htmlspecialchars($training['description']); ?></p>
                            <div class="training-footer">
                                <a href="details.php?id=<?php echo $training['id']; ?>&type=training" class="register-btn">Learn More →</a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- ================= EVENTS SECTION ================= -->
    <section class="events-section">
        <div class="container">
            <h2 class="section-title">Future Events</h2>
            <div class="events-grid" id="eventsGrid">
                <?php
                $events = $connection->query("SELECT * FROM content_items WHERE content_type = 'event' ORDER BY publish_date ASC");
                if ($events && $events->num_rows > 0) {
                    while ($event = $events->fetch_assoc()) {
                        // Get event time from database, use default if empty
                        $event_time = !empty($event['event_time']) ? $event['event_time'] : 'Time TBD';
                        ?>
                        <div class="event-card" data-type="event" data-id="<?php echo $event['id']; ?>">
                            <?php if ($event['image'] && file_exists($event['image'])): ?>
                                <img src="<?php echo $event['image']; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
                            <?php endif; ?>
                            <div class="event-type"><?php echo ucfirst($event['category']); ?></div>
                            <div class="event-format"><?php echo htmlspecialchars($event['tag']); ?></div>
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-footer">
                                <span class="event-date">📅 <?php echo date('M d, Y', strtotime($event['publish_date'])); ?></span>
                                <span class="event-time">⏰ <?php echo htmlspecialchars($event_time); ?></span>
                                <a href="contact.php?event=<?php echo urlencode($event['title']); ?>" class="event-register-btn">Register →</a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- ================= INDUSTRIES SECTION (Static) ================= -->
    <section class="industries-section">
        <div class="container">
            <h2 class="section-title">Industries We Serve</h2>
            <div class="industries-container">
                <div class="industry-box">
                    <img src="images/industry1.jpg" alt="Healthcare">
                    <p>Healthcare</p>
                </div>
                <div class="industry-box">
                    <img src="images/industry2.jpg" alt="Education">
                    <p>Education</p>
                </div>
                <div class="industry-box">
                    <img src="images/industry3.jpg" alt="Finance">
                    <p>Finance</p>
                </div>
                <div class="industry-box">
                    <img src="images/industry4.jpg" alt="Retail">
                    <p>Technology</p>
                </div>
                <div class="industry-box">
                    <img src="images/industry5.jpg" alt="Manufacturing">
                    <p>And Many More..</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SINGLE PAGINATION - MOVED TO MIDDLE/CENTER OF PAGE -->
    <div class="pagination-section">
        <div class="container">
            <div class="pagination">
                <button class="page-btn prev-page" id="prevPageBtn" disabled>← Previous</button>
                <div class="page-numbers" id="pageNumbers"></div>
                <button class="page-btn next-page" id="nextPageBtn">Next →</button>
            </div>
            <div class="pagination-info" id="paginationInfo"></div>
        </div>
    </div>

    <!-- CTA -->
    <?php include 'cta.php'; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>

    <script>
        // Get all items from all sections
        const allSolutions = document.querySelectorAll('.solution-card');
        const allTrainings = document.querySelectorAll('.training-card');
        const allEvents = document.querySelectorAll('.event-card');
        
        // Combine all items
        let allItems = [...allSolutions, ...allTrainings, ...allEvents];
        
        // Pagination variables
        let currentPage = 1;
        let itemsPerPage = 9;
        let currentFilter = 'all';
        
        function getTypeOrderedItems(items, type) {
            const typeItems = items.filter(item => item.getAttribute('data-type') === type);
            return typeItems;
        }
        
        function filterItems() {
            const filterValue = document.getElementById('globalFilterSelect').value;
            currentFilter = filterValue;
            
            allItems.forEach(item => {
                const itemType = item.getAttribute('data-type');
                if (filterValue === 'all' || itemType === filterValue) {
                    item.classList.remove('hide');
                } else {
                    item.classList.add('hide');
                }
            });
            
            currentPage = 1;
            updatePaginationAndDisplay();
        }
        
        function updatePaginationAndDisplay() {
            let visibleItems = [];
            
            if (currentFilter === 'all') {
                const solutions = getTypeOrderedItems(allItems, 'solution');
                const trainings = getTypeOrderedItems(allItems, 'training');
                const events = getTypeOrderedItems(allItems, 'event');
                
                const startIndex = (currentPage - 1) * 3;
                const solutionsToShow = solutions.slice(startIndex, startIndex + 3);
                const trainingsToShow = trainings.slice(startIndex, startIndex + 3);
                const eventsToShow = events.slice(startIndex, startIndex + 3);
                
                visibleItems = [...solutionsToShow, ...trainingsToShow, ...eventsToShow];
                
                const totalPages = Math.ceil(solutions.length / 3);
                updatePaginationUI(totalPages, solutions.length * 3);
                
                allItems.forEach(item => item.style.display = 'none');
                visibleItems.forEach(item => item.style.display = 'block');
                
            } else {
                const filteredItems = allItems.filter(item => !item.classList.contains('hide'));
                const totalItems = filteredItems.length;
                const totalPages = Math.ceil(totalItems / 9);
                
                const startIndex = (currentPage - 1) * 9;
                const endIndex = Math.min(startIndex + 9, totalItems);
                visibleItems = filteredItems.slice(startIndex, endIndex);
                
                allItems.forEach(item => item.style.display = 'none');
                visibleItems.forEach(item => item.style.display = 'block');
                
                updatePaginationUI(totalPages, totalItems);
            }
        }
        
        function updatePaginationUI(totalPages, totalItems) {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            const prevBtn = document.getElementById('prevPageBtn');
            const nextBtn = document.getElementById('nextPageBtn');
            const paginationInfo = document.getElementById('paginationInfo');
            
            if (!pageNumbersDiv) return;
            
            const startItem = (currentPage - 1) * 9 + 1;
            const endItem = Math.min(currentPage * 9, totalItems);
            
            
            pageNumbersDiv.innerHTML = '';
            
            if (totalPages === 0) {
                pageNumbersDiv.innerHTML = '<span style="padding: 10px;">No items found</span>';
                if (prevBtn) prevBtn.disabled = true;
                if (nextBtn) nextBtn.disabled = true;
                return;
            }
            
            // Show limited page numbers for better UX
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                const firstBtn = document.createElement('button');
                firstBtn.className = 'page-num';
                firstBtn.textContent = '1';
                firstBtn.addEventListener('click', () => {
                    currentPage = 1;
                    updatePaginationAndDisplay();
                });
                pageNumbersDiv.appendChild(firstBtn);
                
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '0 5px';
                    pageNumbersDiv.appendChild(dots);
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `page-num ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    updatePaginationAndDisplay();
                });
                pageNumbersDiv.appendChild(pageBtn);
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '0 5px';
                    pageNumbersDiv.appendChild(dots);
                }
                
                const lastBtn = document.createElement('button');
                lastBtn.className = 'page-num';
                lastBtn.textContent = totalPages;
                lastBtn.addEventListener('click', () => {
                    currentPage = totalPages;
                    updatePaginationAndDisplay();
                });
                pageNumbersDiv.appendChild(lastBtn);
            }
            
            if (prevBtn) {
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => {
                    if (currentPage > 1) {
                        currentPage--;
                        updatePaginationAndDisplay();
                    }
                };
            }
            
            if (nextBtn) {
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.onclick = () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updatePaginationAndDisplay();
                    }
                };
            }
        }
        
        document.getElementById('globalFilterSelect').addEventListener('change', filterItems);
        filterItems();
    </script>

</body>

</html>