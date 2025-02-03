<?php 
session_start();
    if(!isset($_SESSION['usuario'])){
        header("Location:../index.php");
    }else{
        if($_SESSION['usuario']=="ok"){
            $nombreUsuario=$_SESSION["nombreUsuario"];
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

    <link rel="stylesheet" href="<?php echo $url;?>/administrador/template/css/cabecera.css">
    
    <!-- Título de la página -->   
    <title>Safety FootPrint</title>

    <div class="contenedor">
        <!-- Logo -->
        <a href="inicio.html"><img src="<?php echo $url;?>/images/logo.png" alt="Safety Footprint Logo" class="logo"></a>
        <h2 class="titulo">SAFETY FOOTPRINT</h2>

        <!-- Menú de Navegación -->
        <nav class="menu-hdr">
            <!-- Botón Moviles -->
        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="menu-icon">
            <span class="menu-open">&#9776;</span> <!-- Icono de abrir (hamburguesa) -->
            <span class="menu-close">&times;</span> <!-- Icono de cerrar (X) -->
        </label>

            <div class="menu-items" id="menu-items">
                <a href="<?php echo $url;?>/administrador/inicio.php" class="activo">Inicio</a>
                <a href="<?php echo $url;?>/administrador/seccion/productos.php">Catalogo</a>
                <a href="<?php echo $url;?>/inicio.php">Ver Web</a>
                <a href="<?php echo $url;?>/administrador/seccion/cerrar.php">Cerrar Sesion</a>
            </div>
        </nav>
    </div>
</header>