<?php 
include("cofig/bd.php");

session_start();

// Destruir la sesión si se recarga la página
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_unset();
    session_destroy();
}

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasenia = $_POST['contrasenia'];

    // Consulta a la base de datos con PDO
    $stmt = $conexion->prepare("SELECT * FROM admin WHERE usuario = :usuario AND contrasenia = :contrasenia");
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($fila) {
        $_SESSION['usuario'] = "ok";
        $_SESSION['nombreUsuario'] = $fila['usuario'];
        header('Location: seccion/productos.php');
        exit();
    } else {
        $mensaje = "Error: El usuario y/o contraseña son incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<!-- header.html -->
<header>

    <?php $url="http://".$_SERVER['HTTP_HOST'] . "/SAFETYFOOT"?>
    <!-- Meta información -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $url;?>/images/logo.png" type="image/x-icon">

    <!-- Enlaces a hojas de estilo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&family=EB+Garamond:wght@400..800&family=Spinnaker&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo $url;?>/administrador/index.css">
    <!-- Título de la página -->
    <title>grupo CAPI</title>
    <div class="contenedor">
        <!-- Logo -->
        <a href="inicio.html"><img src="<?php echo $url;?>/images/logo.png" alt="Safety Footprint Logo" class="logo"></a>
        <h2 class="titulo">GRUPO CAPI</h2>

        <!-- Menú de Navegación -->
            <div class="menu-items" id="menu-items">
                <a href="<?php echo $url;?>/default.php">Ver Web</a>
            </div>
    </div>

</header>

<body>
    <div class="main-content">
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
                <div class="contra-input">
                    <input type="password" name="contrasenia" placeholder="Contraseña" class="campo" id="campo-contra"/>
                    <div class="boton-ojo">
                        <img src="<?php echo $url;?>/images/eye_open.svg" fill="currentColor" class="eye-icon"></img>
                    </div>
                </div>
                <!--<input type="password" name="contrasenia" placeholder="Contraseña" class="campo">-->
                <input type="submit" name="enviar" value="Ingresar" class="btn-enviar">
            </form>
        </div>
    </div>
</body>

<?php include("template/pie.php");?>

</html>

<!--funcionamiento BOTON CONTRASEÑA-->
<script>
    const campoContra = document.getElementById('campo-contra');
    const botonOjo = document.querySelector('.boton-ojo');
    const eyeIcon = document.querySelector('.eye-icon');
    botonOjo.addEventListener('click',()=>{
        if(campoContra.type === 'password'){
            campoContra.type = 'text';
            eyeIcon.src = '<?php echo $url;?>/images/eye_close.svg';
        }else{
            campoContra.type = 'password';
            eyeIcon.src = '<?php echo $url;?>/images/eye_open.svg';
        }
    });
</script>
