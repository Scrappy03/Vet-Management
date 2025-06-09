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
                        <a class="nav-link" href="home"><i class="bi bi-house-door me-1"></i>Portal Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="bi bi-question-circle me-1"></i>Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements"><i class="bi bi-megaphone me-1"></i>Announcements</a>
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

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h1 class="display-4">
                        <i class="bi bi-question-circle me-3 text-primary"></i>Support Center
                    </h1>
                    <p class="lead text-muted">Get help with VetCare systems and procedures</p>
                </div>
            </div>
        </div>

        <!-- Support Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Contact Support</h3>
                <div class="row g-4">
                    {foreach $support_categories as $category}
                        <div class="col-md-6 col-xl-3">
                            <div class="card h-100 support-category-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-{$category.icon} display-4 mb-3 text-primary"></i>
                                    <h5 class="card-title">{$category.title}</h5>
                                    <p class="card-text">{$category.description}</p>
                                    <a href="mailto:{$category.contact}" class="btn btn-outline-primary">
                                        <i class="bi bi-envelope me-1"></i>Contact
                                    </a>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>

        <!-- Emergency Contact Banner -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="alert-heading mb-1">
                                <i class="bi bi-exclamation-triangle me-2"></i>Emergency Support
                            </h5>
                            <p class="mb-0">For urgent technical issues that affect patient care, call our emergency hotline
                                immediately.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                            <a href="tel:+448001234567" class="btn btn-warning">
                                <i class="bi bi-telephone me-1"></i>+44 800 123 4567
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Frequently Asked Questions</h3>
                <div class="accordion" id="faqAccordion">
                    {foreach $faq_items as $index => $faq}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{$index}">
                                <button class="accordion-button {if $index != 0}collapsed{/if}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{$index}"
                                    aria-expanded="{if $index == 0}true{else}false{/if}" aria-controls="collapse{$index}">
                                    {$faq.question}
                                </button>
                            </h2>
                            <div id="collapse{$index}" class="accordion-collapse collapse {if $index == 0}show{/if}"
                                aria-labelledby="heading{$index}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {$faq.answer}
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>

        <!-- Additional Resources Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Additional Resources</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-book me-2 text-primary"></i>User Manual
                                </h5>
                                <p class="card-text">Complete guide to using VetCare systems</p>
                                <a href="#" class="btn btn-outline-primary">Download PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-play-circle me-2 text-primary"></i>Video Tutorials
                                </h5>
                                <p class="card-text">Step-by-step video guides for common tasks</p>
                                <a href="#" class="btn btn-outline-primary">Watch Videos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-chat-dots me-2 text-primary"></i>Live Chat
                                </h5>
                                <p class="card-text">Real-time support during business hours</p>
                                <a href="#" class="btn btn-outline-primary">Start Chat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .support-category-card {
            transition: transform 0.2s ease-in-out;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .support-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--bs-primary);
            color: white;
        }
    </style>
{/block}