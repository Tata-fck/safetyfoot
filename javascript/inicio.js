//---------------------SLIDER 2 ----------------------------
const btnLeft2 = document.querySelector(".btn-l2"),
      btnRight2 = document.querySelector(".btn-r2"),
      slider2 = document.querySelector("#slider2"),
      sliderSection2 = document.querySelectorAll(".slider-section2");

let operacion2 = 0,
    counter2 = 0,
    autoSlideInterval2,
    widthImg2 = 100 / sliderSection2.length;

btnLeft2.addEventListener("click", () => { moveToLeft2(), resetAutoSlide2() });
btnRight2.addEventListener("click", () => { moveToRight2(), resetAutoSlide2() });

function setAutoSlide2() {
    autoSlideInterval2 = setInterval(() => {
        moveToRight2();
    }, 7000);
}

function resetAutoSlide2() {
    clearInterval(autoSlideInterval2);
    setAutoSlide2();
}

function moveToRight2() {
    if (counter2 >= sliderSection2.length - 1) {
        counter2 = 0;
        operacion2 = 0;
        slider2.style.transform = `translate(-${operacion2}%)`;
        slider2.style.transition = "none";
        return;
    }
    counter2++;
    operacion2 += widthImg2;
    slider2.style.transform = `translate(-${operacion2}%)`;
    slider2.style.transition = "all ease .6s";
}

function moveToLeft2() {
    counter2--;
    if (counter2 < 0) {
        counter2 = sliderSection2.length - 1;
        operacion2 = widthImg2 * (sliderSection2.length - 1);
        slider2.style.transform = `translate(-${operacion2}%)`;
        slider2.style.transition = "none";
        return;
    }
    operacion2 -= widthImg2;
    slider2.style.transform = `translate(-${operacion2}%)`;
    slider2.style.transition = "all ease .6s";
}

// Inicializar el auto-slide para el segundo slider
setAutoSlide2();

// Variables para manejar el deslizamiento táctil
let startX2 = 0;
let currentX2 = 0;
let isDragging2 = false;

// Event listeners para el deslizamiento táctil
slider2.addEventListener('touchstart', (e) => {
    startX2 = e.touches[0].clientX;
    isDragging2 = true;
    slider2.style.transition = "none"; // Deshabilitar la transición mientras se arrastra
});

slider2.addEventListener('touchmove', (e) => {
    if (isDragging2) {
        currentX2 = e.touches[0].clientX;
        const diffX2 = currentX2 - startX2;
        slider2.style.transform = `translateX(calc(-${operacion2}% + ${diffX2}px))`;
    }
});

slider2.addEventListener('touchend', (e) => {
    isDragging2 = false;
    const diffX2 = currentX2 - startX2;
    if (diffX2 > 50) { // Deslizar a la derecha
        moveToLeft2();
    } else if (diffX2 < -50) { // Deslizar a la izquierda
        moveToRight2();
    } else { // Volver a la posición original
        slider2.style.transform = `translateX(-${operacion2}%)`;
        slider2.style.transition = "all ease .6s";
    }
});

//--------------------- GALERIA ----------------------------
const btnL = document.querySelector(".galery-btn-l"),
      btnR = document.querySelector(".galery-btn-r"),
      items = document.querySelector("#galery"),
      item = document.querySelectorAll(".item");

let op = 0,
    count = 0,
    IntervalSlide,
    widthitem = 200 / item.length; 

// Imprimir el valor de item.length en la consola
console.log('Número de elementos en la galería:', item.length);

btnL.addEventListener("click", () => { moveLeft(), resetSlide() });
btnR.addEventListener("click", () => { moveRight(), resetSlide() });
item.forEach(el => el.style.width = `${widthitem}%`);

function startSlide() {
    IntervalSlide = setInterval(() => {
        moveRight();
    }, 5000);
}

function resetSlide() {
    clearInterval(IntervalSlide);
    startSlide();
}

function moveRight() {
    if (count >= item.length - Math.floor(items.offsetWidth / item[0].offsetWidth)) {
        count = 0;
        op = 0;
        items.style.transform = `translateX(-${op}%)`;
        items.style.transition = "none";
        return;
    }
    count++;
    op = count * widthitem;
    items.style.transform = `translateX(-${op}%)`;
    items.style.transition = "all ease .6s";
}

function moveLeft() {
    if (count <= 0) {
        count = item.length - Math.floor(items.offsetWidth / item[0].offsetWidth);
        op = widthitem * count;
        items.style.transform = `translateX(-${op}%)`;
        items.style.transition = "none";
        return;
    }
    count--;
    op = count * widthitem;
    items.style.transform = `translateX(-${op}%)`;
    items.style.transition = "all ease .6s";
}

// Iniciar el carrusel automáticamente
startSlide();