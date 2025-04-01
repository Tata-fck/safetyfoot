<?php include("elements/header/header.html");

include("administrador/cofig/bd.php");
$sentenciaSQL = $conexion->prepare(
    "SELECT p.id, p.marca, p.nombre, p.preciomen, 
           (SELECT i.nom_archivo FROM imagenes i 
            WHERE i.id_producto = p.id 
            ORDER BY i.num_archivo ASC LIMIT 1) AS imagen
    FROM productos p 
    LIMIT 7"
);
$sentenciaSQL->execute();
$listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Enlaces a hojas de estilo -->
<link rel="stylesheet" href="css/catalogo.css">

<body>
<div class="main-content">
    <div class="container-items">
        <?php foreach ($listaProductos as $producto) { ?>
        <div class="item">
            <figure>
                <img
                    src="./img/<?php echo $producto['imagen']; ?>"
                    alt="producto"
                />
            </figure>
            <div class="info-product">
                <h2><?php echo $producto['marca']; ?></h2>
                <h2><?php echo $producto['nombre']; ?></h2>
                <p class="price">$<?php echo $producto['preciomen']; ?></p>
                <button class="ver-p" onclick="window.location.href='detalle.php?id=<?php echo $producto['id']; ?>'">Ver detalles</button>
            </div>
        </div>  
        <?php } ?> 
    </div>
</div>
</body>

<?php include("elements/footer/footer.html");?>