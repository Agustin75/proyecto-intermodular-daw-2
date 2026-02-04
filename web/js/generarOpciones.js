document.addEventListener('DOMContentLoaded', function () {

    const btnGenerar = document.getElementById('generarOpciones');
    const inputNum = document.getElementById('numOpciones');
    const cont = document.getElementById('contenedorOpciones');

    if (!btnGenerar || !inputNum || !cont) return;

    function renderOpciones(num) {
        cont.innerHTML = '';

        for (let i = 0; i < num; i++) {
            const div = document.createElement('div');
            div.classList.add('opcion-item');

            div.innerHTML = `
                <input type="checkbox" name="opcionCorrecta[]" value="${i}">
                <input type="text" name="opcionTexto[]" placeholder="Opción ${i + 1}">
            `;

            cont.appendChild(div);
        }
    }

    // 1) Al cargar la página:
    //    - Si el contenedor YA tiene opciones (las ha pintado PHP desde $params),
    //      no hacemos nada.
    //    - Si está vacío (crear trivia nueva), generamos automáticamente.
    if (cont.children.length === 0) {
        const numInicial = parseInt(inputNum.value) || 0;
        if (numInicial > 0) {
            renderOpciones(numInicial);
        }
    }

    // 2) Al pulsar el botón "Generar":
    //    Siempre regeneramos según el número actual.
    btnGenerar.addEventListener('click', function () {
        const num = parseInt(inputNum.value) || 0;
        if (num <= 0) return;
        renderOpciones(num);
    });

});
