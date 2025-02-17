import {controlador_css, buscar_persona, cerrar_modal, alert_registro_fallido, alert_registro_exitoso, modal_visitante, convertir_mayuscula} from '../modales/modales.js'

document.addEventListener('DOMContentLoaded', ()=>{
    
    let btn_agregar_usuario = document.getElementById('agregar_usuario');
    let btn_visitante = document.getElementById('visitantes');
    let btn_aprendiz = document.getElementById('aprendices');
    let btn_vigilantes = document.getElementById('vigilantes');
    let btn_funcionarios = document.getElementById('funcionarios')
    const userCargo = document.getElementById('userCargo').value;

    
    
    btn_agregar_usuario.addEventListener('click', ()=>{
        // llamar la funcion para que muestre la modal de legir tipo de usuario 
        modal_elegir_usuario();
        
    })

    // console.log(buscar_persona(1414141414));
     

    //funciones que solo van el pc
    if (window.innerWidth>=1023) {
        dibujar_tabla_aprendices()
        btn_visitante.addEventListener('click',()=>{
            dibujar_tabla_visitantes()
        })
    
        btn_aprendiz.addEventListener('click',()=>{
            dibujar_tabla_aprendices()
        })
        
        btn_vigilantes.addEventListener('click',()=>{
            dibujar_tabla_vigilante()
        })

        btn_funcionarios.addEventListener('click',()=>{
            dibujar_tabla_funcionarios()
        })
        busqueda_tabla()
    }
    

    //finciones para responsi
    if (window.innerWidth<1023) {
        select_usuario_reponsi()
        busqueda_card_responsi()
    }

})


