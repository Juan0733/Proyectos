/* Enviar formularios con fetch */
const formularios_api=document.querySelectorAll(".formulario-api");

formularios_api.forEach(formularios => {

    formularios.addEventListener("submit",function(e){
        
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Quieres realizar la acción solicitada",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, realizar',
            cancelButtonText: 'No, cancelar',
            customClass: {
                popup: 'alerta-contenedor',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar'
            }
        }).then((result) => {
            if (result.isConfirmed){

                let data = new FormData(this);
                let method=this.getAttribute("method");
                let action=this.getAttribute("action");

                let encabezados= new Headers();

                let config={
                    method: method,
                    headers: encabezados,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data
                };

                fetch(action,config)
                .then(respuesta => respuesta.json())
                .then(respuesta =>{ 
                    return manejo_de_alertas(respuesta);
                })
                .catch((error) => {
                    console.error('Hubo un problema con la solicitud fetch:', error);
                    Swal.fire({
                        icon: 'info',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud.',
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            popup: 'alerta-contenedor',
                            confirmButton: 'btn-confirmar'
                        }
                    });
                });
            }
        })

    });

});

function manejo_de_alertas(respuesta){
    if(respuesta.tipoMensaje =="normal"){

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

    }else if(respuesta.tipoMensaje == "confirmado"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            timer: 2000,
            confirmButtonText: 'Aceptar',
            customClass: {
                popup: 'alerta-contenedor',
                confirmButton: 'btn-confirmar',
                icon: 'icon-alert'
            }
        });

    }else if(respuesta.tipoMensaje =="recargar"){
        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if(respuesta.tipoMensaje =="limpiar"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                document.querySelector(".Formulario-fetch").reset();
            }
        });

    }else if(respuesta.tipoMensaje =="redireccionar"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                    window.location.href=respuesta.url;
            }
        });
    }
}
