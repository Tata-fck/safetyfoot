<?php
include("administrador/cofig/bd.php");

// Configurar encabezados para JSON y CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Leer el carrito enviado desde el cliente
$carrito = json_decode(file_get_contents('php://input'), true);

// Validar que el carrito no esté vacío o mal formado
if (!$carrito || !is_array($carrito) || empty($carrito)) {
    echo json_encode(['error' => 'El carrito está vacío o mal formado.']);
    exit;
}

try {
    $productos = [];
    foreach ($carrito as $id_producto => $tallas) {
        // Validar que las tallas sean un array
        if (!is_array($tallas)) {
            continue; // Ignorar datos mal formados
        }

        // Obtener información del producto
        $sentenciaSQL = $conexion->prepare("
            SELECT p.id, p.nombre, p.marca, p.preciomen, p.preciomay, 
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
            // Convertir precios a números
            $producto['preciomen'] = (float)$producto['preciomen'];
            $producto['preciomay'] = (float)$producto['preciomay'];
        
            // Agregar información del producto y sus tallas
            $productos[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'marca' => $producto['marca'],
                'imagen' => $producto['imagen'],
                'preciomen' => $producto['preciomen'],
                'preciomay' => $producto['preciomay'],
                'tallas' => $tallas
            ];
        } else {
            // Registrar en el log si el producto no se encuentra
            error_log("Producto no encontrado: ID $id_producto");
        }
    }

    // Devolver los productos en formato JSON
    echo json_encode($productos);
} catch (Exception $e) {
    // Manejar errores y devolver un mensaje JSON
    echo json_encode(['error' => 'Error al procesar los datos del carrito.']);
    error_log($e->getMessage()); // Registrar el error en el log del servidor
}