async function modal_elegir_usuario() {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_usuario.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('modal_usuario_e')
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';
        evento_cerrar_modal()
        selecionUsuario();
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


function selecionUsuario() {
    const tipoUsuario = document.getElementById("tipo_usuario_s");
    
    document.getElementById('continuar').addEventListener('click', ()=>{
        if (tipoUsuario.value === "visitante") {
            //mandarlo a la modal de registro visitante 
            modal_visitante("usuarios", false, dibujar_tabla_visitantes, alert_confirmacion)
            
        } else if (tipoUsuario.value === "aprendiz") {
            modal_aprendiz()
            
        } else if (tipoUsuario.value === "vigilante") {
            modal_vigilante()          
        } else if (tipoUsuario.value === "funcionario") {
            modal_funcionario()
        }
    })
    
}



function select_usuario_reponsi() {
    const tipoUsuario = document.getElementById("filtro_usuarios")
    dibujar_card_aprendices()
    tipoUsuario.addEventListener('change', (event) => {
        
        if (tipoUsuario.value =="visitantes") {
            dibujar_card_visitantes()
        } else if (tipoUsuario.value === "aprendices") {
            dibujar_card_aprendices()
        } else if(tipoUsuario.value === "funcionarios"){
            dibujar_card_funcionarios()
        }else{
            dibujar_card_vigilantes()
        }
    });
    
}
function evento_cerrar_modal(){
    document.getElementById('cerrar').addEventListener('click', ()=>{
        contenedor_modal.innerHTML = "";
        contenedor_modal.style.display = 'none';
    })
}



// servicios de tablas 
async function servicio_visitantes(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/visitantes.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_visitantes_responsi(limite){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/visitantes_responsi.php?limite=' + encodeURI(limite));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_aprendices(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/aprendices.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_aprendices_responsi(limite){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/aprendices_responsi.php?limite=' + encodeURI(limite));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_vigilantes(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/vigilantes.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_vigilantes_responsi(limite){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/vigilantes_responsi.php?limite=' + encodeURI(limite));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_funcionarios(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/funcionarios.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();
        
        
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function servicio_funcionarios_responsi(limite){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/funcionarios_responsi.php?limite=' + encodeURI(limite));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// microservicio para detalle de persona

async function micro_detalle_persona(documento) {
    try {
        const response = await fetch('../../microservicios/detalle_persona.php?documento=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// microservicio para desactivar persona del sistema o volver activar
async function desactivar_activar_persona(tabla, documento, estado){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/estado_usuario.php?tabla=' + encodeURI(tabla) + '&documento=' + encodeURI(documento) + '&estado=' + encodeURI(estado));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

// funcion de detalle persona
function detalle_persona() {
    const ver_mas = document.querySelectorAll('.icon')
    const contenedor_modal = document.getElementById('contenedor_modal');
    ver_mas.forEach(function(button) {
        button.addEventListener('click', function(){
            let persona_id = this.getAttribute('data-id');
            micro_detalle_persona(persona_id).then(respuesta=>{
                if (respuesta.cod ==200) {
                    if (respuesta.datos.tabla == "aprendices") {  
                        
                        dibujar_aprendiz(respuesta)     
                    } else{
                        if (respuesta.datos.tabla == "funcionarios") {
                            dibujar_funcionario_vigi(respuesta)
                            document.getElementById('titulo_modal').textContent= "funcionario"
                        } else if (respuesta.datos.tabla == "vigilantes") {
                            dibujar_funcionario_vigi(respuesta)
                            document.getElementById('titulo_modal').textContent= "Vigilante"
                        } else if (respuesta.datos.tabla == "visitantes") {
                            dibujar_visitante(respuesta)
                        }
                    }
                }else{

                }
            })
            
            
        })
    })
}

function dibujar_aprendiz(respuesta) {
    contenedor_modal.innerHTML=`
            <div class="modal-body">
                    <article id="contenedor_titulo_icon">
                        <h1 id="titulo_modal">Aprendiz</h1>
                        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                    </article>
                    <div class="modal-grid">
                        <div class="modal-bloque">
                            <h3>Tipo Doc:</h3>
                            <span>${respuesta.datos.tipo_documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Número de Identificación:</h3>
                            <span>${respuesta.datos.documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Nombre:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.nombres)}</span>
                        </div>
                                    
                        <div class="modal-bloque">
                            <h3>Apellidos:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.apellidos)}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Movil:</h3>
                            <span>${respuesta.datos.telefono}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Correo Electronico:</h3>
                            <span>${respuesta.datos.email}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Número de Ficha:</h3>
                            <span>${respuesta.datos.ficha}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Programa:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.programa)}</span>
                        </div>
                    </div>
            </div>
    `
    contenedor_modal.style.display = 'flex';
    evento_cerrar_modal()
}

function dibujar_funcionario_vigi(respuesta) {
    contenedor_modal.innerHTML=`
            <div class="modal-body">
                    <article id="contenedor_titulo_icon">
                        <h1 id="titulo_modal">Funcionario</h1>
                        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                    </article>
                    <div class="modal-grid">
                        <div class="modal-bloque">
                            <h3>Tipo Doc:</h3>
                            <span>${respuesta.datos.tipo_documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Número de Identificación:</h3>
                            <span>${respuesta.datos.documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Nombre:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.nombres)}</span>
                        </div>
                                    
                        <div class="modal-bloque">
                            <h3>Apellidos:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.apellidos)}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Movil:</h3>
                            <span>${respuesta.datos.telefono}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Correo Electronico:</h3>
                            <span>${respuesta.datos.email}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Cargo:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.cargo)}</span>
                        </div>
                    </div>
            </div>
    `
    contenedor_modal.style.display = 'flex';
    evento_cerrar_modal()
}

function dibujar_visitante(respuesta) {
    contenedor_modal.innerHTML=`
            <div class="modal-body">
                    <article id="contenedor_titulo_icon">
                        <h1 id="titulo_modal">Visitante</h1>
                        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                    </article>
                    <div class="modal-grid">
                        <div class="modal-bloque">
                            <h3>Tipo Doc:</h3>
                            <span>${respuesta.datos.tipo_documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Número de Identificación:</h3>
                            <span>${respuesta.datos.documento}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Nombre:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.nombres)}</span>
                        </div>
                                    
                        <div class="modal-bloque">
                            <h3>Apellidos:</h3>
                            <span>${convertir_mayuscula(respuesta.datos.apellidos)}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Movil:</h3>
                            <span>${respuesta.datos.telefono}</span>
                        </div>
                        <div class="modal-bloque">
                            <h3>Correo Electronico:</h3>
                            <span>${respuesta.datos.email}</span>
                        </div>
                        
                    </div>
            </div>
    `
    contenedor_modal.style.display = 'flex';
    evento_cerrar_modal()
}

// dibujar tablas

function dibujar_tabla_aprendices(){
    let cont = document.getElementById('contenedor_tabla')
    cont.style.height=""
    let tabla = document.getElementById('cuerpo_tabla');
    servicio_aprendices().then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.aprendices.forEach(entrada => {
                    tabla.innerHTML += `
                    <tr>
                        <td>${entrada.tipo_documento}</td>
                        <td>${entrada.numero}</td>
                        <td>${convertir_mayuscula(entrada.nombres)}</td>
                        <td>${convertir_mayuscula(entrada.apellidos)}</td>
                        <td>Aprendiz</td>
                        <td>${convertir_mayuscula(entrada.ubicacion)}</td>
                        <td><i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i></td>
                    </tr>
                    `
                    
            });
            detalle_persona()
        }else{
            tabla.innerHTML = '<tr><td colspan="7">No se encontraron aprendices</td></tr>';
        }
        
    })
    
}

function dibujar_tabla_visitantes(){
    
    let tabla = document.getElementById('cuerpo_tabla');
    servicio_visitantes().then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.visitantes.forEach(entrada => {
                    tabla.innerHTML += `
                    <tr>
                        <td>${entrada.tipo_documento}</td>
                        <td>${entrada.numero}</td>
                        <td>${convertir_mayuscula(entrada.nombres)}</td>
                        <td>${convertir_mayuscula(entrada.apellidos)}</td>
                        <td>Visitante</td>
                        <td>${convertir_mayuscula(entrada.ubicacion)}</td>
                        <td><i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i></td>
                    </tr>
                    `
            });
            detalle_persona()
        }else{
            tabla.innerHTML = '<tr><td colspan="7">No se encontraron visitantes</td></tr>';
        }
        
    })
}

function dibujar_tabla_vigilante(){
    let cont = document.getElementById('contenedor_tabla')
    const userCargo = document.getElementById('userCargo').value;
    cont.style.height=""
    let tabla = document.getElementById('cuerpo_tabla');
    servicio_vigilantes().then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.vigilantes.forEach(entrada => {
                    let iconos=""

                    if(userCargo == "subdirector"){
                        if (entrada.estado=="ACTIVO") {
                            iconos= `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                        }else{
                            iconos= `<i data-id="${entrada.numero}" class='activar bx bx-user-check'></i>`
                        }

                        iconos+= `
                        <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                        <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>`
                    }else if(userCargo == "coordinador"){
                        iconos= `
                        <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                        `
                    }else if(userCargo == "jefe"){
                        if(entrada.cargo == "razo"){
                            if (entrada.estado=="ACTIVO") {
                                iconos= `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                            }else{
                                iconos= `<i data-id="${entrada.numero}" class='activar bx bx-user-check'></i>`
                            }
    
                            iconos+= `
                            <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                            <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>`
                        }else{
                            iconos= `
                            <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                            `
                        }
                    }
                    
                   
                    tabla.innerHTML += `
                    <tr>
                        <td>${entrada.tipo_documento}</td>
                        <td>${entrada.numero}</td>

                        <td>${convertir_mayuscula(entrada.nombres)}</td>
                        <td>${convertir_mayuscula(entrada.apellidos)}</td>
                        <td>Vigilante</td>
                        <td>${convertir_mayuscula(entrada.ubicacion)}</td>
                        <td><div class="fila-icons-vi"> ${iconos}</div></td>

                    </tr>
                    `
            });
            activar_persona_sistema("vigilantes", "tabla")
            desactivar_persona_sistema("vigilantes", "tabla")
            edit_fun_vig()
            detalle_persona();
        }else{
            tabla.innerHTML = '<tr><td colspan="7">No se encontraron vigilantes</td></tr>';
        }
        
    })
}

