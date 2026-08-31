// public/js/platform/activity.js

/**
 * Show a toast notification with a message.
 * @param {string} message - Text to display.
 * @param {number} [duration=3000] - How long to show (ms).
 */
function showToast(message, duration = 3000) {
  let toast = document.createElement('div');
  toast.className = 'toast';
  toast.textContent = message;
  document.body.appendChild(toast);
  // Force reflow for transition
  void toast.offsetWidth;
  toast.classList.add('show');
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

/**
 * Toggle loading state on a button.
 * Adds a spinner and disables pointer events.
 * @param {HTMLButtonElement|HTMLAnchorElement} btn
 * @param {boolean} loading
 */
function setButtonLoading(btn, loading = true) {
  if (loading) {
    btn.classList.add('btn-loading');
    // Append spinner if not already present
    if (!btn.querySelector('.platform-action-spinner')) {
      const spinner = document.createElement('span');
      spinner.className = 'platform-action-spinner';
      btn.appendChild(spinner);
    }
  } else {
    btn.classList.remove('btn-loading');
    const spinner = btn.querySelector('.platform-action-spinner');
    if (spinner) spinner.remove();
  }
}

// Attach listeners after DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  // Export to Excel button
  const exportBtn = document.querySelector('a.btn-export');
  if (exportBtn) {
    exportBtn.addEventListener('click', () => {
      setButtonLoading(exportBtn, true);
      // Navigation will happen; reset after brief delay
      setTimeout(() => setButtonLoading(exportBtn, false), 500);
    });
  }

  // Print button
  const printBtn = document.querySelector('button.btn-primary');
  if (printBtn) {
    printBtn.addEventListener('click', () => {
      setButtonLoading(printBtn, true);
      // Assuming printCommissionReport triggers async; hide after 1s
      setTimeout(() => setButtonLoading(printBtn, false), 1000);
    });
  }
});
