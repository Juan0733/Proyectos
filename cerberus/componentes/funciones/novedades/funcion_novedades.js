import {convertir_mayuscula} from '../modales/modales.js'

document.addEventListener('DOMContentLoaded', ()=>{
    
    inicializar_fecha()
    
    dibujar_card_novedades()
    select_tipo()
    select_fecha()
    dibujar_busqueda_novedades()
    
    
    
})



// microservicios para traer novedades 
async function microservicio_novedades(tipo, fecha){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/novedades.php?tipo=' + encodeURI(tipo) + '&fecha=' + encodeURI(fecha));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// microservicio para busqueda de novedad para 
async function busqueda_novedad(tipo, fecha, documento){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/busqueda_novedad.php?tipo=' + encodeURI(tipo) + '&fecha=' + encodeURI(fecha) + '&documento=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}






// dibujar card de novedades 
function dibujar_card_novedades(){
    let tabla = document.getElementById('contenedor-cards');
    let fecha = document.getElementById('fecha').value
    let tipo = document.getElementById('movimiento')
    let tipo_novedad;
    if (tipo.value == "SA") {
        tipo_novedad = "salida no registrada"
    }else{
        tipo_novedad = "entrada no registrada"
    }
    microservicio_novedades(tipo_novedad, fecha).then(respuesta=>{
        if(respuesta){
            
            if (Array.isArray(respuesta)) {
                tabla.innerHTML = '';
                respuesta.forEach(entrada => {
                    
                        tabla.innerHTML += `
                            <article class="card">
                                <h1>${convertir_mayuscula(entrada.tipo_novedad)}</h1>
                                <p class="nombre">${convertir_mayuscula(entrada.nombres)} ${convertir_mayuscula(entrada.apellidos)}</p>
                                <p class="fecha-registro">Fecha Registrada:</p>
                                <div class="contenedor-fecha">
                                    <p>${entrada.fecha}</p>
                                    <p>${entrada.hora}</p>
                                </div>
                                <i data-hora="${entrada.hora}" data-fecha="${entrada.fecha}" data-nombres="${entrada.nombres + " " + entrada.apellidos}"  data-observacion="${entrada.observacion}" data-titulo="${entrada.tipo_novedad }"  class='icon bx bx-list-ul'></i>
                            </article>
                        `
                       
                });
                dibujar_modal()
            } else{
                tabla.innerHTML = '';
                tabla.innerHTML += `
                    <h2 id="mensaje_respuesta">No se encuentra novedades en el dia de hoy</h2>
                `
            }
           
        }
        
    })
}

// dibujar modal
function dibujar_modal() {
    const ver_mas = document.querySelectorAll('.icon');
    const contenedor_modal = document.getElementById('contenedor_modal');
    ver_mas.forEach(function(button) {
        button.addEventListener('click', function() {
            const hora = button.getAttribute('data-hora');
            const fecha = button.getAttribute('data-fecha');
            const nombres = button.getAttribute('data-nombres');
            const observacion = button.getAttribute('data-observacion');
            const titulo = button.getAttribute('data-titulo');
           
            
            contenedor_modal.innerHTML=` 
                <div class="modal-body">
                        <article id="contenedor_titulo_icon">
                            <h1 id="titulo_modal">${convertir_mayuscula(titulo)}</h1>
                            <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                        </article>
                        <div>
                            
                            <p>${convertir_mayuscula(nombres)} </p>
                        
                            <h3 class="fecha-registro">Fecha registrada:</h3>
                            <div class="modal-bloque">
                                <p>${fecha}</p>
                                <p>${hora}</p>
                            </div>
                            <h3>Observacion:</h3>
                            <p>${observacion}</p>
                        </div>
                </div>
             `
            contenedor_modal.style.display = 'flex';
            evento_cerrar_modal()
        });
    });
}

// dibujar card de busqueda por usuario
function dibujar_busqueda_novedades(){
    let tabla = document.getElementById('contenedor-cards');
    let fecha = document.getElementById('fecha')
    let tipo_se = document.getElementById('movimiento')
    let busqueda = document.getElementById('busqueda_novedad')
    let tipo_novedad;

    tipo_se.addEventListener('change', () => {
        if (tipo_se.value == "SA") {
            tipo_novedad = "salida no registrada";
        } else {
            tipo_novedad = "entrada no registrada";
        }
        
    });
    let timeoutId; 

    busqueda.addEventListener('input', ()=>{
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(() => {
        
            if(busqueda.value != ''){
                busqueda_novedad(tipo_novedad, fecha.value, busqueda.value).then(respuesta=>{
                    if(respuesta){    
                        
                        if (Array.isArray(respuesta)) {
                            tabla.innerHTML = '';
                            respuesta.forEach(entrada => {
                                    
                                    tabla.innerHTML += `
                                        <article class="card">
                                            <h1>${convertir_mayuscula(entrada.tipo_novedad)}</h1>
                                            <p class="nombre">${convertir_mayuscula(entrada.nombres)}</p>
                                            <p class="fecha-registro">Fecha Registrada:</p>
                                            <div class="contenedor-fecha">
                                                <p>${entrada.fecha}</p>
                                                <p>${entrada.hora}</p>
                                            </div>
                                             <i data-hora="${entrada.hora}" data-fecha="${entrada.fecha}" data-nombres="${entrada.nombres + " " + entrada.apellidos}" data-observacion="${entrada.observacion}" data-titulo="${entrada.tipo_novedad }"  class='icon bx bx-list-ul'></i>
                                        </article>
                                    `
                            });
                            dibujar_modal()
                        } else{
                            tabla.innerHTML = '';
                            tabla.innerHTML += `
                                <h2 id="mensaje_respuesta">No se encuentra novedades en el dia de hoy</h2>
                            `
                        }
                    
                    }
                
                })
            }else{
                dibujar_card_novedades();
            }
        },1000)
    })

   
}

// funcion para el select de tipo
function select_tipo() {
    document.getElementById('busqueda_novedad').value= ""
    let tipo_select = document.getElementById('movimiento')
    tipo_select.addEventListener('change', ()=>{
        dibujar_card_novedades()
        document.getElementById('busqueda_novedad').value= ""
    }) 
}

// select para fecha
function select_fecha() {
    document.getElementById('busqueda_novedad').value= ""
    let fecha_select = document.getElementById('fecha')
    fecha_select.addEventListener('change', ()=>{
        dibujar_card_novedades()
        document.getElementById('busqueda_novedad').value= ""
    }) 
}

// funcion para inicializar el valor de input fecha del momento
function inicializar_fecha() {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${year}-${month}-${day}`; // Construir en formato YYYY-MM-DD
    
    document.getElementById('fecha').value = fechaActual;
}


function evento_cerrar_modal(){
    document.getElementById('cerrar').addEventListener('click', ()=>{
        contenedor_modal.innerHTML = "";
        contenedor_modal.style.display = 'none';
    })
}