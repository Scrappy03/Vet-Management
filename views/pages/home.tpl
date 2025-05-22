{extends file="layouts/main.tpl"}
{block name="body"}
    <!-- Main navigation bar for the staff portal. -->
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
                        <a class="nav-link active" href="#"><i class="bi bi-house-door me-1"></i>Portal Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#support"><i class="bi bi-question-circle me-1"></i>Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#announcements"><i class="bi bi-megaphone me-1"></i>Announcements</a>
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

    <!-- A carousel component to showcase images. -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./images/MedicineDog.webp" class="d-block w-100" alt="Veterinary Clinic Reception"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Welcome to VetCare</h2>
                    <p>Your trusted veterinary management system</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/Geko.webp" class="d-block w-100" alt="Examination Room"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Modern Facilities</h2>
                    <p>State-of-the-art equipment and facilities</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/Puppy.webp" class="d-block w-100" alt="Staff Meeting"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Dedicated Team</h2>
                    <p>Professional staff committed to excellence</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container py-5">
        <!-- Welcome section prompting users to login or get help. -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-4">Staff Portal</h1>
                <p class="lead mb-4">Welcome to the VetCare staff management system. Please login to access your
                    dashboard.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="dashboard" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Access Dashboard
                    </a>
                    <a href="#support" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-headset me-2"></i>Get Help
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick access cards for schedule, resources, and updates. -->
        <div class="row g-4 mb-5" id="features">
            <div class="col-md-4">
                <div class="card h-100 quick-access-card">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check display-4 mb-3 text-primary"></i>
                        <h3>Today's Schedule</h3>
                    <p>Quick access to today's appointments and schedules.</p>
                        <a href="/schedule" class="btn btn-outline-primary mt-3">View Schedule</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 quick-access-card">
                    <div class="card-body text-center">
                        <i class="bi bi-person-badge display-4 mb-3 text-primary"></i>
                        <h3>Staff Resources</h3>
                        <p>Access training materials, policies, and procedures.</p>
                        <a href="/resources" class="btn btn-outline-primary mt-3">View Resources</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 quick-access-card">
                    <div class="card-body text-center">
                        <i class="bi bi-bell display-4 mb-3 text-primary"></i>
                        <h3>Recent Updates</h3>
                        <p>View the latest system updates and announcements.</p>
                        <a href="/updates" class="btn btn-outline-primary mt-3">View Updates</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer with support contact info and © notice. -->
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
    </div>
{/block}