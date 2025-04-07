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

function adjustSliderWidth() {
    const sliderSections = document.querySelectorAll(".slider-section2");
    const slider = document.querySelector("#slider2");

    // Calcular el ancho dinámico
    const totalSections = sliderSections.length;
    const sectionWidth = 100 / totalSections;

    // Ajustar el ancho del slider y de cada sección
    slider.style.width = `${totalSections * 100}%`;
    sliderSections.forEach(section => {
        section.style.width = `${sectionWidth}%`;
    });

    // Actualizar la variable global para el ancho de cada imagen
    widthImg2 = sectionWidth;
}

// Inicializar el auto-slide para el segundo slider
adjustSliderWidth();
setAutoSlide2();

// Validar que solo se ingresen números en CANTIDAD
function soloNumeros(event) {
    var charCode = event.which || event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}