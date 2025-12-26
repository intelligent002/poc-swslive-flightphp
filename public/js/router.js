function showView(view, push = true) {
    $('.view').addClass('d-none');
    $('#' + view).removeClass('d-none');

    if (push) {
        history.pushState({view}, '', '#' + view);
    }
}

function navigate(view, push = true) {
    const protectedViews = ['home', 'profile'];

    if (protectedViews.includes(view)) {
        return checkSession(
            () => showView(view, push),
            () => {
            }
        );
    }

    showView(view, push);
}

window.onpopstate = e => {
    navigate(e.state?.view || 'login', false);
};
