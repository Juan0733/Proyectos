import { consultarCategoriasProductos, emojis } from './categorias-api.js'
import { consultarProductos } from './productos-api.js'
import { registrarPedido, registrarItems } from './pedidos-api.js'
import { modalAgregarItem, pedido } from './modal-agregar-item.js'

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
            contadorProductos.innerHTML = `<span aria-hidden="true">${datos.total_productos}</span>`;
        
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
    consultarProductos(filtroCategoria, filtroEstado, limite, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            contenedorProductos.innerHTML = '';
            if(datos.productos.length > 0){
                datos.productos.forEach(producto => {
                    contenedorProductos.innerHTML +=  `
                    <div class="product-card producto" data-id="${producto.codigo_producto}">
                        <img src="${producto.foto == 'sin_foto.jpg' ? `${urlBase}app/views/img/img-defecto/${producto.foto}` : `${urlBase}app/views/img/${datos.carpeta}/${producto.foto}`}" class="product-image">
                        <div class="product-info">
                            <div class="product-category">${producto.categoria}</div>
                            <h3 class="product-name" title="Hot Dog Vegano">${producto.nombre}</h3>
                            <div class="content-product-actions">
                                <span class="product-price">$${producto.precio_venta}</span>
                            </div>
                        </div>
                    </div>`
                });

                if(datos.productos.length == limite){
                    contenedorProductos.innerHTML += '<button type="button" id="ver_mas">Ver Mas</button>';
                    document.getElementById('ver_mas').addEventListener('click', dibujarProductos)
                }

                eventoSeleccionarProducto();
            }else if(datos.productos.length < 1){
                contenedorProductos.innerHTML += '<h1>No se encontraron productos</h1>';
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorProductos.innerHTML = '<h1>Error al consultar productos</h1>';
            mensajero(datos);
        }
    })
}

function eventoSeleccionarProducto(){
    const bntsProducto = document.querySelectorAll('.producto');

    bntsProducto.forEach(button => {
        let codigoProducto = button.getAttribute('data-id');
        button.addEventListener('click', ()=>{
            modalAgregarItem(codigoProducto, urlBase);
        })
    });
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

function eventoGenerarPedido(){
    let btnGenerarPedido = document.getElementById('generar_pedido');
    btnGenerarPedido.addEventListener('click', ()=>{
        if(pedido.items.length > 0){
            let datos = new FormData();

            datos.append('accion', 'registrar_pedido');
            datos.append('pedido', JSON.stringify(pedido));

            registrarPedido(datos, urlBase).then(datos=>{
                mensajero(datos);
            })
        }
    })
}

function eventoRegistrarItems(){
    let btnRegistrarItems = document.getElementById('registrar_items');
    btnRegistrarItems.addEventListener('click', ()=>{
        if(pedido.items.length > 0){
            let idPedido = document.getElementById('pedido').value;
            let datos = new FormData();
            pedido.id = idPedido;

            datos.append('accion', 'registrar_items');
            datos.append('pedido', JSON.stringify(pedido));

            console.log(pedido);

            registrarItems(datos, urlBase).then(datos=>{
                mensajero(datos);
            })
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
                window.location.replace(urlBase+respuesta.url);
            }
        }
    })
}

document.addEventListener('DOMContentLoaded', ()=>{
    urlBase = document.getElementById('url').value;
    pedido.mesa = document.getElementById('numero').value;
    if(document.getElementById('pedido')){
       pedido.id = document.getElementById('pedido').value;
       eventoRegistrarItems();
    }else{
        eventoGenerarPedido();
    }
    dibujarCategorias();
})