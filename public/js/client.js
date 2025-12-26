function apiRegister(data) {
    return apiJson('/API/v1/register', 'POST', data);
}

function apiLogin(email, password) {
    return apiJson('/API/v1/login', 'POST', {email, password});
}

function apiLogout() {
    return apiJson('/API/v1/logout', 'POST');
}

function apiFetchMe() {
    return apiJson('/API/v1/me', 'GET');
}

function apiUpdateMe(data) {
    return apiJson('/API/v1/me', 'PUT', data);
}
