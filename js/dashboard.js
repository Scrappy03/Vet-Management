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
});
