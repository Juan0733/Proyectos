async function consultarMesas(disponibilidad, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/mesa-api.php?disponibilidad='+encodeURI(disponibilidad)+'&accion=listar_mesas');
        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const datos = await response.json();

        return datos;
    } catch (error) {
       console.error('Hubo un error:', error) ;
    }
}
export{consultarMesas};

async function consultarMesa(codigo, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/mesa-api.php?codigo='+encodeURI(codigo)+'&accion=consultar_mesa');
        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const datos = await response.json();

        return datos;
    } catch (error) {
       console.error('Hubo un error:', error) ;
    }
}
export{consultarMesa};

async function validarUsuarioMesa(codigo, urlBase){
    try {
        const response = await fetch(urlBase+'app/api/mesa-api.php?codigo='+encodeURI(codigo)+'&accion=validar_usuario_mesa');
        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const datos = await response.json();

        return datos;
    } catch (error) {
       console.error('Hubo un error:', error) ;
    }
}
export{validarUsuarioMesa};



async function registrarMesa(datos, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/mesa-api.php', {
            method: 'POST',
            body: datos
        })

        if(!response.ok) throw new Error("Hubo un error en la solicitud");

        const data = await response.json();

        return data;
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{registrarMesa}

async function eliminarMesa(codigo, urlBase) {
    try {
        const response = await fetch(urlBase+'app/api/mesa-api.php?codigo='+encodeURI(codigo)+'&accion=eliminar_mesa');
        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const datos = await response.json();

        return datos;
    } catch (error) {
       console.error('Hubo un error:', error) ;
    }
}
export{eliminarMesa}