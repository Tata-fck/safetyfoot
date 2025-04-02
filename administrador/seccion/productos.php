<?php include("../template/cabecera.php")?>
<?php 
// Recuperar datos del formulario
$txtID = isset($_POST['txtID']) ? $_POST['txtID'] : "";
$txtMarca = isset($_POST['txtMarca']) ? $_POST['txtMarca'] : "";
$txtNombre = isset($_POST['txtNombre']) ? $_POST['txtNombre'] : "";
$txtPrecioMen = isset($_POST['txtPrecioMen']) ? $_POST['txtPrecioMen'] : "";
$txtPrecioMay = isset($_POST['txtPrecioMay']) ? $_POST['txtPrecioMay'] : "";
$txtDescripcion = isset($_POST['txtDescripcion']) ? $_POST['txtDescripcion'] : "";
// Se recibe el arreglo de imágenes (no modifiques la parte de previsualización)
$txtImagen = isset($_FILES['txtImagen']) ? $_FILES['txtImagen'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../cofig/bd.php");

switch($accion){
    case "agregar":
        // Si no se envía ID se genera uno único
        if(empty($txtID)){
            $txtID = uniqid();
        }
        // Insertar el producto en la tabla `productos`
        $sentenciaSQL = $conexion->prepare("INSERT INTO productos (id, marca, nombre, preciomen, preciomay, descripcion) 
            VALUES (:id, :marca, :nombre, :preciomen, :preciomay, :descripcion)");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':marca', $txtMarca);
        $sentenciaSQL->bindParam(':preciomen', $txtPrecioMen);
        $sentenciaSQL->bindParam(':preciomay', $txtPrecioMay);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->execute();

        // Procesar todas las imágenes subidas
        if($txtImagen && !empty($txtImagen['name'][0])){
            foreach($txtImagen['name'] as $i => $imagenName) {
                if($imagenName != ""){
                    $extension = pathinfo($imagenName, PATHINFO_EXTENSION);
                    $tmpImagen = $txtImagen["tmp_name"][$i];
                    
                    // Contar las imágenes actuales para este producto (incluso si se vuelven a insertar)
                    $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM imagenes WHERE id_producto = :id_producto");
                    $sentenciaSQL->bindParam(':id_producto', $txtID);
                    $sentenciaSQL->execute();
                    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
                    $numArchivo = $resultado['total'] + 1;
                    
                    // Generar el nombre del archivo siguiendo las reglas
                    $nombreArchivo = $txtID . "-" . $numArchivo . "." . $extension;
                    
                    if($tmpImagen != ""){
                        move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
                    }
                    
                    // Insertar la imagen en la tabla usando INSERT IGNORE para evitar duplicados
                    $sentenciaSQL = $conexion->prepare("INSERT IGNORE INTO imagenes (nom_archivo, num_archivo, id_producto) VALUES (:nom_archivo, :num_archivo, :id_producto)");
                    $sentenciaSQL->bindParam(':nom_archivo', $nombreArchivo);
                    $sentenciaSQL->bindParam(':num_archivo', $numArchivo);
                    $sentenciaSQL->bindParam(':id_producto', $txtID);
                    $sentenciaSQL->execute();
                }
            }
        }
        header("Location: productos.php");
        break;
        
    case "modificar":
        // Procesar imágenes marcadas para eliminación
        if(!empty($_POST['imagenesParaEliminar'])){
            $imagenesParaEliminar = explode(',', $_POST['imagenesParaEliminar']);
            foreach($imagenesParaEliminar as $imagen){
                $rutaImagen = "../../img/" . $imagen;
                if(file_exists($rutaImagen)){
                    unlink($rutaImagen);
                }
                $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE nom_archivo = :nom_archivo");
                $sentenciaSQL->bindParam(':nom_archivo', $imagen);
                $sentenciaSQL->execute();
            }
        }
        
        // Actualizar datos del producto
        $sentenciaSQL = $conexion->prepare("UPDATE productos SET marca = :marca, nombre = :nombre, preciomen = :preciomen, preciomay = :preciomay, descripcion = :descripcion WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':marca', $txtMarca);
        $sentenciaSQL->bindParam(':preciomen', $txtPrecioMen);
        $sentenciaSQL->bindParam(':preciomay', $txtPrecioMay);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->execute();
        
        // Procesar nuevas imágenes subidas (se reinsertan todas usando INSERT IGNORE)
        if($txtImagen && !empty($txtImagen['name'][0])){
            foreach($txtImagen['name'] as $i => $imagenName) {
                if($imagenName != ""){
                    $extension = pathinfo($imagenName, PATHINFO_EXTENSION);
                    $tmpImagen = $txtImagen["tmp_name"][$i];
                    
                    // Contar las imágenes actuales para este producto
                    $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM imagenes WHERE id_producto = :id_producto");
                    $sentenciaSQL->bindParam(':id_producto', $txtID);
                    $sentenciaSQL->execute();
                    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
                    $numArchivo = $resultado['total'] + 1;
                    
                    $nombreArchivo = $txtID . "-" . $numArchivo . "." . $extension;
                    
                    if($tmpImagen != ""){
                        move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
                    }
                    
                    // Insertar la imagen utilizando INSERT IGNORE
                    $sentenciaSQL = $conexion->prepare("INSERT IGNORE INTO imagenes (nom_archivo, num_archivo, id_producto) VALUES (:nom_archivo, :num_archivo, :id_producto)");
                    $sentenciaSQL->bindParam(':nom_archivo', $nombreArchivo);
                    $sentenciaSQL->bindParam(':num_archivo', $numArchivo);
                    $sentenciaSQL->bindParam(':id_producto', $txtID);
                    $sentenciaSQL->execute();
                }
            }
        }
        header("Location: productos.php");
        break;
        
    case "cancelar":
        $txtID = "";
        $txtMarca = "";
        $txtNombre = "";
        $txtPrecioMen = "";
        $txtPrecioMay = "";
        $txtDescripcion = "";
        $txtImagen = "";
        $imagenes = [];
        header("Location: productos.php");
        break;
        
    case "select":
        // Obtener los datos del producto seleccionado
        $sentenciaSQL = $conexion->prepare("SELECT * FROM productos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $txtID = $producto['id'];
        $txtMarca = $producto['marca'];
        $txtNombre = $producto['nombre'];
        $txtPrecioMen = $producto['preciomen'];
        $txtPrecioMay = $producto['preciomay'];
        $txtDescripcion = $producto['descripcion'];
        
        // Obtener imágenes asociadas al producto
        $sentenciaSQL = $conexion->prepare("SELECT nom_archivo FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
        $imagenes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
        
        // Para la previsualización, se carga la primera imagen (si existe)
        if(!empty($imagenes)){
            $txtImagen = $imagenes[0]['nom_archivo'];
        } else {
            $txtImagen = "";
        }
        break;
        
    case "borrar":
        // Eliminar imágenes asociadas
        $sentenciaSQL = $conexion->prepare("SELECT nom_archivo FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
        $imagenes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
        foreach($imagenes as $imagen){
            $rutaImagen = "../../img/" . $imagen['nom_archivo'];
            if(file_exists($rutaImagen)){
                unlink($rutaImagen);
            }
        }
        $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE id_producto = :id_producto");
        $sentenciaSQL->bindParam(':id_producto', $txtID);
        $sentenciaSQL->execute();
        
        // Eliminar el producto
        $sentenciaSQL = $conexion->prepare("DELETE FROM productos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: productos.php");
        break;
        
    case "eliminarImagen":
        if(!empty($_POST['imagenEliminar'])){
            $imagenEliminar = $_POST['imagenEliminar'];
            $rutaImagen = "../../img/" . $imagenEliminar;
            if(file_exists($rutaImagen)){
                unlink($rutaImagen);
            }
            $sentenciaSQL = $conexion->prepare("DELETE FROM imagenes WHERE nom_archivo = :nom_archivo");
            $sentenciaSQL->bindParam(':nom_archivo', $imagenEliminar);
            $sentenciaSQL->execute();
        }
        header("Location: productos.php");
        break;
}

if($accion !== "select"){
    $txtID = "";
    $txtMarca = "";
    $txtNombre = "";
    $txtImagen = "";
    $txtPrecioMen = "";
    $txtPrecioMay = "";
    $txtDescripcion = "";
}

$sentenciaSQL = $conexion->prepare("SELECT p.id, p.marca, p.nombre, p.preciomen, p.preciomay, p.descripcion,
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
                    <input type="number" required class="campo-precio" value="<?php echo $txtPrecioMen; ?>" name="txtPrecioMen" placeholder="Precio Menudeo" onkeypress="return soloNumeros(event);">
                    <input type="number" required class="campo-precio" value="<?php echo $txtPrecioMay; ?>" name="txtPrecioMay" placeholder="Precio Mayoreo" onkeypress="return soloNumeros(event);">
                    <textarea required class="campo" name="txtDescripcion" placeholder="Descripción" oninput="autoResize(this)"><?php echo $txtDescripcion; ?></textarea>
                    
                    <!-- Botón de selección de imagen -->
                    <label for="txtImagen" class="file-label">Añadir Imagen</label>
                    <input type="file" name="txtImagen[]" id="txtImagen" accept=".jpg, .jpeg, .png" class="file-label" style="display: none;" multiple>


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
                            <?php if (empty($imagenes) && $accion === "select") { ?>
                                <p>No hay imágenes asociadas a este producto.</p>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <!-- Botones de acción -->
                    <div class="botones-acciones">
                        <?php if($accion !== "select"): ?>
                            <input type="submit" name="accion" value="agregar" class="btn-enviar">
                        <?php endif; ?>
                        
                        <?php if($accion === "select"): ?>
                            <input type="submit" name="accion" value="modificar" class="btn-enviar">
                        <?php endif; ?>
                        
                        <input type="submit" name="accion" value="cancelar" class="btn-borrar">
                        <input type="hidden" name="imagenesParaEliminar" id="imagenesParaEliminar" value="">
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
                            <th>Precio Men</th>
                            <th>Precio May</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaProductos as $producto) { ?>
                        <tr>
                            <td>
                                <div class="imagenes-grid">
                                    <?php
                                    // Mostrar todas las imágenes asociadas al producto
                                    $imagenes = explode(',', $producto['imagenes']);
                                    foreach ($imagenes as $imagen) {
                                        if (!empty($imagen)) {
                                            echo '<img src="../../img/' . $imagen . '" class="img-table">';
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo $producto['marca']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['preciomen']; ?></td>
                            <td><?php echo $producto['preciomay']; ?></td>
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