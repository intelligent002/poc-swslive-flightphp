function setAuthUI(loggedIn) {
    $('#menu-guest').toggleClass('d-none', loggedIn);
    $('#menu-auth').toggleClass('d-none', !loggedIn);
}

function fillUser(u) {
    $('#home-name').text(u.name);
    $('#home-email').text(u.email);
    $('#home-date_of_birth').text(u.date_of_birth);

    $('#profile-name').val(u.name);
    $('#profile-email').val(u.email);
    $('#profile-date_of_birth').val(u.date_of_birth);
}

function showErrors(prefix, errors) {
    for (const field in errors) {
        const input = $(`#${prefix}-${field}`);
        input.addClass('is-invalid');
        $(`#err-${prefix}-${field}`).text(errors[field]);
    }
}

function clearErrors(prefix) {
    $(`[id^="${prefix}-"]`).removeClass('is-invalid');
    $(`[id^="err-${prefix}-"]`).text('');
}