import {consultarProducto} from './productos-api.js'

let contenedorModal;
let codigoProducto;
let urlBase;

function modalDetalleProducto(codigo, url) {
    contenedorModal = document.getElementById('contenedor_modal');
    contenedorModal.innerHTML = '<article id="contenedor_producto"></article>';
    codigoProducto = codigo;
    urlBase = url;
    dibujarProducto();
    contenedorModal.style.display = 'flex';
}
export{modalDetalleProducto}

function dibujarProducto(){
    consultarProducto(codigoProducto, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            const contenedorProducto = document.getElementById('contenedor_producto');
            let ingredientes = '';
            for (let i = 0; i < datos.ingredientes.length; i++) {
                if(i == datos.ingredientes.length - 1){
                    ingredientes += datos.ingredientes[i]['nombre'];
                }else if(i < datos.ingredientes.length){
                    ingredientes += datos.ingredientes[i]['nombre'] + ', ';
                }
            }
            contenedorProducto.innerHTML = `
                <img src="${datos.producto.foto == 'sin_foto.jpg' ? `${urlBase}app/views/img/img-defecto/${datos.producto.foto}` : `${urlBase}app/views/img/${datos.carpeta}/${datos.producto.foto}`}" class="product-image">
                <div class="product-info">
                    <div class="product-category">${datos.producto.categoria}</div>
                    <h3 class="product-name" title="Hot Dog Vegano">${datos.producto.nombre}</h3>
                    <div class="content-product-actions">
                        <span class="product-price">$${datos.producto.precio_venta}</span>
                    </div>
                </div>
            `
            if(datos.producto.tipo == 'cocina'){
                
                contenedorProducto.innerHTML += `<p>Ingredientes: ${ingredientes}</p>`
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorProducto.innerHTML = '<h1>Error al consultar producto</h1>';
            mensajero(datos);
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
    })
}

function cerrarModal(){
    contenedorModal.innerHTML = '';
    contenedorModal.style.display = 'none';
}