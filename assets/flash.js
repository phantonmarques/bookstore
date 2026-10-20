function iniciarFlashes() {
    document.querySelectorAll('[data-flash]').forEach((elemento) => {
        if (elemento.dataset.inicializado) return;

        elemento.dataset.inicializado = 'true';

        const fechar = () => {
            clearTimeout(temporizador);
            elemento.classList.add('is-closing');
            setTimeout(() => elemento.remove(), 200);
        };

        const temporizador = setTimeout(fechar, 8000);

        elemento.querySelector('[data-flash-close]')
            .addEventListener('click', fechar, { once: true });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarFlashes);
} else {
    iniciarFlashes();
}
