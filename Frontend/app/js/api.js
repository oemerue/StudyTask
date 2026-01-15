export const API_BASE = '/StudyTask/backend'; // ggf. Ordnernamen anpassen

export async function apiFetch(path, options = {}) {
  const res = await fetch(`${API_BASE}${path}`, {
    credentials: 'include',                 // für PHP-Session
    headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options
  });

  const data = await res.json().catch(() => null);

  if (!res.ok) {
    const msg = data && data.error ? data.error : 'Unbekannter Serverfehler';
    throw new Error(msg);
  }

  return data;
}
