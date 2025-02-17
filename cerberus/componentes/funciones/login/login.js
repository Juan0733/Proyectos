import {alert_registro_fallido} from '../modales/modales.js'

async function servicio_login(datos) {
    try {
        const response = await fetch('servicios/confirmador_login.php', {
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

document.addEventListener('DOMContentLoaded', ()=>{
    var formulario = document.getElementById("forma_acceso");

    formulario.addEventListener("submit", function(evento) {
        evento.preventDefault();

        let datos = new FormData();
        datos.append('documento', document.getElementById("documento").value);
        datos.append('codigo_pw', document.getElementById("codigo_pw").value);

        servicio_login(datos).then(respuesta=>{
            if(respuesta.cod == 400 || respuesta.cod == 500){
                alert_registro_fallido(respuesta.msj);
            }else if(respuesta.cod == 200){
                location.replace(respuesta.panel);
            }
        })

    });
})