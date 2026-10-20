function iniciarMenu() {
    const toggle = document.querySelector('[data-dropdown="options"]');
    const menu = document.querySelector('.nav-aside');

    if (!toggle || !menu) return;

    const fechar = () => {
        menu.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const alternar = (event) => {
        event.preventDefault();
        const aberto = menu.classList.toggle('active');
        toggle.setAttribute('aria-expanded', String(aberto));
    };

    toggle.addEventListener('click', alternar);

    document.addEventListener('click', (event) => {
        if (menu.classList.contains('active') && !menu.contains(event.target) && event.target !== toggle && !toggle.contains(event.target)) {
            fechar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            fechar();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarMenu);
} else {
    iniciarMenu();
}
