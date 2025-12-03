// Frontend/js/logout.js

// Pfad zu deinem Backend anpassen, falls dein Ordner nicht "StudyTask" heißt
const API_BASE = '/StudyTask/backend';

async function doLogout() {
  try {
    // Backend-Logout (PHP-Session zerstören)
    await fetch(`${API_BASE}/auth/logout.php`, {
      method: 'POST',
      credentials: 'include'
    });
  } catch (e) {
    console.error('Fehler beim Logout-Request:', e);
  }

  // Frontend-Daten löschen
  localStorage.clear();
  sessionStorage.clear();

}

// Automatisch ausführen, sobald die Seite geladen ist
document.addEventListener('DOMContentLoaded', doLogout);
