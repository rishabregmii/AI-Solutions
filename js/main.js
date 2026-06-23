// js/main.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ================= MOBILE MENU TOGGLE =================
    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navbar.classList.toggle('active');
        });
    }
    
    // ================= INSIGHTS PAGE =================
    
    // Get ALL case studies (all 6)
    const allCases = document.querySelectorAll('.case-card');
    
    // Get ALL articles (all 6)
    const allArticles = document.querySelectorAll('.article-card');
    
    // Get filter elements
    const caseCategorySelect = document.getElementById('caseCategorySelect');
    const articleCategorySelect = document.getElementById('articleCategorySelect');
    const globalSearchInput = document.getElementById('globalSearchInput');
    const globalSearchBtn = document.getElementById('globalSearchBtn');
    
    // Pagination variables
    let currentPage = 1;
    let itemsPerPage = 6;
    let currentVisibleCases = [];
    let currentVisibleArticles = [];
    
    // Function to filter case studies
    function getFilteredCases() {
        const selectedCategory = caseCategorySelect ? caseCategorySelect.value : 'all';
        const searchTerm = globalSearchInput ? globalSearchInput.value.toLowerCase().trim() : '';
        
        const filtered = [];
        
        allCases.forEach(card => {
            const category = card.getAttribute('data-case-cat');
            const title = card.querySelector('h3').innerText.toLowerCase();
            const description = card.querySelector('p').innerText.toLowerCase();
            
            const categoryMatch = selectedCategory === 'all' || category === selectedCategory;
            const searchMatch = searchTerm === '' || 
                title.includes(searchTerm) || 
                description.includes(searchTerm);
            
            if (categoryMatch && searchMatch) {
                filtered.push(card);
            }
        });
        
        return filtered;
    }
    
    // Function to filter articles
    function getFilteredArticles() {
        const selectedCategory = articleCategorySelect ? articleCategorySelect.value : 'all';
        const searchTerm = globalSearchInput ? globalSearchInput.value.toLowerCase().trim() : '';
        
        const filtered = [];
        
        allArticles.forEach(card => {
            const category = card.getAttribute('data-article-cat');
            const title = card.querySelector('h3').innerText.toLowerCase();
            const description = card.querySelector('p').innerText.toLowerCase();
            
            const categoryMatch = selectedCategory === 'all' || category === selectedCategory;
            const searchMatch = searchTerm === '' || 
                title.includes(searchTerm) || 
                description.includes(searchTerm);
            
            if (categoryMatch && searchMatch) {
                filtered.push(card);
            }
        });
        
        return filtered;
    }
    
    // Main function to update display and pagination
    function updateDisplay() {
        currentVisibleCases = getFilteredCases();
        currentVisibleArticles = getFilteredArticles();
        
        const allVisibleItems = [];
        const maxLength = Math.max(currentVisibleCases.length, currentVisibleArticles.length);
        
        for (let i = 0; i < maxLength; i++) {
            if (i < currentVisibleCases.length) allVisibleItems.push(currentVisibleCases[i]);
            if (i < currentVisibleArticles.length) allVisibleItems.push(currentVisibleArticles[i]);
        }
        
        const totalItems = allVisibleItems.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }
        if (currentPage < 1) currentPage = 1;
        
        allCases.forEach(card => card.style.display = 'none');
        allArticles.forEach(card => card.style.display = 'none');
        
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const itemsToShow = allVisibleItems.slice(startIndex, endIndex);
        
        itemsToShow.forEach(item => {
            item.style.display = 'block';
        });
        
        updatePaginationUI(totalPages);
    }
    
    function updatePaginationUI(totalPages) {
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
                updateDisplay();
            });
            pageNumbersDiv.appendChild(pageBtn);
        }
        
        if (prevBtn) {
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateDisplay();
                }
            };
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateDisplay();
                }
            };
        }
    }
    
    if (caseCategorySelect) {
        caseCategorySelect.addEventListener('change', () => {
            currentPage = 1;
            updateDisplay();
        });
    }
    
    if (articleCategorySelect) {
        articleCategorySelect.addEventListener('change', () => {
            currentPage = 1;
            updateDisplay();
        });
    }
    
    function performSearch() {
        currentPage = 1;
        updateDisplay();
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
    
    if (allCases.length > 0 || allArticles.length > 0) {
        updateDisplay();
    }

    // ================= TESTIMONIALS PAGE =================
    
    const allTestimonials = document.querySelectorAll('.testimonial-card');
    const testimonialCategorySelect = document.getElementById('testimonialCategorySelect');
    
    let currentTestimonialPage = 1;
    let testimonialsPerPage = 6;
    let currentVisibleTestimonials = [];
    
    function getFilteredTestimonials() {
        const selectedCategory = testimonialCategorySelect ? testimonialCategorySelect.value : 'all';
        const filtered = [];
        
        allTestimonials.forEach(card => {
            const category = card.getAttribute('data-category');
            const categoryMatch = selectedCategory === 'all' || category === selectedCategory;
            
            if (categoryMatch) {
                filtered.push(card);
            }
        });
        
        return filtered;
    }
    
    function updateTestimonialsDisplay() {
        if (allTestimonials.length === 0) return;
        
        currentVisibleTestimonials = getFilteredTestimonials();
        const totalItems = currentVisibleTestimonials.length;
        const totalPages = Math.ceil(totalItems / testimonialsPerPage);
        
        if (currentTestimonialPage > totalPages && totalPages > 0) {
            currentTestimonialPage = totalPages;
        }
        if (currentTestimonialPage < 1) currentTestimonialPage = 1;
        
        allTestimonials.forEach(card => card.style.display = 'none');
        
        const startIndex = (currentTestimonialPage - 1) * testimonialsPerPage;
        const endIndex = Math.min(startIndex + testimonialsPerPage, totalItems);
        const itemsToShow = currentVisibleTestimonials.slice(startIndex, endIndex);
        
        itemsToShow.forEach(item => {
            item.style.display = 'block';
        });
        
        updateTestimonialPaginationUI(totalPages);
    }
    
    function updateTestimonialPaginationUI(totalPages) {
        const pageNumbersDiv = document.getElementById('pageNumbers');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        
        if (!pageNumbersDiv) return;
        
        pageNumbersDiv.innerHTML = '';
        
        if (totalPages === 0) {
            pageNumbersDiv.innerHTML = '<span style="padding: 10px;">No testimonials found</span>';
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            return;
        }
        
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-num ${i === currentTestimonialPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentTestimonialPage = i;
                updateTestimonialsDisplay();
            });
            pageNumbersDiv.appendChild(pageBtn);
        }
        
        if (prevBtn) {
            prevBtn.disabled = currentTestimonialPage === 1;
            prevBtn.onclick = () => {
                if (currentTestimonialPage > 1) {
                    currentTestimonialPage--;
                    updateTestimonialsDisplay();
                }
            };
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentTestimonialPage === totalPages;
            nextBtn.onclick = () => {
                if (currentTestimonialPage < totalPages) {
                    currentTestimonialPage++;
                    updateTestimonialsDisplay();
                }
            };
        }
    }
    
    if (testimonialCategorySelect) {
        testimonialCategorySelect.addEventListener('change', () => {
            currentTestimonialPage = 1;
            updateTestimonialsDisplay();
        });
    }
    
    if (allTestimonials.length > 0) {
        updateTestimonialsDisplay();
    }

    // ================= GALLERY PAGE =================
    
    const allGalleryItems = document.querySelectorAll('.gallery-card');
    const galleryCategorySelect = document.getElementById('galleryCategorySelect');
    
    let currentGalleryPage = 1;
    let galleryItemsPerPage = 6;
    let currentVisibleGallery = [];
    
    function getFilteredGallery() {
        const selectedCategory = galleryCategorySelect ? galleryCategorySelect.value : 'all';
        const filtered = [];
        
        allGalleryItems.forEach(card => {
            const category = card.getAttribute('data-category');
            const categoryMatch = selectedCategory === 'all' || category === selectedCategory;
            
            if (categoryMatch) {
                card.classList.remove('hide');
                filtered.push(card);
            } else {
                card.classList.add('hide');
            }
        });
        
        return filtered;
    }
    
    function updateGalleryDisplay() {
        if (allGalleryItems.length === 0) return;
        
        currentVisibleGallery = getFilteredGallery();
        const totalItems = currentVisibleGallery.length;
        const totalPages = Math.ceil(totalItems / galleryItemsPerPage);
        
        if (currentGalleryPage > totalPages && totalPages > 0) {
            currentGalleryPage = totalPages;
        }
        if (currentGalleryPage < 1) currentGalleryPage = 1;
        
        allGalleryItems.forEach(card => card.style.display = 'none');
        
        const startIndex = (currentGalleryPage - 1) * galleryItemsPerPage;
        const endIndex = Math.min(startIndex + galleryItemsPerPage, totalItems);
        const itemsToShow = currentVisibleGallery.slice(startIndex, endIndex);
        
        itemsToShow.forEach(item => {
            item.style.display = 'block';
        });
        
        updateGalleryPaginationUI(totalPages);
    }
    
    function updateGalleryPaginationUI(totalPages) {
        const pageNumbersDiv = document.getElementById('pageNumbers');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        
        if (!pageNumbersDiv) return;
        
        pageNumbersDiv.innerHTML = '';
        
        if (totalPages === 0) {
            pageNumbersDiv.innerHTML = '<span style="padding: 10px;">No images found</span>';
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            return;
        }
        
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-num ${i === currentGalleryPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentGalleryPage = i;
                updateGalleryDisplay();
            });
            pageNumbersDiv.appendChild(pageBtn);
        }
        
        if (prevBtn) {
            prevBtn.disabled = currentGalleryPage === 1;
            prevBtn.onclick = () => {
                if (currentGalleryPage > 1) {
                    currentGalleryPage--;
                    updateGalleryDisplay();
                }
            };
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentGalleryPage === totalPages;
            nextBtn.onclick = () => {
                if (currentGalleryPage < totalPages) {
                    currentGalleryPage++;
                    updateGalleryDisplay();
                }
            };
        }
    }
    
    if (galleryCategorySelect) {
        galleryCategorySelect.addEventListener('change', () => {
            currentGalleryPage = 1;
            updateGalleryDisplay();
        });
    }
    
    if (allGalleryItems.length > 0) {
        updateGalleryDisplay();
    }
    
    // ================= GALLERY LIGHTBOX MODAL =================
    // ONLY create modal if gallery items exist on this page
    if (allGalleryItems.length > 0) {
        // Check if modal already exists
        let modal = document.getElementById('galleryModal');
        
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'galleryModal';
            modal.className = 'modal';
            modal.innerHTML = `
                <span class="modal-close">&times;</span>
                <img class="modal-img" id="galleryModalImg" src="" alt="Enlarged view">
            `;
            document.body.appendChild(modal);
        }
        
        const modalImg = document.getElementById('galleryModalImg');
        const modalClose = modal.querySelector('.modal-close');
        
        // Remove any existing event listeners by cloning
        const newModal = modal.cloneNode(true);
        modal.parentNode.replaceChild(newModal, modal);
        
        const newModalImg = newModal.querySelector('.modal-img');
        const newModalClose = newModal.querySelector('.modal-close');
        
        allGalleryItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const img = this.querySelector('.gallery-image');
                if (img) {
                    newModalImg.src = img.src;
                    newModal.classList.add('active');
                }
            });
        });
        
        newModalClose.addEventListener('click', function() {
            newModal.classList.remove('active');
        });
        
        newModal.addEventListener('click', function(e) {
            if (e.target === newModal) {
                newModal.classList.remove('active');
            }
        });
    }
});