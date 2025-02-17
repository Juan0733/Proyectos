function mostrarRegistro() {
    const login = document.getElementById("contenedor_login");
    const registro = document.getElementById("contenedor_registro");

    login.classList.add("mostrar");

    registro.classList.add("mostrar");
}

function mostrarLogin() {
    const login = document.getElementById("contenedor_login");
    const registro = document.getElementById("contenedor_registro");

    login.classList.remove("mostrar");

    registro.classList.remove("mostrar");
}
