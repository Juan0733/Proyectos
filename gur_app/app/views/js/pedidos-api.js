async function registrarPedido(datos, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php', {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');
    
        const data = await response.json();

        return data;
           
    } catch (error) {
        console.error('Hubo un error:', error);
    }
    
}
export{registrarPedido}

async function registrarItems(datos, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php', {
            method: 'POST',
            body: datos
        })

        if(!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data;

    } catch (error) {
        console.error('Hubo un error:', error)
    }
    
}
export{registrarItems}

async function eliminarItem(codigo, estadoMesero, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php?item='+encodeURI(codigo)+'&estado_mesero='+encodeURI(estadoMesero)+'&accion='+encodeURI('eliminar_item'));

        if(!response.ok) throw new Error('Error en la solicitud');

        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{eliminarItem}

async function entregarItem(codigo, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php?item='+encodeURI(codigo)+'&accion='+encodeURI('entregar_item'));

        if(!response.ok) throw new Error('Error en la solicitud');

        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{entregarItem}

async function entregarItems(pedido, producto, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php?pedido='+encodeURI(pedido)+'&producto='+encodeURI(producto)+'&accion='+encodeURI('entregar_items'));

        if(!response.ok) throw new Error('Error en la solicitud');

        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{entregarItems}



async function consultarPedidosMesa(mesa, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/pedido-api.php?mesa='+encodeURI(mesa)+'&accion='+encodeURI('consultar_pedidos_mesa'));
        if (!response.ok) throw new Error('Error en la solicitud');
    
        const data = await response.json();

        return data;
           
    } catch (error) {
        console.error('Hubo un error:', error);
    }
    
}
export{consultarPedidosMesa}

