function showToast(message, type = 'success') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toastId = 'toast-' + Date.now();
    const toastElement = document.createElement('div');
    toastElement.className = 'toast';
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');
    toastElement.setAttribute('id', toastId);

    // Determine icon and background color based on type
    let icon, bgClass;
    switch (type) {
        case 'success':
            icon = 'bi-check-circle-fill';
            bgClass = 'bg-success';
            break;
        case 'danger':
        case 'error':
            icon = 'bi-exclamation-triangle-fill';
            bgClass = 'bg-danger';
            break;
        case 'warning':
            icon = 'bi-exclamation-triangle-fill';
            bgClass = 'bg-warning';
            break;
        case 'info':
            icon = 'bi-info-circle-fill';
            bgClass = 'bg-info';
            break;
        default:
            icon = 'bi-info-circle-fill';
            bgClass = 'bg-primary';
    }

    toastElement.innerHTML = `
        <div class="toast-header ${bgClass} text-white">
            <i class="bi ${icon} me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    // Add toast to container
    toastContainer.appendChild(toastElement);

    // Initialize and show toast
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    toast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

function showConfirmDialog(message, onConfirm, onCancel = null) {
    // Create modal backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.style.zIndex = '1050';

    // Create modal container
    const modalContainer = document.createElement('div');
    modalContainer.className = 'modal fade show';
    modalContainer.style.display = 'block';
    modalContainer.style.zIndex = '1055';

    modalContainer.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-question-circle-fill text-warning me-2"></i>
                        Confirmation Required
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">${message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-action="cancel">Cancel</button>
                    <button type="button" class="btn btn-danger" data-action="confirm">Confirm</button>
                </div>
            </div>
        </div>
    `;

    // Add event listeners
    modalContainer.addEventListener('click', function (e) {
        const action = e.target.getAttribute('data-action');
        if (action === 'confirm') {
            if (onConfirm) onConfirm();
            removeModal();
        } else if (action === 'cancel') {
            if (onCancel) onCancel();
            removeModal();
        }
    });

    function removeModal() {
        document.body.removeChild(backdrop);
        document.body.removeChild(modalContainer);
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
    }

    // Add to DOM
    document.body.appendChild(backdrop);
    document.body.appendChild(modalContainer);
    document.body.classList.add('modal-open');
    document.body.style.paddingRight = '17px'; // Account for scrollbar

    // Focus the confirm button
    setTimeout(() => {
        modalContainer.querySelector('[data-action="confirm"]').focus();
    }, 100);
}
