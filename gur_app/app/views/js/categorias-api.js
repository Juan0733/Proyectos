var emojis = { 
    1: "🍕",  
    2: "🍔", 
    3: "🍣",  
    4: "🍎", 
    5: "🥑", 
    6: "🍦", 
    7: "🍇", 
    8: "🍫", 
    9: "🍿", 
    10: "🥗", 
    11: "🍉", 
    12: "🍌", 
    13: "🍒", 
    14: "🍪", 
    15: "🍩", 
    16: "🌭", 
    17: "🌮", 
    18: "🥟", 
    19: "🍔", 
    20: "🍁", 
    21: "🍱", 
    22: "🍜", 
    23: "🥖", 
    24: "🍞", 
    25: "🥨", 
    26: "🧇", 
    27: "🥯", 
    28: "🧀", 
    29: "🥙", 
    30: "🌯",
    31: "☕", 
    32: "🍵", 
    33: "🍶", 
    34: "🍺", 
    35: "🍷", 
    36: "🍸", 
    37: "🥤", 
    38: "🧃", 
    39: "🥛", 
    40: "🧋" ,
    41: "🍗", 
    42: "🍖", 
    43: "🥩", 
    44: "🥓", 
    45: "🍱"
};
export{emojis}

async function consultarCategorias(urlBase, estado='') {
    try {
        const reponse = await fetch(urlBase+'app/api/categoria-api.php?estado='+encodeURI(estado)+'&accion='+encodeURI('listar_categorias'));
        if(!reponse.ok) throw new Error('Error en la solicitud');

        const datos = await reponse.json()
        
        return datos;
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarCategorias}

async function consultarCategoriasProductos(urlBase, estado) {
    try {
        const reponse = await fetch(urlBase+'app/api/categoria-api.php?estado='+encodeURI(estado)+'&accion='+encodeURI('listar_categorias_productos'));
        if(!reponse.ok) throw new Error('Error en la solicitud');

        const datos = await reponse.json()
        
        return datos;
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{consultarCategoriasProductos}

async function eliminarCategoria(codigo, urlBase){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch(urlBase+'app/api/categoria-api.php?codigo=' + encodeURI(codigo)+'&accion='+encodeURI('eliminar_categoria'));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        return datos;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{eliminarCategoria}

async function registrarCategoria(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/categoria-api.php',{
            method: 'POST',
            body: datos
        })
        if(!response.ok) throw new Error("Error en la solicitud");
        
        const data = await response.json();

        return data;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 
export{registrarCategoria}

async function actualizarCategoria(datos, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/categoria-api.php', {
            method: 'POST',
            body: datos
        })

        if(!response.ok) throw new Error("Error en la solicitud");
        

        const data = await response.json();

        return data;

    } catch (error) {
        console.error("Hubo un error:", error)
    }
}
export{actualizarCategoria}

async function consultarCategoria(codigo, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/categoria-api.php?codigo='+encodeURI(codigo)+'&accion='+encodeURI('consultar_categoria'))
        if(!response.ok) throw new Error("Error en la solicitud");
        
        const data = await response.json();
    
       return data;

    } catch (error) {
        console.error("Hubo un error:", error);
    }
    
}
export{consultarCategoria}