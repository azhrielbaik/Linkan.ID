window.showSuccessToast = function(message) {
    let toast = document.getElementById('profileSuccessToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'profileSuccessToast';
        toast.style.cssText = 'position: fixed; bottom: 24px; right: 24px; background: #10B981; color: #ffffff; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 13px; box-shadow: 0 4px 14px rgba(16,185,129,0.3); z-index: 9999; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; opacity: 0; transform: translateY(10px);';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> <span>' + message + '</span>';
        document.body.appendChild(toast);
    } else {
        toast.querySelector('span').innerText = message;
    }
    
    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);
    
    // Animate out and remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        // Optionally remove from DOM after transition
        setTimeout(() => {
            if (toast && toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
};
