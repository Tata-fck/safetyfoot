<?php 
session_start();
if($_POST){
    if(($_POST['usuario']=="alex")&&($_POST['contrasenia']=="sistema")){
        $_SESSION['usuario']="ok";
        $_SESSION['nombreUsuario']="Alex";
        header('Location:seccion/productos.php');
    }else{
        $mensaje="Error: El usuario y/o contraseña son incorrectos";
    }  
}  
?>
<!DOCTYPE html>
<html lang="es">
<!-- header.html -->
<header>

    <?php $url="http://".$_SERVER['HTTP_HOST']."/safetyfoot" ?>
    <!-- Meta información -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $url;?>/images/logo.png" type="image/x-icon">

    <!-- Enlaces a hojas de estilo -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&family=EB+Garamond:wght@400..800&family=Spinnaker&display=swap" rel="stylesheet">
    
    <!-- Título de la página -->
    <title>Safety FootPrint</title>
    <div class="contenedor">
        <h2 class="titulo">SAFETY FOOTPRINT</h2>
    </div>
</header>

<link rel="stylesheet" href="<?php echo $url;?>/administrador/index.css">

<body>
    <div class="principal">
        <h2 class="titulo">
            ADMINISTRADOR</br>
            INICIO DE SESION
        </h2>
        <?php if(isset($mensaje)) {?>
        <div class="mensaje">
            <?php echo $mensaje;?>
        </div>
        <?php } ?>
        <form method="POST" autocomplete="off">
            <input type="usuario" name="usuario" placeholder="Usuario" class="campo">
            <input type="contrasenia" name="contrasenia" placeholder="Contraseña" class="campo">
            <input type="submit" name="enviar" value="Ingresar" class="btn-enviar">
        </form>
    </div>
</body>

<?php include("template/pie.php");?>