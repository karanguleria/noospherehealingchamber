document.addEventListener("DOMContentLoaded", function () {
    const path = window.location.pathname;
    //alert('path');
    // Match exact login path (Nova default: /nova/login)
    if (path === '/nova/login') {
        document.body.classList.add('nova-login-page');
    }
});
