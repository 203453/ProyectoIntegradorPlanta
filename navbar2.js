const navToggle = document.querySelector(".nav-toggle");
const navMenu = document.querySelector(".nav-menun");

navToggle.addEventListener("click", () => {
    navMenu.classList.toggle("nav-menun_visible");

    if(navMenu.classList.contains(nav-menun_visible)) {
        navToggle.setAttribute("aria-label", "Cerrar menú");
    } else {
        navToggle.setAttribute("aria-label", "Abrir menú");
    }
});