// funcion para desactivar persona del sistema como para vigilante o funcionario tipo coordinador 
function desactivar_persona_sistema(tabla, lugar) {
    let desactivar = document.querySelectorAll('.desac')
    desactivar.forEach(function(button){
        button.addEventListener('click', function(){
            let documento = this.getAttribute('data-id')
            
            console.log(documento);

           
            
            Swal.fire({
                html: `
                <h2 class="alert-titulo">¿Estas Seguro?</h2>
                <i class='icon-info-azul bx bx-info-circle'></i>
                <p class="alert-mensaje">¡Estás a punto de desactivar a este usuario!</p>
            `,
                background: "#E7E7E7",
                confirmButtonColor: "#115CED",
                confirmButtonText: "SI, DESACTIVAR",
                showCloseButton: true, // Muestra el botón de cerrar en la esquina
                customClass: {
                    popup: 'contenedor-alert', // Personalización adicional del modal
                    confirmButton: 'alert-pel' // Personalización del botón
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    desactivar_activar_persona(tabla, documento,"INACTIVO").then(respuesta =>{
                        
                        
                        if (respuesta.cod==404) {
                            alert_registro_fallido(respuesta.msj)
                            
                        } else{
                            if (respuesta.cod ==200) {
                                alert_registro_exitoso(respuesta.msj)
                            }
                            if (tabla=="funcionarios") {
                                if (lugar=="tabla") {
                                    dibujar_tabla_funcionarios()
                                }else{
                                    dibujar_card_funcionarios()
                                }
                                
                            }else{
                                if (lugar=="tabla") {
                                    dibujar_tabla_vigilante()
                                }else{
                                    dibujar_card_vigilantes()
                                }

                                
                                
                            }
                        }
                    })
                }
            })

        })
    })
}

// funcion para activar persona
function activar_persona_sistema(tabla, lugar) {
    let desactivar = document.querySelectorAll('.activar')
    desactivar.forEach(function(button){
        button.addEventListener('click', function(){
            let documento = this.getAttribute('data-id')
            
            
            Swal.fire({
                html: `
                <h2 class="alert-titulo">¿Estas Seguro?</h2>
                <i class='icon-info-azul bx bx-info-circle'></i>
                <p class="alert-mensaje">¡Estás a punto de activar a este usuario!</p>
            `,
                background: "#E7E7E7",
                confirmButtonColor: "#115CED",
                confirmButtonText: "SI, ACTIVAR",
                showCloseButton: true, // Muestra el botón de cerrar en la esquina
                customClass: {
                    popup: 'contenedor-alert', // Personalización adicional del modal
                    confirmButton: 'alert-pel' // Personalización del botón
                }
             
            }).then((result) => {
                if (result.isConfirmed) {
                    desactivar_activar_persona(tabla, documento,"ACTIVO").then(respuesta =>{
                        
                        
                        
                        if (respuesta.cod==404) {
                            alert_registro_fallido(respuesta.msj)
                            
                        } else{
                            if (respuesta.cod ==200) {
                                alert_registro_exitoso(respuesta.msj)
                            }
                            if (tabla=="funcionarios") {
                                if (lugar=="tabla") {
                                    dibujar_tabla_funcionarios()
                                }else{
                                    dibujar_card_funcionarios()
                                }
                                
                            }else{
                                if (lugar=="tabla") {
                                    dibujar_tabla_vigilante()
                                }else{
                                    dibujar_card_vigilantes()
                                }
                                
                            }
                        }
                    })
                }
            })

        })
    })
}



// funcion para editar vigilante
function edit_fun_vig() {
    const edit = document.querySelectorAll('.edit')
    const contenedor_modal = document.getElementById('contenedor_modal');
    edit.forEach(function(button) {
        button.addEventListener('click', function(){  
            let documento = this.getAttribute('data-id')
            micro_detalle_persona(documento).then(respuesta =>{
                
                if (respuesta.datos.tabla== "vigilantes") {
                    modal_vigilante(respuesta)
                }else{
                    modal_funcionario(respuesta)
                }
                
                
            })
            
            
            
        })
    })
}

// funciomn para edit que rellena los datos de los input
function dibujar_contenido_inputs(respuesta) {
    document.getElementById('titulo_r').textContent = respuesta.datos.tabla == "funcionarios"? 'Actualizar Funcionario': 'Actualizar Vigilante'
    document.getElementById('cargo').value= respuesta.datos.cargo 
    document.getElementById('tipo_documento').value= respuesta.datos.tipo_documento
    document.getElementById('tipo_documento').disabled= true
    document.getElementById('numero_documento').value = respuesta.datos.documento
    document.getElementById('numero_documento').disabled= true
    document.getElementById('nombres').value= respuesta.datos.nombres
    document.getElementById('nombres').disabled= true
    document.getElementById('apellidos').value = respuesta.datos.apellidos
    document.getElementById('apellidos').disabled= true

    // datos que si se pueden editar
    document.getElementById('correo').value=  respuesta.datos.email
    document.getElementById('telefono').value = respuesta.datos.telefono

    // boton cambiar su texto
    document.getElementById('registrar').textContent = "ACTUALIZAR";
}


