import { consultarCategorias, eliminarCategoria, emojis } from './categorias-api.js'
import {modalFormularioCategoria} from './modal-formulario-categoria.js'

let urlBase;

function dibujarCategorias(){
    let contenedorCategorias = document.getElementById('contenedor_categorias');
    consultarCategorias(urlBase).then((datos)=>{
        if(datos.tipo == 'OK'){
            contenedorCategorias.innerHTML = '<button id="agregar_categoria">AGREGAR</button>';
            if(datos.categorias.length > 0){ 
                datos.categorias.forEach(categoria => {
                    contenedorCategorias.innerHTML += `
                        <div>
                            <h2>${categoria.nombre}</h2>
                            <p>${categoria.ubicacion}</p>
                            ${emojis[categoria.emoji]}
                            <button type="button" class="btn-eliminar" data-id="${categoria.contador}">Eliminar</button>
                            <button type="button" class="btn-editar" data-id="${categoria.contador}">Editar</button>
                        </div>
                    `
                });
                
                eventoEliminar();
                eventoEditar();
            }else if(datos.categorias.length < 1){
                contenedorCategorias.innerHTML = '<h1>No se encontraron categorias</h1>';
            } 
            eventoAgregar();
        }else if(datos.tipo == 'ERROR'){
            contenedorCategorias.innerHTML = '<h1>Error al consultar las categorias</h1>';
            mensajero(datos);
        }
    })
}

function eventoEliminar(){
    const btnsEliminar = document.querySelectorAll('.btn-eliminar');
    btnsEliminar.forEach(button => {
        button.addEventListener('click', ()=>{
            let codigoCategoria = button.getAttribute('data-id');
            let mensaje = "¿Esta seguro que desea eliminar esta categoria?"
            alertConfirmacion(mensaje, codigoCategoria);
        })
    });
}

function eventoEditar(){
    const btnsEditar = document.querySelectorAll('.btn-editar');
    btnsEditar.forEach(button=>{
        button.addEventListener('click', ()=>{
            let codigoCategoria = button.getAttribute('data-id');
            modalFormularioCategoria('editar_categoria', urlBase, codigoCategoria);
            
        })
    })
}

function eventoAgregar(){
    const btnAgregar = document.getElementById('agregar_categoria');

    btnAgregar.addEventListener('click', ()=>{
        modalFormularioCategoria('registrar_categoria', urlBase);
    })
}

function alertConfirmacion(mensaje, codigo){
    Swal.fire({
        icon: 'warning',
        title: 'Eliminar Categoria',
        text: mensaje,
        confirmButtonText: 'CONFIRMAR',
        showCloseButton: true,
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarCategoria(codigo, urlBase).then((datos)=>{
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
                dibujarCategorias();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    urlBase = document.getElementById('url').value;
    dibujarCategorias();
})