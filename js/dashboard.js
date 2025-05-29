document.addEventListener('DOMContentLoaded', function () {
    // Initialize reminder elements
    const remindersList = document.querySelector('.reminders-list');
    const reminderForm = document.querySelector('#addReminderModal form');
    const addReminderBtn = document.querySelector('#addReminderModal .btn-primary');

    // Handle adding new reminders
    addReminderBtn.addEventListener('click', function () {
        const reminderText = document.querySelector('#reminderText').value;
        const reminderTime = document.querySelector('#reminderTime').value;

        if (reminderText.trim() !== '') {
            const formattedTime = new Date(`2000/01/01 ${reminderTime}`).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });

            const newReminder = document.createElement('div');
            newReminder.className = 'reminder-item';
            newReminder.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="reminder${Date.now()}">
                    <label class="form-check-label" for="reminder${Date.now()}">
                        ${reminderText}
                    </label>
                </div>
                <small class="text-muted">${formattedTime}</small>
            `;

            remindersList.appendChild(newReminder);

            // Clear form and close modal
            reminderForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('addReminderModal')).hide();
        }
    });

    // Toggle reminder completed state
    remindersList.addEventListener('change', function (e) {
        if (e.target.matches('.form-check-input')) {
            const label = e.target.nextElementSibling;
            if (e.target.checked) {
                label.style.textDecoration = 'line-through';
                label.style.opacity = '0.7';
            } else {
                label.style.textDecoration = 'none';
                label.style.opacity = '1';
            }
        }
    });

    // Handle appointment view buttons
    document.querySelectorAll('.view-appointment').forEach(button => {
        button.addEventListener('click', function () {
            const appointmentId = this.getAttribute('data-id');
            viewAppointmentDetails(appointmentId);
        });
    });

    // Function to view appointment details
    function viewAppointmentDetails(appointmentId) {
        // Show loading indicator
        const modalContent = document.getElementById('appointmentDetailsContent');
        modalContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading appointment details...</p></div>';

        // Show the modal
        const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
        appointmentModal.show();

        // Fetch appointment details
        fetch(`api/appointments.php?id=${appointmentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Format date and time
                const startTime = new Date(data.start_time);
                const endTime = new Date(data.end_time);

                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const timeOptions = { hour: '2-digit', minute: '2-digit' };

                // Build HTML content
                let html = `
                    <div class="appointment-details">
                        <div class="mb-3">
                            <h6>Pet Information</h6>
                            <p class="mb-1"><strong>Name:</strong> ${data.pet_name}</p>
                            <p class="mb-1"><strong>Owner:</strong> ${data.owner_name}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Appointment Information</h6>
                            <p class="mb-1"><strong>Type:</strong> ${data.appointment_type}</p>
                            <p class="mb-1"><strong>Date:</strong> ${startTime.toLocaleDateString(undefined, dateOptions)}</p>
                            <p class="mb-1"><strong>Time:</strong> ${startTime.toLocaleTimeString(undefined, timeOptions)} - ${endTime.toLocaleTimeString(undefined, timeOptions)}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-${data.status === 'completed' ? 'success' : 'primary'}">${data.status}</span></p>
                        </div>`;

                if (data.notes) {
                    html += `
                        <div class="mb-3">
                            <h6>Notes</h6>
                            <p>${data.notes}</p>
                        </div>`;
                }

                if (data.care_status) {
                    html += `
                        <div class="mb-3">
                            <h6>Care Status</h6>
                            <p>${data.care_status}</p>
                        </div>`;
                }

                html += `</div>`;

                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching appointment details:', error);
                modalContent.innerHTML = `<div class="alert alert-danger">Failed to load appointment details: ${error.message}</div>`;
            });
    }

    // Initialize search functionality 
    initSearchForm();
    loadFilterOptions();

    function initSearchForm() {
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                performSearch();
            });
        }

        // Toggle advanced search filters
        const toggleAdvancedBtn = document.getElementById('toggleAdvancedSearch');
        const advancedFilters = document.getElementById('advancedFilters');
        if (toggleAdvancedBtn && advancedFilters) {
            toggleAdvancedBtn.addEventListener('click', function () {
                const isHidden = advancedFilters.style.display === 'none';
                advancedFilters.style.display = isHidden ? 'block' : 'none';
                this.innerHTML = isHidden ?
                    '<i class="bi bi-sliders me-1"></i>Hide Advanced' :
                    '<i class="bi bi-sliders me-1"></i>Advanced Filters';
            });
        }

        // Clear search results button
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function () {
                clearSearch();
            });
        }

        // Reset filters button
        const resetFiltersBtn = document.getElementById('resetFilters');
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function () {
                resetSearchForm();
            });
        }

        // Export results button
        const exportBtn = document.getElementById('exportResultsBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                exportSearchResults();
            });
        }

        // Show stats button
        const showStatsBtn = document.getElementById('showStatsBtn');
        if (showStatsBtn) {
            showStatsBtn.addEventListener('click', function () {
                showSearchStatistics();
            });
        }

        // Quick search presets
        const quickSearchToday = document.getElementById('quickSearchToday');
        if (quickSearchToday) {
            quickSearchToday.addEventListener('click', function () {
                setQuickSearch('today');
            });
        }

        const quickSearchUrgent = document.getElementById('quickSearchUrgent');
        if (quickSearchUrgent) {
            quickSearchUrgent.addEventListener('click', function () {
                setQuickSearch('urgent');
            });
        }

        const quickSearchFollowUp = document.getElementById('quickSearchFollowUp');
        if (quickSearchFollowUp) {
            quickSearchFollowUp.addEventListener('click', function () {
                setQuickSearch('follow_up');
            });
        }

        // Date range change handler
        const dateRangeSelect = document.getElementById('dateRange');
        const specificDateInput = document.getElementById('searchDate');
        if (dateRangeSelect && specificDateInput) {
            dateRangeSelect.addEventListener('change', function () {
                if (this.value) {
                    specificDateInput.value = '';
                    specificDateInput.disabled = true;
                } else {
                    specificDateInput.disabled = false;
                }
            });
        }

        // Real-time search on input (with debouncing)
        const searchInput = document.getElementById('searchQuery');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                if (this.value.length >= 3 || this.value.length === 0) {
                    searchTimeout = setTimeout(() => {
                        performSearch();
                    }, 500);
                }
            });
        }
    }

    function loadFilterOptions() {
        fetch('api/appointments.php?filter_options=true')
            .then(response => response.json())
            .then(data => {
                // Populate species filter
                const speciesSelect = document.getElementById('speciesFilter');
                if (speciesSelect && data.species) {
                    data.species.forEach(species => {
                        const option = document.createElement('option');
                        option.value = species;
                        option.textContent = species.charAt(0).toUpperCase() + species.slice(1);
                        speciesSelect.appendChild(option);
                    });
                }

                // Populate appointment type filter
                const typeSelect = document.getElementById('appointmentTypeFilter');
                if (typeSelect && data.appointment_types) {
                    data.appointment_types.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        typeSelect.appendChild(option);
                    });
                }

                // Update status filter with actual statuses from database
                const statusSelect = document.getElementById('searchType');
                if (statusSelect && data.statuses) {
                    // Clear existing options except "All Statuses"
                    while (statusSelect.children.length > 1) {
                        statusSelect.removeChild(statusSelect.lastChild);
                    }

                    data.statuses.forEach(status => {
                        const option = document.createElement('option');
                        option.value = status;
                        option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        statusSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading filter options:', error);
            });
    }

    function performSearch() {
        const query = document.getElementById('searchQuery').value;
        const type = document.getElementById('searchType').value;
        const date = document.getElementById('searchDate').value;
        const dateRange = document.getElementById('dateRange').value;
        const species = document.getElementById('speciesFilter')?.value || 'all';
        const appointmentType = document.getElementById('appointmentTypeFilter')?.value || 'all';
        const ageRange = document.getElementById('ageRangeFilter')?.value || '';
        const urgency = document.getElementById('urgencyFilter')?.value || '';
        const sortBy = document.getElementById('sortBy')?.value || 'start_time';
        const sortOrder = document.getElementById('sortOrder')?.value || 'ASC';
        const limit = document.getElementById('searchLimit')?.value || '100';

        // Show loading indicator
        const searchResults = document.getElementById('searchResults');
        const searchResultsCount = document.getElementById('searchResultsCount');
        if (searchResults) {
            searchResults.innerHTML = '<tr><td colspan="9" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Searching...</td></tr>';

            // Show results card
            document.getElementById('searchResultsCard').style.display = 'block';

            // Build search URL
            let searchUrl = `api/appointments.php?search=true&q=${encodeURIComponent(query)}`;
            if (type && type !== 'all') {
                searchUrl += `&type=${encodeURIComponent(type)}`;
            }
            if (dateRange) {
                searchUrl += `&date_range=${encodeURIComponent(dateRange)}`;
            } else if (date) {
                searchUrl += `&date=${encodeURIComponent(date)}`;
            }
            if (species && species !== 'all') {
                searchUrl += `&species=${encodeURIComponent(species)}`;
            }
            if (appointmentType && appointmentType !== 'all') {
                searchUrl += `&appointment_type=${encodeURIComponent(appointmentType)}`;
            }
            if (ageRange) {
                searchUrl += `&age_range=${encodeURIComponent(ageRange)}`;
            }
            if (urgency) {
                searchUrl += `&urgency=${encodeURIComponent(urgency)}`;
            }
            searchUrl += `&sort_by=${encodeURIComponent(sortBy)}&sort_order=${encodeURIComponent(sortOrder)}`;
            if (limit !== '0') {
                searchUrl += `&limit=${encodeURIComponent(limit)}`;
            }

            // Fetch search results
            fetch(searchUrl)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                    // Show stats button if there are results
                    const showStatsBtn = document.getElementById('showStatsBtn');
                    if (showStatsBtn && data.length > 0) {
                        showStatsBtn.style.display = 'inline-block';
                    }
                })
                .catch(error => {
                    console.error('Error searching appointments:', error);
                    searchResults.innerHTML = `<tr><td colspan="9" class="text-center py-3 text-danger">Error searching appointments: ${error.message}</td></tr>`;
                    if (searchResultsCount) {
                        searchResultsCount.textContent = 'Error loading results';
                    }
                });
        }
    }

    function displaySearchResults(data) {
        const searchResults = document.getElementById('searchResults');
        const searchResultsCount = document.getElementById('searchResultsCount');

        if (data.length > 0) {
            let html = '';
            data.forEach(appointment => {
                const startTime = new Date(appointment.start_time);
                const petAge = appointment.pet_age ? `(${appointment.pet_age}y)` : '';
                const staffName = appointment.staff_name || 'Not assigned';

                html += `
                    <tr>
                        <td>
                            <div class="fw-bold">${appointment.pet_name}</div>
                            <small class="text-muted">${appointment.breed || appointment.species} ${petAge}</small>
                        </td>
                        <td>
                            <div>${appointment.owner_name}</div>
                            ${appointment.phone ? `<small class="text-muted">${appointment.phone}</small>` : ''}
                        </td>
                        <td>${startTime.toLocaleDateString()}</td>
                        <td>${startTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</td>
                        <td>${appointment.appointment_type}</td>
                        <td>
                            <span class="badge bg-light text-dark">${appointment.species}</span>
                        </td>
                        <td><span class="status-badge status-${appointment.status}">${appointment.status}</span></td>
                        <td>
                            <small>${staffName}</small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary view-appointment" data-id="${appointment.appointment_id}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            searchResults.innerHTML = html;

            // Add event listeners to new view buttons
            searchResults.querySelectorAll('.view-appointment').forEach(button => {
                button.addEventListener('click', function () {
                    const appointmentId = this.getAttribute('data-id');
                    viewAppointmentDetails(appointmentId);
                });
            });

            if (searchResultsCount) {
                searchResultsCount.textContent = `${data.length} appointment${data.length !== 1 ? 's' : ''} found`;
            }
        } else {
            searchResults.innerHTML = '<tr><td colspan="9" class="text-center py-3">No appointments found matching your search criteria.</td></tr>';
            if (searchResultsCount) {
                searchResultsCount.textContent = 'No results found';
            }
        }
    }

    function clearSearch() {
        document.getElementById('searchForm').reset();
        document.getElementById('searchResultsCard').style.display = 'none';

        // Re-enable date input if disabled
        const specificDateInput = document.getElementById('searchDate');
        if (specificDateInput) {
            specificDateInput.disabled = false;
        }

        // Hide advanced filters
        const advancedFilters = document.getElementById('advancedFilters');
        const toggleBtn = document.getElementById('toggleAdvancedSearch');
        if (advancedFilters && toggleBtn) {
            advancedFilters.style.display = 'none';
            toggleBtn.innerHTML = '<i class="bi bi-sliders me-1"></i>Advanced Filters';
        }
    }

    function resetSearchForm() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'date') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
            input.disabled = false;
        });
    }

    function exportSearchResults() {
        const query = document.getElementById('searchQuery').value;
        const type = document.getElementById('searchType').value;
        const date = document.getElementById('searchDate').value;
        const dateRange = document.getElementById('dateRange').value;
        const species = document.getElementById('speciesFilter')?.value || 'all';
        const appointmentType = document.getElementById('appointmentTypeFilter')?.value || 'all';
        const ageRange = document.getElementById('ageRangeFilter')?.value || '';
        const urgency = document.getElementById('urgencyFilter')?.value || '';
        const sortBy = document.getElementById('sortBy')?.value || 'start_time';
        const sortOrder = document.getElementById('sortOrder')?.value || 'ASC';

        // Build search URL for export
        let exportUrl = `api/appointments.php?search=true&export=csv&q=${encodeURIComponent(query)}`;
        if (type && type !== 'all') {
            exportUrl += `&type=${encodeURIComponent(type)}`;
        }
        if (dateRange) {
            exportUrl += `&date_range=${encodeURIComponent(dateRange)}`;
        } else if (date) {
            exportUrl += `&date=${encodeURIComponent(date)}`;
        }
        if (species && species !== 'all') {
            exportUrl += `&species=${encodeURIComponent(species)}`;
        }
        if (appointmentType && appointmentType !== 'all') {
            exportUrl += `&appointment_type=${encodeURIComponent(appointmentType)}`;
        }
        if (ageRange) {
            exportUrl += `&age_range=${encodeURIComponent(ageRange)}`;
        }
        if (urgency) {
            exportUrl += `&urgency=${encodeURIComponent(urgency)}`;
        }
        exportUrl += `&sort_by=${encodeURIComponent(sortBy)}&sort_order=${encodeURIComponent(sortOrder)}`;

        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = `appointments_export_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function showSearchStatistics() {
        const query = document.getElementById('searchQuery').value;
        const type = document.getElementById('searchType').value;
        const date = document.getElementById('searchDate').value;
        const dateRange = document.getElementById('dateRange').value;
        const species = document.getElementById('speciesFilter')?.value || 'all';
        const appointmentType = document.getElementById('appointmentTypeFilter')?.value || 'all';
        const ageRange = document.getElementById('ageRangeFilter')?.value || '';
        const urgency = document.getElementById('urgencyFilter')?.value || '';

        // Build stats URL
        let statsUrl = `api/appointments.php?search_stats=true&q=${encodeURIComponent(query)}`;
        if (type && type !== 'all') {
            statsUrl += `&type=${encodeURIComponent(type)}`;
        }
        if (dateRange) {
            statsUrl += `&date_range=${encodeURIComponent(dateRange)}`;
        } else if (date) {
            statsUrl += `&date=${encodeURIComponent(date)}`;
        }
        if (species && species !== 'all') {
            statsUrl += `&species=${encodeURIComponent(species)}`;
        }
        if (appointmentType && appointmentType !== 'all') {
            statsUrl += `&appointment_type=${encodeURIComponent(appointmentType)}`;
        }
        if (ageRange) {
            statsUrl += `&age_range=${encodeURIComponent(ageRange)}`;
        }
        if (urgency) {
            statsUrl += `&urgency=${encodeURIComponent(urgency)}`;
        }

        // Show loading modal
        const modal = new bootstrap.Modal(document.getElementById('searchStatsModal'));
        const content = document.getElementById('searchStatsContent');
        content.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading statistics...</p></div>';
        modal.show();

        // Fetch statistics
        fetch(statsUrl)
            .then(response => response.json())
            .then(stats => {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Overview</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Results:</strong> ${stats.total_results}</li>
                                <li><strong>Upcoming This Week:</strong> ${stats.upcoming_this_week}</li>
                                <li><strong>Average Pet Age:</strong> ${stats.average_age} years</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>By Status</h6>
                            <ul class="list-unstyled">`;

                Object.entries(stats.by_status).forEach(([status, count]) => {
                    html += `<li><strong>${status.charAt(0).toUpperCase() + status.slice(1)}:</strong> ${count}</li>`;
                });

                html += `</ul></div></div><div class="row mt-3">
                        <div class="col-md-6">
                            <h6>By Species</h6>
                            <ul class="list-unstyled">`;

                Object.entries(stats.by_species).forEach(([species, count]) => {
                    html += `<li><strong>${species.charAt(0).toUpperCase() + species.slice(1)}:</strong> ${count}</li>`;
                });

                html += `</ul></div><div class="col-md-6">
                            <h6>By Age Group</h6>
                            <ul class="list-unstyled">
                                <li><strong>Young (0-2 years):</strong> ${stats.by_age_group.young}</li>
                                <li><strong>Adult (3-7 years):</strong> ${stats.by_age_group.adult}</li>
                                <li><strong>Senior (8+ years):</strong> ${stats.by_age_group.senior}</li>
                            </ul>
                        </div>
                    </div>`;

                if (Object.keys(stats.by_type).length > 0) {
                    html += `<div class="row mt-3">
                            <div class="col-12">
                                <h6>By Appointment Type</h6>
                                <ul class="list-unstyled">`;
                    Object.entries(stats.by_type).forEach(([type, count]) => {
                        html += `<li><strong>${type}:</strong> ${count}</li>`;
                    });
                    html += `</ul></div></div>`;
                }

                content.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading statistics:', error);
                content.innerHTML = '<div class="alert alert-danger">Error loading statistics. Please try again.</div>';
            });
    }

    function setQuickSearch(preset) {
        // Reset form first
        resetSearchForm();

        switch (preset) {
            case 'today':
                document.getElementById('dateRange').value = 'today';
                break;
            case 'urgent':
                document.getElementById('urgencyFilter').value = 'urgent';
                break;
            case 'follow_up':
                document.getElementById('urgencyFilter').value = 'follow_up';
                break;
        }

        // Show advanced filters if not already shown
        const advancedFilters = document.getElementById('advancedFilters');
        const toggleBtn = document.getElementById('toggleAdvancedSearch');
        if (advancedFilters && advancedFilters.style.display === 'none') {
            advancedFilters.style.display = 'block';
            toggleBtn.innerHTML = '<i class="bi bi-sliders me-1"></i>Hide Advanced';
        }

        // Perform the search
        performSearch();
    }

    // ===== Notifications Management =====

    // Dismiss individual notification
    window.dismissNotification = function (button) {
        const notificationItem = button.closest('.notification-item');
        notificationItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        notificationItem.style.opacity = '0';
        notificationItem.style.transform = 'translateX(100%)';

        setTimeout(() => {
            notificationItem.remove();

            // Hide panel if no notifications left
            const notificationsList = document.getElementById('notificationsList');
            if (notificationsList && notificationsList.children.length === 0) {
                document.getElementById('notificationsPanel').style.display = 'none';
            }
        }, 300);
    };

    // Dismiss all notifications
    const dismissAllBtn = document.getElementById('dismissAllNotifications');
    if (dismissAllBtn) {
        dismissAllBtn.addEventListener('click', function () {
            const notificationsPanel = document.getElementById('notificationsPanel');
            notificationsPanel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            notificationsPanel.style.opacity = '0';
            notificationsPanel.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                notificationsPanel.style.display = 'none';
            }, 300);
        });
    }

    // Auto-refresh notifications every 2 minutes
    setInterval(refreshNotifications, 120000);

    // ===== Activity Feed Management =====

    // Refresh activity feed
    window.refreshActivity = function () {
        const activityFeed = document.getElementById('activityFeed');
        if (!activityFeed) return;

        // Show loading state
        activityFeed.innerHTML = `
            <div class="p-3 text-center">
                <div class="spinner-border spinner-border-sm text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0 mt-2">Refreshing activity...</p>
            </div>
        `;

        fetch('api/appointments.php?recent_activity=true&limit=8')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.activity) {
                    renderActivityFeed(data.activity);
                } else {
                    showActivityError();
                }
            })
            .catch(error => {
                console.error('Error refreshing activity:', error);
                showActivityError();
            });
    };

    // Render activity feed items
    function renderActivityFeed(activities) {
        const activityFeed = document.getElementById('activityFeed');
        if (!activityFeed) return;

        if (activities.length === 0) {
            activityFeed.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 mb-2"></i>
                    <p class="mb-0">No recent activity</p>
                </div>
            `;
            return;
        }

        activityFeed.innerHTML = activities.map(activity => {
            const statusIcon = getStatusIcon(activity.status);
            const timeDisplay = getTimeDisplay(activity);

            return `
                <div class="activity-item">
                    <div class="activity-icon">
                        ${statusIcon}
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">
                            ${activity.pet_name} - ${capitalizeFirst(activity.appointment_type)}
                        </div>
                        <div class="activity-subtitle">
                            ${activity.owner_name}
                            ${activity.staff_name ? ` • ${activity.staff_name}` : ''}
                        </div>
                        <div class="activity-time">
                            ${timeDisplay}
                        </div>
                    </div>
                    <div class="activity-actions">
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="viewAppointmentDetails(${activity.appointment_id})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    function getStatusIcon(status) {
        switch (status) {
            case 'completed':
                return '<i class="bi bi-check-circle-fill text-success"></i>';
            case 'cancelled':
                return '<i class="bi bi-x-circle-fill text-danger"></i>';
            case 'in_progress':
                return '<i class="bi bi-play-circle-fill text-warning"></i>';
            default:
                return '<i class="bi bi-clock-fill text-info"></i>';
        }
    }

    function getTimeDisplay(activity) {
        const startTime = new Date(activity.start_time);
        const endTime = activity.end_time ? new Date(activity.end_time) : null;

        switch (activity.status) {
            case 'completed':
                return endTime ? `Completed ${formatTime(endTime)}` : 'Completed';
            case 'cancelled':
                return 'Cancelled';
            default:
                return formatTime(startTime);
        }
    }

    function formatTime(date) {
        return date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    }

    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function showActivityError() {
        const activityFeed = document.getElementById('activityFeed');
        if (activityFeed) {
            activityFeed.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="bi bi-exclamation-triangle fs-1 mb-2"></i>
                    <p class="mb-0">Error loading activity</p>
                    <button class="btn btn-sm btn-outline-secondary mt-2" onclick="refreshActivity()">
                        <i class="bi bi-arrow-clockwise"></i> Try Again
                    </button>
                </div>
            `;
        }
    }

    // ===== Real-time Dashboard Updates =====

    // Refresh notifications from server
    function refreshNotifications() {
        fetch('api/appointments.php?notifications=true')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications) {
                    updateNotificationsPanel(data.notifications);
                }
            })
            .catch(error => {
                console.error('Error refreshing notifications:', error);
            });
    }

    // Update notifications panel with new data
    function updateNotificationsPanel(notifications) {
        const existingPanel = document.getElementById('notificationsPanel');

        if (notifications.length === 0) {
            if (existingPanel) {
                existingPanel.style.display = 'none';
            }
            return;
        }

        // If panel doesn't exist but we have notifications, we might need to reload
        if (!existingPanel) {
            // Could add a subtle indicator that new notifications are available
            return;
        }

        // Update the notifications list
        const notificationsList = document.getElementById('notificationsList');
        if (notificationsList) {
            notificationsList.innerHTML = notifications.map(notification => {
                const priorityClass = `priority-${notification.priority}`;
                const iconHtml = getNotificationIcon(notification.type);

                return `
                    <div class="notification-item ${priorityClass}" data-appointment-id="${notification.appointment_id}">
                        <div class="d-flex align-items-start">
                            <div class="notification-icon me-3">
                                ${iconHtml}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="notification-title mb-1">${notification.title}</h6>
                                <p class="notification-message mb-1">${notification.message}</p>
                                <small class="text-muted">${formatTime(new Date(notification.timestamp))}</small>
                            </div>
                            <div class="notification-actions">
                                <button class="btn btn-sm btn-outline-primary me-1" 
                                        onclick="viewAppointmentDetails(${notification.appointment_id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" 
                                        onclick="dismissNotification(this)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Update urgent count
        const urgentCount = notifications.filter(n => n.priority === 'urgent' || n.priority === 'high').length;
        const urgentBadge = existingPanel.querySelector('.badge');
        if (urgentBadge) {
            urgentBadge.textContent = urgentCount;
            urgentBadge.style.display = urgentCount > 0 ? 'inline' : 'none';
        }

        existingPanel.style.display = 'block';
    }

    function getNotificationIcon(type) {
        switch (type) {
            case 'overdue':
                return '<i class="bi bi-clock-history text-danger"></i>';
            case 'upcoming':
                return '<i class="bi bi-clock text-warning"></i>';
            case 'emergency':
                return '<i class="bi bi-exclamation-triangle-fill text-danger"></i>';
            case 'follow_up':
                return '<i class="bi bi-arrow-repeat text-info"></i>';
            default:
                return '<i class="bi bi-bell text-primary"></i>';
        }
    }

    // ===== Dashboard Auto-refresh =====

    // Auto-refresh various dashboard components
    setInterval(() => {
        refreshNotifications();
        // Refresh activity feed every 5 minutes
        if (Math.random() < 0.2) { // 20% chance every minute = roughly every 5 minutes
            refreshActivity();
        }
    }, 60000); // Check every minute

    // ===== Performance Charts =====

});
