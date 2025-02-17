async function registrarProducto(datos, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/producto-api.php', {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');
    
        const data = await response.json();

        console.log('productos')

        return data;
           
    } catch (error) {
        console.error('Hubo un error:', error);
    }
    
}
export{registrarProducto}

async function actualizarProducto(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/producto-api.php', {
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
export{actualizarProducto}

async function consultarProducto(codigo, urlBase) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/producto-api.php?accion='+encodeURI('consultar_producto')+'&codigo='+encodeURI(codigo));
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        
        const datos = await response.json();
       
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarProducto}

async function consultarProductos(categoria, estado, limite, urlBase){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/producto-api.php?categoria='+encodeURI(categoria)+'&estado='+encodeURI(estado)+'&limite='+encodeURI(limite)+'&accion='+encodeURI('listar_productos'));
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarProductos}

async function consultarReceta(codigo, urlBase) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/producto-api.php?accion='+encodeURI('consultar_receta')+'&codigo='+encodeURI(codigo));
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        
        const datos = await response.json();
       
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarReceta}

async function eliminarProducto(codigo, urlBase){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/producto-api.php?codigo=' + encodeURI(codigo)+'&accion='+encodeURI('eliminar_producto'));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{eliminarProducto}

async function restaurarProducto(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/producto-api.php', {
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
export{restaurarProducto}