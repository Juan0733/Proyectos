import { consultarCategoriasProductos, emojis } from './categorias-api.js'
import { consultarProductos, eliminarProducto } from './productos-api.js';
import { modalFormularioProducto } from './modal-formulario-producto.js'
import { modalDetalleProducto } from './modal-detalle-producto.js'


let urlBase;
let filtroCategoria;
let filtroEstado = 'activo';
let limite = 0;

function cantidadProductos(){
    if(window.innerWidth > 1024 ){
        limite += 10; 
    }
}

function dibujarCategorias(){
    let contenedorCategorias = document.getElementById('lista_categorias');
    let contadorProductos = document.getElementById('total_productos');
    consultarCategoriasProductos(urlBase, filtroEstado).then((datos)=>{
        if(datos.tipo == 'OK'){
            if(contadorProductos){
                contadorProductos.innerHTML = `<span aria-hidden="true">${datos.total_productos}</span>`;
            }
            contenedorCategorias.innerHTML = '';
            if(datos.categorias.length > 0){ 
                if(!filtroCategoria){
                    filtroCategoria = datos.categorias[0]['contador'];
                }
                
                datos.categorias.forEach(categoria => {
                    contenedorCategorias.innerHTML += `
                        <li id="${categoria.contador}" class="category-products">
                            <button class="category-name" aria-pressed="true" data-id="${categoria.contador}">
                                <span>${emojis[categoria.emoji]} ${categoria.nombre}</span>
                                <div class="count-category-items" aria-label="Cantidad de productos en categoría">
                                    <span aria-hidden="true">${categoria.cantidad_productos}</span>
                                </div>
                            </button>
                        </li>
                    `;
                });
                eventoFiltrosCategorias();
                dibujarProductos();
            }else if(datos.categorias.length < 1){
                contenedorCategorias.innerHTML += '<h1>No se encontraron las categorias de los productos</h1>';
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorCategorias.innerHTML += '<h1>Error al consultar las categorias de los productos</h1>';
            mensajero(datos);
        }
    })
}

function dibujarProductos(){
    let contenedorProductos = document.getElementById('contenedor_productos');
    cantidadProductos();
    consultarProductos(filtroCategoria, filtroEstado, limite, urlBase).then((datos)=>{
        if(datos.tipo == 'OK'){
            if(filtroEstado == 'activo' && datos.cargo == 'AD'){
                contenedorProductos.innerHTML = 
                `<div id="agregar_producto" class="add-product-card">
                    <div class="add-icon">+</div>
                    <div>Agregar nuevo producto</div>
                </div>`;
            }else{
                contenedorProductos.innerHTML = '';
            }
            
            if(datos.productos.length > 0){ 
                datos.productos.forEach(producto => {
                    contenedorProductos.innerHTML += `
                        <div class="product-card">
                            <img src="${producto.foto == 'sin_foto.jpg' ? `app/views/img/img-defecto/${producto.foto}` : `app/views/img/${datos.carpeta}/${producto.foto}`}" class="product-image">
                            <div class="product-info">
                                <div class="product-category">${producto.categoria}</div>
                                <h3 class="product-name" title="Hot Dog Vegano">${producto.nombre}</h3>
                                <div class="content-product-actions">
                                    <span class="product-price">$${producto.precio_venta}</span>
                                    <div class="product-actions">
                                        ${filtroEstado == 'activo' && datos.cargo == 'AD'? `<button class="btn-edit" data-id="${producto.codigo_producto}"><ion-icon name="create-outline"></ion-icon></button>
                                        <button class="btn-delete" data-id="${producto.codigo_producto}"><ion-icon name="trash-outline"></ion-icon></button>` : filtroEstado == 'inactivo' && datos.cargo == 'AD' ? `<button class="btn-restaurar" data-id="${producto.codigo_producto}">Restaurar</button>`: filtroEstado == 'activo' && datos.cargo == 'MS' ? `<button class="btn-ver-detalle" data-id="${producto.codigo_producto}">Ver detalle</button>` : ''}
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                });

                if(datos.productos.length == limite){
                    contenedorProductos.innerHTML += '<button type="button" id="ver_mas"> Ver Mas</button>';
                    document.getElementById('ver_mas').addEventListener('click', dibujarProductos)
                }
                
                if(filtroEstado == 'activo' && datos.cargo == 'AD'){
                    eventoEliminar();
                    eventoEditar();
                    eventoAgregar();

                }else if(filtroEstado == 'inactivo' && datos.cargo == 'AD'){
                    eventoRestaurar();
                }else if(filtroEstado == 'activo' && datos.cargo == 'MS'){
                    eventoVerDetalle();
                }
                
            }else if(datos.productos.length < 1){
                contenedorProductos.innerHTML += '<h1>No se encontraron productos</h1>';
                if(filtroEstado == 'activo' && datos.cargo == 'AD'){
                    eventoAgregar();
                }
            } 
        }else if(datos.tipo == 'ERROR'){
            contenedorProductos.innerHTML = '<h1>Error al consultar productos</h1>';
            mensajero(datos);
        }
    })
}

function eventoVerDetalle(){
    const btnsVerDetalle = document.querySelectorAll('.btn-ver-detalle');
    btnsVerDetalle.forEach(button=>{
        button.addEventListener('click', ()=>{
            let codigoProducto = button.getAttribute('data-id');
            modalDetalleProducto(codigoProducto, urlBase);
        })
    })
}

function eventoEliminar(){
    const btnsEliminar = document.querySelectorAll('.btn-delete');
    btnsEliminar.forEach(button => {
        button.addEventListener('click', ()=>{
            let codigoProducto = button.getAttribute('data-id');
            let mensaje = "¿Esta seguro que desea eliminar este producto?"
            alertConfirmacion(mensaje, codigoProducto);
        })
    });
}

function eventoEditar(){
    const btnsEditar = document.querySelectorAll('.btn-edit');
    btnsEditar.forEach(button=>{
        button.addEventListener('click', ()=>{
            let codigoProducto = button.getAttribute('data-id');
            modalFormularioProducto('editar_producto', urlBase, codigoProducto);
        })
    })
}

function eventoAgregar(){
    let btnAgregar = document.getElementById('agregar_producto');
    btnAgregar.addEventListener('click', ()=>{
        modalFormularioProducto('registrar_producto', urlBase);
    })
}

function eventoFiltrosCategorias(){
    const categorias = document.querySelectorAll('.category-name');
    document.getElementById(filtroCategoria).classList.add('active');
    categorias.forEach(button => {
        button.addEventListener('click', ()=>{
            document.getElementById(filtroCategoria).classList.remove('active');
            filtroCategoria = button.getAttribute('data-id');
            document.getElementById(filtroCategoria).classList.add('active');
            limite = 0;
            dibujarProductos();
        })
    });
}

function eventoFiltrosEstado(){
    const estados = document.querySelectorAll('.estado');
    if(estados){
        estados.forEach(button => {
            button.addEventListener('click', ()=>{
                filtroEstado = button.getAttribute('data-id');
                dibujarCategorias();
            })
        })
    }
}

function eventoRestaurar(){
    const btnsRestaurar = document.querySelectorAll('.btn-restaurar');

    btnsRestaurar.forEach(button => {
        button.addEventListener('click', ()=>{
            let codigoProducto = button.getAttribute('data-id');
           modalFormularioProducto('restaurar_producto', urlBase, codigoProducto);
        })
    });
}

function alertConfirmacion(mensaje, codigo){
    Swal.fire({
        icon: 'warning',
        title: 'Eliminar Producto',
        text: mensaje,
        confirmButtonText: 'CONFIRMAR',
        showCloseButton: true,
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarProducto(codigo, urlBase).then((datos=>{
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
                dibujarCategorias();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    urlBase = document.getElementById('url').value;
    dibujarCategorias();
    eventoFiltrosEstado();
})