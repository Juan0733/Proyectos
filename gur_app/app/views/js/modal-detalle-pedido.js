import { consultarPedidosMesa, eliminarItem, entregarItem, entregarItems } from "./pedidos-api.js";

let contenedorModal;
let numeroMesa;
let urlBase;

function modalDetallePedido(mesa, url) {
    
    contenedorModal = document.getElementById('contenedor_modal');
    contenedorModal.innerHTML = '<article id="contenedor_pedidos"></article>';
    numeroMesa = mesa;
    urlBase = url;
    dibujarPedidos();
    contenedorModal.style.display = 'flex';
}
export{modalDetallePedido}

function dibujarPedidos(){
    let contenedorPedidos = document.getElementById('contenedor_pedidos');
    consultarPedidosMesa(numeroMesa, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            contenedorPedidos.innerHTML = '';
            datos.pedidos.forEach(pedido => {
                contenedorPedidos.innerHTML += `
                <div id="${pedido.id}">
                    <div style="display:flex;">
                        <h2>${pedido.titulo}</h2>
                        <button type="button" class="btn-eliminar-pedido" data-id="${pedido.id}">Eliminar pedido</button>
                        <button type="button" class="btn-agregar" data-id="${pedido.id}">Agregar productos</button>
                    </div>
                </div>`
                let contenedorPedido = document.getElementById(pedido.id);
                pedido.productos.forEach(producto => {
                    contenedorPedido.innerHTML += `
                    <div>
                        <div style="display:flex;">
                            <img style="height:30px; width:30px; border-radius:20px;" src="${producto.foto == 'sin_foto.jpg' ? `${urlBase}app/views/img/img-defecto/${producto.foto}` : `${urlBase}app/views/img/${datos.carpeta}/${producto.foto}`}">
                            <div>
                                <h1>${producto.nombre}</h1>
                                <p>x${producto.cantidad} $${producto.subtotal}</p>
                            </div>
                            ${producto.cantidad_sin_entregar > 0 ? 
                            `<button type="button" class="btn-entregar-items" pedido-id="${pedido.id}" producto-id="${producto.id}">Entregar todo</button>` : ''}
                        </div>
                        <div id="${producto.id}${pedido.id}"></div>
                    </div>`;
                    let contenedoItems = document.getElementById(`${producto.id}${pedido.id}`);
                    producto.items.forEach(item => {
                        let ingredientesEliminados = '';
                        for (let i = 0; i < item.ingredientes_eliminados.length; i++) {
                            if(i == item.ingredientes_eliminados.length - 1){
                                ingredientesEliminados += item.ingredientes_eliminados[i]['nombre'];
                            }else if(i < item.ingredientes_eliminados.length){
                                ingredientesEliminados += item.ingredientes_eliminados[i]['nombre'] + ', ';
                            }
                        }
                    
                        for (let i = 0; i < item.cantidad_entregada; i++) {
                            contenedoItems.innerHTML += `
                            <div style="display:flex;">
                                <p>Hora: ${item.hora}</p>
                                <button type="button" class="btn-eliminar-item" data-id="${item.id}" data-estado="entregado">Eliminar</button>
                                ${item.observacion != '' ? `<p>Observacion: ${item.observacion}</p>` : ''}
                                ${item.ingredientes_eliminados.length > 0 ? `<p>Ingredientes eliminados: ${ingredientesEliminados}</p>` : ''}
                            </div>`
                        }

                        for (let i = 0; i < item.cantidad_sin_entregar; i++) {
                            contenedoItems.innerHTML += `
                            <div style="display:flex;">
                                <p>Hora: ${item.hora}</p>
                                <button type="button" class="btn-eliminar-item" data-id="${item.id}" data-estado="sin_entregar">Eliminar</button>
                                <button type="button" class="btn-entregar-item" data-id="${item.id}">Entregar</button>
                                ${item.observacion != '' ? `<p>Observacion: ${item.observacion}</p>` : ''}
                                ${item.ingredientes_eliminados.length > 0 ? `<p>Ingredientes eliminados: ${ingredientesEliminados}</p>` : ''}
                            </div>`
                        }
                    });
                });
                contenedorPedido.innerHTML += `<h2>Total: $${pedido.total}</h2>`;
            });
            contenedorPedidos.innerHTML += '<button id="agregar_pedido" type="button">Generar nuevo pedido</button>';

            eventoAgregarItems();
            eventoEliminarItem();
            eventoEntregarItem();
            eventoEntregarItems();
            eventoNuevoPedido();
        }else if(datos.tipo == 'ERROR'){
            mensajero(datos);
        }     
    });
}

function eventoAgregarItems(){
    const btnsAgregar = document.querySelectorAll('.btn-agregar');
    btnsAgregar.forEach(button => {
        let idpedido = button.getAttribute('data-id');
        button.addEventListener('click', ()=>{
            window.location.replace(`${urlBase}generar-pedido/${numeroMesa}/${idpedido}`)
        });
    });
};

function eventoEliminarItem(){
    const btnsEliminar = document.querySelectorAll('.btn-eliminar-item');

    btnsEliminar.forEach(button => {
        let idItem = button.getAttribute('data-id');
        let estadoMesero = button.getAttribute('data-estado');
        button.addEventListener('click', ()=>{
            let mensaje = "¿Estas seguro que deseas eliminar este producto del pedido?"
           alertConfirmacion(mensaje, idItem, estadoMesero);
        })
    });
}

function eventoEntregarItem(){
    const btnsEntregar = document.querySelectorAll('.btn-entregar-item');

    btnsEntregar.forEach(button=>{
        let idItem = button.getAttribute('data-id');
        button.addEventListener('click', ()=>{
            entregarItem(idItem, urlBase).then(datos=>{
                mensajero(datos);
            })
        })
    })
}

function eventoEntregarItems(){
    const btnsEntregar = document.querySelectorAll('.btn-entregar-items');

    btnsEntregar.forEach(button=>{
        let idPedido = button.getAttribute('pedido-id');
        let idProducto = button.getAttribute('producto-id');
        button.addEventListener('click', ()=>{
            entregarItems(idPedido, idProducto, urlBase).then(datos=>{
                mensajero(datos);
            })
        })
    })
}

function eventoNuevoPedido(){
    const btnNuevoPedido = document.getElementById('agregar_pedido');
    
    btnNuevoPedido.addEventListener('click', ()=>{
        window.location.replace(`generar-pedido/${numeroMesa}`);
    })
}

function cerrarModal(){
    contenedorModal.innerHTML = '';
    contenedorModal.style.display = 'none';
}

function alertConfirmacion(mensaje, codigo, estadoMesero){
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
            eliminarItem(codigo, estadoMesero, urlBase).then((datos=>{
                if(datos.tipo == 'OK' && datos.cod_error == 250){
                    dibujarPedidos();
                }
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
                dibujarPedidos();
            }
        }
    });
}

