{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}
    <div class="dashboard-sidebar">
        <div class="text-center mb-4 content-transition">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4 content-transition">
            <a href="dashboard" class="dashboard-nav-link active">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="calendar" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="patients" class="dashboard-nav-link">
                <i class="bi bi-clipboard2-pulse"></i>
                Patients
            </a>
            <a href="staff" class="dashboard-nav-link">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="settings" class="dashboard-nav-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>

        <div class="mt-auto">
            <a href="logout" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <main class="dashboard-main">
        <div class="d-flex justify-content-between align-items-center mb-4 content-transition">
            <div>
                <h4 class="mb-0">Dashboard Overview</h4>
                <p class="text-muted mb-0">Welcome,
                    {if isset($user_name) && $user_name != ''}{$user_name}{elseif isset($user.email)}{$user.email}{else}User{/if}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="calendar" class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> New Appointment
                </a>
                <a href="patients" class="btn btn-outline-primary">
                    <i class="bi bi-person-plus"></i> New Patient
                </a>
            </div>
        </div>

        <!-- Notifications Panel -->
        {if isset($notifications) && $notifications}
            <div class="card mb-4 border-warning" id="notificationsPanel">
                <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-warning">
                        <i class="bi bi-bell-fill me-2"></i>Notifications
                        {if isset($urgent_notifications_count) && $urgent_notifications_count > 0}
                            <span class="badge bg-danger">{$urgent_notifications_count}</span>
                        {/if}
                    </h5>
                    <button class="btn btn-sm btn-outline-warning" id="dismissAllNotifications">
                        <i class="bi bi-x-lg"></i> Dismiss All
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="notification-list" id="notificationsList">
                        {foreach from=$notifications item=notification}
                            <div class="notification-item {if $notification.priority == 'urgent'}priority-urgent{elseif $notification.priority == 'high'}priority-high{else}priority-medium{/if}"
                                data-appointment-id="{$notification.appointment_id}">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon me-3">
                                        {if $notification.type == 'overdue'}
                                            <i class="bi bi-clock-history text-danger"></i>
                                        {elseif $notification.type == 'upcoming'}
                                            <i class="bi bi-clock text-warning"></i>
                                        {elseif $notification.type == 'emergency'}
                                            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                        {elseif $notification.type == 'follow_up'}
                                            <i class="bi bi-arrow-repeat text-info"></i>
                                        {/if}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="notification-title mb-1">{$notification.title}</h6>
                                        <p class="notification-message mb-1">{$notification.message}</p>
                                        <small class="text-muted">{$notification.formatted_time}</small>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                            onclick="viewAppointmentDetails({$notification.appointment_id})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dismissNotification(this)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        {/if}

        <!-- Enhanced search form -->
        <div class="card mb-4">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-search me-2"></i>Search Appointments
                </h5>
                <button class="btn btn-sm btn-outline-secondary" id="toggleAdvancedSearch">
                    <i class="bi bi-sliders me-1"></i>Advanced Filters
                </button>
                <div class="btn-group ms-2" role="group" aria-label="Quick search presets">
                    <button type="button" class="btn btn-sm btn-outline-info" id="quickSearchToday">Today</button>
                    <button type="button" class="btn btn-sm btn-outline-info" id="quickSearchUrgent">Urgent</button>
                    <button type="button" class="btn btn-sm btn-outline-info" id="quickSearchFollowUp">Follow-ups</button>
                </div>
            </div>
            <div class="card-body">
                <form class="row g-3" id="searchForm">
                    <!-- Basic search row -->
                    <div class="col-md-6">
                        <label for="searchQuery" class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="searchQuery"
                                placeholder="Search by pet name, owner, type, phone, staff...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="searchType" class="form-label">Status</label>
                        <select class="form-select" id="searchType">
                            <option value="all">All Statuses</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                    </div>

                    <!-- Advanced search filters (hidden by default) -->
                    <div id="advancedFilters" class="col-12" style="display: none;">
                        <hr class="my-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <select class="form-select" id="dateRange">
                                    <option value="">Custom Date</option>
                                    <option value="today">Today</option>
                                    <option value="tomorrow">Tomorrow</option>
                                    <option value="this_week">This Week</option>
                                    <option value="next_week">Next Week</option>
                                    <option value="this_month">This Month</option>
                                    <option value="next_month">Next Month</option>
                                    <option value="past_week">Past Week</option>
                                    <option value="past_month">Past Month</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="searchDate" class="form-label">Specific Date</label>
                                <input type="date" class="form-control" id="searchDate">
                            </div>
                            <div class="col-md-3">
                                <label for="speciesFilter" class="form-label">Species</label>
                                <select class="form-select" id="speciesFilter">
                                    <option value="all">All Species</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="appointmentTypeFilter" class="form-label">Appointment Type</label>
                                <select class="form-select" id="appointmentTypeFilter">
                                    <option value="all">All Types</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="ageRangeFilter" class="form-label">Pet Age Range</label>
                                <select class="form-select" id="ageRangeFilter">
                                    <option value="">All Ages</option>
                                    <option value="young">Young (0-2 years)</option>
                                    <option value="adult">Adult (3-7 years)</option>
                                    <option value="senior">Senior (8+ years)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="urgencyFilter" class="form-label">Urgency Level</label>
                                <select class="form-select" id="urgencyFilter">
                                    <option value="">All Urgency Levels</option>
                                    <option value="routine">Routine</option>
                                    <option value="follow_up">Follow-up</option>
                                    <option value="urgent">Urgent/Emergency</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sortBy" class="form-label">Sort By</label>
                                <select class="form-select" id="sortBy">
                                    <option value="start_time">Date & Time</option>
                                    <option value="pet_name">Pet Name</option>
                                    <option value="owner_name">Owner Name</option>
                                    <option value="appointment_type">Appointment Type</option>
                                    <option value="status">Status</option>
                                    <option value="species">Species</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sortOrder" class="form-label">Sort Order</label>
                                <select class="form-select" id="sortOrder">
                                    <option value="ASC">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="searchLimit" class="form-label">Results Limit</label>
                                <select class="form-select" id="searchLimit">
                                    <option value="25">25 results</option>
                                    <option value="50">50 results</option>
                                    <option value="100" selected>100 results</option>
                                    <option value="200">200 results</option>
                                    <option value="0">No limit</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-none d-md-block">&nbsp;</label>
                                <button type="button" class="btn btn-outline-secondary w-100" id="resetFilters">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results Section (hidden by default) -->
        <div class="card mb-4" id="searchResultsCard" style="display: none;">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Search Results</h5>
                    <small class="text-muted" id="searchResultsCount">Loading...</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-success me-2" id="showStatsBtn" style="display: none;">
                        <i class="bi bi-bar-chart me-1"></i>Stats
                    </button>
                    <button class="btn btn-sm btn-outline-info me-2" id="exportResultsBtn">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSearchBtn">
                        <i class="bi bi-x-circle me-1"></i>Clear Results
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pet Name</th>
                                <th>Owner</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Species</th>
                                <th>Status</th>
                                <th>Staff</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="searchResults">
                            <!-- Results will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3 class="fs-4 mb-1">{if isset($today_appointments_count)}{$today_appointments_count}{else}0{/if}</h3>
                    <p class="text-muted mb-0">Today's Appointments</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-capsule"></i>
                </div>
                <h3 class="fs-4 mb-1">10</h3>
                <p class="text-muted mb-0">Prescription Requests</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fs-4 mb-1">{if isset($completed_appointments)}{$completed_appointments}{else}0{/if}</h3>
                <p class="text-muted mb-0">Appointments Completed</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <h3 class="fs-4 mb-1">{if isset($upcoming_appointments)}{$upcoming_appointments}{else}0{/if}</h3>
                <p class="text-muted mb-0">Appointments Pending</p>
            </div>
        </div>
    </div>

    <!-- Weather Widget -->
    {if isset($weather)}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-info">
                <div class="card-header bg-info bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info">
                        <i class="bi bi-cloud-sun me-2"></i>Weather & Veterinary Alerts
                        {if isset($weather.demo_mode) && $weather.demo_mode}
                        <span class="badge bg-secondary ms-2">Demo Mode</span>
                        {elseif isset($weather.api_error) && $weather.api_error}
                        <span class="badge bg-danger ms-2">API Error</span>
                        {/if}
                    </h5>
                    <small class="text-muted">
                        {$weather.city} • Updated: {$weather.last_updated}
                        {if isset($weather.demo_mode) && $weather.demo_mode}
                        <br><em>Configure OpenWeatherMap API key for live data</em>
                        {elseif isset($weather.api_error) && $weather.api_error}
                        <br><em>Please check your API key configuration</em>
                        {/if}
                    </small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Current Weather -->
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="weather-icon me-3">
                                    <img src="https://openweathermap.org/img/w/{$weather.icon}.png"
                                        alt="{$weather.description}" class="img-fluid">
                                </div>
                                <div>
                                    <h3 class="mb-0">{$weather.temperature}°C</h3>
                                    <p class="text-muted mb-0">{$weather.description}</p>
                                    <small class="text-muted">Feels like {$weather.feels_like}°C</small>
                                </div>
                            </div>
                        </div>

                        <!-- Weather Details -->
                        <div class="col-md-4">
                            <div class="weather-details">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Humidity:</span>
                                    <span>{$weather.humidity}%</span>
                                </div>
                                <div class="weather-activity">
                                    <small class="text-muted d-block">Activity Recommendation:</small>
                                    <span class="badge bg-secondary">{$weather.activity_recommendation}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Weather Alerts -->
                        <div class="col-md-4">
                            {if $weather.alerts}
                            <h6 class="text-warning mb-2">
                                <i class="bi bi-exclamation-triangle"></i> Alerts
                            </h6>
                            {foreach from=$weather.alerts item=alert}
                            <div
                                class="alert alert-{if $alert.priority == 'high'}warning{elseif $alert.priority == 'medium'}info{else}light{/if} py-2 px-3 mb-2">
                                <i class="bi bi-{$alert.icon} me-1"></i>
                                <small>{$alert.message}</small>
                            </div>
                            {/foreach}
                            {else}
                            <div class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                <small>No weather alerts</small>
                            </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seasonal Reminders -->
        <div class="col-lg-4">
            <div class="card border-success h-100">
                <div class="card-header bg-success bg-opacity-10">
                    <h6 class="mb-0 text-success">
                        <i class="bi bi-calendar4-week me-2"></i>Seasonal Veterinary Reminders
                    </h6>
                </div>
                <div class="card-body">
                    {if $weather.seasonal_reminders}
                    <ul class="list-unstyled mb-0">
                        {foreach from=$weather.seasonal_reminders item=reminder}
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            <small>{$reminder}</small>
                        </li>
                        {/foreach}
                    </ul>
                    {else}
                    <p class="text-muted mb-0">
                        <small>No seasonal reminders at this time.</small>
                    </p>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    {/if}

    <!-- Today's Schedule Summary -->
        {if isset($schedule_summary)}
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-calendar-day me-2"></i>Today's Schedule
                    </h5>
                    <span class="badge bg-primary">{$schedule_summary.total_appointments} Total</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 p-2 rounded me-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{$schedule_summary.completed}</div>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                                    <i class="bi bi-clock text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{$schedule_summary.upcoming}</div>
                                    <small class="text-muted">Upcoming</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                                    <i class="bi bi-play-circle text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{$schedule_summary.in_progress}</div>
                                    <small class="text-muted">In Progress</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 p-2 rounded me-2">
                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{$schedule_summary.urgent}</div>
                                    <small class="text-muted">Urgent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    {if $schedule_summary.first_appointment && $schedule_summary.last_appointment}
                    <hr class="my-3">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>First: {$schedule_summary.first_appointment_time}</span>
                        <span>Last: {$schedule_summary.last_appointment_time}</span>
                    </div>
                    {/if}
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-info bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info">
                        <i class="bi bi-activity me-2"></i>Recent Activity
                    </h5>
                    <button class="btn btn-sm btn-outline-info" onclick="refreshActivity()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed" id="activityFeed">
                        {if isset($recent_activity) && $recent_activity}
                        {foreach from=$recent_activity item=activity}
                        <div class="activity-item">
                            <div class="activity-icon">
                                {if $activity.status == 'completed'}
                                <i class="bi bi-check-circle-fill text-success"></i>
                                {elseif $activity.status == 'cancelled'}
                                <i class="bi bi-x-circle-fill text-danger"></i>
                                {elseif $activity.status == 'in_progress'}
                                <i class="bi bi-play-circle-fill text-warning"></i>
                                {else}
                                <i class="bi bi-clock-fill text-info"></i>
                                {/if}
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    {$activity.pet_name} - <span
                                        style="text-transform: capitalize;">{$activity.appointment_type}</span>
                                </div>
                                <div class="activity-subtitle">
                                    {$activity.owner_name}
                                    {if $activity.staff_name} • {$activity.staff_name}{/if}
                                </div>
                                <div class="activity-time">
                                    {if $activity.status == 'completed'}
                                    Completed {$activity.end_time_formatted}
                                    {elseif $activity.status == 'cancelled'}
                                    Cancelled
                                    {else}
                                    {$activity.start_time_formatted}
                                    {/if}
                                </div>
                            </div>
                            <div class="activity-actions">
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="viewAppointmentDetails({$activity.appointment_id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        {/foreach}
                        {else}
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-calendar-x fs-1 mb-2"></i>
                            <p class="mb-0">No recent activity</p>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="calendar" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 appointments-table">
                        <thead>
                            <tr>
                                <th>Pet Name</th>
                                <th>Owner</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {if isset($appointments) && $appointments}
                            {foreach from=$appointments item=appointment}
                            <tr>
                                <td>{$appointment.pet_name}</td>
                                <td>{$appointment.owner}</td>
                                <td>{$appointment.date}</td>
                                <td>{$appointment.time}</td>
                                <td>{$appointment.type}</td>
                                <td><span class="status-badge status-{$appointment.status}">
                                        {if isset($appointment.status)}{$appointment.status}{/if}
                                    </span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-appointment"
                                        data-id="{$appointment.id}">View</button>
                                </td>
                            </tr>
                            {/foreach}
                            {else}
                            <tr>
                                <td colspan="7" class="text-center py-3">No upcoming appointments found</td>
                            </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Today's Reminders Card -->
                    <div class="card">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Today's Reminders</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#addReminderModal">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="reminders-list">
                        <div class="reminder-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="reminder1">
                                <label class="form-check-label" for="reminder1">
                                    Call Mr. Smith about Alfie's medication
                                        </label>
                                    </div>
                                    <small class="text-muted">10:00 AM</small>
                                </div>
                                <div class="reminder-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="reminder2">
                                        <label class="form-check-label" for="reminder2">
                                            Order new vaccine supplies
                                        </label>
                                    </div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                                <div class="reminder-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="reminder3">
                                        <label class="form-check-label" for="reminder3">
                                            Staff meeting - Q3 Planning
                                        </label>
                                    </div>
                                    <small class="text-muted">4:30 PM</small>
                                </div>
                            </div>
        </main>

        <!-- Add Reminder Modal -->
        <div class="modal fade" id="addReminderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Reminder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="reminderText" class="form-label">Reminder</label>
                                <input type="text" class="form-control" id="reminderText">
                            </div>
                            <div class="mb-3">
                                <label for="reminderTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="reminderTime">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Add Reminder</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Statistics Modal -->
        <div class="modal fade" id="searchStatsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search Results Statistics</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="searchStatsContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Details Modal -->
        <div class="modal fade" id="appointmentDetailsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Appointment Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="appointmentDetailsContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="calendar" class="btn btn-primary">Go to Calendar</a>
                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
    {/block}