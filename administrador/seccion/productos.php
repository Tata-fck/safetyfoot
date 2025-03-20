<?php include("../template/cabecera.php")?>
<?php 
$txtID = isset($_POST['txtID']) ? $_POST['txtID'] : "";
$txtMarca = isset($_POST['txtMarca']) ? $_POST['txtMarca'] : "";
$txtNombre = isset($_POST['txtNombre']) ? $_POST['txtNombre'] : "";
$txtPrecio = isset($_POST['txtPrecio']) ? $_POST['txtPrecio'] : "";
$txtDescripcion = isset($_POST['txtDescripcion']) ? $_POST['txtDescripcion'] : "";
$txtImagen = isset($_FILES['txtImagen']['name']) ? $_FILES['txtImagen']['name'] : "";
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../cofig/bd.php");

switch($accion){
    case "agregar":
        // Generar un ID único para el producto
        if (empty($txtID)) {
            $txtID = uniqid(); // Generar un ID único
        }
    
        // Insertar el producto en la tabla `productos`
        $sentenciaSQL = $conexion->prepare("INSERT INTO productos (id, marca, nombre, precio, descripcion) 
            VALUES (:id, :marca, :nombre, :precio, :descripcion)");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':marca', $txtMarca);
        $sentenciaSQL->bindParam(':precio', $txtPrecio);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->execute();
    
        // Usar el ID generado para asociar las imágenes
        $ultimoID = $txtID;
    
        if ($txtImagen != "") {
            $extension = pathinfo($_FILES["txtImagen"]["name"], PATHINFO_EXTENSION);
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
    
            // Contar cuántas imágenes ya existen para este producto
            $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM imagenes WHERE id_producto = :id_producto");
            $sentenciaSQL->bindParam(':id_producto', $ultimoID);
            $sentenciaSQL->execute();
            $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
            $numArchivo = $resultado['total'] + 1;
    
            // Generar el nombre del archivo
            $nombreArchivo = $ultimoID . "-" . $numArchivo . "." . $extension;
    
            if ($tmpImagen != "") {
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
            }
    
            // Insertar el registro en la tabla `imagenes`
            $sentenciaSQL = $conexion->prepare("INSERT INTO imagenes (nom_archivo, num_archivo, id_producto) VALUES (:nom_archivo, :num_archivo, :id_producto)");
            $sentenciaSQL->bindParam(':nom_archivo', $nombreArchivo);
            $sentenciaSQL->bindParam(':num_archivo', $numArchivo);
            $sentenciaSQL->bindParam(':id_producto', $ultimoID);
            $sentenciaSQL->execute();
        }
    
        header("Location: productos.php");
        break;  

    case "modificar":
        // Procesar las imágenes marcadas para eliminación
        if (!empty($_POST['imagenesParaEliminar'])) {
            $imagenesParaEliminar = explode(',', $_POST['imagenesParaEliminar']);
            foreach ($imagenesParaEliminar as $imagen) {
                // Eliminar el archivo de imagen del sistema de archivos
                $rutaImagen = "../../img/" . $imagen;
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
    
                // Eliminar el registro de la imagen de la tabla `imagenes`
                $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE nom_archivo = :nom_archivo");
                $sentenciaSQL->bindParam(':nom_archivo', $imagen);
                $sentenciaSQL->execute();
            }
        }
    
        // Actualizar los datos del producto
        $sentenciaSQL = $conexion->prepare("UPDATE productos SET marca = :marca, nombre = :nombre, precio = :precio, descripcion = :descripcion WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':marca', $txtMarca);
        $sentenciaSQL->bindParam(':precio', $txtPrecio);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->execute();
    
        // Procesar nuevas imágenes subidas
        if (!empty($_FILES['txtImagen']['name'])) {
            $extension = pathinfo($_FILES["txtImagen"]["name"], PATHINFO_EXTENSION);
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
    
            // Contar cuántas imágenes ya existen para este producto
            $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM imagenes WHERE id_producto = :id_producto");
            $sentenciaSQL->bindParam(':id_producto', $txtID);
            $sentenciaSQL->execute();
            $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
            $numArchivo = $resultado['total'] + 1;
    
            // Generar el nombre del archivo
            $nombreArchivo = $txtID . "-" . $numArchivo . "." . $extension;
    
            if ($tmpImagen != "") {
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
            }
    
            // Insertar el registro en la tabla `imagenes`
            $sentenciaSQL = $conexion->prepare("INSERT INTO imagenes (nom_archivo, num_archivo, id_producto) VALUES (:nom_archivo, :num_archivo, :id_producto)");
            $sentenciaSQL->bindParam(':nom_archivo', $nombreArchivo);
            $sentenciaSQL->bindParam(':num_archivo', $numArchivo);
            $sentenciaSQL->bindParam(':id_producto', $txtID);
            $sentenciaSQL->execute();
        }
    
        header("Location: productos.php");
        break;

    case "cancelar":
        // Limpiar las variables del formulario
        $txtID = "";
        $txtMarca = "";
        $txtNombre = "";
        $txtPrecio = "";
        $txtDescripcion = "";
        $txtImagen = "";
        $imagenes = [];

        // Redirigir al usuario a la página principal
        header("Location: productos.php");
        break;

    case "select":
        // Obtener los datos del producto
        $sentenciaSQL = $conexion->prepare("SELECT * FROM productos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
        // Cargar los datos del producto en las variables
        $txtID = $producto['id'];
        $txtMarca = $producto['marca'];
        $txtNombre = $producto['nombre'];
        $txtPrecio = $producto['precio'];
        $txtDescripcion = $producto['descripcion'];
    
        // Obtener las imágenes asociadas al producto
        $sentenciaSQL = $conexion->prepare("SELECT nom_archivo FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
        $imagenes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    
        // Si hay imágenes, cargar la primera imagen en `$txtImagen` para previsualización
        if (!empty($imagenes)) {
            $txtImagen = $imagenes[0]['nom_archivo']; // Cargar la primera imagen
        } else {
            $txtImagen = ""; // No hay imágenes
        }
    break;

    case "borrar":
        // Obtener las imágenes asociadas al producto
        $sentenciaSQL = $conexion->prepare("SELECT nom_archivo FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
        $imagenes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    
        // Eliminar los archivos de imagen del sistema de archivos
        foreach ($imagenes as $imagen) {
            $rutaImagen = "../../img/" . $imagen['nom_archivo'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
    
        // Eliminar las imágenes de la tabla `imagenes`
        $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
    
        // Eliminar el producto de la tabla `productos`
        $sentenciaSQL = $conexion->prepare("DELETE FROM productos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
    
        header("Location: productos.php");
        break;

    case "eliminarImagen":
        if (!empty($_POST['imagenEliminar'])) {
            $imagenEliminar = $_POST['imagenEliminar'];

            // Eliminar el archivo de imagen del sistema de archivos
            $rutaImagen = "../../img/" . $imagenEliminar;
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }

            // Eliminar el registro de la imagen de la tabla `imagenes`
            $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE nom_archivo = :nom_archivo");
            $sentenciaSQL->bindParam(':nom_archivo', $imagenEliminar);
            $sentenciaSQL->execute();
        }

        header("Location: productos.php");
        break;
}

if ($accion !== "select") {
    $txtID = "";
    $txtMarca = "";
    $txtNombre = "";
    $txtImagen = "";
    $txtPrecio = "";
    $txtDescripcion = "";
}

$sentenciaSQL = $conexion->prepare("SELECT p.id, p.marca, p.nombre, p.precio, p.descripcion,
    GROUP_CONCAT(i.nom_archivo) AS imagenes
    FROM productos p
    LEFT JOIN imagenes i ON p.id = i.id_producto
    GROUP BY p.id
");
$sentenciaSQL->execute();
$listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/productos.css">

<body>
    <div class="contenedor-principal">
        <h2 class="tit-bd">Administrador de Catálogo</h2>

        <!-- Contenedor principal con dos columnas -->
        <div class="grid-contenedor">
            <!-- Columna izquierda: Formulario -->
            <div class="columna-formulario">
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" required class="campo" value="<?php echo $txtID; ?>" name="txtID" placeholder="ID">
                    <input type="text" class="campo" value="<?php echo $txtMarca; ?>" name="txtMarca" placeholder="Marca">
                    <input type="text" required class="campo" value="<?php echo $txtNombre; ?>" name="txtNombre" placeholder="Nombre del Producto">
                    <input type="text" required class="campo" value="<?php echo $txtPrecio; ?>" name="txtPrecio" placeholder="Precio">
                    <textarea required class="campo" name="txtDescripcion" placeholder="Descripción" oninput="autoResize(this)"><?php echo $txtDescripcion; ?></textarea>
                    
                    <!-- Botón de selección de imagen -->
                    <label for="txtImagen" class="file-label">Añadir Imagen</label>
                    <input type="file" name="txtImagen" id="txtImagen" accept=".jpg, .jpeg, .png" class="file-label" style="display: none;">

                    <!-- Previsualización de imagen -->
                    <div class="previsualizacion">
                        <?php if (!empty($imagenes)) { ?>
                            <?php foreach ($imagenes as $imagen) { ?>
                                <div class="img-container">
                                    <img src="../../img/<?php echo $imagen['nom_archivo']; ?>" class="img-slct">
                                    <button type="button" class="delete-btn" onclick="marcarParaEliminar('<?php echo $imagen['nom_archivo']; ?>')">X</button>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p>No hay imágenes asociadas a este producto.</p>
                        <?php } ?>
                    </div>

                    <!-- Botones de acción -->
                    <div class="botones-acciones">
                        <input type="submit" name="accion" value="agregar" class="btn-enviar">
                        <input type="submit" name="accion" value="modificar" class="btn-enviar">
                        <input type="submit" name="accion" value="cancelar" class="btn-enviar">
                        <input type="hidden" id="imagenesParaEliminar" name="imagenesParaEliminar" value="">
                    </div>
                </form>
            </div>

            <!-- Columna derecha: Tabla -->
            <div class="columna-tabla">
                <table class="contenido-table">
                    <thead>
                        <tr>
                            <th>Imágenes</th>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaProductos as $producto) { ?>
                        <tr>
                            <td>
                                <?php
                                // Mostrar todas las imágenes asociadas al producto
                                $imagenes = explode(',', $producto['imagenes']);
                                foreach ($imagenes as $imagen) {
                                    if (!empty($imagen)) {
                                        echo '<img src="../../img/' . $imagen . '" class="img-table" style="max-width: 100px; margin: 5px;">';
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo $producto['marca']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td><?php echo $producto['descripcion']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="txtID" value="<?php echo $producto['id']; ?>"/>
                                    <input type="submit" name="accion" value="select" class="btn-slct"/>
                                    <input type="submit" name="accion" value="borrar" class="btn-borrar"/>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="productos.js"></script> 

</body>

<?php include("../template/pie.php")?>