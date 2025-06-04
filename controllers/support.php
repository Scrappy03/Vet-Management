<?php
// Include necessary files
require_once(__DIR__ . '/../includes/boot.include.php');

// Page-specific data
$page_title = "Support - VetCare Clinic";
$page_description = "VetCare Support - Get help with the veterinary management system";
$page_keywords = "veterinary support, tech help, pet clinic support, animal hospital support";

// Support categories and FAQ data
$support_categories = [
    [
        'title' => 'Technical Support',
        'icon' => 'laptop',
        'description' => 'Hardware and software issues, system troubleshooting',
        'contact' => 'tech@vetcare.co.uk'
    ],
    [
        'title' => 'Account & Login',
        'icon' => 'person-circle',
        'description' => 'Password resets, account access, user permissions',
        'contact' => 'accounts@vetcare.co.uk'
    ],
    [
        'title' => 'Training & Procedures',
        'icon' => 'book',
        'description' => 'System training, new feature guidance, best practices',
        'contact' => 'training@vetcare.co.uk'
    ],
    [
        'title' => 'Emergency Support',
        'icon' => 'telephone',
        'description' => 'Urgent issues requiring immediate assistance',
        'contact' => '+44 800 123 4567'
    ]
];

$faq_items = [
    [
        'question' => 'How do I reset my password?',
        'answer' => 'Click the "Forgot Password" link on the login page and follow the instructions sent to your email.'
    ],
    [
        'question' => 'Why can\'t I access certain features?',
        'answer' => 'Access to features depends on your user role. Contact your administrator if you need additional permissions.'
    ],
    [
        'question' => 'How do I schedule an appointment?',
        'answer' => 'Go to the Calendar page and click on the desired date and time slot. Fill in the appointment details and save.'
    ],
    [
        'question' => 'Can I export patient data?',
        'answer' => 'Yes, patient data can be exported from the Patients page using the export button. Different formats are available.'
    ],
    [
        'question' => 'How do I update patient information?',
        'answer' => 'Navigate to the patient\'s profile page and click the "Edit" button. Make your changes and save.'
    ]
];

// Assign data to template
$Smarty->assign('page_title', $page_title);
$Smarty->assign('page_description', $page_description);
$Smarty->assign('page_keywords', $page_keywords);
$Smarty->assign('support_categories', $support_categories);
$Smarty->assign('faq_items', $faq_items);
$Smarty->assign('view_name', 'support');

// Template will be displayed by index.php
?>
