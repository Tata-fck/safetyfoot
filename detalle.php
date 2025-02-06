<?php include("elements/header/header.html");?>

<?php 
include("administrador/cofig/bd.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sentenciaSQL = $conexion->prepare("SELECT * FROM zapato WHERE id = :id");
$sentenciaSQL->bindParam(':id', $id);
$sentenciaSQL->execute();
$producto = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado";
    exit;
}
?>

    <link rel="stylesheet" href="css/detalle.css" />
<body>
		<main>
			<div class="container-img">
                    <img src="./img/<?php echo $producto['imagen']; ?>" alt="producto" />
			</div>
			<div class="container-info-product">
				<div class="container-price">
					<b class = "nombre"><?php echo $producto['nombre']; ?></b><br>
                    $<?php echo $producto['precio']; ?>
				</div>

				<div class="container-details-product">
					<div class="form-group">
						<label for="colour">Color</label>
						<select name="colour" id="colour">
							<option disabled selected value="">
								Escoge una opción
							</option>
							<option value="rojo">Rojo</option>
							<option value="blanco">Blanco</option>
							<option value="beige">Beige</option>
						</select>
					</div>
					<div class="form-group">
						<label for="size">Talla</label>
						<select name="size" id="size">
							<option disabled selected value="">
								Escoge una opción
							</option>
							<option value="40">40</option>
							<option value="42">42</option>
							<option value="43">43</option>
							<option value="44">44</option>
						</select>
					</div>
					<button class="btn-clean">Limpiar</button>
				</div>

				<div class="container-add-cart">
					<div class="container-quantity">
						<input
							type="number"
							placeholder="1"
							value="1"
							min="1"
							class="input-quantity"
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
    $sentenciaSQL = $conexion->prepare("SELECT * FROM zapato LIMIT 5");
    $sentenciaSQL->execute();
    $listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    ?>
			<h2>Productos Relacionados</h2>
			<div class="container-items">
        <?php foreach ($listaProductos as $zapato) { ?>
        <div class="item">
            <figure>
                <img
                    src="./img/<?php echo $zapato['imagen']; ?>"
                    alt="producto"
                />
            </figure>
            <div class="info-product">
                <h2><?php echo $zapato['nombre']; ?></h2>
                <p class="price"><?php echo $zapato['precio']; ?></p>
                <button class="ver-p" onclick="window.location.href='detalle.php?id=<?php echo $zapato['id']; ?>'">Ver detalles</button>
            </div>
        </div>  
        <?php } ?> 
    </div>
	</body>

    <?php include("elements/footer/footer.html"); ?>