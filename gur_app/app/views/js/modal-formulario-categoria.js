import { registrarCategoria, actualizarCategoria, consultarCategoria } from './categorias-api.js'

let contenedorModal
let codigo;
let urlBase;
let accion;
let nombre;
let ubicacion;
let emoji;

async function modalFormularioCategoria(operacion, url, idCategoria='') {
    try {
        const response = await fetch(url+'app/views/content/modal-formulario-categoria-view.php');

        if(!response.ok) throw new Error('Hubo un error en la solicitud');

        const modal = await response.text();

        contenedorModal = document.getElementById('contenedor_modal');

        contenedorModal.innerHTML = modal;

        urlBase = url;
        accion = operacion;
        nombre = document.getElementById('nombre');
        ubicacion = document.getElementById('ubicacion');
        emoji = document.getElementById('emoji');
        if(accion == 'editar_categoria'){
            codigo = idCategoria;
            dibujarCategoria();
        }
        eventoFormulario();

        contenedorModal.style.display = 'flex';

    } catch (error) {
        
    }
}
export{modalFormularioCategoria}

function dibujarCategoria(){
    consultarCategoria(codigo, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            nombre.value = datos.categoria.nombre;
            ubicacion.value = datos.categoria.ubicacion;
            emoji.value = datos.categoria.emoji;
        } 
    })
}

function eventoFormulario(){

    let formulario = document.getElementById('forma_registro');
    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();
        
        let datos = new FormData();
        datos.append('accion', accion);
        datos.append('nombre', nombre.value);
        datos.append('ubicacion', ubicacion.value);
        datos.append('emoji', emoji.value)
        if(accion == 'registrar_categoria'){
            registrarCategoria(datos, urlBase).then(datos=>{
                mensajero(datos);
            });
        }else if(accion == 'editar_categoria'){
            datos.append('codigo', codigo);
            actualizarCategoria(datos, urlBase).then(datos=>{
                mensajero(datos);
            });
        }
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
