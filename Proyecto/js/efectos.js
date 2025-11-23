document.addEventListener("DOMContentLoaded", () => {
    const titulo = document.querySelector(".titulo");

    // Establecer transición gradual
    titulo.style.transition = "color 0.6s ease";

    titulo.addEventListener("mouseover", () => {
        titulo.style.color = "#8B4513";  // ← Color caliente recomendado
    });

    titulo.addEventListener("mouseout", () => {
        titulo.style.color = "";  // ← Vuelve al color original
    });
});