function dibujar_tabla_funcionarios(){
    const userCargo = document.getElementById('userCargo').value;
    let cont = document.getElementById('contenedor_tabla')
    cont.style.height=""
    let tabla = document.getElementById('cuerpo_tabla');
    servicio_funcionarios().then(respuesta=>{
        if(respuesta.cod == 200){
           
            tabla.innerHTML = '';

                respuesta.funcionarios.forEach(entrada => {
                    let iconos=""
                    
                    if(userCargo == "subdirector"){
                        if(entrada.cargo == "coordinador"){
                            if (entrada.estado=="ACTIVO"){
                                iconos = `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                            }else{
                                iconos = `<i data-id="${entrada.numero}"  class='activar bx bx-user-check'></i>`
                            }
                            iconos+=`
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }else{
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }
                    }else if(userCargo == "coordinador"){
                        if(entrada.cargo == 'instructor'){
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }else{
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                            `
                        }
                    }else if(userCargo == 'jefe'){
                        iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                            `
                    }
                    
                    tabla.innerHTML += `
                    <tr>
                        <td>${entrada.tipo_documento}</td>
                        <td>${entrada.numero}</td>

                        <td>${convertir_mayuscula(entrada.nombres)}</td>
                        <td>${convertir_mayuscula(entrada.apellidos)}</td>
                        <td>Funcionario</td>
                        <td>${convertir_mayuscula(entrada.ubicacion)}</td>
                        <td><div class="fila-icons">${iconos}</div></td>

                    </tr>
                    `
            });
            activar_persona_sistema("funcionarios", "tabla")
            desactivar_persona_sistema("funcionarios", "tabla")
            edit_fun_vig()
            detalle_persona();
        }else{
            tabla.innerHTML = '<tr><td colspan="7">No se encontraron funcionarios</td></tr>';
        }
    })
}


// barra de busqueda para tabla 
function busqueda_tabla() {
    const userCargo = document.getElementById('userCargo').value;
    let tabla = document.getElementById('cuerpo_tabla');
    let buscar_u = document.getElementById('busqueda_usuarios')
    let timeoutId; 
    buscar_u.addEventListener('input', () => {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            
            buscar_persona(buscar_u.value).then(respuesta=>{
                if(respuesta){
                    
                    
                    tabla.innerHTML = '';
                    if (respuesta.persona) {
                        let iconos="";
                        if(respuesta.persona != respuesta.usr){
                            if(userCargo == "subdirector"){
                                if(respuesta.tipo_usuario == 'funcionario' || respuesta.tipo_usuario == 'vigilante'){
                                    if(respuesta.cargo == 'coordinador' || respuesta.cargo == 'razo' || respuesta.cargo == "jefe"){
                                        if (respuesta.estado=="ACTIVO"){
                                            iconos = `<i data-id="${respuesta.persona}" class='desac bx bx-user-x'></i>`
                                        }else{
                                            iconos = `<i data-id="${respuesta.persona}"  class='activar bx bx-user-check'></i>`
                                        }
                                        iconos+=`
                                            <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                            <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                        `
                                    }else{
                                        iconos = `
                                        <i data-id="${respuesta.numero}" class='icon bx bx-show-alt'></i>
                                        <i data-id="${respuesta.numero}" class='edit bx bx-edit'></i>
                                        `
                                    }
                                }else{
                                    iconos = `
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        `
                                }
                            }else if(userCargo == "coordinador"){
                                if(respuesta.tipo_usuario == "funcionario" && respuesta.cargo == "instructor"){
                                    iconos = `
                                    <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                    <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                    `
                                }else{
                                    iconos = `
                                    <i data-id="${respuesta.numero}" class='icon bx bx-show-alt'></i>
                                    `
                                } 
                            }else if(userCargo == "jefe"){
                                if(respuesta.tipo_usuario == 'vigilante' && respuesta.cargo == 'razo'){
                                    if (respuesta.estado=="ACTIVO"){
                                        iconos = `<i data-id="${respuesta.persona}" class='desac bx bx-user-x'></i>`
                                    }else{
                                        iconos = `<i data-id="${respuesta.persona}"  class='activar bx bx-user-check'></i>`
                                    }
                                    iconos+=`
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                    `
                                }else{
                                    iconos = `
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        `
                                }
                            }
                        }else{
                            iconos = `
                            <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                            `
                        }

                        tabla.innerHTML = `
                            <tr>
                                <td>${respuesta.tipo_doc}</td>
                                <td>${respuesta.persona}</td>
                                <td>${convertir_mayuscula(respuesta.nombres)}</td>
                                <td>${convertir_mayuscula(respuesta.apellidos)}</td>
                                <td>${convertir_mayuscula(respuesta.tipo_usuario)}</td>
                                <td>${convertir_mayuscula(respuesta.ubicacion)}</td>
                                <td><div class="fila-icons">${iconos}</div></td>
                            </tr>
                        `;
                        activar_persona_sistema(respuesta.tipo_usuario + "s", "tabla")
                        desactivar_persona_sistema(respuesta.tipo_usuario + "s", "tabla")
                        edit_fun_vig()
                        detalle_persona()
                    } else {
                        
                        tabla.innerHTML = '<tr><td colspan="7">No se encontraron resultados</td></tr>';
                    }
                }
            })  

        }, 1000)

    });
   
}

