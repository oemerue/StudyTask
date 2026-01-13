(async() => {
    try {
        const res = await fetch("/Studytask/Backend/auth/me.php", { credentials: "include" });

        if (!res.ok) {
            window.location.href = "../site/login.html";
            return;
        }
    } catch {
        window.location.href = "../site/login.html";
    }
})();