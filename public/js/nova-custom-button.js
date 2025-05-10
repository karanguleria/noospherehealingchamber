document.addEventListener('DOMContentLoaded', function () {
    if (window.location.pathname === '/nova/resources/invitations/new') {
        const observer = new MutationObserver(() => {
            const btn = document.querySelector('[dusk="create-and-add-another-button"] span');
            if (btn && btn.textContent.trim() === 'Create & Add Another') {
                btn.textContent = 'Send & Invite Another';
                observer.disconnect(); // Stop watching once updated
            }
        });

        // Observe changes in the document body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});
