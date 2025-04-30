<?php 
include("elements/header/header.html");
include("administrador/cofig/bd.php");
?>

<body>
    <link rel="stylesheet" href="css/carrito.css" />

    <h2>Resumen del Carrito</h2>
    <div class="contenedor-principal">
        <div id="contenedor-tablas"></div>
        <div id="resumen-precios">
            <h3>Resumen de Precios</h3>
            <ul id="lista-precios"></ul>
            <p><strong>Total:</strong> $<span id="total-precio">0.00</span></p>
        </div>
    </div>
</body>

<script>
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    const contenedorTablas = document.getElementById('contenedor-tablas');
    const listaPrecios = document.getElementById('lista-precios');
    const totalPrecio = document.getElementById('total-precio');

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

            let total = 0;

            // Generar tablas para cada producto
            data.forEach(producto => {
                let subtotal = 0;
                let tabla = `
                    <table class="tabla-nombre">
                        <tr>
                            <th colspan="3">${producto.marca} ${producto.nombre}</th>
                        </tr>
                    </table>
                    <table class="tabla-producto">
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
                    const cantidad = producto.tallas[talla];
                    const precio = producto.preciomen; // Usar precio menor
                    subtotal += cantidad * precio;
                    tabla += `
                        <tr>
                            <td>${talla}</td>
                            <td>
                                <button class="btn-decrementar" data-id="${producto.id}" data-talla="${talla}">-</button>
                                <input type="number" class="input-cantidad" data-id="${producto.id}" data-talla="${talla}" value="${cantidad}" min="0">
                                <button class="btn-incrementar" data-id="${producto.id}" data-talla="${talla}">+</button>
                            </td>
                        </tr>
                    `;
                }

                tabla += `</table>`;
                contenedorTablas.innerHTML += tabla;

                // Agregar al resumen de precios
                listaPrecios.innerHTML += `<li>${producto.nombre}: $${subtotal.toFixed(2)}</li>`;
                total += subtotal;
            });

            totalPrecio.textContent = total.toFixed(2);
        })
        .catch(error => {
            console.error('Error al obtener los productos del carrito:', error);
            contenedorTablas.innerHTML = '<p>Ocurrió un error al cargar el carrito.</p>';
        });
    }

    // Manejar eventos de incremento, decremento y edición manual
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-incrementar')) {
            const id = event.target.dataset.id;
            const talla = event.target.dataset.talla;
            carrito[id][talla]++;
            actualizarCarrito();
        } else if (event.target.classList.contains('btn-decrementar')) {
            const id = event.target.dataset.id;
            const talla = event.target.dataset.talla;
            if (carrito[id][talla] > 0) {
                carrito[id][talla]--;
                actualizarCarrito();
            }
        }
    });

    document.addEventListener('input', (event) => {
        if (event.target.classList.contains('input-cantidad')) {
            const id = event.target.dataset.id;
            const talla = event.target.dataset.talla;
            const nuevaCantidad = parseInt(event.target.value, 10);
            if (!isNaN(nuevaCantidad) && nuevaCantidad >= 0) {
                carrito[id][talla] = nuevaCantidad;
                actualizarCarrito();
            }
        }
    });

    function actualizarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
        location.reload(); // Recargar para reflejar los cambios
    }
</script>

<?php include("elements/footer/footer.html"); ?>