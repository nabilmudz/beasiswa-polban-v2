function showAlert(type, title, message) {
    const alertOverlay = document.getElementById('alertOverlay');
    const alertPopup = document.getElementById('alertPopup');
    const alertIcon = document.getElementById('alertIcon');
    const alertTitle = document.getElementById('alertTitle');
    const alertMessage = document.getElementById('alertMessage');

    if (!alertOverlay || !alertPopup || !alertIcon || !alertTitle || !alertMessage) {
        console.error('Required alert elements not found in the DOM.');
        return;
    }

    // Update title, message, and icon based on type
    alertTitle.textContent = title;
    alertMessage.textContent = message;
    alertIcon.className = 'w-10 h-10 flex items-center justify-center rounded-full';

    switch (type) {
        case 'success':
            alertIcon.classList.add('bg-green-100', 'text-green-600');
            alertIcon.innerHTML = '<i class="fas fa-check-circle text-2xl"></i>';
            break;
        case 'error':
            alertIcon.classList.add('bg-red-100', 'text-red-600');
            alertIcon.innerHTML = '<i class="fas fa-times-circle text-2xl"></i>';
            break;
        case 'warning':
            alertIcon.classList.add('bg-yellow-100', 'text-yellow-600');
            alertIcon.innerHTML = '<i class="fas fa-exclamation-circle text-2xl"></i>';
            break;
        case 'info':
            alertIcon.classList.add('bg-blue-100', 'text-blue-600');
            alertIcon.innerHTML = '<i class="fas fa-info-circle text-2xl"></i>';
            break;
    }

    // Show popup with slide-in animation
    alertOverlay.classList.remove('hidden');
    alertPopup.classList.remove('translate-x-full');
    alertPopup.classList.add('translate-x-0');

    // Automatically hide after 3 seconds
    setTimeout(() => {
        alertPopup.classList.remove('translate-x-0');
        alertPopup.classList.add('translate-x-full');
        setTimeout(() => {
            alertOverlay.classList.add('hidden');
        }, 500); // Wait for the slide-out animation to complete
    }, 5000);
}

// Close alert manually
const alertClose = document.getElementById('alertClose');
if (alertClose) {
    alertClose.addEventListener('click', () => {
        const alertPopup = document.getElementById('alertPopup');
        if (alertPopup) {
            alertPopup.classList.remove('translate-x-0');
            alertPopup.classList.add('translate-x-full');
            setTimeout(() => {
                document.getElementById('alertOverlay').classList.add('hidden');
            }, 300); // Wait for the slide-out animation to complete
        }
    });
}
