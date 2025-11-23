document.addEventListener("DOMContentLoaded", () => {
    const enlaces = document.querySelectorAll(".navegacion-principal a");
    const secciones = document.querySelectorAll(".seccion");

    enlaces.forEach(enlace => {
        enlace.addEventListener("click", e => {
            e.preventDefault();

            const destino = enlace.dataset.section;

            // Ocultar todas las secciones
            secciones.forEach(sec => sec.classList.remove("activa"));

            // Mostrar la sección seleccionada
            document.getElementById(destino).classList.add("activa");

            // Marcar el enlace activo
            enlaces.forEach(l => l.classList.remove("activo"));
            enlace.classList.add("activo");
        });
    });
});
