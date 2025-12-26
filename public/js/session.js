let currentUser = null;
let sessionChecked = false;

function resetSessionCache() {
    currentUser = null;
    sessionChecked = false;
}

function checkSession(onAuth, onGuest) {
    if (sessionChecked && currentUser) {
        return onAuth && onAuth();
    }

    if (sessionChecked && !currentUser) {
        return onGuest && onGuest();
    }

    apiFetchMe()
        .done(res => {
            sessionChecked = true;
            currentUser = res.data;
            fillUser(currentUser);
            setAuthUI(true);
            onAuth && onAuth();
        })
        .fail(() => {
            sessionChecked = true;
            currentUser = null;
            setAuthUI(false);
            showView('login');
            onGuest && onGuest();
        });
}
