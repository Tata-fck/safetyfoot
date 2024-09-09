document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('mensaje');

    function adjustHeight() {
        textarea.style.height = 'auto'; // Resetea la altura
        textarea.style.height = textarea.scrollHeight + 'px'; // Ajusta la altura al contenido
    }

    textarea.addEventListener('input', adjustHeight); // Llama a adjustHeight al escribir
    adjustHeight(); // Ajusta la altura inicial
});