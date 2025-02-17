async function microservicio_conteo_aprendices(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_aprendices.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function microservicio_conteo_funcionarios(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_funcionarios.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
async function microservicio_conteo_visitantes(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_visitantes.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function microservicio_conteo_motos(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_motos.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function microservicio_conteo_carros(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_carros.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


function conteo_visitantes(){
    let visitante_conteo = document.getElementById('cantidad_visitantes');
    microservicio_conteo_visitantes().then(respuesta=>{
        if(respuesta.cod == 200){
            visitante_conteo.textContent = respuesta.conteo;
        }
    })
}

function conteo_aprendices(){
    let aprendiz_conteo = document.getElementById('cantidad_aprendices');
    microservicio_conteo_aprendices().then(respuesta=>{
        if(respuesta.cod == 200){
            aprendiz_conteo.textContent = respuesta.conteo;
        }
    })
}

function conteo_funcionarios(){
    let funcionario_cantidad = document.getElementById('cantidad_funcionarios');
    microservicio_conteo_funcionarios().then(respuesta=>{
        if(respuesta.cod == 200){
            funcionario_cantidad.textContent = respuesta.conteo;
        }
    })
}

function conteo_carros(){
    let carro_cantidad = document.getElementById('cantidad_carros');
    microservicio_conteo_carros().then(respuesta=>{
        if(respuesta.cod == 200){
            carro_cantidad.textContent = respuesta.conteo;
        }
    })
}

function conteo_motos(){
    let moto_cantidad = document.getElementById('cantidad_motos');
    microservicio_conteo_motos().then(respuesta=>{
        if(respuesta.cod == 200){
            moto_cantidad.textContent = respuesta.conteo;
        }
    })
}

function cantidad_cards(){
    conteo_funcionarios();
    conteo_aprendices();
    conteo_visitantes();
    conteo_carros();
    conteo_motos();
}


document.addEventListener('DOMContentLoaded', ()=>{
    cantidad_cards();
    setInterval(cantidad_cards, 40000);
})

