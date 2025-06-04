{extends file="layouts/main.tpl"}
{block name="body"}
    <!-- Main navigation bar for the staff portal -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <div class="invisible d-none d-lg-block" style="width: 100px;"></div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home"><i class="bi bi-house-door me-1"></i>Portal Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="support"><i class="bi bi-question-circle me-1"></i>Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="announcements"><i
                                class="bi bi-megaphone me-1"></i>Announcements</a>
                    </li>
                </ul>
                <div class="nav-item">
                    <a href="login" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content area -->
    <div class="container py-5">
        <!-- Page header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h1 class="display-4">
                        <i class="bi bi-megaphone me-3 text-primary"></i>Announcements
                    </h1>
                    <p class="lead text-muted">Stay updated with the latest clinic news and important information</p>
                </div>
            </div>
        </div>

        <!-- Filter and search section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchAnnouncements" placeholder="Search announcements...">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="Equipment">Equipment</option>
                    <option value="Schedule">Schedule</option>
                    <option value="Procedures">Procedures</option>
                    <option value="Meeting">Meeting</option>
                </select>
            </div>
        </div>

        <!-- Announcements list -->
        <div class="row g-4" id="announcementsList">
            {foreach from=$announcements item=announcement}
                <div class="col-12 announcement-item" data-category="{$announcement.category}">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{$announcement.title}</h5>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {$announcement.date_formatted}
                                    </small>
                                    <span class="badge bg-secondary">{$announcement.category}</span>
                                    {if $announcement.priority == 'high'}
                                        <span class="badge bg-danger">High Priority</span>
                                    {elseif $announcement.priority == 'medium'}
                                        <span class="badge bg-warning">Medium Priority</span>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{$announcement.content}</p>
                            <small class="text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                Posted by: {$announcement.author}
                            </small>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

        <!-- No results message (hidden by default) -->
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3 class="text-muted">No announcements found</h3>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-custom-primary text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">For technical support: support@vetcare.co.uk</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">© 2025 VetCare Staff Portal</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for search and filter functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchAnnouncements');
            const categoryFilter = document.getElementById('categoryFilter');
            const announcementsList = document.getElementById('announcementsList');
            const noResults = document.getElementById('noResults');

            function filterAnnouncements() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value;
                const items = document.querySelectorAll('.announcement-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const title = item.querySelector('.card-title').textContent.toLowerCase();
                    const content = item.querySelector('.card-text').textContent.toLowerCase();
                    const category = item.dataset.category;

                    const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm);
                    const matchesCategory = !selectedCategory || category === selectedCategory;

                    if (matchesSearch && matchesCategory) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    announcementsList.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    announcementsList.style.display = 'flex';
                    noResults.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', filterAnnouncements);
            categoryFilter.addEventListener('change', filterAnnouncements);
        });
    </script>
{/block}