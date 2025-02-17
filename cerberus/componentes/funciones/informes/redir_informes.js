document.addEventListener("DOMContentLoaded", function() {
    const btnTabla = document.getElementById("btn-tabla");
    const btnGrafica = document.getElementById("btn-grafica");

    btnGrafica.addEventListener("click", function() {

        btnTabla.classList.remove("activo");

        btnGrafica.classList.add("activo");

        location.href = 'informes_graficas.php'; 
    });

    btnTabla.addEventListener("click", function() {
        btnGrafica.classList.remove("activo");

        btnTabla.classList.add("activo");
        
        location.href = 'informes_tablas.php'; 
    });
});
