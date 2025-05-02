<?php 
include("elements/header/header.html");
include("administrador/cofig/bd.php");
?>
<body>
    <link rel="stylesheet" href="css/compra-carrito.css" />

    <h2 class="titulo-carrito">Metodo de Contacto</h2>
    <div class="contenedor-principal">
        <div class="cont-form">
            <form id="contactForm" method="POST" autocomplete="off">
                <input type="text" name="nombre" placeholder="Nombre y Apellido" class="campo" value="<?php echo $_POST['nombre'] ?? ''; ?>">
                <select name="metodo" id="metodo" class="campo">
                    <option disabled selected value="">Metodo de Contacto Preferido</option>
                    <option value="correo">Correo</option>
                    <option value="whatsApp">WhatsApp</option>
                    <option value="llamada">Llamada</option>
                </select>
                <input type="email" name="email" placeholder="Tu Email" class="campo" value="<?php echo $_POST['email'] ?? ''; ?>">
                <input type="tel" name="celular" placeholder="Numero de Telefono" class="campo" value="<?php echo $_POST['celular'] ?? ''; ?>">
                <textarea id="mensaje" name="mensaje" placeholder="Urgencia o Mensaje Adicional..."><?php echo $_POST['mensaje'] ?? ''; ?></textarea>
                <input type="hidden" name="origen" value="compra">
                <input type="hidden" name="carrito" id="carritoInput" value="">
                <input type="hidden" name="carrito_detallado" id="carrito_detallado" value="">

                <p id="responseMessage"></p>
            </form>
        </div>
        <div id="resumen-precios">
            <h3>Resumen de Precios</h3>
            <ul id="lista-precios"></ul>
            <p><strong>Total:</strong> $<span id="total-precio">0.00</span></p>
            <div class="boton-continuar">
                <button type="submit" form="contactForm" class="btn-continuar">
                    <i class="fa-solid fa-plus"></i>
                    <img src="images/compra.svg" class="img-paloma"> Finalizar la compra
                </button>
            </div>
        </div>
    </div>
</body>

<script>
    // Pasar el carrito inicial al hidden
    let carritoLS = localStorage.getItem('carrito');
    if (!carritoLS || carritoLS === '') carritoLS = '{}';
    document.getElementById('carritoInput').value = carritoLS;

    const listaPrecios = document.getElementById('lista-precios');
    const totalPrecio = document.getElementById('total-precio');
    const form = document.getElementById('contactForm');
    const responseMessage = document.getElementById('responseMessage');

    // Enviar el formulario con fetch
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Normaliza el carrito: convierte objeto a array de productos
        let carritoLS = localStorage.getItem('carrito');
        let carritoArray = [];
        if (carritoLS && carritoLS !== '{}') {
            try {
                const carritoObj = JSON.parse(carritoLS);
                carritoArray = Object.values(carritoObj); // array de productos
            } catch (e) {
                carritoArray = [];
            }
        }
        document.getElementById('carritoInput').value = JSON.stringify(carritoArray);

        // Guardar el carrito detallado en el hidden antes de enviar
        const carritoDetallado = localStorage.getItem("carrito_detallado") || "[]";
        document.getElementById("carrito_detallado").value = carritoDetallado;

        // Imprime en consola para depuración
        console.log('Carrito enviado:', carritoArray);
        console.log('Carrito detallado enviado:', carritoDetallado);

        // Envía el formulario por fetch
        const formData = new FormData(form);
        fetch('elements/enviar-carrito.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            responseMessage.innerHTML = "<p style='color:green; font-size:1.4em; font-weight:bold;'>GRACIAS POR TU COMPRA !!<br>En breve te contactaremos</p>";
        })
        .catch(error => {
            responseMessage.innerHTML = "<p style='color:red;'><bold>Error al enviar el formulario. Inténtalo más tarde.</bold></p>";
            console.error('Error:', error);
        });
    });

    // Obtener y mostrar los productos del carrito y guardar el detallado
    let carrito = {};
    try {
        carrito = JSON.parse(localStorage.getItem('carrito') || '{}');
    } catch (e) {
        carrito = {};
    }
    fetch('obtener_productos_carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(carrito)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.length) return;

        // Guardar el carrito detallado para el envío
        localStorage.setItem("carrito_detallado", JSON.stringify(data));

        let total = 0;
        data.forEach(producto => {
            let cantidadTotal = 0;
            for (let talla in producto.tallas) {
                cantidadTotal += producto.tallas[talla];
            }
            const precioUsado = cantidadTotal > 5 ? producto.preciomay : producto.preciomen;
            const subtotal = cantidadTotal * precioUsado;

            // Mostrar ambos precios si hay oferta
            let precioHtml = '';
            if (cantidadTotal > 5) {
                precioHtml = `<span style="text-decoration:line-through;color:red;">$${producto.preciomen.toFixed(2)}</span><br> 
                            <span style="color:green;">$${producto.preciomay.toFixed(2)} c/u</span>`;
            } else {
                precioHtml = `$${producto.preciomen.toFixed(2)} c/u`;
            }

            listaPrecios.innerHTML += `
                <li class="resumen-item">
                    <img src="./img/${producto.imagen}" alt="${producto.nombre}" class="resumen-imagen">
                    <div class="resumen-detalles">
                        <p class="resumen-nombre">${producto.marca} ${producto.nombre}</p>
                        <p class="resumen-cantidad">${cantidadTotal} unidades</p>
                        <p class="resumen-precio">${precioHtml}</p>
                        <p class="resumen-subtotal">Subtotal: $${subtotal.toFixed(2)}</p>
                    </div>
                </li>
            `;
            total += subtotal;
        });

            totalPrecio.textContent = total.toFixed(2);
        })
    .catch(error => console.error('Error al obtener los productos del carrito:', error));
</script>

<?php include("elements/footer/footer.html"); ?>