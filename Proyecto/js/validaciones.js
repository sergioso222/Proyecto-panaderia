function validarFormulario(formId) {
    const form = document.getElementById(formId);
    let valid = true;
    let errores = [];

    form.querySelectorAll("input, textarea").forEach(input => {
        if (input.hasAttribute("required") && input.value.trim() === "") {
            valid = false;
            errores.push(`El campo "${input.name}" no puede estar vacío`);
        }

        if (input.type === "email" && input.value.trim() !== "") {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(input.value)) {
                valid = false;
                errores.push(`Email inválido: ${input.value}`);
            }
        }

        if (input.type === "password" && input.value.length < 6) {
            valid = false;
            errores.push("La contraseña debe tener al menos 6 caracteres");
        }
    });

    if (!valid) {
        alert(errores.join("\n"));
    }

    return valid;
}

// ---------- NUEVO: mostrar/ocultar formulario de registro ----------
document.addEventListener("DOMContentLoaded", () => {
    const btnRegistro = document.getElementById("btnMostrarRegistro");
    const registroForm = document.getElementById("registroForm");

    btnRegistro.addEventListener("click", () => {
        if (registroForm.style.display === "none" || registroForm.style.display === "") {
            registroForm.style.display = "block";
            btnRegistro.textContent = "Ocultar registro";
        } else {
            registroForm.style.display = "none";
            btnRegistro.textContent = "Registrarse";
        }
    });
});
