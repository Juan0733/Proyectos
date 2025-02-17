/* Archivo encargado de controlar el envio y respuesta del formulario */
var formulario = document.getElementById("form_visitantes");

formulario.addEventListener("submit", function(a) {
    a.preventDefault(); //Evita que el navegador procese de manera predeterminada el form
    fetch("../../servicios/registro_visitantes.php", {
        method: 'POST',
        body: datos
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        console.log(datos);
        try {
            let datosRecibidos = JSON.parse(datos);
            console.log("mis datos ya parseados: " + datosRecibidos);
            alert(`${datosRecibidos.titulo}: ${datosRecibidos.msj}`);
            if (datosRecibidos.redirect) {
                window.replace(datosRecibidos.redericcion);
            }
        } catch (e) {
            alert("Ups ha ocurrido un error <br>Lo sentimos.");
        }
    })
    .catch(error => {
        console.error('Error al obtener la respuesta:', error);
        alert('Hubo un error al procesar tu solicitud. Por favor, intenta de nuevo.');
    });
});