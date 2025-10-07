function mostrarSeccion(seccion) {
    document.querySelectorAll("section").forEach(s => s.style.display = "none");
    document.getElementById(seccion).style.display = "block";
}
