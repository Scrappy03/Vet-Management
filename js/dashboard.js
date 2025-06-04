document.addEventListener('DOMContentLoaded', function () {
    // Debug for page load
    console.log('Dashboard.js loaded');

    // Check if the appointment table exists
    const appointmentTable = document.querySelector('.appointment-table');
    console.log('Appointment table exists:', !!appointmentTable);

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

                html += `
                    <div class="d-flex justify-content-end mt-3">
                        <a href="calendar?appointment=${data.id}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Close
                        </button>
                    </div>
                </div>`;

                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching appointment details:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error loading appointment details. Please try again.
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Close
                        </button>
                    </div>
                `;
            });
    }

    // Handle dismiss notification using event delegation
    function dismissNotification(button) {
        const notificationItem = button.closest('.notification-item');
        if (notificationItem) {
            notificationItem.style.height = notificationItem.offsetHeight + 'px';
            setTimeout(() => {
                notificationItem.classList.add('fade-out');
                setTimeout(() => {
                    notificationItem.remove();

                    // If no more notifications, hide the panel
                    const notificationsList = document.getElementById('notificationsList');
                    if (notificationsList && notificationsList.children.length === 0) {
                        const notificationsPanel = document.getElementById('notificationsPanel');
                        if (notificationsPanel) {
                            notificationsPanel.remove();
                        }
                    }
                }, 300);
            }, 0);
        }
    }

    // Event delegation for dismiss notification buttons
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="dismiss-notification"]')) {
            e.preventDefault();
            dismissNotification(e.target.closest('[data-action="dismiss-notification"]'));
        }
    });

    // Handle dismiss all notifications
    const dismissAllBtn = document.getElementById('dismissAllNotifications');
    if (dismissAllBtn) {
        dismissAllBtn.addEventListener('click', function () {
            const notificationsPanel = document.getElementById('notificationsPanel');
            if (notificationsPanel) {
                notificationsPanel.classList.add('fade-out');
                setTimeout(() => {
                    notificationsPanel.remove();
                }, 300);
            }
        });
    }

    // Initialize simple search functionality
    function initializeSimpleSearch() {
        const searchInput = document.getElementById('appointmentSearch');
        const statusFilter = document.getElementById('statusFilter');
        const clearButton = document.getElementById('clearAppointmentSearch');

        // Debug - check if elements exist
        console.log('Search input exists:', !!searchInput);
        console.log('Status filter exists:', !!statusFilter);
        console.log('Clear button exists:', !!clearButton);

        if (!searchInput || !statusFilter || !clearButton) {
            console.error('One or more search elements not found!');
            return;
        }

        // Get all appointment rows from the table
        function getAppointmentRows() {
            return document.querySelectorAll('.appointment-table tbody tr');
        }

        // Filter appointments based on search criteria
        function filterAppointments() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedStatus = statusFilter.value.toLowerCase();
            const rows = getAppointmentRows();

            // Remove any existing no-results message
            const tbody = document.querySelector('.appointment-table tbody');
            const existingMessage = tbody.querySelector('.no-results-row');
            if (existingMessage) {
                existingMessage.remove();
            }

            let visibleCount = 0;

            // Debug
            console.log('Search term:', searchTerm);
            console.log('Selected status:', selectedStatus);
            console.log('Total rows:', rows.length);

            // Only process rows that are not message rows
            const dataRows = Array.from(rows).filter(row => !row.classList.contains('no-results-row'));

            dataRows.forEach(row => {
                const petName = row.querySelector('.pet-name')?.textContent.toLowerCase() || '';
                const ownerName = row.querySelector('.owner-name')?.textContent.toLowerCase() || '';
                const appointmentType = row.querySelector('.appointment-type')?.textContent.toLowerCase() || '';
                const statusBadge = row.querySelector('.status-badge');
                const status = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';
                const statusClass = statusBadge ? statusBadge.className : '';

                // Debug for first row
                if (row === dataRows[0]) {
                    console.log('First row data:', {
                        petName,
                        ownerName,
                        appointmentType,
                        status,
                        statusClass
                    });
                }

                const matchesSearch = !searchTerm ||
                    petName.includes(searchTerm) ||
                    ownerName.includes(searchTerm) ||
                    appointmentType.includes(searchTerm);

                // More lenient status matching
                let matchesStatus = true;
                if (selectedStatus) {
                    matchesStatus = status.includes(selectedStatus) ||
                        statusClass.includes(`status-${selectedStatus}`);
                }

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update search input placeholder with count
            if (searchInput.value.trim() || selectedStatus) {
                searchInput.placeholder = `${visibleCount} appointment(s) found`;
            } else {
                searchInput.placeholder = 'Search appointments by pet name, owner, or type...';
            }

            // Show no results message if needed
            if (visibleCount === 0 && (searchTerm || selectedStatus)) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                    <td colspan="7" class="text-center py-3 text-muted">
                        <i class="bi bi-search me-2"></i>
                        No appointments match your search.
                        <button class="btn btn-sm btn-link p-0 ms-2" id="clearSearchFromMessage">Clear search</button>
                    </td>
                `;
                tbody.appendChild(noResultsRow);

                // Add event listener to the clear button
                document.getElementById('clearSearchFromMessage').addEventListener('click', function () {
                    searchInput.value = '';
                    statusFilter.value = '';
                    filterAppointments();
                });
            }
        }

        // Clear search function
        function clearSearch() {
            searchInput.value = '';
            statusFilter.value = '';
            filterAppointments();
        }

        // Event listeners
        searchInput.addEventListener('input', filterAppointments);
        statusFilter.addEventListener('change', filterAppointments);
        clearButton.addEventListener('click', clearSearch);

        // Initial filtering to set up the UI
        filterAppointments();
    }    // Initialize search after DOM is loaded
    initializeSimpleSearch();

    // Check for appointment table body
    const appointmentTableBody = document.querySelector('.appointment-table tbody');

    // Refresh activity function
    function refreshActivity() {
        const activityFeed = document.getElementById('activityFeed');
        const refreshBtn = document.querySelector('[data-action="refresh-activity"]');

        if (!activityFeed) return;

        // Show loading state
        if (refreshBtn) {
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> <span class="spinner-border spinner-border-sm ms-1"></span>';
        }

        // Fetch updated activity data
        fetch('api/appointments.php?recent_activity=1')
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success && data.activity) {
                    console.log('Activity array:', data.activity);
                    // Update the activity feed with new data
                    updateActivityFeed(data.activity);
                    showToast('Activity refreshed successfully!', 'success');
                } else {
                    console.error('API returned error:', data.error || 'Unknown error');
                    showToast('Error refreshing activity: ' + (data.error || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error refreshing activity:', error);
                showToast('Network error while refreshing activity', 'error');
            })
            .finally(() => {
                // Reset button state
                if (refreshBtn) {
                    refreshBtn.disabled = false;
                    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                }
            });
    }

    // Update activity feed with new data
    function updateActivityFeed(activities) {
        const activityFeed = document.getElementById('activityFeed');
        if (!activityFeed) return;

        if (!activities || activities.length === 0) {
            activityFeed.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 mb-2"></i>
                    <p class="mb-0">No recent activity</p>
                </div>
            `;
            return;
        }

        // Generate HTML for activities
        let html = '';
        activities.forEach(activity => {
            // Handle different status values and provide fallbacks
            const status = activity.status || 'unknown';
            const statusIcon = status === 'completed' ? 'check-circle-fill text-success' :
                status === 'cancelled' ? 'x-circle-fill text-danger' :
                    status === 'in_progress' ? 'play-circle-fill text-warning' :
                        'clock-fill text-info';

            // Use field names that match the template exactly
            const petName = activity.pet_name || 'Unknown Pet';
            const ownerName = activity.owner_name || 'Unknown Owner';
            const appointmentType = activity.appointment_type || 'Appointment';
            const staffName = activity.staff_name || '';

            // Use the formatted times from the API (now provided by server)
            let timeDisplay = '';
            if (status === 'completed' && activity.end_time_formatted) {
                timeDisplay = `Completed ${activity.end_time_formatted}`;
            } else if (status === 'cancelled') {
                timeDisplay = 'Cancelled';
            } else if (activity.start_time_formatted) {
                timeDisplay = activity.start_time_formatted;
            } else {
                timeDisplay = 'No time available';
            }

            // Use the appointment ID
            const appointmentId = activity.appointment_id;

            html += `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-${statusIcon}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">${petName} - <span style="text-transform: capitalize;">${appointmentType}</span></div>
                        <div class="activity-subtitle text-muted small">
                            ${ownerName}${staffName ? ' • ' + staffName : ''}
                        </div>
                        <div class="activity-time">
                            <small class="text-muted">${timeDisplay}</small>
                        </div>
                    </div>
                    <div class="activity-actions">
                        ${appointmentId ? `
                            <button class="btn btn-sm btn-outline-primary"
                                data-action="view-appointment" data-appointment-id="${appointmentId}">
                                <i class="bi bi-eye"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        activityFeed.innerHTML = html;
    }

    // Add event delegation for refresh activity button
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="refresh-activity"]')) {
            e.preventDefault();
            refreshActivity();
        }

        // Handle appointment details viewing using event delegation  
        if (e.target.closest('[data-action="view-appointment"]')) {
            e.preventDefault();
            const button = e.target.closest('[data-action="view-appointment"]');
            const appointmentId = button.dataset.appointmentId;
            if (appointmentId) {
                viewAppointmentDetails(parseInt(appointmentId));
            }
        }
    });

});
