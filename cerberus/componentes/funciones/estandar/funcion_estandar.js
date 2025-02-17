import{modal_registro_novedad} from '../modales/modales.js'

let alertas = 0;

async function microservicio_multitud(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/cont_multitud.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

function conteo_multitud(){
    let multitud = document.getElementById('multitud');
    microservicio_multitud().then(respuesta=>{
        if(respuesta.cod == 200){
            multitud.textContent = `Multitud: ${respuesta.conteo}`;
        }
    })
}

function eventos_notificacion(){
    let contenedor_notificacion = document.getElementById('contenedor_notifi');
    let notificacion_movil = document.getElementById('notificacion_menu');
    let notificacion_pc = document.getElementById('notificacion');
    let cerrar_notificacion = document.getElementById('cerrar_notificacion');

    notificacion_movil.addEventListener('click', ()=>{
        contenedor_notificacion.style.display = 'block';
        toggleAnimation();
    })
    
    notificacion_pc.addEventListener('click', ()=>{
        contenedor_notificacion.style.display = 'block';
        toggleAnimation();
    })
    
    cerrar_notificacion.addEventListener('click', ()=>{
        contenedor_notificacion.style.display = 'none';
        toggleAnimation();
    })
}

function evento_novedad(){
    let btns_novedad = document.getElementById('cont_alerts').querySelectorAll('.btn_novedad');

    btns_novedad.forEach(btn => {
        btn.addEventListener('click', ()=>{
            let usuario = btn.getAttribute('data-id');
            modal_registro_novedad('notificaciones', usuario, dibujar_alertas)
        })
    });

    
}

function dibujar_alertas(){
    let contenedor_alerts = document.getElementById('cont_alerts');

    microservicio_alerta_persona().then(respuesta=>{
        if(respuesta.cod == 404 || respuesta.cod == 500){
            contenedor_alerts.innerHTML = '<p id="msj_notifi">No hay notificaciones</p>'
            alertas = 0;
            toggleAnimation();
        }else if(respuesta.cod == 200){
            if(respuesta.datos.length == 0){
                contenedor_alerts.innerHTML = '<p id="msj_notifi">No hay notificaciones</p>'
                alertas = 0;
                toggleAnimation();
            }else{
                contenedor_alerts.innerHTML = '';
                respuesta.datos.forEach(usuario => {
                    contenedor_alerts.innerHTML += `
                        <div class="cont_alert">
                            <i id="icon_alert" class="bx bx-error-alt"></i>
                            <div class ="contenido_alert">
                                <h3 id="titulo-alert">Tiempo Permanencia</h3>
                                <p>Persona con número de documento <strong>${usuario}</strong>, cumplio más de <strong>24 horas</strong> dentro en el CAB</p>
                                <button type="button" class="btn_novedad" data-id="${usuario}">REGISTRAR NOVEDAD</button>
                            </div>
                        </div>
                    `
                });

                alertas = respuesta.datos.length;
                toggleAnimation();
                evento_novedad();
            }
        }
    })
}

async function microservicio_alerta_persona() {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/alerta_personas.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

function toggleAnimation(){
    const notificacion_movil = document.getElementById('notificacion_menu')
    const notificacion_pc = document.getElementById('notificacion');
    const contenedor_notificacion = document.getElementById('contenedor_notifi');

    if(alertas == 0 || contenedor_notificacion.style.display == 'block'){
        notificacion_movil.classList.remove('pulse');
        notificacion_pc.classList.remove('pulse');
            
    }else{
        notificacion_movil.classList.add('pulse');
        notificacion_pc.classList.add('pulse');
    }
}

document.addEventListener('DOMContentLoaded', ()=>{
    conteo_multitud();
    eventos_notificacion();
    dibujar_alertas();
    setInterval(conteo_multitud, 60000);
    setInterval(dibujar_alertas, 3600000);
})


