function somenteCamposNumericos() {
    document.addEventListener('input', (event) => {
        const campo = event.target;

        if (!(campo instanceof HTMLInputElement)) return;
        if (!campo.matches('[data-somente-numeros]')) return;

        campo.value = campo.value.replace(/[^0-9]/g, '');
    });
}

somenteCamposNumericos();
