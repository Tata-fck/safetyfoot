<?php 
include("elements/header/header.html");
include("administrador/cofig/bd.php");
?>
<body>
    <link rel="stylesheet" href="css/carrito.css" />

    <h2>Resumen del Carrito</h2>
    <div id="contenedor-tablas"></div>
</body>

<script>
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    const contenedorTablas = document.getElementById('contenedor-tablas');

    if (Object.keys(carrito).length === 0) {
        contenedorTablas.innerHTML = '<p>El carrito está vacío.</p>';
    } else {
        fetch('obtener_productos_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(carrito)
        })
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                contenedorTablas.innerHTML = '<p>No se encontraron productos en el carrito.</p>';
                return;
            }

            // Generar tablas para cada producto
            data.forEach(producto => {
                let tabla = `
                    <table class="tabla-producto">
                        <tr>
                            <th colspan="3">${producto.nombre}</th>
                        </tr>
                        <tr>
                            <td rowspan="${Object.keys(producto.tallas).length + 1}">
                                <img src="./img/${producto.imagen}" alt="${producto.nombre}" class="imagen-producto">
                            </td>
                            <th>Talla</th>
                            <th>Cantidad</th>
                        </tr>
                `;

                // Agregar filas para las tallas
                for (let talla in producto.tallas) {
                    tabla += `
                        <tr>
                            <td>${talla}</td>
                            <td>${producto.tallas[talla]}</td>
                        </tr>
                    `;
                }

                tabla += `</table>`;
                contenedorTablas.innerHTML += tabla;
            });
        })
        .catch(error => {
            console.error('Error al obtener los productos del carrito:', error);
            contenedorTablas.innerHTML = '<p>Ocurrió un error al cargar el carrito.</p>';
        });
    }
</script>

<?php include("elements/footer/footer.html"); ?>