document.addEventListener('DOMContentLoaded', ()=>{

    if (window.innerWidth>=768){
        select_tablas()
        busqueda_tabla()
        
    } else{
        select_card()
        busqueda_card_responsi()
    }
    
    
})





// llamado de micro servicio de vehiculos
async function microservicio_vehiculos(documento) {
    try {
        const response = await fetch('../../microservicios/vehiculos.php?tipo=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// micro servicio de solo una busqueda de vehiculo
async function busqueda_vehiculo(documento) {
    try {
        const response = await fetch('../../microservicios/bus_vehiculo.php?placa=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// microservicio para detalle
async function micros_detalle(documento) {
    try {
        const response = await fetch('../../microservicios/buscar_vehiculo.php?placa=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// dibujar tabla de vehiculos

function dibujar_tabla_vehiculo(tipo = String){
    let cont = document.getElementById('contenedor_tabla')
    cont.style.height=""
    let tabla = document.getElementById('cuerpo_tabla');
    microservicio_vehiculos(tipo).then(respuesta=>{
        if(respuesta.cod == 200){
            
            
            tabla.innerHTML = '';
                respuesta.datos.forEach(entrada => {
                    tabla.innerHTML += `
                    <tr>
                        <td>${entrada.placa}</td>
                        <td>${entrada.tipo}</td>
                        <td><i data-id="${entrada.placa}" class='icon bx bx-show-alt'></i></td>
                    </tr>
                    `
            });
            detalle_vehiculo()
        }else if(respuesta.cod == 404){
            tabla.innerHTML = '<tr><td colspan="7">No se encontraron vehiculos</td></tr>';
        }
        
    })
}

// dibujar card para celular

function dibujar_card_vehiculo(tipo=String){
    
    let tabla = document.getElementById('contenedor_tabla');
    microservicio_vehiculos(tipo).then(respuesta=>{
        if(respuesta.cod == 200){
            
            
            tabla.innerHTML = '';
                respuesta.datos.forEach(entrada => {
                    tabla.innerHTML += `
                        <article class="card_veh">
                            <div class="card-fila-veh">
                                <h3>Placa:</h3>
                                <h3>${entrada.placa}</h3>
                                <i data-id="${entrada.placa}" class='icon bx bx-show-alt ver_mas_veh' ></i>
                            </div>
                        </article>
                    `
            });
            detalle_vehiculo()
        }else if(respuesta.cod == 404){
            tabla.innerHTML = '<div id="mensaje_no">No se encontraron resultados</div>';
        }
        
    })
}

// funcion para select

function select_tablas() {
    const tipoCarro = document.getElementById("filtro_veh")
    dibujar_tabla_vehiculo("moto")
    tipoCarro.addEventListener('change', (event) => {
        if (tipoCarro.value =="moto") {
            dibujar_tabla_vehiculo("moto")
        } else{
            dibujar_tabla_vehiculo("carro")
        }
    });
    
}

// funcion para select responsi

function select_card() {
    const tipoCarro = document.getElementById("filtro_veh")
    dibujar_card_vehiculo("moto")
    tipoCarro.addEventListener('change', (event) => {
        if (tipoCarro.value =="moto") {
            dibujar_card_vehiculo("moto")
        } else{
            dibujar_card_vehiculo("carro")
        }
    });
    
}

// funcion para barra de busqueda table y pc
function busqueda_tabla() {
    
    let tabla = document.getElementById('cuerpo_tabla');
    let buscar_v = document.getElementById('busqueda_veh')
    let timeoutId; 
    buscar_v.addEventListener('input', () => {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            if(buscar_v.value != ''){
                busqueda_vehiculo(buscar_v.value).then(respuesta=>{
                    if(respuesta){
                        console.log(respuesta);
                        tabla.innerHTML = '';
                        if (respuesta.placa) {
                            tabla.innerHTML = `
                                <tr>
                                    <td>${respuesta.placa}</td>
                                    <td>${respuesta.tipo}</td>
                                    <td><i data-id="${respuesta.placa}" class='icon bx bx-show-alt'></i></td>
                                </tr>
                            `;
                           
                        } else {
                           
                            tabla.innerHTML = '<tr><td colspan="7">No se encontraron resultados</td></tr>';
                        }
                        detalle_vehiculo();
                    }
                    
                })  
            }else{
                select_tablas();;
            }
            

        }, 1000)
    }); 
}




// barra de busqueda para responsi

function busqueda_card_responsi() {
   
    let tabla = document.getElementById('contenedor_tabla');
    let buscar_v = document.getElementById('busqueda_veh')
    let timeoutId; 
    buscar_v.addEventListener('input', () => {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            if(buscar_v.value != ''){
                busqueda_vehiculo(buscar_v.value).then(respuesta=>{
                    if(respuesta){
                        
                        tabla.innerHTML = '';
                        if (respuesta.placa) {
                            tabla.innerHTML = `
                               <article class="card_veh">
                                    <div class="card-fila-veh">
                                        <h3>Placa:</h3>
                                        <h3>${respuesta.placa}</h3>
                                        <i data-id="${respuesta.placa}" class='icon bx bx-show-alt ver_mas_veh' ></i>
                                    </div>
                                 </article>
                            `;
                           
                        } else {
                           
                            tabla.innerHTML = '<div id="mensaje_no">No se encontraron resultados</div>';
                        }
                    }
                    detalle_vehiculo()
                })  
            }else{
                select_card();
            }
        }, 1000)
    }); 
   
}

// modal vehicular

function detalle_vehiculo() {
    const ver_mas = document.querySelectorAll('.icon')
    const contenedor_modal = document.getElementById('contenedor_modal');
    ver_mas.forEach(function(button) {
        button.addEventListener('click', function(){
            let vehiculo_id = this.getAttribute('data-id');
            
            
            micros_detalle(vehiculo_id).then(respuesta=>{
                
                if (respuesta.cod ==200) {
                    dibujar_modal(respuesta)
                    
                }else{
                    
                    
                }
            })
            
            
        })
    })
}

function dibujar_modal(respuesta) {
    const propietarios = respuesta.propietarios.map(propietario => `<span>${propietario.documento}-${propietario.nombres} ${propietario.apellidos}</span>`).join('');
    contenedor_modal.innerHTML=`
            <div class="modal-body">
                    <article id="contenedor_titulo_icon">
                        <h1 id="titulo_modal">${respuesta.tipo}</h1>
                        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                    </article>
                    <div class="modal-grid">
                        <div class="modal-bloque">
                            <h3>Numero de placa:</h3>
                            <span>${respuesta.placa}</span>
                        </div>
                        <div class="modal-propietarios">
                            <h3>Propietarios:</h3>
                            ${propietarios}
                        </div>
                        
                    </div>
            </div>
    `
    contenedor_modal.style.display = 'flex';
    evento_cerrar_modal()
}

function evento_cerrar_modal(){
    document.getElementById('cerrar').addEventListener('click', ()=>{
        contenedor_modal.innerHTML = "";
        contenedor_modal.style.display = 'none';
    })
}