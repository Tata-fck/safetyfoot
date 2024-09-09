document.addEventListener("DOMContentLoaded", function() {
    fetch('elements/header/header.html')
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('afterbegin', data);
            
            const menuIcon = document.querySelector('.menu-icon');
            const menuItems = document.querySelector('.menu-items');
            const titulo = document.querySelector('.titulo');
            const logo = document.querySelector('.logo');

            function toggleMenu() {
                menuItems.classList.toggle('active');
                if (window.innerWidth <= 830) {
                    titulo.style.display = menuItems.classList.contains('active') ? 'none' : 'block';
                    logo.classList.toggle('logo-expanded', menuItems.classList.contains('active'));
                }
            }

            if (menuIcon && menuItems) {
                menuIcon.addEventListener('click', toggleMenu);
            }
        })
        .catch(error => console.error('Error loading header:', error));
});
