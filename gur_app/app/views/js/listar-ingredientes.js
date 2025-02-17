import{ consultarIngredientes, eliminarIngrediente } from './ingredientes-api.js'
import { modalFormularioIngrediente } from './modal-formulario-ingrediente.js'

let urlBase;
let filtroEstado = 'activo';

function dibujarIngredientes() {
       
    let contenedorIngredientes = document.getElementById('contenedor_ingredientes');
    consultarIngredientes(filtroEstado, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            contenedorIngredientes.innerHTML = '';
            if(datos.ingredientes.length > 0){ 
                datos.ingredientes.forEach(ingrediente => {
                    contenedorIngredientes.innerHTML += `
                        <div>
                            <h2>${ingrediente.nombre}</h2>
                            <p>Unidad Medida: ${ingrediente.unidad_medida}</p>
                            <p>Stock Actual: ${ingrediente.stock_actual}</p>
                            ${filtroEstado == 'activo' ? `<button type="button" class="btn-eliminar" data-id="${ingrediente.codigo_ingrediente}">Eliminar</button>
                            <button type="button" class="btn-editar" data-id="${ingrediente.codigo_ingrediente}">Editar</button>` : `<button type="button" class="btn-restaurar" data-id="${ingrediente.codigo_ingrediente}">Restaurar</button>`}
                        </div>
                    `
                });
                
                if(filtroEstado == 'activo'){
                    eventoEliminar();
                    eventoEditar();
                }else if(filtroEstado == 'inactivo'){
                    eventoRestaurar();
                }
                
            }else if(datos.ingredientes.length < 1){
                contenedorIngredientes.innerHTML = '<h1>No se encontraron ingredientes</h1>';
            } 

            if(filtroEstado == 'activo'){
                eventoAgregar(); 
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorIngredientes.innerHTML = '<h1>Error al consultar ingredientes</h1>';
            mensajero(datos);
        }
    })
}

function eventoAgregar(){
    let btnAgregar = document.getElementById('agregar_ingrediente');
    btnAgregar.addEventListener('click', ()=>{
        modalFormularioIngrediente('registrar_ingrediente', urlBase);
    })
}

function eventoEliminar(){
    const btnsEliminar = document.querySelectorAll('.btn-eliminar');
    btnsEliminar.forEach(button => {
        button.addEventListener('click', ()=>{
            let codigoIngrediente = button.getAttribute('data-id');
            let mensaje = "¿Esta seguro que desea eliminar este ingrediente?"
            alertConfirmacion(mensaje, codigoIngrediente);
        })
    });
}

function eventoEditar(){
    const btnsEditar = document.querySelectorAll('.btn-editar');
    btnsEditar.forEach(button=>{
        button.addEventListener('click', ()=>{
            let codigoIngrediente = button.getAttribute('data-id');
            modalFormularioIngrediente('editar_ingrediente', urlBase, codigoIngrediente);
        })
    })
}

function eventoFiltrosEstado(){
    const estados = document.querySelectorAll('.estado');
    if(estados){
        estados.forEach(button => {
            button.addEventListener('click', ()=>{
                filtroEstado = button.getAttribute('data-id');
                dibujarIngredientes();
            })
        })
    }
}

function eventoRestaurar(){
    const btnsRestaurar = document.querySelectorAll('.btn-restaurar');
    btnsRestaurar.forEach(button => {
        button.addEventListener('click', ()=>{
            let codigoIngrediente = button.getAttribute('data-id');
            modalFormularioIngrediente('restaurar_ingrediente', urlBase, codigoIngrediente);
        })
    });
}

function alertConfirmacion(mensaje, codigo){
    Swal.fire({
        icon: 'warning',
        title: 'Eliminar Ingrediente',
        text: mensaje,
        confirmButtonText: 'CONFIRMAR',
        showCloseButton: true,
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarIngrediente(codigo, urlBase).then(datos=>{
                mensajero(datos);
            });
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
                dibujarIngredientes();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    urlBase = document.getElementById('url').value;
    dibujarIngredientes();
    eventoFiltrosEstado();
})