function syncActiveHashLink() {
    document.querySelectorAll('[data-hash-nav] a.sheet-subnav-link').forEach((link) => {
        const hash = link.getAttribute('href').split('#')[1];
        link.classList.toggle('sheet-subnav-link--active', hash === location.hash.slice(1));
    });
}

window.addEventListener('hashchange', syncActiveHashLink);
document.addEventListener('DOMContentLoaded', syncActiveHashLink);
