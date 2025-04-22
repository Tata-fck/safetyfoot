<?php
include("administrador/cofig/bd.php");

// Configurar encabezados para JSON
header('Content-Type: application/json');

// Leer el carrito enviado desde el cliente
$carrito = json_decode(file_get_contents('php://input'), true);

// Validar que el carrito no esté vacío
if (!$carrito || empty($carrito)) {
    echo json_encode([]);
    exit;
}

try {
    $productos = [];
    foreach ($carrito as $id_producto => $tallas) {
        if (!is_array($tallas)) {
            continue; // Ignorar datos mal formados
        }

        // Obtener información del producto
        $sentenciaSQL = $conexion->prepare("
            SELECT p.id, p.nombre, 
                   (SELECT i.nom_archivo FROM imagenes i 
                    WHERE i.id_producto = p.id 
                    ORDER BY i.num_archivo ASC LIMIT 1) AS imagen
            FROM productos p 
            WHERE p.id = :id_producto
        ");
        $sentenciaSQL->bindParam(':id_producto', $id_producto, PDO::PARAM_STR);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            // Agregar información del producto y sus tallas
            $productos[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'imagen' => $producto['imagen'],
                'tallas' => $tallas
            ];
        }
    }

    // Devolver los productos en formato JSON
    echo json_encode($productos);
} catch (Exception $e) {
    // Manejar errores y devolver un mensaje JSON
    echo json_encode(['error' => 'Error al procesar los datos del carrito.']);
    error_log($e->getMessage()); // Registrar el error en el log del servidor
}