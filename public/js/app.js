function register() {
    clearErrors('register')

    apiRegister({
        name: $('#register-name').val(),
        email: $('#register-email').val(),
        date_of_birth: $('#register-date_of_birth').val(),
        password: $('#register-password').val(),
        password_confirm: $('#register-password-confirm').val()
    }).done(() => {
        navigate('login')
    }).fail(xhr => {
        if (xhr.responseJSON?.errors) {
            showErrors('register', xhr.responseJSON?.errors);
        }
    });
}

function login() {
    clearErrors('login')
    apiLogin(
        $('#login-email').val(),
        $('#login-password').val()
    ).done(() => {
        resetSessionCache();
        checkSession(() => navigate('home'));
    }).fail(xhr => {
        if (xhr.responseJSON?.errors) {
            showErrors('login', xhr.responseJSON?.errors);
        }
    });
}

function logout() {
    apiLogout().always(() => {
        resetSessionCache();
        setAuthUI(false);
        navigate('login');
    });
}

function meUpdate() {
    clearErrors('profile')

    apiUpdateMe({
        name: $('#profile-name').val(),
        email: $('#profile-email').val(),
        date_of_birth: $('#profile-date_of_birth').val()
    }).done(() => {
        resetSessionCache();
        checkSession(() => navigate('home'));
    }).fail(xhr => {
        if (xhr.responseJSON?.errors) {
            showErrors('profile', xhr.responseJSON?.errors);
        }
    });
}

$(function () {
    const initialView = location.hash
        ? location.hash.substring(1)
        : 'login';

    checkSession(
        () => navigate(initialView === 'login' ? 'home' : initialView, false),
        () => navigate('login', false)
    );
});
