const API_BASE = "/Studytask/Backend";

async function apiRequest(path, method = "GET", body = null) {
    const options = {
        method: method,
        headers: { "Content-Type": "application/json" },
        credentials: "include"
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    const response = await fetch(API_BASE + path, options);

    // Antwort IMMER zuerst als Text lesen
    const text = await response.text();

    let data = null;
    if (text) {
        try {
            data = JSON.parse(text);
        } catch (e) {
            // ⛔ kein JSON → echter Serverfehler sichtbar machen
            throw new Error(
                "Server hat kein gültiges JSON geliefert:\n" +
                text.slice(0, 300)
            );
        }
    }

    if (!response.ok) {
        // ✅ KEIN optional chaining
        if (data && data.error) {
            throw new Error(data.error);
        }
        if (data && data.message) {
            throw new Error(data.message);
        }
        throw new Error(response.statusText);
    }

    return data;
}