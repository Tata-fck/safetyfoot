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
<body>
    <link rel="stylesheet" href="css/detalle.css" />
	<div class="container-main">
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
					$<?php echo $producto['preciomen']; ?> - $<?php echo $producto['preciomay']; ?><br>
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

				<div class="container-details-product">
					<div class="title-description">
						<h4>Seleccionar Tallas</h4>
						<i class="fa-solid fa-chevron-down"></i>
					</div>
					<table class="table-tallas">
						<tr class="header">
							<th>Talla</th>
							<th>Cantidad</th>
							<th>Talla</th>
							<th>Cantidad</th>
						</tr>
						<?php for ($talla = 22; $talla <= 32; $talla++) : ?>
							<tr>
								<?php
								$t1 = $talla;
								$talla++;
								$t2 = $talla;
								?>
								<td><strong><?php echo $t1; ?></strong></td>
								<td>
									<input type="number"
										name="tallas[<?php echo $t1; ?>]"
										data-talla="<?php echo $t1; ?>"
										placeholder="Cantidad"
										min="0"
										class="input-quantity"
										onkeypress="return soloNumeros(event);"
									/>
								</td>
								<td><strong><?php echo $t2; ?></strong></td>
								<td>
									<input type="number"
										name="tallas[<?php echo $t2; ?>]"
										data-talla="<?php echo $t2; ?>"
										placeholder="Cantidad"
										min="0"
										class="input-quantity"
										onkeypress="return soloNumeros(event);"
									/>
								</td>
							</tr>
						<?php endfor; ?>
					</table>
					<div class="container-add-cart">
						<button class="btn-add-to-cart" onclick="añadirAlCarrito(event)">
							<i class="fa-solid fa-plus"></i>
							<img src="images/carrito.svg" class="img-carrito"> Añadir al carrito
						</button>
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
	</div>
	<!--<div class="container-detalles-product">-->
		<div class="t-galeria">
			<h1 class="t-ofertas">Informacion de Costos</h1>
			<i class="fa-solid fa-chevron-down"></i>
		</div>
		<table class="table-precios">
			<tr class="header">
				<th>No. DE PIEZAS</th>
				<th>1 - 5 Unidades</th>
				<th>6+ Unidades</th>
			</tr>
			<tr>
				<td class="t-precio">PRECIO $</td>
				<td><?php echo $producto['preciomen'];?></td>
				<td><?php echo $producto['preciomay'];?></td>
			</tr>
		</table>
	<!--</div>-->

		

<?php
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
	<!--Ofertas-->
    <div class="t-galeria">
        <h1 class="t-ofertas"> Productos Relacionados </h1>
    </div>
	<div class="box-galeria">
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
							<p class="price">$<?php echo $producto['preciomen']; ?> - $<?php echo $producto['preciomay'];?></p>
							<button onclick="window.location.href='detalle.php?id=<?php echo $producto['id']; ?>'">Ver detalles</button>
						</div>
					</div>
				<?php } ?> 
				</div>
			</div>
			<div class="galery-btn-r"><img src="images/inicio/galery-flecha-r.svg"></div>
		</div>
	</div>
	
	<script src="javascript/detalle.js"></script> 
</body>

    <?php include("elements/footer/footer.html"); ?>