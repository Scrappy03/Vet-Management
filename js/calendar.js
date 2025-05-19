document.addEventListener('DOMContentLoaded', function () {
    // Get current date for default display
    const currentDate = new Date();

    // Define colors for different appointment types
    const eventColors = {
        'Check-up': '#0d6efd', // primary blue
        'Vaccination': '#198754', // success green
        'Surgery': '#dc3545', // danger red
        'Dental Cleaning': '#6f42c1', // purple
        'default': '#0d6efd' // default blue
    };

    // Calendar element
    const calendarEl = document.getElementById('calendar');

    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // Instead of initialEvents, use events function to fetch data from the API
        events: function (info, successCallback, failureCallback) {
            // Format dates as ISO strings for API
            const startStr = info.startStr;
            const endStr = info.endStr;

            // Make AJAX call to get events for the requested date range
            fetch(`api/appointments.php?start=${startStr}&end=${endStr}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Process events and apply color formatting
                    const formattedEvents = data.map(event => {
                        const color = event.status === 'completed' ? '#6c757d' : eventColors[event.type] || eventColors['default'];

                        return {
                            id: event.id,
                            title: event.title,
                            start: event.start,
                            end: event.end,
                            backgroundColor: color,
                            borderColor: color,
                            textColor: '#ffffff',
                            classNames: [`event-${event.status}`, `event-type-${event.type.toLowerCase().replace(/\s+/g, '-')}`],
                            extendedProps: {
                                owner: event.owner_name,
                                type: event.type,
                                status: event.status,
                                pet: event.pet_name,
                                notes: event.notes || '',
                                careStatus: event.care_status || ''
                            }
                        };
                    });

                    successCallback(formattedEvents);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },

        // Keep your existing event rendering, click handlers, etc.
        eventClick: function (info) {
            // Create a nicer modal for event details instead of alert
            const event = info.event;
            const props = event.extendedProps;

            // Format dates nicely
            const startDate = event.start.toLocaleDateString(undefined, {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const startTime = event.start.toLocaleTimeString(undefined, {
                hour: 'numeric',
                minute: '2-digit'
            });

            const endTime = event.end.toLocaleTimeString(undefined, {
                hour: 'numeric',
                minute: '2-digit'
            });

            // Get care status display text and class
            let careStatusHTML = '';
            if (props.careStatus) {
                let careStatusText = '';
                switch (props.careStatus) {
                    case 'aggressive':
                        careStatusText = 'Aggressive - Use Caution';
                        break;
                    case 'anxious':
                        careStatusText = 'Anxious - Needs Gentle Handling';
                        break;
                    case 'special-needs':
                        careStatusText = 'Special Needs - See Notes';
                        break;
                }
                careStatusHTML = `
                    <div class="mb-3">
                        <strong>Animal Care Status:</strong> 
                        <span class="care-status-badge care-status-${props.careStatus}">${careStatusText}</span>
                    </div>
                `;
            }

            // Create modal HTML
            const modalHTML = `
                <div class="modal fade" id="eventDetailsModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Appointment Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <h5 class="mb-3">${props.type} - ${props.pet}</h5>
                                <div class="mb-3">
                                    <strong>Date:</strong> ${startDate}
                                </div>
                                <div class="mb-3">
                                    <strong>Time:</strong> ${startTime} - ${endTime}
                                </div>
                                <div class="mb-3">
                                    <strong>Pet Owner:</strong> ${props.owner}
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong> 
                                    <span class="status-badge status-${props.status}">${props.status.charAt(0).toUpperCase() + props.status.slice(1)}</span>
                                </div>
                                ${careStatusHTML}
                                ${props.notes ? `<div class="mb-3"><strong>Notes:</strong> ${props.notes}</div>` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Edit Appointment</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove any existing modal
            const existingModal = document.getElementById('eventDetailsModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Show the modal
            const eventModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
            eventModal.show();
        },

        dateClick: function (info) {
            // Open the new appointment modal when a date is clicked
            const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
            document.getElementById('appointmentDate').value = info.dateStr;
            modal.show();

            // Update the schedule section for the clicked date
            updateScheduleForDate(info.date);
        },

        // Update schedule when navigating the calendar
        datesSet: function (info) {
            const currentView = info.view.type;
            let dateToShow;

            if (currentView === 'dayGridMonth') {
                // In month view, use today's date or the selected date
                dateToShow = calendar.getDate();
            } else if (currentView === 'timeGridWeek') {
                // In week view, use the first visible date
                dateToShow = info.start;
            } else if (currentView === 'timeGridDay') {
                // In day view, use the displayed date
                dateToShow = info.start;
            }

            updateScheduleForDate(dateToShow);
        }
    });

    calendar.render();

    // Initialize the schedule with the current calendar date
    updateScheduleForDate(calendar.getDate());

    // Function to update the schedule section for a specific date
    function updateScheduleForDate(date) {
        // Format the date for display
        const formattedDate = date.toLocaleDateString(undefined, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Update the schedule title
        const scheduleTitle = document.getElementById('scheduleTitle');
        scheduleTitle.textContent = formattedDate + ' Schedule';

        // Get events for the selected date
        const events = calendar.getEvents().filter(event => {
            const eventDate = event.start;
            return eventDate.getFullYear() === date.getFullYear() &&
                eventDate.getMonth() === date.getMonth() &&
                eventDate.getDate() === date.getDate();
        });

        // Sort events by start time
        events.sort((a, b) => a.start - b.start);

        // Clear the current schedule
        const scheduleTimeline = document.getElementById('scheduleTimeline');
        scheduleTimeline.innerHTML = '';

        // Check if there are events for this date
        if (events.length === 0) {
            scheduleTimeline.innerHTML = '<div class="text-center text-muted py-4">No appointments scheduled for this day.</div>';
            return;
        }

        // Add each event to the schedule
        events.forEach(event => {
            const timeString = event.start.toLocaleTimeString(undefined, {
                hour: 'numeric',
                minute: '2-digit'
            });

            const props = event.extendedProps;

            // Add care status indicator if needed
            let careStatusIndicator = '';
            if (props.careStatus) {
                let icon = '';
                switch (props.careStatus) {
                    case 'aggressive':
                        icon = '<i class="bi bi-exclamation-triangle text-danger"></i>';
                        break;
                    case 'anxious':
                        icon = '<i class="bi bi-heart text-warning"></i>';
                        break;
                    case 'special-needs':
                        icon = '<i class="bi bi-info-circle text-info"></i>';
                        break;
                }
                careStatusIndicator = `<span class="ms-2" title="${props.careStatus}">${icon}</span>`;
            }

            // Show owner info only if available
            const ownerInfo = props.owner ? `<p class="mb-0 text-muted">Owner: ${props.owner}</p>` : '';

            const scheduleItem = document.createElement('div');
            scheduleItem.className = 'schedule-item';
            scheduleItem.innerHTML = `
                <div class="schedule-time">${timeString}</div>
                <div class="schedule-content">
                    <h6>${props.type} - ${props.pet}${careStatusIndicator}</h6>
                    ${ownerInfo}
                </div>
                <div class="schedule-status">
                    <span class="status-badge status-${props.status}">${props.status.charAt(0).toUpperCase() + props.status.slice(1)}</span>
                </div>
            `;

            // Add click event to open event details
            scheduleItem.addEventListener('click', () => {
                // Trigger the same event modal as clicking on the calendar event
                calendar.getEventById(event.id)?.trigger('click');
            });

            scheduleTimeline.appendChild(scheduleItem);
        });
    }

    // Handle adding new appointments - update to use AJAX
    document.getElementById('scheduleAppointmentBtn').addEventListener('click', function () {
        const patientId = document.getElementById('pet').value;
        const staffId = document.getElementById('staff').value;
        const date = document.getElementById('appointmentDate').value;
        const time = document.getElementById('appointmentTime').value;
        const type = document.getElementById('appointmentType').value;
        const notes = document.getElementById('appointmentNotes').value;
        const careStatus = document.getElementById('animalCareStatus').value;

        // Basic validation
        if (!patientId || !staffId || !date || !time || !type) {
            alert('Please fill in all required fields');
            return;
        }

        // Create appointment data object
        const appointmentData = {
            patient_id: patientId,
            staff_id: staffId,
            appointment_type: type,
            start_time: `${date}T${time}`,
            notes: notes,
            care_status: careStatus,
            status: 'upcoming'
        };

        // Send POST request to API
        fetch('api/appointments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(appointmentData)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Refresh events from the server
                    calendar.refetchEvents();

                    // Update schedule if the new event is for the currently displayed date
                    const currentDate = calendar.getDate();
                    const appointmentDate = new Date(date);

                    if (appointmentDate.getFullYear() === currentDate.getFullYear() &&
                        appointmentDate.getMonth() === currentDate.getMonth() &&
                        appointmentDate.getDate() === currentDate.getDate()) {
                        updateScheduleForDate(currentDate);
                    }

                    // Show success message
                    alert('Appointment scheduled successfully!');
                } else {
                    alert('Failed to schedule appointment: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error scheduling appointment:', error);
                alert('Error scheduling appointment. Please try again.');
            })
            .finally(() => {
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('newAppointmentModal'));
                modal.hide();
                document.getElementById('appointmentForm').reset();
            });
    });
});