// barra de busqueda para responsi

function busqueda_card_responsi() {
    const userCargo = document.getElementById('userCargo').value;
    let tabla = document.getElementById('contenedor_tabla');
    let buscar_u = document.getElementById('busqueda_usuarios')
    let timeoutId; 
    buscar_u.addEventListener('input', () => {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            buscar_persona(buscar_u.value).then(respuesta=>{
                if(respuesta){
                    
                    
                    tabla.innerHTML = '';
                    if (respuesta.persona) {
                        let iconos="";
                        if(respuesta.persona != respuesta.usr){
                            if(userCargo == "subdirector"){
                                if(respuesta.tipo_usuario == 'funcionario' || respuesta.tipo_usuario == 'vigilante'){
                                    if(respuesta.cargo == 'coordinador' || respuesta.cargo == 'razo' || respuesta.cargo == "jefe"){
                                        if (respuesta.estado=="ACTIVO"){
                                            iconos = `<i data-id="${respuesta.persona}" class='desac bx bx-user-x'></i>`
                                        }else{
                                            iconos = `<i data-id="${respuesta.persona}"  class='activar bx bx-user-check'></i>`
                                        }
                                        iconos+=`
                                            <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                            <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                        `
                                    }else{
                                        iconos = `
                                        <i data-id="${respuesta.numero}" class='icon bx bx-show-alt'></i>
                                        <i data-id="${respuesta.numero}" class='edit bx bx-edit'></i>
                                        `
                                    }
                                }else{
                                    iconos = `
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        `
                                }
                            }else if(userCargo == "coordinador"){
                                if(respuesta.tipo_usuario == "funcionario" && respuesta.cargo == "instructor"){
                                    iconos = `
                                    <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                    <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                    `
                                }else{
                                    iconos = `
                                    <i data-id="${respuesta.numero}" class='icon bx bx-show-alt'></i>
                                    `
                                } 
                            }else if(userCargo == "jefe"){
                                if(respuesta.tipo_usuario == 'vigilante' && respuesta.cargo == "razo"){
                                    if (respuesta.estado=="ACTIVO"){
                                        iconos = `<i data-id="${respuesta.persona}" class='desac bx bx-user-x'></i>`
                                    }else{
                                        iconos = `<i data-id="${respuesta.persona}"  class='activar bx bx-user-check'></i>`
                                    }
                                    iconos+=`
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        <i data-id="${respuesta.persona}" class='edit bx bx-edit'></i>
                                    `
                                }else{
                                    iconos = `
                                        <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                                        `
                                }
                            }
                        }else{
                            iconos = `
                            <i data-id="${respuesta.persona}" class='icon bx bx-show-alt'></i>
                            `
                        }
                        
                        tabla.innerHTML = `
                            
                            <article class="card_usuario">
                                <h3 id="nombre_completo_u">${convertir_mayuscula(respuesta.nombres)} ${convertir_mayuscula(respuesta.apellidos)}</h3>
                                <h3 id="cedula_u">${respuesta.persona}</h3>
                                <h3 id="tipo_u">${convertir_mayuscula(respuesta.ubicacion)}</h3>
                                <div id="cont_opc">
                                   ${iconos}            
                                </div>
                            </article>
                        `;
                        
                        activar_persona_sistema(respuesta.tipo_usuario + "s", "adaptable")
                        desactivar_persona_sistema(respuesta.tipo_usuario + "s", "adaptable")
                        edit_fun_vig()
                        detalle_persona()
                    } else {
                        
                        tabla.innerHTML = '<div id="mensaje_no">No se encontraron resultados</div>';
                    }
                }
            })  

        }, 1000)

    });
   
}


/* dibujar card para responsi */

function dibujar_card_aprendices(){
    let tabla = document.getElementById('contenedor_tabla');

    let limite;
    if (window.innerWidth>=768) {
        limite= 8
    }else{
        limite= 3
    }
    servicio_aprendices_responsi(limite).then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.aprendices.forEach(entrada => {
                    tabla.innerHTML += `
                        <article class="card_usuario">
                            <h3 id="nombre_completo_u">${convertir_mayuscula(entrada.nombres)} ${convertir_mayuscula(entrada.apellidos)}</h3>
                            <h3 id="cedula_u">${entrada.numero}</h3>
                            <h3 id="tipo_u">${convertir_mayuscula(entrada.ubicacion)}</h3>
                            <div id="cont_opc">
                                <i data-id="${entrada.numero}" id="ver_mas_u" class='icon bx bx-show-alt'></i>            
                            </div>
                        </article>
                    `
                
            });
            detalle_persona()
        }
        
    })
}

function dibujar_card_funcionarios(){
    const userCargo = document.getElementById('userCargo').value;
    let tabla = document.getElementById('contenedor_tabla');
    let limite;
    if (window.innerWidth>=768) {
        limite= 8
    }else{
        limite= 3
    }
    servicio_funcionarios_responsi(limite).then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.funcionarios.forEach(entrada => {
                    let iconos=""
                    
                    if(userCargo == "subdirector"){
                        if(entrada.cargo == "coordinador"){
                            if (entrada.estado=="ACTIVO"){
                                iconos = `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                            }else{
                                iconos = `<i data-id="${entrada.numero}"  class='activar bx bx-user-check'></i>`
                            }
                            iconos+=`
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }else{
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }
                    }else if(userCargo == "coordinador"){
                        if(entrada.cargo == 'instructor'){
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                                <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>
                            `
                        }else{
                            iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                            `
                        }
                    }else if(userCargo == 'jefe'){
                        iconos = `
                                <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i>
                            `
                    }
                    tabla.innerHTML += `
                        <article class="card_usuario">
                            <h3 id="nombre_completo_u">${convertir_mayuscula(entrada.nombres)} ${convertir_mayuscula(entrada.apellidos)}</h3>
                            <h3 id="cedula_u">${entrada.numero}</h3>
                            <h3 id="tipo_u">${convertir_mayuscula(entrada.ubicacion)}</h3>
                            <div id="cont_opc">
                                ${iconos}            
                            </div>
                        </article>
                    `
            });
        }
        activar_persona_sistema("funcionarios", "adaptable")
        desactivar_persona_sistema("funcionarios", "adaptable")
        edit_fun_vig()
        detalle_persona()
    })
}

