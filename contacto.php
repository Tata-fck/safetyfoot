<?php include("elements/header/header.html");?>

    <link rel="stylesheet" href="css/contacto.css">

    <div class="main-content">
        <div class="cont-form">
            <div class="info-form">
                <h2>Contáctanos</h2>
                <p>Por favor envía tus dudas o comentarios en el siguiente formulario.</p>
                <a href="https://wa.me/5215613199804" target="_blank"><i class="fa fa-phone"></i> 55-1234-5678</a>
                <a href="mailto:ventas@grupocapi.com.mx?subject=Razon%20de%20Contacto&body=Nombre: %0AEmpresa: %0ATelefono: %0AComentario: %0A" target="_blank">
                    <i class="fa fa-envelope"></i> ventas@grupocapi.com.mx </a>
                <a href="https://maps.app.goo.gl/i3JnuHWJKzqqUZfX6?g_st=com.google.maps.preview.copy"><i class="fa fa-map-marked"></i> C. Juan E. Hernández y Davalos 85b, Algarín, Cuauhtémoc, 06880 Ciudad de México, CDMX</a>
            </div>
            <form id="contactForm" autocomplete="off">
                <input type="hidden" name="origen" value="contacto">
                <input type="text" name="nombre" placeholder="Tu Nombre" class="campo">
                <input type="email" name="email" placeholder="Tu Email" class="campo">
                <input type="tel" name="celular" placeholder="Tu WhatsApp" class="campo">
                <textarea id="mensaje" name= "mensaje" placeholder="Tu Mensaje..."></textarea>
                <input type="submit" name="enviar" value="Enviar Mensaje" class="btn-enviar">
            </form>
            
            <p id="responseMessage"></p>
        </div>
    </div>

<script>
document.getElementById("contactForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    var formData = new FormData(this);

    fetch("elements/enviar.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("responseMessage").innerHTML = data; 
    })
    .catch(error => {
        document.getElementById("responseMessage").innerHTML = "<p style='color:red;'>Error al enviar.</p>";
    });
});
</script>


<?php include("elements/footer/footer.html");?>