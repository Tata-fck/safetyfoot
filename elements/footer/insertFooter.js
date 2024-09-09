document.addEventListener("DOMContentLoaded", function() {

    // Cargar e insertar el pie de página
    fetch('elements/footer/footer.html')
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('beforeend', data);
        });
});