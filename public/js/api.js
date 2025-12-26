function apiJson(url, method, payload) {
    return $.ajax({
        url,
        method,
        data: JSON.stringify(payload),
        contentType: 'application/json',
        dataType: 'json'
    });
}