// Redimensionamiento de textarea
function autoResize(textarea) {
    textarea.style.height = 'auto'; // Restablece la altura para calcular el nuevo tamaño
    textarea.style.height = textarea.scrollHeight + 'px'; // Ajusta la altura al contenido
}

// Redimensionar automáticamente los textareas con contenido al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("textarea").forEach(function (textarea) {
        autoResize(textarea);
    });

    // Agregar evento para previsualizar imágenes al subir un archivo
    const inputImagen = document.getElementById('txtImagen');
    const previewContainer = document.querySelector('.previsualizacion');

    if (inputImagen) {
        inputImagen.addEventListener('change', function (event) {
            const file = event.target.files[0]; // Obtener el archivo seleccionado

            // Limpiar cualquier previsualización anterior
            previewContainer.innerHTML = '';

            if (file) {
                const reader = new FileReader();

                // Leer el archivo como una URL de datos
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result; // Asignar la URL de datos como fuente de la imagen
                    img.className = 'img-slct'; // Aplicar la clase CSS para el estilo
                    img.style.maxWidth = '150px'; // Ajustar el tamaño de la imagen
                    img.style.margin = '5px';
                    previewContainer.appendChild(img); // Agregar la imagen al contenedor de previsualización
                };

                reader.readAsDataURL(file); // Leer el archivo como una URL de datos
            }
        });
    }
});

// Almacenar imágenes hasta confirmar eliminación
let imagenesParaEliminar = [];

function marcarParaEliminar(nombreImagen) {
    // Agregar la imagen a la lista de imágenes para eliminar
    if (!imagenesParaEliminar.includes(nombreImagen)) {
        imagenesParaEliminar.push(nombreImagen);
    }

    // Actualizar el campo oculto con las imágenes marcadas
    document.getElementById('imagenesParaEliminar').value = imagenesParaEliminar.join(',');

    // Opcional: Ocultar la imagen visualmente
    const imgContainer = event.target.closest('.img-container');
    if (imgContainer) {
        imgContainer.style.display = 'none';
    }
}