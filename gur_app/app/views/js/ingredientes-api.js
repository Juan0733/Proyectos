async function registrarIngrediente(datos, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/ingrediente-api.php', {
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
export{registrarIngrediente}

async function actualizarIngrediente(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/ingrediente-api.php', {
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
export{actualizarIngrediente}

async function consultarIngrediente(codigo, urlBase) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/ingrediente-api.php?accion='+encodeURI('consultar_ingrediente')+'&codigo='+encodeURI(codigo));
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        
        const datos = await response.json();
       
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarIngrediente}

async function consultarIngredientes(estado, urlBase){
    try {
        const reponse = await fetch(urlBase+'app/api/ingrediente-api.php?estado='+encodeURI(estado)+'&accion='+encodeURI('listar_ingredientes'));
        if(!reponse.ok) throw new Error('Error en la solicitud');

        const datos = await reponse.json()
        
        return datos;
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarIngredientes}

async function eliminarIngrediente(codigo, urlBase){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/ingrediente-api.php?codigo=' + encodeURI(codigo)+'&accion='+encodeURI('eliminar_ingrediente'));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{eliminarIngrediente}

async function restaurarIngrediente(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/ingrediente-api.php', {
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
export{restaurarIngrediente}