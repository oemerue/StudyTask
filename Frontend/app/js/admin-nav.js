(async() => {
    try {
        const me = await apiRequest("/auth/me.php", "GET");

        if (me.role !== "ADMIN") return;

        const nav = document.querySelector(".nav-list");
        if (!nav) return;

        // Verhindert doppeltes Einfügen
        if (document.getElementById("adminNavLink")) return;

        const li = document.createElement("li");
        li.id = "adminNavLink";

        li.innerHTML = `
            <a href="/Studytask/Frontend/app/admin/dashboard.html">Admin</a>
        `;

        nav.appendChild(li);

    } catch (e) {
        console.error("Admin-Navigation Fehler", e);
    }
})();