function dibujar_card_vigilantes(){
    let tabla = document.getElementById('contenedor_tabla');
    const userCargo = document.getElementById('userCargo').value;
    let limite;
    if (window.innerWidth>=768) {
        limite= 8
    }else{
        limite= 3
    }
    servicio_vigilantes_responsi(limite).then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.vigilantes.forEach(entrada => {
                    let iconos=""
                    if(userCargo == "subdirector"){
                        if (entrada.estado=="ACTIVO") {
                            iconos= `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                        }else{
                            iconos= `<i data-id="${entrada.numero}" class='activar bx bx-user-check'></i>`
                        }

                        iconos+= `
                        <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                        <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>`
                    }else if(userCargo == "coordinador"){
                        iconos= `
                        <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                        `
                    }else if(userCargo == "jefe"){
                        if(entrada.cargo == "razo"){
                            if (entrada.estado=="ACTIVO") {
                                iconos= `<i data-id="${entrada.numero}" class='desac bx bx-user-x'></i>`
                            }else{
                                iconos= `<i data-id="${entrada.numero}" class='activar bx bx-user-check'></i>`
                            }
    
                            iconos+= `
                            <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                            <i data-id="${entrada.numero}" class='edit bx bx-edit'></i>`
                        }else{
                            iconos= `
                            <i data-id="${entrada.numero}" class='icon bx bx-show-alt'></i> 
                            `
                        }
                    }
                    tabla.innerHTML += `
                        <article class="card_usuario">
                            <h3 id="nombre_completo_u">${convertir_mayuscula(entrada.nombres)} ${convertir_mayuscula(entrada.apellidos)}</h3>
                            <h3 id="cedula_u">${entrada.numero}</h3>
                            <h3 id="tipo_u">${convertir_mayuscula(entrada.ubicacion)}</h3>
                            <div id="cont_opc">
                                ${iconos}            
                            </div>
                        </article>
                    `
            });
        }
        activar_persona_sistema("vigilantes", "adaptable")
        desactivar_persona_sistema("vigilantes", "adaptable")
        edit_fun_vig()
        detalle_persona()
    })
}

function dibujar_card_visitantes(){
    let tabla = document.getElementById('contenedor_tabla');
    let limite;
    if (window.innerWidth>=768) {
        limite= 8
    }else{
        limite= 3
    }
    servicio_visitantes_responsi(limite).then(respuesta=>{
        if(respuesta.cod == 200){
            tabla.innerHTML = '';
                respuesta.visitantes.forEach(entrada => {
                    tabla.innerHTML += `
                        <article class="card_usuario">
                            <h3 id="nombre_completo_u">${convertir_mayuscula(entrada.nombres)} ${convertir_mayuscula(entrada.apellidos)}</h3>
                            <h3 id="cedula_u">${entrada.numero}</h3>
                            <h3 id="tipo_u">${convertir_mayuscula(entrada.ubicacion)}</h3>
                            <div id="cont_opc">
                                <i data-id="${entrada.numero}" id="ver_mas_u" class='icon bx bx-show-alt'></i>            
                            </div>
                        </article>
                    `
            });
        }
        detalle_persona()
    })
}


// modales de los otros usuarios

async function modal_vigilante(datos = null) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_registro_vigilante.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css("visitante");
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

       
        contenedor_modal.innerHTML = modal;        
        contenedor_modal.style.display = 'flex';
        
        evento_cerrar_modal();
        
        
        if (datos) {
            dibujar_contenido_inputs(datos)
            actualizar_vigilante()
        }else{
            evento_registrar_vigilante()

        }
    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 


function actualizar_vigilante(){
   
    let btnformulario = document.getElementById('registrar');
    const inputs = document.querySelectorAll(".input");

    btnformulario.addEventListener('click', ()=>{
       
        let todosValidos = true;
        let cargo = document.getElementById('cargo');
        let numeroDocumento = document.getElementById('numero_documento');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let contrasena = document.getElementById('contrasena');
        let confirmar_c = document.getElementById('confirmar');

        inputs.forEach((input) => {
            if (!input.checkValidity()) {
                todosValidos = false;
                input.reportValidity(); 
                console.log('hola');
                return; 
            }
        });
    
        if (todosValidos) {
            if (contrasena.value!== confirmar_c.value) {
                document.getElementById('contrasena_mal').textContent = "contraseña no coincide"
            }else{
                
                document.getElementById('contrasena_mal').textContent = ""; 
                let datos = new FormData();
                datos.append('cargo', cargo.value);
                datos.append('numero_documento', numeroDocumento.value);
                datos.append('movil', telefono.value);
                datos.append('correo', correoElec.value);
                datos.append('contrasena', contrasena.value);

                modicar_vigilante(datos)

            }   
        }
                    
    })

    document.getElementById('confirmar').addEventListener('input', () => {
        document.getElementById('contrasena_mal').textContent = ""; 
    });

    
}

function evento_registrar_vigilante(){
   
    let btnformulario = document.getElementById('registrar');
    const inputs = document.querySelectorAll(".input");

    btnformulario.addEventListener('click', ()=>{
       
        let todosValidos = true;
        let cargo = document.getElementById('cargo');
        let tipoDocumento = document.getElementById('tipo_documento');
        let numeroDocumento = document.getElementById('numero_documento');
        let nombres = document.getElementById('nombres'); 
        let apellidos = document.getElementById('apellidos');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let contrasena = document.getElementById('contrasena');
        let confirmar_c = document.getElementById('confirmar');

        inputs.forEach((input) => {
            if (!input.checkValidity()) {
                todosValidos = false;
                input.reportValidity(); 
                console.log('hola');
                return; 
            }
        });
    
        if (todosValidos) {
            if (contrasena.value!== confirmar_c.value) {
                document.getElementById('contrasena_mal').textContent = "contraseña no coincide"
            }else{
                
                document.getElementById('contrasena_mal').textContent = ""; 
                let datos = new FormData();
                datos.append('cargo', cargo.value);
                datos.append('tipo_documento', tipoDocumento.value);
                datos.append('numero_documento', numeroDocumento.value);
                datos.append('nombres', nombres.value);
                datos.append('apellidos', apellidos.value);
                datos.append('movil', telefono.value);
                datos.append('correo', correoElec.value);
                datos.append('contrasena', contrasena.value);


                buscar_persona(numeroDocumento.value).then(respuesta=>{
                    if(respuesta.cod == 200){
                        if(respuesta.tipo_usuario == 'vigilante'){
                            let mensaje = '¡El usuario ya existe, no es posible registrarlo!';
                            alert_registro_fallido(mensaje);
                        }else{
                            let mensaje = `Esta persona ya se encuentra registrada como ${respuesta.tipo_usuario}, ¿Desea hacer el cambio a vigilante?`
                            alert_confirmacion(mensaje, datos, registrar_vigilante);
                        }
                    }else if(respuesta.cod == 404){
                        registrar_vigilante(datos);
                    }
                })

            }   
        }
                    
    })

    document.getElementById('confirmar').addEventListener('input', () => {
        document.getElementById('contrasena_mal').textContent = ""; 
    });

    
}

async function registrar_vigilante(datos) {
    try {
        const response = await fetch("../../servicios/reg_vigilantes.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 

        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
         }else if(respuesta.cod == 200){
             cerrar_modal();
             dibujar_tabla_vigilante();
             alert_registro_exitoso(respuesta.msj);
         }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function modal_aprendiz() {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_registro_aprendiz.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css("visitante")
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

       
        contenedor_modal.innerHTML = modal;        
        contenedor_modal.style.display = 'flex';

        evento_cerrar_modal();
        evento_registrar_aprendiz();
        lista_programas();
    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

function evento_registrar_aprendiz(){
   
    let btnformulario = document.getElementById('registrarA');
    const inputs = document.querySelectorAll(".input");

    btnformulario.addEventListener('click', ()=>{
        
        let todosValidos = true;
        let tipoDocumento = document.getElementById('tipo_documento');
        let numeroDocumento = document.getElementById('numero_documento');
        let nombres = document.getElementById('nombres'); 
        let apellidos = document.getElementById('apellidos');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let ficha = document.getElementById('ficha');
        let programa = document.getElementById('programa');


        inputs.forEach((input) => {
            if (!input.checkValidity()) {
                todosValidos = false;
                input.reportValidity(); 
                return; 
            }
        });
    
        if (todosValidos) {
            let datos = new FormData();
            datos.append('tipo_documento', tipoDocumento.value);
            datos.append('numero_documento', numeroDocumento.value);
            datos.append('nombres', nombres.value);
            datos.append('apellidos', apellidos.value);
            datos.append('movil', telefono.value);
            datos.append('correo', correoElec.value);
            datos.append('ficha', ficha.value);
            datos.append('programa', programa.value);

            buscar_persona(numeroDocumento.value).then(respuesta=>{
                if(respuesta.cod == 200){
                    if(respuesta.tipo_usuario == 'aprendiz'){
                        let mensaje = '¡El usuario ya existe como aprendiz, no es posible registrarlo!';
                        alert_registro_fallido(mensaje);
                    }else{
                        let mensaje = `Esta persona ya se encuentra registrada como ${respuesta.tipo_usuario}, ¿Desea hacer el cambio a aprendiz?`
                        alert_confirmacion(mensaje, datos, registrar_aprendiz);
                    }
                }else if(respuesta.cod == 404){
                    registrar_aprendiz(datos);
                }
            })
        }
    })
}

async function registrar_aprendiz(datos) {
    try {
        const response = await fetch("../../servicios/reg_aprendices.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 
        
        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 200){
            cerrar_modal();
            dibujar_tabla_aprendices();
            alert_registro_exitoso(respuesta.msj);
        }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


async function modal_funcionario(datos =null) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_registro_funcionario.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css("visitante")
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

       
        contenedor_modal.innerHTML = modal;        
        contenedor_modal.style.display = 'flex';

        evento_cerrar_modal();
      

        if (datos) {
            dibujar_contenido_inputs(datos);
            evento_seleccion_cargo(datos.datos.cargo);
            actualizar_funcionario();
        }else{
            evento_registrar_funcionario();
            evento_seleccion_cargo();
        }
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 


//actilizar funcionario
function actualizar_funcionario() {
    let btnformulario = document.getElementById('registrar');

    btnformulario.addEventListener('click',()=>{
        let numeroDocumento = document.getElementById('numero_documento');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let cargo = document.getElementById('cargo');
        let contrasena = '';
        let confirmar_c = '';
        let todosValidos = true;
        if(cargo.value == 'coordinador'){     
            contrasena = document.getElementById('contrasena').value;
            confirmar_c = document.getElementById('confirmar').value;
        }

        if (!telefono.checkValidity()) {
            telefono.reportValidity()
            todosValidos = false
        } else{
            if (!correoElec.checkValidity()) {
                correoElec.reportValidity()
                todosValidos = false
            } else if (!cargo.checkValidity()) {
                cargo.reportValidity()
                todosValidos = false
            }
        }

        if (todosValidos) {
            if (contrasena !== confirmar_c) {
                document.getElementById('contrasena_mal').textContent = "contraseña no coincide"
            }else{
                
                document.getElementById('contrasena_mal').textContent = ""; 

                let datos = new FormData();
                
                datos.append('numero_documento', numeroDocumento.value);
                datos.append('movil', telefono.value);
                datos.append('correo', correoElec.value);
                datos.append('cargo', cargo.value);

                if(cargo.value == 'coordinador'){
                    datos.append('contrasena', contrasena);
                }
                
                modicar_funcionario(datos)
            }
            
        }
        
    })
}

function evento_seleccion_cargo(cargo=false){
    let select_cargo = document.getElementById('cargo');
    let cont_credenciales = document.getElementById('credenciales');
    let inputs_credenciales = cont_credenciales.querySelectorAll('input');

    if(cargo == "coordinador"){
        cont_credenciales.style.display = 'flex';

        inputs_credenciales.forEach(input => {
            input.setAttribute('required', '')
        });
    }

    select_cargo.addEventListener('change', ()=>{
        if(select_cargo.value == 'coordinador'){
            cont_credenciales.style.display = 'flex';

            inputs_credenciales.forEach(input => {
                input.setAttribute('required', '')
            });

        }else{
            cont_credenciales.style.display = 'none';

            inputs_credenciales.forEach(input => {
                input.removeAttribute('required')
            });            
        }
    })
}


function evento_registrar_funcionario(){
    let btnformulario = document.getElementById('registrar');
    const inputs = document.querySelectorAll(".input");
    
   
    btnformulario.addEventListener('click', ()=>{
        
        let todosValidos = true;
        let tipoDocumento = document.getElementById('tipo_documento');
        let numeroDocumento = document.getElementById('numero_documento');
        let nombres = document.getElementById('nombres'); 
        let apellidos = document.getElementById('apellidos');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let cargo = document.getElementById('cargo');
        let contrasena = '';
        let confirmar_c = '';

        if(cargo.value == 'coordinador'){     
            contrasena = document.getElementById('contrasena').value;
            confirmar_c = document.getElementById('confirmar').value;
        }

        inputs.forEach((input) => {
            if (!input.checkValidity()) {
                todosValidos = false;
                input.reportValidity(); 
                return; 
            }
        });
    
        if (todosValidos) {
            if (contrasena !== confirmar_c) {
                document.getElementById('contrasena_mal').textContent = "contraseña no coincide"
            }else{
                
                document.getElementById('contrasena_mal').textContent = ""; 

                let datos = new FormData();
                datos.append('tipo_documento', tipoDocumento.value);
                datos.append('numero_documento', numeroDocumento.value);
                datos.append('nombres', nombres.value);
                datos.append('apellidos', apellidos.value);
                datos.append('movil', telefono.value);
                datos.append('correo', correoElec.value);
                datos.append('cargo', cargo.value);

                if(cargo.value == 'coordinador'){
                    datos.append('contrasena', contrasena);
                }
            
                buscar_persona(numeroDocumento.value).then(respuesta=>{
                    if(respuesta.cod == 200){
                        if(respuesta.tipo_usuario == 'funcionario'){
                            let mensaje = '¡El usuario ya existe como funcionario, no es posible registrarlo!';
                            alert_registro_fallido(mensaje);
                        }else{
                            let mensaje = `Esta persona ya se encuentra registrada como ${respuesta.tipo_usuario}, ¿Desea hacer el cambio a funcionario?`
                            alert_confirmacion(mensaje, datos, registrar_funcionario);
                        }
                    }else if(respuesta.cod == 404){
                        registrar_funcionario(datos);
                    }
                })
            }
        }
                    
    })

    document.getElementById('confirmar').addEventListener('input', () => {
        document.getElementById('contrasena_mal').textContent = ""; 
    });
}

async function registrar_funcionario(datos) {
    try {
        const response = await fetch("../../servicios/reg_funcionarios.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 
        
        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 200){
            cerrar_modal();
            dibujar_tabla_funcionarios();
            alert_registro_exitoso(respuesta.msj);
        }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function modicar_funcionario(datos) {
    try {
        const response = await fetch("../../microservicios/actualizar_funcionario.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 
        
        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 200){
            cerrar_modal();
            dibujar_tabla_funcionarios();
            alert_registro_exitoso(respuesta.msj);
        }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function modicar_vigilante(datos) {
    try {
        const response = await fetch("../../microservicios/actualizar_vigilante.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 
        
        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 200){
            cerrar_modal();
            dibujar_tabla_vigilante()
            alert_registro_exitoso(respuesta.msj);
        }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

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


function alert_confirmacion(mensaje, datos, servicio, lugar=false, tabla_visitantes=false){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Persona Existente</h2>
            <i class='icon-pel bx bx-error-alt'></i>
            <p class="alert-mensaje">${mensaje}</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'CONFIRMAR',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-pel' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            if(lugar){
                servicio(datos, lugar, tabla_visitantes);
            }else{
                servicio(datos);
            }
        }
    })

}









