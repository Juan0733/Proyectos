import {consultarProducto} from './productos-api.js'

let codigoProducto;
let urlBase;
let formularioIngredientes;
let inputsIngredientes;
let contenedorModal; 
let ingredientesEliminados;
let pedido = {
    id: '',
    items: [],
    total: 0,
    mesa: ''
};
export{pedido}

function modalAgregarItem(codigo, url) {
    contenedorModal = document.getElementById('contenedor_modal');
    contenedorModal.innerHTML = '<article id="contenedor_producto"></article>';
    codigoProducto = codigo;
    urlBase = url;
    dibujarProducto();
    contenedorModal.style.display = 'flex';
}
export{modalAgregarItem}

function dibujarProducto(){
    consultarProducto(codigoProducto, urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            const contenedorProducto = document.getElementById('contenedor_producto');
            contenedorProducto.innerHTML = `
                <img src="${datos.producto.foto == 'sin_foto.jpg' ? `${urlBase}app/views/img/img-defecto/${datos.producto.foto}` : `${urlBase}app/views/img/${datos.carpeta}/${datos.producto.foto}`}" class="product-image">
                <div class="product-info">
                    <h3 class="product-name" title="Hot Dog Vegano">${datos.producto.nombre}</h3>
                    <div class="content-product-actions">
                        <span class="product-price">$${datos.producto.precio_venta}</span>
                        <div class="product-actions">
                            <button type="button" class="btn-sumar">+</button>
                            <input type="number" id="cantidad" value="1" readonly> 
                            <button type="button" class="btn-restar">-</button>
                        </div>
                    </div>
                    <label for="observacion">Observacion:</label>
                    <input type="text" id="observacion" name="observacion">
                </div>
            `
            if(datos.producto.tipo == 'cocina'){
                contenedorProducto.innerHTML += `
                <form id="formulario_ingredientes"></form>
                <button type="button" id="agregar_item">AGREGAR</button>`
                formularioIngredientes = document.getElementById('formulario_ingredientes');
                datos.ingredientes.forEach(ingrediente => {
                    formularioIngredientes.innerHTML += `
                    <input type="checkbox" value="${ingrediente.codigo_ingrediente}">${ingrediente.nombre}
                `
                });
                inputsIngredientes = formularioIngredientes.querySelectorAll('input[type="checkbox"]');
                checkedIngredientes();
            }else if(datos.producto.tipo == 'estand'){
                contenedorProducto.innerHTML += `
                    <button type="button" id="agregar_item">AGREGAR</button>`
            }
            eventoAgregarItem();
            eventoSumar();
            eventoRestar();
        }else if(datos.tipo == 'ERROR'){
            contenedorProducto.innerHTML = '<h1>Error al consultar producto</h1>';
            mensajero(datos);
        }
    })
}

function checkedIngredientes(){
    inputsIngredientes.forEach(input => {
        input.checked = true;
    });
}

function dibujarItems(){
    let contenedorItems = document.getElementById('contenedor_items');
    contenedorItems.innerHTML = '';

    pedido.items.forEach(item => {
        let ingredientes = '';
        for (let i = 0; i < item.ingredientes_eliminados.length; i++) {
            if(i == item.ingredientes_eliminados.length - 1){
                ingredientes += item.ingredientes_eliminados[i]['nombre'];
            }else if(i < item.ingredientes_eliminados.length){
                ingredientes += item.ingredientes_eliminados[i]['nombre'] + ', ';
            }
        }
        contenedorItems.innerHTML += `
            <div style="display:flex;">
                <img style="height:30px; width:30px; border-radius:20px;" src="${item.foto}">
                <div>
                    <h3>${item.nombre}</h3>
                    <p>x${item.cantidad} $${item.subtotal}</p>
                </div>
                ${item.observacion != '' ? `<p>Observacion: ${item.observacion}</p>` : ''}
                ${item.ingredientes_eliminados.length > 0 ? `<p>Ingredientes eliminados: ${ingredientes}</p>` : ''}
                <button class="eliminar-item" data-id="${item.id}"><ion-icon name="trash-outline"></ion-icon></button>
            </div>
        `
    });

    contenedorItems.innerHTML += `<h1>Total: $${pedido.total}</h1>`;

    eventoEliminarItem();
}

function eventoSumar(){
    const btnsSumar = document.querySelectorAll('.btn-sumar');

    btnsSumar.forEach(button => {
        button.addEventListener('click', ()=>{
            let inputCantidad = document.getElementById(`cantidad`);
            let cantidad = parseInt(inputCantidad.value) + 1;
            inputCantidad.value = cantidad;
        })
    });
}

