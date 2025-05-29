<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Development diagnostics
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_log("Dashboard: Session state: " . print_r($_SESSION, true));
}

// Try to get user from session first
$user_data = get_current_user();

// Direct database query regardless of session state
if (isset($_SESSION['user_id'])) {
    // Get fresh user data directly from database
    $User = new User($Conn);
    $fresh_user_data = $User->getUserById($_SESSION['user_id']);
    
    if ($fresh_user_data) {
        // Use database data instead of session data
        $user_data = $fresh_user_data;
        
        // Update session with fresh data
        $_SESSION['user_data'] = $fresh_user_data;
    }
}

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Log what we found for debugging
error_log("Dashboard user data: " . print_r($user_data, true));
error_log("First name: '$first_name', Last name: '$last_name', Display name: '$user_name'");

// Template data binding
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);

// Get appointment data for the dashboard
$Appointment = new Appointment($Conn);

// Get today's date in the format required for the database
$today = date('Y-m-d');

// Get appointment statistics
$stats = $Appointment->getAppointmentStats($today);

// Assign stats to template
$Smarty->assign('today_appointments_count', $stats['total']);
$Smarty->assign('completed_appointments', $stats['completed']);
$Smarty->assign('upcoming_appointments', $stats['upcoming']);

// Get upcoming appointments
$upcomingAppointments = $Appointment->getUpcomingAppointments(5);
$Smarty->assign('appointments', $upcomingAppointments);

// Get dashboard notifications
$notifications = $Appointment->getDashboardNotifications();

// Format notification timestamps
foreach ($notifications as &$notification) {
    if (isset($notification['timestamp'])) {
        $notification['formatted_time'] = date('H:i', strtotime($notification['timestamp']));
    }
}
unset($notification);

$Smarty->assign('notifications', $notifications);

// Get today's schedule summary
$scheduleToday = $Appointment->getTodayScheduleSummary();

// Format schedule times
if ($scheduleToday && isset($scheduleToday['first_appointment'])) {
    $scheduleToday['first_appointment_time'] = date('H:i', strtotime($scheduleToday['first_appointment']));
}
if ($scheduleToday && isset($scheduleToday['last_appointment'])) {
    $scheduleToday['last_appointment_time'] = date('H:i', strtotime($scheduleToday['last_appointment']));
}

$Smarty->assign('schedule_summary', $scheduleToday);

// Get recent activity
$recentActivity = $Appointment->getRecentActivity(8);

// Format activity times
foreach ($recentActivity as &$activity) {
    if (isset($activity['start_time'])) {
        $activity['start_time_formatted'] = date('H:i', strtotime($activity['start_time']));
    }
    if (isset($activity['end_time'])) {
        $activity['end_time_formatted'] = date('H:i', strtotime($activity['end_time']));
    }
}
unset($activity);

$Smarty->assign('recent_activity', $recentActivity);

// Count urgent notifications
$urgentCount = 0;
foreach ($notifications as $notification) {
    if ($notification['priority'] === 'urgent' || $notification['priority'] === 'high') {
        $urgentCount++;
    }
}
$Smarty->assign('urgent_notifications_count', $urgentCount);

// Get mini calendar data for current month
$calendarData = $Appointment->getMiniCalendarData();
$Smarty->assign('calendar_data', $calendarData);