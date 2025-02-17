import {convertir_mayuscula} from '../modales/modales.js'

document.addEventListener('DOMContentLoaded', ()=>{
    
    inicializar_fecha()
    
    
    
    select_movimiento()
    select_tipo()
    select_fecha_fin()
    select_fecha_inicio()
    
    if (anchoPantalla<1023) {
        dibujar_card_informes()
        dibujar_busqueda_card_informes()
    }else{
        dibujar_tabla_informes()
        dibujar_busqueda_tabla_informes()
    }
})



let anchoPantalla = window.innerWidth;



// servicio para obetenr datos para la tabla
async function servicio_informes_tablas(tipo, puerta, fecha_inicio, fecha_fin){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/info_informes_tabla.php?tipo=' + encodeURIComponent(tipo) +
                                      '&puerta=' + encodeURIComponent(puerta) +
                                      '&fecha_inicio=' + encodeURIComponent(fecha_inicio) +
                                      '&fecha_fin=' + encodeURIComponent(fecha_fin));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


// servicio para las card de informes
async function servicio_informes_card(tipo, puerta, fecha_inicio, fecha_fin){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/info_tabla_responsi.php?tipo=' + encodeURIComponent(tipo) +
                                      '&puerta=' + encodeURIComponent(puerta) +
                                      '&fecha_inicio=' + encodeURIComponent(fecha_inicio) +
                                      '&fecha_fin=' + encodeURIComponent(fecha_fin));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// servicio para busqueda por usuario
async function busqueda_informes_tablas(tipo, puerta, fecha_inicio, fecha_fin, documento){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/busqueda_info_tabla.php?tipo=' + encodeURIComponent(tipo) +
                                      '&puerta=' + encodeURIComponent(puerta) +
                                      '&fecha_inicio=' + encodeURIComponent(fecha_inicio) +
                                      '&fecha_fin=' + encodeURIComponent(fecha_fin) + '&documento=' + encodeURIComponent(documento));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// servicio de busqueda para las card por persona
async function busqueda_informes_card(tipo, puerta, fecha_inicio, fecha_fin, documento){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/busqueda_info_responsi.php?tipo=' + encodeURIComponent(tipo) +
                                      '&puerta=' + encodeURIComponent(puerta) +
                                      '&fecha_inicio=' + encodeURIComponent(fecha_inicio) +
                                      '&fecha_fin=' + encodeURIComponent(fecha_fin) + '&documento=' + encodeURIComponent(documento));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// dibujar contenido de la tabla
function dibujar_tabla_informes(){
    let tabla = document.getElementById('cuerpo_tabla');
    let fecha_inico = document.getElementById('desde_calendario')
    let fecha_fin = document.getElementById('hasta_calendario')
    let tipo = document.getElementById('tipos_entsal')
    let puerta = document.getElementById('puerta_entsal')
    
   
    servicio_informes_tablas(tipo.value, puerta.value, fecha_inico.value, fecha_fin.value).then(respuesta=>{
            
                        
            if (respuesta.cod ==200) {
                tabla.innerHTML = '';
                respuesta.datos.forEach(entrada => {
                    tabla.innerHTML += `
                        <tr>
                            <td>${entrada.fecha} - ${entrada.hora}</td>
                            <td>${entrada.movimiento}</td>
                            <td>${entrada.usuario}</td>
                            <td>${entrada.vehiculo}</td>
                            <td>${convertir_mayuscula(entrada.relacion)}</td>
                            <td>${convertir_mayuscula(entrada.puerta)}</td>
                            <td>${entrada.responsable}</td>
                        </tr>
                    `;
                });
                
            } else{
                tabla.innerHTML = '';
                tabla.innerHTML += `
                    
                    <tr>
                        <tr><td colspan="7">No se encontraron resultados </td></tr>
                    </tr>
                `
            }
           
        
        
    })
}

// dibujar contenido de las card
function dibujar_card_informes(){
    let tabla = document.getElementById('informes-contenedor-cards');
    let fecha_inico = document.getElementById('desde_calendario')
    let fecha_fin = document.getElementById('hasta_calendario')
    let tipo = document.getElementById('tipos_entsal')
    let puerta = document.getElementById('puerta_entsal')
    
   
    servicio_informes_card(tipo.value, puerta.value, fecha_inico.value, fecha_fin.value).then(respuesta=>{
            
            
                 
            if (respuesta.cod ==200) {
                tabla.innerHTML = '';
                respuesta.datos.forEach(entrada => {
                    tabla.innerHTML += `
                        <div class="informes-card">
                            <p >${entrada.fecha} - ${entrada.hora}</p>
                            <div class="bloque-info">
                                <p class="info_title">usuario:</p>
                                <p >${entrada.usuario}</p>
                            </div>  
                            <div class="bloque-info">
                                <p class="info_title">responsable:</p>
                                <p >${entrada.responsable}</p>
                            </div>
                            
                        </div>
                    `;
                });
                
            } else{
                tabla.innerHTML = '';
                tabla.innerHTML += `
                    <h3>
                        No se encontraron resultados
                    </h3>
                `
            }
           
        
        
    })
}







