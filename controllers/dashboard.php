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

// Weather API integration
$weatherApiKey = defined('WEATHER_API_KEY') ? WEATHER_API_KEY : '';
$city = defined('WEATHER_DEFAULT_CITY') ? WEATHER_DEFAULT_CITY : 'Ipswich';
if (!empty($weatherApiKey)) {
    $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$weatherApiKey}&units=metric";

    try {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // 5 second timeout
                'method' => 'GET',
                'header' => 'User-Agent: VetCare Dashboard/1.0'
            ]
        ]);
        
        $weatherResponse = file_get_contents($weatherUrl, false, $context);
        
        if ($weatherResponse !== false) {
            $weatherData = json_decode($weatherResponse, true);
            
            // Check for API errors
            if ($weatherData && isset($weatherData['cod']) && $weatherData['cod'] !== 200) {
                $errorMessage = isset($weatherData['message']) ? $weatherData['message'] : 'Unknown API error';
                error_log("Weather API error: Code {$weatherData['cod']} - {$errorMessage}");
                
                // Generate seasonal reminders for error fallback
                $currentMonth = date('n');
                $seasonalReminders = [];
                switch ($currentMonth) {
                    case 12:
                    case 1:
                    case 2: // Winter
                        $seasonalReminders[] = "Winter season: Check heating systems, arthritis flare-ups common";
                        $seasonalReminders[] = "Salt and antifreeze toxicity awareness";
                        break;
                    case 3:
                    case 4:
                    case 5: // Spring
                        $seasonalReminders[] = "Spring season: Flea and tick prevention time";
                        $seasonalReminders[] = "Allergy season beginning - watch for skin irritation";
                        $seasonalReminders[] = "Spring vaccinations due for many pets";
                        break;
                    case 6:
                    case 7:
                    case 8: // Summer
                        $seasonalReminders[] = "Summer season: Peak flea/tick activity";
                        $seasonalReminders[] = "Heartworm prevention critical";
                        $seasonalReminders[] = "Swimming safety and ear infections";
                        break;
                    case 9:
                    case 10:
                    case 11: // Fall
                        $seasonalReminders[] = "Fall season: Prepare for winter health checks";
                        $seasonalReminders[] = "Senior pet wellness exams recommended";
                        $seasonalReminders[] = "Back-to-school routine adjustments for pets";
                        break;
                }
                
                // Provide fallback weather info with error message
                $weatherInfo = [
                    'temperature' => '--',
                    'feels_like' => '--',
                    'humidity' => '--',
                    'condition' => 'API Error',
                    'description' => "API Error: {$errorMessage}",
                    'icon' => '01d',
                    'alerts' => [[
                        'type' => 'api_error',
                        'message' => "Weather API Error: {$errorMessage}. Please check your API key.",
                        'priority' => 'high',
                        'icon' => 'exclamation-triangle'
                    ]],
                    'seasonal_reminders' => $seasonalReminders,
                    'activity_recommendation' => 'Check weather manually',
                    'city' => $city,
                    'last_updated' => date('H:i'),
                    'api_error' => true
                ];
                
                $Smarty->assign('weather', $weatherInfo);
                return; // Exit early on API error
            }
            
            if ($weatherData && isset($weatherData['main'])) {
                $temperature = round($weatherData['main']['temp']);
                $feelsLike = round($weatherData['main']['feels_like']);
                $humidity = $weatherData['main']['humidity'];
                $condition = $weatherData['weather'][0]['main'];
                $description = ucfirst($weatherData['weather'][0]['description']);
                $icon = $weatherData['weather'][0]['icon'];
                
                // Create weather-based veterinary alerts and recommendations
                $weatherAlerts = [];
                $seasonalReminders = [];
                
                // Temperature-based alerts
                if ($temperature > 25) {
                    $weatherAlerts[] = [
                        'type' => 'heat_warning',
                        'message' => "High temperature ({$temperature}°C) - Monitor pets for heat stress and dehydration",
                        'priority' => 'high',
                        'icon' => 'thermometer-high'
                    ];
                } elseif ($temperature < 5) {
                    $weatherAlerts[] = [
                        'type' => 'cold_warning',
                        'message' => "Cold weather ({$temperature}°C) - Watch for hypothermia in small/elderly pets",
                        'priority' => 'medium',
                        'icon' => 'thermometer-snow'
                    ];
                }
                
                // Humidity-based alerts
                if ($humidity > 80) {
                    $weatherAlerts[] = [
                        'type' => 'humidity_warning',
                        'message' => "High humidity ({$humidity}%) - Increased risk of skin conditions",
                        'priority' => 'low',
                        'icon' => 'droplet'
                    ];
                }
                
                // Seasonal reminders based on current month
                $currentMonth = date('n');
                switch ($currentMonth) {
                    case 12:
                    case 1:
                    case 2: // Winter
                        $seasonalReminders[] = "Winter season: Check heating systems, arthritis flare-ups common";
                        $seasonalReminders[] = "Salt and antifreeze toxicity awareness";
                        break;
                    case 3:
                    case 4:
                    case 5: // Spring
                        $seasonalReminders[] = "Spring season: Flea and tick prevention time";
                        $seasonalReminders[] = "Allergy season beginning - watch for skin irritation";
                        $seasonalReminders[] = "Spring vaccinations due for many pets";
                        break;
                    case 6:
                    case 7:
                    case 8: // Summer
                        $seasonalReminders[] = "Summer season: Peak flea/tick activity";
                        $seasonalReminders[] = "Heartworm prevention critical";
                        $seasonalReminders[] = "Swimming safety and ear infections";
                        break;
                    case 9:
                    case 10:
                    case 11: // Fall
                        $seasonalReminders[] = "Fall season: Prepare for winter health checks";
                        $seasonalReminders[] = "Senior pet wellness exams recommended";
                        $seasonalReminders[] = "Back-to-school routine adjustments for pets";
                        break;
                }
                
                // Activity recommendations
                $activityRecommendation = "Moderate exercise recommended";
                if ($temperature > 30) {
                    $activityRecommendation = "Limit outdoor activity - risk of overheating";
                } elseif ($temperature < 0) {
                    $activityRecommendation = "Short walks only - protect paws from ice/salt";
                } elseif ($condition === 'Rain') {
                    $activityRecommendation = "Indoor activities preferred";
                } elseif ($temperature >= 15 && $temperature <= 25) {
                    $activityRecommendation = "Perfect weather for walks and outdoor activities";
                }
                
                $weatherInfo = [
                    'temperature' => $temperature,
                    'feels_like' => $feelsLike,
                    'humidity' => $humidity,
                    'condition' => $condition,
                    'description' => $description,
                    'icon' => $icon,
                    'alerts' => $weatherAlerts,
                    'seasonal_reminders' => $seasonalReminders,
                    'activity_recommendation' => $activityRecommendation,
                    'city' => $city,
                    'last_updated' => date('H:i')
                ];
                
                $Smarty->assign('weather', $weatherInfo);
                
                // Log weather data for debugging
                error_log("Weather data retrieved successfully for {$city}: {$temperature}°C, {$condition}");
            } else {
                error_log("Weather API: Invalid response format");
            }
        } else {
            error_log("Weather API: Failed to fetch data");
        }
    } catch (Exception $e) {
        error_log("Weather API error: " . $e->getMessage());
    }
} else {
    // API key not configured - provide sample data for demonstration
    $weatherInfo = [
        'temperature' => 18,
        'feels_like' => 16,
        'humidity' => 65,
        'condition' => 'Clear',
        'description' => 'Clear sky',
        'icon' => '01d',
        'alerts' => [],
        'seasonal_reminders' => [
            'Spring season: Flea and tick prevention time',
            'Allergy season beginning - watch for skin irritation',
            'Spring vaccinations due for many pets'
        ],
        'activity_recommendation' => 'Perfect weather for walks and outdoor activities',
        'city' => $city,
        'last_updated' => date('H:i'),
        'demo_mode' => true
    ];
    
    $Smarty->assign('weather', $weatherInfo);
    error_log("Weather widget: Using demo data - API not configured");
}