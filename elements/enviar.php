<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $celular = htmlspecialchars($_POST["celular"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    $destinatario = "ventas@grupocapi.com.mx";
    $asunto = "Nuevo mensaje de contacto";

    $cuerpo = "Nombre: $nombre\n";
    $cuerpo .= "Email: $email\n";
    $cuerpo .= "WhatsApp: $celular\n";
    $cuerpo .= "Mensaje:\n$mensaje\n";

    $headers = "From: contacto@clientes.mx\r\nReply-To: $email\n";

    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        echo "<p style='color:green;'>¡MENSAJE ENVIADO CON EXITO!<br>A la brevedad nos comunicaremos con usted</p>";
    } else {
        echo "<p style='color:red;'>Error al enviar el mensaje.</p>";
    }
} else {
    echo "Método no permitido.";
}
?>