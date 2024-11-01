<?php include("elements/header/header.html");?>

<header>
    <link rel="stylesheet" href="css/contacto.css">
</header>

    <div class="cont-form">
        <div class="info-form">
            <h2>Contáctanos</h2>
            <p>Por favor envía tus dudas o comentarios en el siguiente formulario.</p>
            <a href="#"><i class="fa fa-phone"></i> 55-1234-5678</a>
            <a href="#"><i class="fa fa-envelope"></i> safetyf@gmail.com</a>
            <a href="#"><i class="fa fa-map-marked"></i> Mexico City, Mexico</a>
        </div>
        <form action="#" autocomplete="off">
            <input type="text" name="nombre" placeholder="Tu Nombre" class="campo">
            <input type="emal" name="email" placeholder="Tu Email" class="campo">
            <textarea id="mensaje" placeholder="Tu Mensaje..."></textarea>
            <input type="submit" name="enviar" value="Enviar Mensaje" class="btn-enviar">
        </form>
    </div>

<?php include("elements/header/footer.html");?>