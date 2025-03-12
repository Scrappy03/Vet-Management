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

    // Function to create consistent event objects
    function createEventObject(title, start, end, type, pet, status, notes, careStatus) {
        const color = status === 'completed' ? '#6c757d' : eventColors[type] || eventColors['default'];

        // Extract owner from pet string if available (format: "Pet Name (Type) - Owner Name")
        let petName = pet;
        let owner = "";

        if (pet.includes(" - ")) {
            const parts = pet.split(" - ");
            petName = parts[0];
            owner = parts[1];
        }

        return {
            title: title,
            start: start,
            end: end,
            backgroundColor: color,
            borderColor: color,
            textColor: '#ffffff',
            classNames: [`event-${status}`, `event-type-${type.toLowerCase().replace(/\s+/g, '-')}`],
            extendedProps: {
                owner: owner,
                type: type,
                status: status,
                pet: petName,
                notes: notes || '',
                careStatus: careStatus || ''
            }
        };
    }

    // Sample initial events with consistent formatting
    const initialEvents = [
        createEventObject(
            'Vaccination - Max (Dog)',
            '2025-03-10T09:00:00',
            '2025-03-10T10:00:00',
            'Vaccination',
            'Max (Dog) - Jane Smith',
            'upcoming',
            '',
            'aggressive'
        ),
        createEventObject(
            'Regular Checkup - Luna (Cat)',
            '2025-03-10T11:30:00',
            '2025-03-10T12:30:00',
            'Check-up',
            'Luna (Cat) - Mike Johnson',
            'completed',
            '',
            'anxious'
        ),
        createEventObject(
            'Dental Cleaning - Rocky (Dog)',
            '2025-03-10T14:00:00',
            '2025-03-10T15:30:00',
            'Dental Cleaning',
            'Rocky (Dog) - Sarah Williams',
            'upcoming',
            'Needs specific dental tools'
        ),
        createEventObject(
            'Surgery - Bella (Cat)',
            '2025-03-04T10:00:00',
            '2025-03-04T12:00:00',
            'Surgery',
            'Bella (Cat) - John Davis',
            'completed',
            'Post-op care instructions provided',
            'special-needs'
        )
    ];

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: '2025-03-10', // Set to a specific date to show the sample events
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },
        events: initialEvents,
        selectable: true,
        editable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        nowIndicator: true, // show a marker for current time
        slotMinTime: '08:00:00', // Start day view at 8 AM
        slotMaxTime: '18:00:00', // End day view at 6 PM
        expandRows: true, // expand rows to fill available height
        height: 600, // fixed height

        // Event appearance control for consistency across views
        eventDidMount: function (info) {
            // Add tooltips to events
            const tooltip = new bootstrap.Tooltip(info.el, {
                title: `${info.event.title}\n${info.event.extendedProps.owner}\n${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()}`,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });

            // Ensure consistent coloring across all views
            const backgroundColor = info.event.backgroundColor;
            const view = info.view.type;

            // Apply background color to the entire event element
            info.el.style.backgroundColor = backgroundColor;
            info.el.style.borderColor = backgroundColor;
            info.el.style.color = '#ffffff';

            // Specific handling for month view dots
            if (view === 'dayGridMonth') {
                const dotElements = info.el.querySelectorAll('.fc-daygrid-event-dot');
                dotElements.forEach(dot => {
                    dot.style.borderColor = backgroundColor;
                });
            }
        },

        // Force month view to use the block style instead of dots for event rendering
        eventDisplay: 'block',

        // For month view specifically
        views: {
            dayGridMonth: {
                eventMaxStack: 3
            }
        },

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

    // Handle adding new appointments
    document.getElementById('scheduleAppointmentBtn').addEventListener('click', function () {
        const pet = document.getElementById('pet').value;
        const date = document.getElementById('appointmentDate').value;
        const time = document.getElementById('appointmentTime').value;
        const type = document.getElementById('appointmentType').value;
        const notes = document.getElementById('appointmentNotes').value;
        const careStatus = document.getElementById('animalCareStatus').value;

        // Basic validation
        if (!pet || !date || !time || !type) {
            alert('Please fill in all required fields');
            return;
        }

        // Create event
        const startDateTime = new Date(`${date}T${time}`);
        const endDateTime = new Date(startDateTime);
        endDateTime.setMinutes(endDateTime.getMinutes() + 60); // Default 1-hour appointment

        // Extract pet name for title
        const petName = pet.includes(" - ") ? pet.split(" - ")[0] : pet;

        // Create a consistent event object using our helper function
        const eventObject = createEventObject(
            `${type} - ${petName}`,
            startDateTime,
            endDateTime,
            type,
            pet,
            'upcoming',
            notes,
            careStatus
        );

        // Add to calendar
        calendar.addEvent(eventObject);

        // Update schedule if the new event is for the currently displayed date
        const currentDate = calendar.getDate();
        if (startDateTime.getFullYear() === currentDate.getFullYear() &&
            startDateTime.getMonth() === currentDate.getMonth() &&
            startDateTime.getDate() === currentDate.getDate()) {
            updateScheduleForDate(currentDate);
        }

        // Close modal and reset form
        const modal = bootstrap.Modal.getInstance(document.getElementById('newAppointmentModal'));
        modal.hide();
        document.getElementById('appointmentForm').reset();
    });

    // Add event IDs to enable finding events when clicking schedule items
    initialEvents.forEach((event, index) => {
        event.id = 'initial-event-' + index;
    });
});
