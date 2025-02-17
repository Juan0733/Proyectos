import { consultarMesas, registrarMesa } from "./mesas-api.js";

let contenedorModal;
let urlBase;
let formulario;
let accion;

async function modalFormularioMesa(operacion, url, idMesa='') {
    try {
        const response = await fetch(url+'app/views/content/modal-formulario-mesa-view.php');
        
        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const modal = await response.text();
        contenedorModal = document.getElementById('contenedor_modal');

        contenedorModal.innerHTML = modal;

        urlBase = url;
        accion = operacion;
        formulario = document.getElementById('forma_registro');
        dibujarNumerosMesas();
        eventoFormulario();

        contenedorModal.style.display = 'flex';

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{modalFormularioMesa}

function dibujarNumerosMesas(){
    let selectNumeros = document.getElementById('numero');
    consultarMesas('todas', urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            selectNumeros.innerHTML = '';    
            selectNumeros.innerHTML = `<option value="" selected disabled>Seleccionar</option`;
            for (let i = 1; i < 100; i++) {
                let existe = false;
                if(datos.mesas.length > 0){
                    for (let e = 0; e < datos.mesas.length; e++) {
                        if(i == datos.mesas[e].numero){
                            existe = true;
                            break;
                        }
                    }
                }
                    
                if(!existe){
                    selectNumeros.innerHTML += `<option value="${i}">${i}</option`;
                }
            }
        }else{
            formulario.innerHTML = '<h1>Error al consultar numeros de mesas</h1>';
        }
    })
}

function eventoFormulario(){
    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();
        
        let datos = new FormData();
        let numeroMesa = document.getElementById('numero');
        let capacidad = document.getElementById('capacidad');

        datos.append('numero', numeroMesa.value);
        datos.append('capacidad', capacidad.value);
        datos.append('accion', accion);
        
        registrarMesa(datos, urlBase).then(datos=>{
            mensajero(datos);
        })

    })
}

function cerrarModal(){
    contenedorModal.innerHTML = '';
    contenedorModal.style.display = 'none';
}

function mensajero(respuesta){
    Swal.fire({
        icon: respuesta.icono,
        title: respuesta.titulo,
        text: respuesta.mensaje,
        confirmButtonText: 'Aceptar',
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
    }).then((result)=>{
        if (result.isConfirmed) {
            if(respuesta.tipo == 'OK'){
                window.location.replace(urlBase+respuesta.url);
            }
        }
    })
}