function eventoRestar(){
    const btnsRestar = document.querySelectorAll('.btn-restar');

    btnsRestar.forEach(button => {
        button.addEventListener('click', ()=>{
            let inputCantidad = document.getElementById(`cantidad`);
            let cantidad = parseInt(inputCantidad.value) - 1;
            if(cantidad > 0){
                inputCantidad.value = cantidad;
            }
            
        })
    });
}

function capturarIngredientesEliminados(){
    ingredientesEliminados = [];
    inputsIngredientes.forEach(input => {
        if(!input.checked){
            ingredientesEliminados.push(input.value);
        }
    });
}

function validarItemCocinaExistente(observacion){
    let itemExistente = 'no existe';
    for (let i = 0; i < pedido.items.length; i++) {
        if(pedido.items[i].id == codigoProducto){
            if(pedido.items[i].observacion.toLowerCase() == observacion.toLowerCase()){
                if(pedido.items[i].ingredientes_eliminados.length == ingredientesEliminados.length){
                    itemExistente = 'si existe';
                    for (let c = 0; c < pedido.items[i].ingredientes_eliminados.length; c++) {
                        if(pedido.items[i].ingredientes_eliminados[c] != ingredientesEliminados[c]){
                            itemExistente = 'no existe';
                            return itemExistente;
                        }
                    }

                    if(itemExistente == 'si existe'){
                        return i;
                    }
                }
            }
        }
    };
    return itemExistente;
}

function validarItemEstandExistente(observacion){
    let itemExistente = 'no existe';
    for (let i = 0; i < pedido.items.length; i++) {
        if(pedido.items[i].id == codigoProducto){
            if(pedido.items[i].observacion.toLowerCase() == observacion.toLowerCase()){
                return i;
            }
        }
    };

    return itemExistente;
}

function eventoAgregarItem(){
    const btnAgregar = document.getElementById('agregar_item');
    btnAgregar.addEventListener('click', ()=>{
        consultarProducto(codigoProducto, urlBase).then(datos=>{
            if(datos.tipo == 'OK'){
                let cantidad = parseInt(document.getElementById(`cantidad`).value)
                let observacion = document.getElementById('observacion').value;
                let subtotal = cantidad*datos.producto.precio_venta;
                let foto;
    
                if(datos.producto.foto == 'sin_foto.jpg'){
                    foto = `${urlBase}app/views/img/img-defecto/${datos.producto.foto}`;
                }else{
                    foto = `${urlBase}app/views/img/${datos.carpeta}/${datos.producto.foto}`;
                }

                if(datos.producto.tipo == 'cocina'){
                    capturarIngredientesEliminados();
                    if(ingredientesEliminados.length < inputsIngredientes.length){
                        let respuesta = validarItemCocinaExistente(observacion);
                        if(respuesta != 'no existe'){
                            pedido.items[respuesta].cantidad += cantidad;
                            pedido.items[respuesta].subtotal += subtotal;
                        }else if(respuesta == 'no existe'){
                            pedido.items.push({
                                id: codigoProducto,
                                nombre: datos.producto.nombre,
                                cantidad: cantidad,
                                foto: foto,
                                observacion: observacion,
                                ingredientes_eliminados: ingredientesEliminados,
                                subtotal: subtotal
                            });
                        }
                        pedido.total += subtotal;
                        cerrarModal();
                        dibujarItems();
                    }
                }else if(datos.producto.tipo == 'estand'){
                    
                    let respuesta = validarItemEstandExistente(observacion);
                    if(respuesta != 'no existe'){
                        pedido.items[respuesta].cantidad += cantidad;
                        pedido.items[respuesta].subtotal += subtotal;
                    }else if(respuesta == 'no existe'){
                        pedido.items.push({
                            id: codigoProducto,
                            nombre: datos.producto.nombre,
                            cantidad: cantidad,
                            foto: foto,
                            observacion: observacion,
                            ingredientes_eliminados: [],
                            subtotal: subtotal
                        });
                    }
                    pedido.total += subtotal;
                    cerrarModal();
                    dibujarItems();
                }
            }else if(datos.tipo == 'ERROR'){
                mensajero(datos);
            }
        })
    })
}

function eventoEliminarItem(){
    const btnsEliminarItem = document.querySelectorAll('.eliminar-item')

    btnsEliminarItem.forEach(button => {
        let idItem = button.getAttribute('data-id');

        button.addEventListener('click', ()=>{
            for (let i = 0; i < pedido.items.length; i++) {
                if(pedido.items[i]['id'] == idItem){
                    pedido.items.splice(i, 1);
                    pedido.total -= pedido.items[i]['subtotal'];
                    dibujarItems();
                    break;
                }
                
            }
        })
    });
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