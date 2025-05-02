<?php 
include("elements/header/header.html");
include("administrador/cofig/bd.php");
?>

<body>
    <link rel="stylesheet" href="css/carrito.css" />

    <h2 class="titulo-carrito">Resumen del Carrito</h2>
    <div class="contenedor-principal">
        <div id="contenedor-tablas"></div>
        <div id="resumen-precios">
            <h3>Resumen de Precios</h3>
            <ul id="lista-precios"></ul>
            <p><strong>Total:</strong> $<span id="total-precio">0.00</span></p>
            <div class="boton-continuar">
                <button class="btn-continuar" onclick="location.href='compra-carrito.php'">
                    <i class="fa-solid fa-plus"></i>
                    <img src="images/compra.svg" class="img-paloma"> Continuar con la compra
                </button>
            </div>
        </div>
    </div>
</body>

<script>
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    const contenedorTablas = document.getElementById('contenedor-tablas');
    const listaPrecios = document.getElementById('lista-precios');
    const totalPrecio = document.getElementById('total-precio');

    if (Object.keys(carrito).length === 0) {
        contenedorTablas.innerHTML = '<img src="images/carrito-vacio.png" alt="Carrito vacío" class="img-carrito-vacio">';
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
                let cantidadTotal = 0;

                // Calcular la cantidad total de unidades y determinar el precio a usar
                for (let talla in producto.tallas) {
                    const cantidad = producto.tallas[talla];
                    cantidadTotal += cantidad;
                }

                const precioUsado = cantidadTotal > 5 ? producto.preciomay : producto.preciomen;
                subtotal = cantidadTotal * precioUsado;

                // Generar tabla del producto 
                let tabla = `
                    <table class="tabla-nombre">
                        <tr>
                            <th colspan="2">${producto.marca} ${producto.nombre}</th>
                            <th style="text-align: right;">
                                <button class="btn-eliminar-articulo" data-id="${producto.id}">Eliminar artículo</button>
                            </th>
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

                for (let talla in producto.tallas) {
                    const cantidad = producto.tallas[talla];
                    tabla += `
                        <tr>
                            <td class ="celda-talla">${talla}</td>
                            <td style="display: flex; align-items: center; justify-content: center;">
                                <div class="contenedor-cantidad">
                                    <button class="btn-decrementar" data-id="${producto.id}" data-talla="${talla}">-</button>
                                    <input type="number" class="input-cantidad" data-id="${producto.id}" data-talla="${talla}" value="${cantidad}" min="0">
                                    <button class="btn-incrementar" data-id="${producto.id}" data-talla="${talla}">+</button>
                                </div>
                                <button class="btn-eliminar-talla" data-id="${producto.id}" data-talla="${talla}">x</button>
                            </td>
                        </tr>
                    `;
                }

                tabla += `</table>`;
                contenedorTablas.innerHTML += tabla;

                // Determinar el contenido del párrafo de oferta
                const si_oferta = cantidadTotal > 5
                    ? `<p class="resumen-oferta">$${producto.preciomen.toFixed(2)} c/u</p>`
                    : ''; // Si no, un string vacío

                // Agregar al resumen de precios con imagen
                listaPrecios.innerHTML += `
                    <li class="resumen-item">
                        <img src="./img/${producto.imagen}" alt="${producto.nombre}" class="resumen-imagen">
                        <div class="resumen-detalles">
                            <p class="resumen-nombre">${producto.marca} ${producto.nombre}</p>
                            <p class="resumen-cantidad">${cantidadTotal} unidades</p>
                            <p class="resumen-oferta"> ${si_oferta}</p>
                            <p class="resumen-precio">$${precioUsado.toFixed(2)} c/u</p>
                            <p class="resumen-subtotal">Subtotal: $${subtotal.toFixed(2)}</p>
                        </div>
                    </li>
                `;
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

    // Manejar eventos de eliminación
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-eliminar-talla')) {
            const id = event.target.dataset.id;
            const talla = event.target.dataset.talla;
            delete carrito[id][talla];
            if (Object.keys(carrito[id]).length === 0) {
                delete carrito[id];
            }
            actualizarCarrito();
        } else if (event.target.classList.contains('btn-eliminar-articulo')) {
            const id = event.target.dataset.id;
            delete carrito[id];
            actualizarCarrito();
        }
    });

    let inputTimeout;
    document.addEventListener('input', (event) => {
        if (event.target.classList.contains('input-cantidad')) {
            const id = event.target.dataset.id;
            const talla = event.target.dataset.talla;
            const nuevaCantidad = parseInt(event.target.value, 10);

            // Limpiar el temporizador anterior
            clearTimeout(inputTimeout);

            // Configurar un nuevo temporizador
            inputTimeout = setTimeout(() => {
                if (!isNaN(nuevaCantidad) && nuevaCantidad >= 0) {
                    carrito[id][talla] = nuevaCantidad;
                    localStorage.setItem('carrito', JSON.stringify(carrito));
                    location.reload(); // Recargar para reflejar los cambios
                }
            }, 800); // Esperar 500ms después de que el usuario deje de escribir
        }
    });

    function actualizarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
        location.reload(); // Recargar para reflejar los cambios
    }
</script>

<?php include("elements/footer/footer.html"); ?>