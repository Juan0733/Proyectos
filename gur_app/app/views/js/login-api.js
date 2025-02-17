var formulario = document.getElementById("forma_acceso");
formulario.addEventListener("submit",function(e){
    e.preventDefault();
        let data = new FormData(formulario);
        let method=formulario.getAttribute("method");
        let action= formulario.getAttribute("action");

        let encabezados= new Headers();
        
        fetch(action, {
            method: "POST",
            body: data,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
        })
            .then(respuesta => respuesta.json())
            .then(datos => {
                

                if (datos['titulo'] == "OK" ) {
                    window.location.replace(datos.url);

                }
                if (datos.tipo == "error") {

                    return manejo_de_alertas_login(datos);
                }

            });

})






function manejo_de_alertas_login(respuesta){
   
    if(respuesta.tipo =="error"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar',
            customClass: {
                popup: 'alerta-contenedor',
                confirmButton: 'btn-confirmar'
            }
        });
    }
}