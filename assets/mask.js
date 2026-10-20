import IMask from 'imask';

const inicializados = new WeakSet();

function iniciarMascaras() {
    document.querySelectorAll('[data-mascara-preco]').forEach((campo) => {
        if (inicializados.has(campo)) return;

        IMask(campo, {
            mask: Number,
            scale: 2,
            radix: ',',
            thousandsSeparator: '.',
            mapToRadix: [],
            min: 0,
            max: 99999999.99,
            normalizeZeros: true,
            padFractionalZeros: true,
        });

        inicializados.add(campo);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarMascaras);
} else {
    iniciarMascaras();
}
