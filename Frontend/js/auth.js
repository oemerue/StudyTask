async function login(email, password) {
    return apiRequest("/auth/login.php", "POST", {
        email,
        password
    });
}