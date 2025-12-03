import { apiFetch } from './api.js';

// Login
export function initLoginForm() {
  const form = document.querySelector('form');
  if (!form) return;

  const loginIdInput = document.getElementById('loginId');
  const passwordInput = document.getElementById('password');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = loginIdInput.value.trim();
    const password = passwordInput.value;

    if (!email || !password) {
      alert('Bitte E-Mail/Benutzername und Passwort eingeben.');
      return;
    }

    try {
      const user = await apiFetch('/auth/login.php', {
        method: 'POST',
        body: JSON.stringify({ email, password })
      });

      localStorage.setItem('user', JSON.stringify(user));
      window.location.href = '../app/dashboard.html';
    } catch (err) {
      alert(err.message);
    }
  });
}

// Registrierung
export function initRegisterForm() {
  const form = document.querySelector('form');
  if (!form) return;

  const nameInput = document.getElementById('name');
  const matrikelInput = document.getElementById('matrikelnummer');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const passwordConfirmInput = document.getElementById('passwordConfirm');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const display_name   = nameInput.value.trim();
    const matrikelnummer = matrikelInput.value.trim();
    const email          = emailInput.value.trim();
    const password       = passwordInput.value;
    const passwordConfirm= passwordConfirmInput.value;

    if (!display_name || !matrikelnummer || !email || !password) {
      alert('Bitte alle Felder ausfüllen.');
      return;
    }
    if (password !== passwordConfirm) {
      alert('Die Passwörter stimmen nicht überein.');
      return;
    }

    try {
      const user = await apiFetch('/auth/register.php', {
        method: 'POST',
        body: JSON.stringify({ display_name, matrikelnummer, email, password })
      });

      localStorage.setItem('user', JSON.stringify(user));
      window.location.href = 'login.html';
    } catch (err) {
      alert(err.message);
    }
  });
}
