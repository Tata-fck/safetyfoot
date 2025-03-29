<?php include("elements/header/header.html");?>

<?php 
include("administrador/cofig/bd.php");

$id = isset($_GET['id']) ? $_GET['id'] : '';

$sentenciaSQL = $conexion->prepare("
    SELECT p.*, 
           (SELECT GROUP_CONCAT(i.nom_archivo ORDER BY i.num_archivo ASC) FROM imagenes i 
            WHERE i.id_producto = p.id) AS imagenes
    FROM productos p 
    WHERE p.id = :id
");
$sentenciaSQL->bindParam(':id', $id, PDO::PARAM_STR);
$sentenciaSQL->execute();
$producto = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);


if (!$producto) {
    echo "Producto no encontrado";
    exit;
}

$imagenes = explode(',', $producto['imagenes']);
?>

    <link rel="stylesheet" href="css/detalle.css" />
<body>
		<main>
			<div class="container-img">
				<div class="carrusel2">
					<div class="container-carrusel2">
						<div class="carruseles2" id="slider2">
							<?php foreach ($imagenes as $imagen) { ?>
								<section class="slider-section2">
									<img src="./img/<?php echo $imagen; ?>" alt="producto" />
								</section>
							<?php } ?>
						</div>
						<div class="btn-l2"><img src="images/inicio/galery-flecha-l.svg"></div>
						<div class="btn-r2"><img src="images/inicio/galery-flecha-r.svg"></div>
					</div>
				</div>

				<!--<img src="./img/<?php echo $producto['imagen']; ?>" alt="producto" />-->
			</div>
			<div class="container-info-product">
				<div class="container-price">
					<b class = "nombre"><?php echo $producto['marca'] . " " . $producto['nombre']; ?></b><br>
                    $<?php echo $producto['precio']; ?>
				</div>

				<div class="container-details-product">
					<div class="form-group">
						<label for="size">Talla</label>
						<select name="size" id="size">
							<option disabled selected value="">
								Escoge una opción
							</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							<option value="32">32</option>
						</select>
					</div>
					<button class="btn-clean">Limpiar</button>
				</div>

				<div class="container-add-cart">
					<div class="container-quantity">
						<input type="number"
							placeholder=" Cantidad"
							min="1"
							class="input-quantity"
							onkeypress="return soloNumeros(event);"
						/>
						<div class="btn-increment-decrement">
							<i class="fa-solid fa-chevron-up" id="increment"></i>
							<i class="fa-solid fa-chevron-down" id="decrement"></i>
						</div>
					</div>
					<button class="btn-add-to-cart">
						<i class="fa-solid fa-plus"></i>
						Añadir al carrito
					</button>
				</div>

				<div class="container-description">
					<div class="title-description">
						<h4>Descripción</h4>
						<i class="fa-solid fa-chevron-down"></i>
					</div>
					<div class="text-description">
						<p>
                        <?php echo $producto['descripcion']; ?>
						</p>
					</div>
				</div>

				<div class="container-additional-information">
					<div class="title-additional-information">
						<h4>Información adicional</h4>
						<i class="fa-solid fa-chevron-down"></i>
					</div>
					<div class="text-additional-information hidden">
						<p>-----------</p>
					</div>
				</div>

				<div class="container-reviews">
					<div class="title-reviews">
						<h4>Reseñas</h4>
						<i class="fa-solid fa-chevron-down"></i>
					</div>
					<div class="text-reviews hidden">
						<p>-----------</p>
					</div>
				</div>
			</div>
		</main>

		

<?php
	$sentenciaSQL = $conexion->prepare(
		"SELECT p.id, p.nombre, p.precio, 
			(SELECT i.nom_archivo FROM imagenes i 
				WHERE i.id_producto = p.id 
				ORDER BY i.num_archivo ASC LIMIT 1) AS imagen
		FROM productos p 
		LIMIT 7"
	);
	$sentenciaSQL->execute();
	$listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>
	<!--Ofertas-->
    <div class="t-galeria">
        <h1 class="t-ofertas"> Productos Relacionados </h1>
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
                        <p class="price">$<?php echo $producto['precio']; ?></p>
                        <button onclick="window.location.href='detalle.php?id=<?php echo $producto['id']; ?>'">Ver detalles</button>
                    </div>
                </div>
            <?php } ?> 
            </div>
        </div>
        <div class="galery-btn-r"><img src="images/inicio/galery-flecha-r.svg"></div>
    </div>
	
	<script src="javascript/detalle.js"></script> 
	</body>

    <?php include("elements/footer/footer.html"); ?>