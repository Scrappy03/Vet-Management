<?php
// Include necessary files
require_once(__DIR__ . '/../includes/boot.include.php');

// Page-specific data
$page_title = "Announcements - VetCare Clinic";
$page_description = "VetCare Announcements - Latest updates and news for veterinary staff";
$page_keywords = "veterinary announcements, clinic updates, staff news, animal hospital notices";

// Sample announcements data - you can modify this to fetch from database later
$announcements = [
    [
        'id' => 1,
        'title' => 'New Equipment Installation',
        'date' => '2024-06-03',
        'date_formatted' => date('F j, Y', strtotime('2024-06-03')),
        'category' => 'Equipment',
        'priority' => 'high',
        'content' => 'The new digital X-ray machine has been installed in examination room 3. All staff will receive training next week.',
        'author' => 'Dr. Sarah Johnson'
    ],
    [
        'id' => 2,
        'title' => 'Holiday Schedule Update',
        'date' => '2024-06-02',
        'date_formatted' => date('F j, Y', strtotime('2024-06-02')),
        'category' => 'Schedule',
        'priority' => 'medium',
        'content' => 'Please note the updated holiday schedule for the summer months. Check the staff board for your assigned shifts.',
        'author' => 'Administration'
    ],
    [
        'id' => 3,
        'title' => 'New Emergency Protocols',
        'date' => '2024-06-01',
        'date_formatted' => date('F j, Y', strtotime('2024-06-01')),
        'category' => 'Procedures',
        'priority' => 'high',
        'content' => 'Updated emergency response protocols are now in effect. All staff must review the new procedures by end of week.',
        'author' => 'Dr. Michael Chen'
    ],
    [
        'id' => 4,
        'title' => 'Staff Meeting - June 15th',
        'date' => '2024-05-30',
        'date_formatted' => date('F j, Y', strtotime('2024-05-30')),
        'category' => 'Meeting',
        'priority' => 'medium',
        'content' => 'Monthly staff meeting scheduled for June 15th at 2:00 PM in the conference room. Agenda will be sent via email.',
        'author' => 'Administration'
    ]
];

// Assign data to template
$Smarty->assign('page_title', $page_title);
$Smarty->assign('page_description', $page_description);
$Smarty->assign('page_keywords', $page_keywords);
$Smarty->assign('announcements', $announcements);
$Smarty->assign('view_name', 'announcements');

// Template will be displayed by index.php
?>
