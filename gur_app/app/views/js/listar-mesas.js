import { consultarMesas, eliminarMesa, consultarMesa, validarUsuarioMesa } from './mesas-api.js'
import { modalFormularioMesa } from './modal-formulario-mesa.js'
import { modalDetallePedido } from './modal-detalle-pedido.js'

let urlBase;
let filtroDisponibilidad = 'todas';

function dibujarMesas(){
    let contenedorMesas = document.getElementById('contenedor_mesas');
    consultarMesas(filtroDisponibilidad, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            if(datos.mesas.length > 0){
                contenedorMesas.innerHTML = '';
                datos.mesas.forEach(mesa => {
                    contenedorMesas.innerHTML += `
                        <div class="mesa" data-id="${mesa.numero}">${mesa.nombre}</div>
                        <div class="product-actions">
                            ${datos.cargo == 'AD' ? `
                            <button class="btn-delete" data-id="${mesa.numero}"><ion-icon name="trash-outline"></ion-icon></button>` : ''}
                        </div>
                    `;
                });

                if(datos.cargo == 'AD'){
                    eventoAgregar();
                    eventoEliminar();
                }else if(datos.cargo == 'MS'){
                    eventoSeleccionarMesa();
                }
            }else if(datos.mesas.length < 1){
                contenedorMesas.innerHTML = '<h1>No encontraron mesas</h1>';
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorMesas.innerHTML = '<h1>Error al consultar mesas</h1>';
            mensajero(datos);
        }
    })
}

function eventoSeleccionarMesa(){
    const mesas = document.querySelectorAll('.mesa');

    mesas.forEach(mesa => {
        let numeroMesa = mesa.getAttribute('data-id');
        mesa.addEventListener('click', ()=>{
            consultarMesa(numeroMesa, urlBase).then(datos=>{
                if(datos.tipo == 'OK'){
                    if(datos.mesa.disponibilidad == 'ocupada'){
                        validarUsuarioMesa(numeroMesa, urlBase).then(datos=>{
                            if(datos.tipo == 'OK'){
                                modalDetallePedido(numeroMesa, urlBase);
                            }else if(datos.tipo == 'ERROR'){
                                mensajero(datos);
                            }
                        })
                    }else if(datos.mesa.disponibilidad == 'libre'){
                        window.location.replace(`${urlBase}generar-pedido/${numeroMesa}`);
                    }
                }else if(datos.tipo == 'ERROR'){
                    mensajero(datos);
                }
            })
        })
    });
}

function eventoAgregar(){
    const btnAgregar = document.getElementById('agregar_mesa');

    btnAgregar.addEventListener('click', ()=>{
        modalFormularioMesa('registrar_mesa', urlBase);
    })
}

function eventoEliminar(){
    const btnsEliminar = document.querySelectorAll('.btn-delete');
    btnsEliminar.forEach(button => {
        button.addEventListener('click', ()=>{
            let numeroMesa = button.getAttribute('data-id');
            let mensaje = "¿Esta seguro que desea eliminar esta mesa?"
            alertConfirmacion(mensaje, numeroMesa);
        })
    });
}

function eventoFiltrosDisponibilidad(){
    const filtros = document.querySelectorAll('.disponibilidad');
    if(filtros){
        filtros.forEach(button=>{
            button.addEventListener('click', ()=>{
                filtroDisponibilidad = button.getAttribute('data-id');
                dibujarMesas();
            })
        })
    }
}

function alertConfirmacion(mensaje, codigo){
    Swal.fire({
        icon: 'warning',
        title: 'Eliminar Mesa',
        text: mensaje,
        confirmButtonText: 'CONFIRMAR',
        showCloseButton: true,
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarMesa(codigo, urlBase).then((datos=>{
                mensajero(datos);
            }));
        }
    })

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
                dibujarMesas();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    urlBase = document.getElementById('url').value;
    dibujarMesas();
    eventoFiltrosDisponibilidad();
    if(document.getElementById('numero_mesa')){
        let numeroMesa = document.getElementById('numero_mesa').value;
        modalDetallePedido(numeroMesa, urlBase);
    }
})

