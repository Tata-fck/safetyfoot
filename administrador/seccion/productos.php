<?php include("../template/cabecera.php")?>
<?php 
$txtID=(ISSET($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(ISSET($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(ISSET($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";

$accion=(ISSET($_POST['accion']))?$_POST['accion']:"";

include("../cofig/bd.php");

switch($accion){
    case "agregar":
        $sentenciaSQL=$conexion->prepare("INSERT INTO zapato(nombre,imagen) VALUES (:nombre,:imagen);");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);

        $fecha= new DateTime();
        $nombreArchivo=($txtImagen != "")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen != ""){
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }
            
        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();

        header("Location:productos.php");
        break;

    case "modificar":
        $sentenciaSQL=$conexion->prepare("UPDATE zapato SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();

        if($txtImagen != ""){
            $fecha= new DateTime();
            $nombreArchivo=($txtImagen != "")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL=$conexion->prepare("SELECT imagen FROM zapato WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $zapato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($zapato["imagen"]) && ($zapato["imagen"]!="imagen.jpg")){
                if(file_exists("../../img/".$zapato["imagen"])){
                    unlink("../../img/".$zapato["imagen"]);
                }
            }

            $sentenciaSQL=$conexion->prepare("UPDATE zapato SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
        }

        header("Location:productos.php");
        break;
    case "cancelar":
        header("Location:productos.php");
        break;
    case "select":
        $sentenciaSQL=$conexion->prepare("SELECT * FROM zapato WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $zapato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre=$zapato['nombre'];
        $txtImagen=$zapato['imagen'];

        echo "<br/>".$txtImagen;

        break;
    case "borrar":
        $sentenciaSQL=$conexion->prepare("SELECT imagen FROM zapato WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $zapato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($zapato["imagen"]) && ($zapato["imagen"]!="imagen.jpg")){
            if(file_exists("../../img/".$zapato["imagen"])){
                unlink("../../img/".$zapato["imagen"]);
            }
        }

        $sentenciaSQL=$conexion->prepare("DELETE FROM zapato WHERE id = :id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();

        header("Location:productos.php");
        break;
}

if ($accion !== "select") {
    $txtID = "";
    $txtNombre = "";
    $txtImagen = "";
}

$sentenciaSQL=$conexion->prepare("SELECT * FROM zapato");
$sentenciaSQL->execute();
$listaZapatos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/productos.css">

<body>
    <h2 class="tit-bd">Administrador de Catalogo</h2>
    <div class="cont-form">
        <div class="info-form">
        <nav class="menu-bd">
            <div class="menu-itms" id="menu-bd">
                <a href="<?php echo $url;?>/administrador/inicio.php" class="activo">Añadir</a>
                <a href="<?php echo $url;?>/administrador/seccion/productos.php">Editar</a>
                <a href="<?php echo $url;?>/inicio.php">Eliminar</a>
            </div>
        </nav>
            <form method="POST" enctype="multipart/form-data">
            <input type="text" class="campo" required readonly class="" value="<?php echo $txtID;?>" name="txtID" placeholder="ID">
            <input type="text" required class="campo" value="<?php echo $txtNombre;?>" name="txtNombre" placeholder="Nombre Calzado">
            <!input type="file" value="<?php echo $txtImagen;?>" name="txtImagen" id="txtImagen" placeholder="Imagen de Presentacion" 
            accept=".jpg, .jpeg, .png" class="form-img">
            <div class="previsualizacion">
                <div class="box">
                    <label for="txtImagen" class="file-label">Seleccionar Imagen</label>
                    <input type="file" required class="form-img" name="txtImagen" id="txtImagen" accept=".jpg, .jpeg, .png">
                </div>

                <div class="box">
                    <?php if($txtImagen != ""){ ?>
                        <img src="../../img/<?php echo $zapato['imagen'];?>"
                        class="img-slct">
                    <?php }?>
                </div>
            </div>

            <input type="submit" name="accion" <?php echo ($accion=="select")?"disabled":"";?> value="agregar" class="btn-enviar">
            <input type="submit" name="accion" <?php echo ($accion!="select")?"disabled":"";?> value="modificar" class="btn-enviar">
            <input type="submit" name="accion" <?php echo ($accion!="select")?"disabled":"";?> value="cancelar" class="btn-enviar">
        </form>
        </div>

        <table class="contenido-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaZapatos as $zapato) {?>
                <tr>
                    <td>
                        <img src="../../img/<?php echo $zapato['imagen'];?>" class="img-table">
                    </td>
                    <td><?php echo $zapato['id'];?></td>
                    <td>
                        <?php echo $zapato['nombre'];?>
                    </td>
                    <td>???</td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $zapato['id'];?>"/>
                            <input type="submit" name="accion" value="select" class="btn-slct"/>
                            <input type="submit" name="accion" value="borrar" class="btn-borrar"/>
                        </form>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</body>

<?php include("../template/pie.php")?>

Catalogo