// dibujar card de busqueda por usuario
function dibujar_busqueda_tabla_informes(){
    let tabla = document.getElementById('cuerpo_tabla');
    let fecha_inico = document.getElementById('desde_calendario')
    let fecha_fin = document.getElementById('hasta_calendario')
    let tipo = document.getElementById('tipos_entsal')
    let puerta = document.getElementById('puerta_entsal')
    let busqueda = document.getElementById('buscar_informe')
    

    let timeoutId; 

    busqueda.addEventListener('input', ()=>{
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(() => {
        
            busqueda_informes_tablas(tipo.value, puerta.value, fecha_inico.value, fecha_fin.value, busqueda.value).then(respuesta=>{
                if (respuesta.cod ==200) {
                    tabla.innerHTML = '';
                    respuesta.datos.forEach(entrada => {
                        tabla.innerHTML += `
                            <tr>
                                <td>${entrada.fecha} - ${entrada.hora}</td>
                                <td>${entrada.movimiento}</td>
                                <td>${entrada.usuario}</td>
                                <td>${entrada.vehiculo}</td>
                                <td>${convertir_mayuscula(entrada.relacion)}</td>
                                <td>${convertir_mayuscula(entrada.puerta)}</td>
                                <td>${entrada.responsable}</td>
                            </tr>
                        `;
                    });
                    
                } else{
                    tabla.innerHTML = '';
                    tabla.innerHTML += `
                        
                        <tr>
                            '<tr><td colspan="7">No se encontraron resultados </td></tr>'
                        </tr>
                    `
                }
            
            })

        },1000)
    })

   
}

// busqueda de persona para la card informes
function dibujar_busqueda_card_informes(){
    let tabla = document.getElementById('informes-contenedor-cards');
    let fecha_inico = document.getElementById('desde_calendario')
    let fecha_fin = document.getElementById('hasta_calendario')
    let tipo = document.getElementById('tipos_entsal')
    let puerta = document.getElementById('puerta_entsal')
    let busqueda = document.getElementById('buscar_informe')
    

    let timeoutId; 

    busqueda.addEventListener('input', ()=>{
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(() => {
        
            busqueda_informes_card(tipo.value, puerta.value, fecha_inico.value, fecha_fin.value, busqueda.value).then(respuesta=>{
                if (respuesta.cod ==200) {
                    tabla.innerHTML = '';
                    respuesta.datos.forEach(entrada => {
                        tabla.innerHTML += `
                            <div class="informes-card">
                            <p >${entrada.fecha} - ${entrada.hora}</p>
                            <div class="bloque-info">
                                <p class="info_title">usuario:</p>
                                <p >${entrada.usuario}</p>
                            </div>  
                            <div class="bloque-info">
                                <p class="info_title">responsable:</p>
                                <p >${entrada.responsable}</p>
                            </div>
                            
                        </div>
                        `;
                    });
                    
                } else{
                    tabla.innerHTML = '';
                    tabla.innerHTML += `
                        
                        <h3>
                            No se encontraron resultados
                        </h3>
                    `
                }
            
            })

        },1000)
    })

   
}
















































// funciones para los select o fechas
function select_movimiento() {
   
    let tipo_select = document.getElementById('tipos_entsal')
    tipo_select.addEventListener('change', ()=>{
        document.getElementById('buscar_informe').value= ""
        if (anchoPantalla>1023) {
            dibujar_tabla_informes()
        }else{
            dibujar_card_informes()
        }

        
    }) 
}

function select_tipo() {
    
    let tipo_select = document.getElementById('puerta_entsal')
    tipo_select.addEventListener('change', ()=>{
        document.getElementById('buscar_informe').value= ""
        if (anchoPantalla>1023) {
            dibujar_tabla_informes()
        }else{
            dibujar_card_informes()
        }

    }) 
}

function select_fecha_inicio() {
    // document.getElementById('busqueda_novedad').value= ""
    let tipo_select = document.getElementById('desde_calendario')
    tipo_select.addEventListener('change', ()=>{
        document.getElementById('buscar_informe').value= ""
        if (anchoPantalla>1023) {
            dibujar_tabla_informes()
        }else{
            dibujar_card_informes()
        }

    }) 
}

function select_fecha_fin() {
    // document.getElementById('busqueda_novedad').value= ""
    let tipo_select = document.getElementById('hasta_calendario')
    tipo_select.addEventListener('change', ()=>{
        document.getElementById('buscar_informe').value= ""
        if (anchoPantalla>1023) {
            dibujar_tabla_informes()
        }else{
            dibujar_card_informes()
        }

    }) 
}



// funcion para inicializar el valor de input fecha del momento
function inicializar_fecha() {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${year}-${month}-${day}`; // Construir en formato YYYY-MM-DD
    
    document.getElementById('desde_calendario').value = fechaActual;
    document.getElementById('hasta_calendario').value = fechaActual;
}