<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ...otros campos...
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $metodo = filter_input(INPUT_POST, "metodo", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $celular = filter_input(INPUT_POST, "celular", FILTER_SANITIZE_STRING);
    $mensaje = filter_input(INPUT_POST, "mensaje", FILTER_SANITIZE_STRING);
    $origen = filter_input(INPUT_POST, "origen", FILTER_SANITIZE_STRING);

    // Usar el carrito detallado
    $carritoDetallado = isset($_POST['carrito_detallado']) ? json_decode($_POST['carrito_detallado'], true) : [];

    // ...validaciones...

    $cuerpo = '
    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #222;">
        <p style="font-size:20px; font-weight:bold; color:#943838; margin-bottom:10px;">Nuevo Pedido!!</p>
        <p style="font-size:15px;"><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>
        <p style="font-size:15px;"><strong>Método de Contacto:</strong> ' . htmlspecialchars($metodo) . '</p>
        <p style="font-size:15px;"><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p style="font-size:15px;"><strong>WhatsApp:</strong> ' . htmlspecialchars($celular) . '</p>
        <p style="font-size:15px;"><strong>Mensaje:</strong></p>
        <div style="background:#f8f8f8; border-radius:6px; padding:10px 15px; margin-bottom:20px;">' . nl2br(htmlspecialchars($mensaje)) . '</div>
    ';

    if ($origen === "compra" && $carritoDetallado && is_array($carritoDetallado)) {
        $cuerpo .= '
        <h3 style="color:#943838; margin-top:30px;">Resumen del Carrito</h3>
        <table style="border-collapse:collapse; width:90%; font-size:18px; margin-bottom:20px;">
            <tr style="background:#f2f2f2;">
                <th style="padding:12px 8px; border:1px solid #ddd;">ID</th>
                <th style="padding:12px 8px; border:1px solid #ddd;">Marca</th>
                <th style="padding:12px 8px; border:1px solid #ddd;">Modelo</th>
                <th style="padding:12px 8px; border:1px solid #ddd;">Cantidades</th>
                <th style="padding:12px 8px; border:1px solid #ddd;">Precio Unitario</th>
                <th style="padding:12px 8px; border:1px solid #ddd;">Precio Total</th>
            </tr>
        ';

        foreach ($carritoDetallado as $producto) {
            $totalCantidad = 0;
            $detalleTallas = "";

            foreach ($producto['tallas'] as $talla => $cantidad) {
                $detalleTallas .= '<span style="display:inline-block; min-width:40px; margin-right:8px;">' . htmlspecialchars($talla) . ': <strong>' . htmlspecialchars($cantidad) . '</strong></span><br>';
                $totalCantidad += (int)$cantidad;
            }

            $precioUnitario = ($totalCantidad > 5) ? $producto['preciomay'] : $producto['preciomen'];
            $precioTotal = $precioUnitario * $totalCantidad;

            // Mostrar ambos precios si aplica mayoreo
            $precioUnitarioHtml = '';
            if ($totalCantidad > 5) {
                $precioUnitarioHtml = '<span style="text-decoration:line-through; color:#b71c1c;">$' . number_format($producto['preciomen'], 2) . '</span> 
                    <span style="color:#388e3c; font-weight:bold;">$' . number_format($producto['preciomay'], 2) . ' c/u</span>';
            } else {
                $precioUnitarioHtml = '<span style="color:#222;">$' . number_format($producto['preciomen'], 2) . ' c/u</span>';
            }

            $cuerpo .= '
            <tr>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center;">' . htmlspecialchars($producto['id']) . '</td>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center;">' . htmlspecialchars($producto['marca']) . '</td>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center;">' . htmlspecialchars($producto['nombre']) . '</td>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center;">' . $detalleTallas . '</td>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center;">' . $precioUnitarioHtml . '</td>
                <td style="padding:10px 8px; border:1px solid #ddd; text-align:center; font-weight:bold;">$' . number_format($precioTotal, 2) . '</td>
            </tr>
            ';
        }

        $cuerpo .= '</table>';
    }

    $cuerpo .= '<p style="font-size:14px; color:#888; margin-top:30px;">Este mensaje fue generado automáticamente desde el sitio web de GrupoCAPI.</p></div>';


    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Enviar correo
    if (mail("ventas@grupocapi.com.mx", "NUEVO PEDIDO DE " . $nombre . "!!!!", $cuerpo, $headers)) {
        echo "<p style='color:green;'>¡Mensaje enviado con éxito!</p>";
    } else {
        echo "<p style='color:red;'>Error al enviar el mensaje. Inténtalo más tarde.</p>";
    }
}
?>