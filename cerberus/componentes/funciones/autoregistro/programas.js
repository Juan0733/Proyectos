async function microservicio_programas() {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/buscar_programas.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


function lista_programas(){
    let datalist = document.getElementById('programas');
    microservicio_programas().then(respuesta=>{
        if(respuesta.cod == 200){
            respuesta.programas.forEach(programa => {
                datalist.innerHTML += `<option>${programa[0]}</option>`
            });
        }
    })
}


document.addEventListener('DOMContentLoaded', ()=>{
    lista_programas();
})