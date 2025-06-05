<?php
/* Smarty version 5.5.0, created on 2025-06-05 13:34:35
  from 'file:pages/support.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.0',
  'unifunc' => 'content_68419ceb63b602_58726112',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3385d98be7f582386e97b37099bc299275c6d36c' => 
    array (
      0 => 'pages/support.tpl',
      1 => 1749081767,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68419ceb63b602_58726112 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/pages';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_178203014468419ce0deee28_64208322', "body");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "body"} */
class Block_178203014468419ce0deee28_64208322 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/pages';
?>

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
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-0"><i class="bi bi-question-circle me-2"></i>Support Center</h1>
                        <p class="text-muted">Get help with VetCare systems and procedures</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Contact Support</h3>
                <div class="row g-4">
                    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('support_categories'), 'category');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('category')->value) {
$foreach0DoElse = false;
?>
                        <div class="col-md-6 col-xl-3">
                            <div class="card h-100 support-category-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-<?php echo $_smarty_tpl->getValue('category')['icon'];?>
 display-4 mb-3 text-primary"></i>
                                    <h5 class="card-title"><?php echo $_smarty_tpl->getValue('category')['title'];?>
</h5>
                                    <p class="card-text"><?php echo $_smarty_tpl->getValue('category')['description'];?>
</p>
                                    <a href="mailto:<?php echo $_smarty_tpl->getValue('category')['contact'];?>
" class="btn btn-outline-primary">
                                        <i class="bi bi-envelope me-1"></i>Contact
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
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
                    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('faq_items'), 'faq', false, 'index');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('index')->value => $_smarty_tpl->getVariable('faq')->value) {
$foreach1DoElse = false;
?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $_smarty_tpl->getValue('index');?>
">
                                <button class="accordion-button <?php if ($_smarty_tpl->getValue('index') != 0) {?>collapsed<?php }?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $_smarty_tpl->getValue('index');?>
"
                                    aria-expanded="<?php if ($_smarty_tpl->getValue('index') == 0) {?>true<?php } else { ?>false<?php }?>" aria-controls="collapse<?php echo $_smarty_tpl->getValue('index');?>
">
                                    <?php echo $_smarty_tpl->getValue('faq')['question'];?>

                                </button>
                            </h2>
                            <div id="collapse<?php echo $_smarty_tpl->getValue('index');?>
" class="accordion-collapse collapse <?php if ($_smarty_tpl->getValue('index') == 0) {?>show<?php }?>"
                                aria-labelledby="heading<?php echo $_smarty_tpl->getValue('index');?>
" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo $_smarty_tpl->getValue('faq')['answer'];?>

                                </div>
                            </div>
                        </div>
                    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
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
<?php
}
}
/* {/block "body"} */
}
