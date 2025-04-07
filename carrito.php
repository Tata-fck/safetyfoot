<?php 
include("elements/header/header.html");
include("administrador/cofig/bd.php");
?>
<body>
    <link rel="stylesheet" href="css/carrito.css" />

    <h2>Resumen del Carrito</h2>
        <table id="tabla-carrito">
            <tr>
                <th>Talla</th>
                <th>Cantidad</th>
            </tr>
        </table>
</body>

<script>
	const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
	const tabla = document.getElementById('tabla-carrito');

	if (Object.keys(carrito).length === 0) {
        tabla.innerHTML += '<tr><td colspan="2">El carrito está vacío.</td></tr>';
    } else {
        for (let talla in carrito) {
            let row = `<tr>
                <td>${talla}</td>
                <td>${carrito[talla]}</td>
            </tr>`;
            tabla.innerHTML += row;
        }
    }
</script>


<?php include("elements/footer/footer.html");?>
