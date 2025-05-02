<?php include("elements/header/header.html");

include("administrador/cofig/bd.php");

$sentenciaSQL = $conexion->prepare(
    "SELECT p.id, p.nombre, p.preciomen, p.preciomay,
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
 <link rel="stylesheet" href="css/inicio.css">

<body>
    <!--Pagina de Incio-->
    <!--Carrusel 1-->
    <div class="carrusel2">
        <div class="container-carrusel2">
            <div class="carruseles2" id="slider2">
                <section class="slider-section2">
                    <img src="images/inicio/carrusel-1.png">
                </section>
                <section class="slider-section2">
                    <img src="images/inicio/carrusel-1.png">
                </section>
                <section class="slider-section2">
                    <img src="images/inicio/carrusel-1.png">
                </section>
            </div>
            <div class="btn-l2"><img src="images/inicio/flecha-L.svg"></div>
            <div class="btn-r2"><img src="images/inicio/flecha-R.svg"></div>
            <div class="texto2">
                <h2>PRESENTACION</h2>
                <p>“protege tus pasos impulsando tu futuro” </p>
                <button>Explorar el Catalogo</button>
            </div>
        </div>
    </div>

    <!--Ofertas-->
    <div class="t-galeria">
        <h1 class="t-ofertas"> Nuestras Ofertas </h1>
    </div>
    <div class="galeria">
        <div class="galery-btn-l"><img src="images/inicio/galery-flecha-l.svg"></div>
        <div class="container-galeria">
            <div class="items" id="galery">
            <?php foreach ($listaProductos as $producto) { ?>
                <div class="item">
                    <figure>
                        <img
                            src="./img/<?php echo $producto['imagen']; ?>"
                            alt="producto"
                        />
                    </figure>
                    <div class="info-product">
                        <h2><?php echo $producto['nombre']; ?></h2>
                        <p class="price">$<?php echo $producto['preciomen']; ?> - $<?php echo $producto['preciomay']; ?></p>
                        <button onclick="window.location.href='detalle.php?id=<?php echo $producto['id']; ?>'">Ver Detalles</button>
                    </div>
                </div>
            <?php } ?> 
            </div>
        </div>
        <div class="galery-btn-r"><img src="images/inicio/galery-flecha-r.svg"></div>
    </div>

    <!--INFORMACION DE ENVIO-->
    <div class="container-envio">
        <div class="columna-envio">
            <div class="box">
                
                    <p class="envio-inicio">Rapidos y Seguros</p>
                
                <h2>ENVIOS</h2>
                
                    <p>Compra 6 pares o más</p>
                
                    <p>y obten un precio especial.</p>
            </div>
            <div class="box">
                <figure>
                    <img src="images/inicio/envio.jpg" alt="Safety Footprint Logo" >
                </figure>
            </div>
        </div>
    </div>
    <script src="javascript/inicio.js"></script> 
</body>

<?php include("elements/footer/footer.html");?>