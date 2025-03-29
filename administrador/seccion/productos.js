// Validar que solo se ingresen números en precio
function soloNumeros(event) {
    var charCode = event.which || event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
        return false;
    }
    return true;
}

// Redimensionar textarea
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.addEventListener("DOMContentLoaded", function () {
    // Redimensionar todos los textareas
    document.querySelectorAll("textarea").forEach(function (textarea) {
        autoResize(textarea);
        textarea.addEventListener('input', () => autoResize(textarea));
    });

    const inputImagen = document.getElementById('txtImagen');
    const previewContainer = document.querySelector('.previsualizacion');
    let archivosSeleccionados = [];

    if (inputImagen) {
        inputImagen.addEventListener('change', function(e) {
            // Obtener los archivos seleccionados recientemente
            const nuevosArchivos = Array.from(e.target.files);
            // Guardar el número previo de archivos para calcular el índice global
            let prevLength = archivosSeleccionados.length;
            // Agregar los nuevos archivos al arreglo global
            archivosSeleccionados = archivosSeleccionados.concat(nuevosArchivos);

            // Actualizar el input con todos los archivos seleccionados
            const dataTransfer = new DataTransfer();
            archivosSeleccionados.forEach(file => dataTransfer.items.add(file));
            inputImagen.files = dataTransfer.files;

            // Generar previsualización para cada archivo nuevo
            nuevosArchivos.forEach((file, index) => {
                const globalIndex = prevLength + index; // Índice único para cada archivo
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'img-container temp-preview';
                    imgContainer.dataset.index = globalIndex; // Guardamos el índice

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-slct';
                    img.style.maxWidth = '150px';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'delete-btn';
                    btn.textContent = 'X';
                    btn.addEventListener('click', function() {
                        // Eliminar el archivo correspondiente usando el índice almacenado
                        const idx = parseInt(imgContainer.dataset.index);
                        archivosSeleccionados.splice(idx, 1);

                        // Actualizar el input con la nueva lista de archivos
                        const newDataTransfer = new DataTransfer();
                        archivosSeleccionados.forEach(file => newDataTransfer.items.add(file));
                        inputImagen.files = newDataTransfer.files;

                        // Eliminar la previsualización y actualizar índices
                        imgContainer.remove();
                        actualizarIndicesPrevisualizaciones();
                    });

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(btn);
                    previewContainer.appendChild(imgContainer);
                };

                reader.readAsDataURL(file);
            });
        });
    }

    // Función para actualizar los índices de cada previsualización después de eliminar una imagen
    function actualizarIndicesPrevisualizaciones() {
        const contenedores = previewContainer.querySelectorAll('.img-container.temp-preview');
        contenedores.forEach((container, index) => {
            container.dataset.index = index;
        });
    }
});

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
