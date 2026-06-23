<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Solutions - Insights</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/insights.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Database Connection -->
    <?php include 'admin/database.php'; ?>

    <!-- Insights Heading - JUST BELOW HEADER, CENTERED -->
    <section class="insights-header">
        <div class="container">
            <h1 class="insights-title">Insights</h1>
        </div>
    </section>

    <!-- Description Paragraph -->
    <section class="insights-description-section">
        <div class="container">
            <p class="insights-description">Real success stories and expert articles on how AI is transforming digital workplaces across industries.</p>
        </div>
    </section>

    <!-- ONE SEARCH BAR (Extreme Right) -->
    <div class="search-bar-container">
        <div class="container">
            <div class="search-wrapper-right">
                <input type="text" id="globalSearchInput" placeholder="Search case studies or articles..." class="search-input">
                <button class="search-btn" id="globalSearchBtn">🔍 Search</button>
            </div>
        </div>
    </div>

    <!-- ================= CASE STUDIES SECTION ================= -->
    <section class="case-section">
        <div class="container">

            <h2 class="section-title">Case Studies</h2>

            <!-- Category Dropdown (Dynamic from Database) -->
            <div class="filter-row">
                <div class="dropdown-wrapper">
                    <select id="caseCategorySelect" class="category-dropdown">
                        <option value="all">All Categories</option>
                        <?php
                        // Get unique categories from case studies in database
                        $categories_query = $connection->query("SELECT DISTINCT category FROM content_items WHERE content_type = 'case_study' AND category IS NOT NULL AND category != '' ORDER BY category ASC");
                        if ($categories_query && $categories_query->num_rows > 0) {
                            while ($cat = $categories_query->fetch_assoc()) {
                                $category_value = strtolower($cat['category']);
                                $category_label = ucfirst($cat['category']);
                                echo '<option value="' . htmlspecialchars($category_value) . '">' . htmlspecialchars($category_label) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Case Studies Grid - Dynamic from Database -->
            <div class="cases-grid" id="casesGrid">
                <?php
                // Fetch case studies from database
                $case_studies = $connection->query("SELECT * FROM content_items WHERE content_type = 'case_study' ORDER BY id ASC");
                
                if ($case_studies && $case_studies->num_rows > 0) {
                    while ($case = $case_studies->fetch_assoc()) {
                        ?>
                        <div class="case-card" data-case-cat="<?php echo strtolower($case['category']); ?>" data-search="<?php echo htmlspecialchars($case['title'] . ' ' . $case['description']); ?>">
                            <?php if ($case['image'] && file_exists($case['image'])): ?>
                                <img src="<?php echo $case['image']; ?>" alt="<?php echo htmlspecialchars($case['title']); ?>" class="case-image">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($case['title']); ?>" class="case-image">
                            <?php endif; ?>
                            <div class="case-tag"><?php echo ucfirst($case['category']); ?></div>
                            <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                            <p><?php echo htmlspecialchars($case['description']); ?></p>
                            <a href="details.php?id=<?php echo $case['id']; ?>&type=case_study" class="read-more">Read case study →</a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>
    </section>

    <!-- ================= ARTICLES SECTION ================= -->
    <section class="articles-section">
        <div class="container">

            <h2 class="section-title">Articles</h2>

            <!-- Category Dropdown (Dynamic from Database) -->
            <div class="filter-row">
                <div class="dropdown-wrapper">
                    <select id="articleCategorySelect" class="category-dropdown">
                        <option value="all">All Topics</option>
                        <?php
                        // Get unique categories from articles in database
                        $article_cats = $connection->query("SELECT DISTINCT category FROM content_items WHERE content_type = 'article' AND category IS NOT NULL AND category != '' ORDER BY category ASC");
                        if ($article_cats && $article_cats->num_rows > 0) {
                            while ($cat = $article_cats->fetch_assoc()) {
                                $category_value = strtolower($cat['category']);
                                $category_label = ucfirst($cat['category']);
                                echo '<option value="' . htmlspecialchars($category_value) . '">' . htmlspecialchars($category_label) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Articles Grid - Dynamic from Database -->
            <div class="articles-grid" id="articlesGrid">
                <?php
                // Fetch articles from database
                $articles = $connection->query("SELECT * FROM content_items WHERE content_type = 'article' ORDER BY id ASC");
                
                if ($articles && $articles->num_rows > 0) {
                    while ($article = $articles->fetch_assoc()) {
                        ?>
                        <div class="article-card" data-article-cat="<?php echo strtolower($article['category']); ?>" data-search="<?php echo htmlspecialchars($article['title'] . ' ' . $article['description']); ?>">
                            <?php if ($article['image'] && file_exists($article['image'])): ?>
                                <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
                            <?php else: ?>
                                <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
                            <?php endif; ?>
                            <div class="article-date"><?php echo date('M d, Y', strtotime($article['publish_date'])); ?></div>
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p><?php echo htmlspecialchars($article['description']); ?></p>
                            <a href="details.php?id=<?php echo $article['id']; ?>&type=article" class="read-more">Read article →</a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>
    </section>

    <!-- PAGINATION (Centered) -->
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

    <script>
        // Get all case studies and articles
        const caseCards = document.querySelectorAll('.case-card');
        const articleCards = document.querySelectorAll('.article-card');
        const allItems = [...caseCards, ...articleCards];
        
        // Get filter elements
        const caseCategorySelect = document.getElementById('caseCategorySelect');
        const articleCategorySelect = document.getElementById('articleCategorySelect');
        const globalSearchInput = document.getElementById('globalSearchInput');
        const globalSearchBtn = document.getElementById('globalSearchBtn');
        
        // Pagination variables
        let currentPage = 1;
        let itemsPerPage = 6;
        let currentFilterCase = 'all';
        let currentFilterArticle = 'all';
        let currentSearchTerm = '';
        
        function filterAndPaginate() {
            // Filter case studies
            caseCards.forEach(card => {
                const category = card.getAttribute('data-case-cat');
                const searchData = card.getAttribute('data-search').toLowerCase();
                const categoryMatch = currentFilterCase === 'all' || category === currentFilterCase;
                const searchMatch = currentSearchTerm === '' || searchData.includes(currentSearchTerm);
                
                if (categoryMatch && searchMatch) {
                    card.classList.remove('hide');
                } else {
                    card.classList.add('hide');
                }
            });
            
            // Filter articles
            articleCards.forEach(card => {
                const category = card.getAttribute('data-article-cat');
                const searchData = card.getAttribute('data-search').toLowerCase();
                const categoryMatch = currentFilterArticle === 'all' || category === currentFilterArticle;
                const searchMatch = currentSearchTerm === '' || searchData.includes(currentSearchTerm);
                
                if (categoryMatch && searchMatch) {
                    card.classList.remove('hide');
                } else {
                    card.classList.add('hide');
                }
            });
            
            // Pagination
            const visibleItems = allItems.filter(item => !item.classList.contains('hide'));
            const totalPages = Math.ceil(visibleItems.length / itemsPerPage);
            
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            allItems.forEach(item => item.style.display = 'none');
            visibleItems.slice(startIndex, endIndex).forEach(item => item.style.display = 'block');
            
            updatePaginationUI(totalPages, visibleItems.length);
        }
        
        function updatePaginationUI(totalPages, totalItems) {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            const prevBtn = document.getElementById('prevPageBtn');
            const nextBtn = document.getElementById('nextPageBtn');
            
            if (!pageNumbersDiv) return;
            
            pageNumbersDiv.innerHTML = '';
            
            if (totalPages === 0) {
                pageNumbersDiv.innerHTML = '<span style="padding: 10px;">No results found</span>';
                if (prevBtn) prevBtn.disabled = true;
                if (nextBtn) nextBtn.disabled = true;
                return;
            }
            
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `page-num ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    filterAndPaginate();
                });
                pageNumbersDiv.appendChild(pageBtn);
            }
            
            if (prevBtn) {
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => {
                    if (currentPage > 1) {
                        currentPage--;
                        filterAndPaginate();
                    }
                };
            }
            
            if (nextBtn) {
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.onclick = () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        filterAndPaginate();
                    }
                };
            }
        }
        
        // Event listeners
        if (caseCategorySelect) {
            caseCategorySelect.addEventListener('change', (e) => {
                currentFilterCase = e.target.value;
                currentPage = 1;
                filterAndPaginate();
            });
        }
        
        if (articleCategorySelect) {
            articleCategorySelect.addEventListener('change', (e) => {
                currentFilterArticle = e.target.value;
                currentPage = 1;
                filterAndPaginate();
            });
        }
        
        function performSearch() {
            currentSearchTerm = globalSearchInput.value.toLowerCase().trim();
            currentPage = 1;
            filterAndPaginate();
        }
        
        if (globalSearchBtn) {
            globalSearchBtn.addEventListener('click', performSearch);
        }
        
        if (globalSearchInput) {
            globalSearchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
        
        // Initialize
        filterAndPaginate();
    </script>

</body>

</html>