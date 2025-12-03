// Jahr im Footer
document.getElementById('y').textContent = new Date().getFullYear();

// Mobile Navigation
const toggle = document.querySelector('.nav-toggle');
const list = document.querySelector('.nav-list');
if (toggle && list) {
    toggle.addEventListener('click', () => {
        const open = list.classList.toggle('show');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
}

// Platzhalter – hier kommt später echte Logik (Fetch/API etc.)
console.log('StudyTasks starter ready.');