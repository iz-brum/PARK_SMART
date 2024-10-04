const hamburguer = document.querySelector('.menu-hamburguer');
const navLinks = document.querySelector('.nav-links');

hamburguer.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});
