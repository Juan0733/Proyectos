import {alert_registro_fallido, alert_registro_exitoso, controlador_css, modal_registro_vehiculo, evento_cerrar_modal, convertir_mayuscula} from '../modales/modales.js';

function select_fecha() {
    document.getElementById('busqueda_agenda').value= ""
    let fecha_select = document.getElementById('fecha')
    fecha_select.addEventListener('change', ()=>{
        dibujar_card_agendas();
        document.getElementById('busqueda_agenda').value= ""
    }) 
}

function busqueda_agendas(){
    
    let busqueda = document.getElementById('busqueda_agenda')
    let timeoutId; 
    busqueda.addEventListener('input', ()=>{
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            if(busqueda.value != ''){
                dibujar_card_agendas(busqueda.value)
            }else{
                dibujar_card_agendas()
            }
            
        },1000)
    })
}


function evento_cerrar_modal_principal(){
    document.getElementById('cerrar_principal').addEventListener('click', ()=>{
        cerrar_modal_principal(); 
    })
}

function cerrar_modal_principal(){
    let contenedor_modal = document.getElementById('contenedor_modal_principal');
    contenedor_modal.innerHTML = "";
    contenedor_modal.style.display = 'none';
}

async function modal_agenda(datos=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('modal_registro_agenda.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('agendas');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal_principal');

        
        // Insertamos el HTML en el modal y lo mostramos
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';

        if(datos){
            dibujar_contenido_inputs(datos);
            evento_actualizar_agenda();
           
        }else{
            evento_select();
            evento_registrar_agenda();
            evento_agregar_vehiculo();
            
        }
        
        limitar_fecha_agenda();
        evento_cerrar_modal_principal();
        

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

function evento_select(){
    const tipo_agenda = document.getElementById('tipo_agenda');
    const carga_masiva = document.getElementById('contenedor_carga_masiva');
    const contenedor_tipo_doc = document.getElementById('contenedor_tipo_doc');
    const contenedor_numero_doc= document.getElementById('contenedor_numero_doc');
    const individual_section = document.getElementById('individual_section');
    const inputs_individual = document.querySelectorAll('.campo_individual');
    const input_masiva = document.getElementById('carga_masiva_e');

    tipo_agenda.addEventListener('change', ()=>{
        const tipo_seleccionado = tipo_agenda.value;

        if (tipo_seleccionado === 'individual') {
            contenedor_tipo_doc.style.display = 'flex';
            contenedor_numero_doc.style.display = 'flex';
            individual_section.style.display = 'flex';
            carga_masiva.style.display = 'none';
            input_masiva.removeAttribute('required');
            inputs_individual.forEach(input => {
                input.setAttribute('required', '');
            });
        } else if (tipo_seleccionado === 'carga_masiva') {
            carga_masiva.style.display = 'flex';
            individual_section.style.display = 'none';
            contenedor_tipo_doc.style.display = 'none';
            contenedor_numero_doc.style.display = 'none';
            input_masiva.setAttribute('required', '');
            inputs_individual.forEach(input => {
                input.removeAttribute('required');
            });
        } 
    }); 
}

function evento_registrar_agenda(){
    let btn_registrar = document.getElementById('registrar');
    let inputs = document.querySelectorAll('.campo');

    btn_registrar.addEventListener('click', ()=>{
        let validos = true;

        inputs.forEach(input => {
            if(!input.checkValidity()){
                input.reportValidity();
                validos = false;
                return
            }
        });
       
        

        if(validos){
            let titulo_agenda = document.getElementById('titulo_agenda');
            let fecha_agenda = document.getElementById('fecha_agenda');
            let motivo_visita = document.getElementById('motivo_visita_a');
            let tipo_agenda = document.getElementById('tipo_agenda');
            
            let datos = new FormData();

            datos.append('titulo_agenda', titulo_agenda.value)
            datos.append('fecha_agenda', fecha_agenda.value)
            datos.append('motivo_visita', motivo_visita.value)

            if(tipo_agenda.value == 'carga_masiva'){
                let archivo = document.getElementById('carga_masiva_e')
                datos.append('carga_masiva_e', archivo.files[0])
            }else if(tipo_agenda.value == 'individual'){
                let tipo_documento = document.getElementById('tipo_documento_a');
                let numero_documento = document.getElementById('numero_documento_a');
                let nombres = document.getElementById('nombres_a');
                let apellidos = document.getElementById('apellidos_a');
                let movil = document.getElementById('movil_a');
                let correo = document.getElementById('correo_a');

                datos.append('tipo_documento', tipo_documento.value);
                datos.append('numero_documento', numero_documento.value);
                datos.append('nombres', nombres.value);
                datos.append('apellidos', apellidos.value);
                datos.append('movil', movil.value);
                datos.append('correo', correo.value);
            }

            microservicio_registrar_agenda(datos).then(respuesta=>{
                if(respuesta.cod == 400 || respuesta.cod == 500){
                    alert_registro_fallido(respuesta.msj);
                }else if(respuesta.cod == 200){
                    cerrar_modal_principal();
                    alert_registro_exitoso(respuesta.msj);
                    dibujar_card_agendas();
                }
            })

        }
    })
}

function evento_agregar_vehiculo(){
    let btn_vehiculo = document.getElementById('agregar_vehiculo');
    let modal_agenda = document.getElementById('contenedor_modal_principal').querySelector('#forma_registro');
    btn_vehiculo.addEventListener('click', ()=>{
        modal_registro_vehiculo('agendas');
        modal_agenda.style.display = 'none';
    });
}

function dibujar_contenido_inputs(datos){
    document.getElementById('titulo_agenda').value = datos.titulo;
    document.getElementById('fecha_agenda').value = datos.fecha_agenda;
    document.getElementById('motivo_visita_a').value = datos.motivo;
    document.getElementById('agregar_vehiculo').style.display = 'none';
    document.getElementById('titulo_modal').innerText = "Actualizar Agenda"
    document.getElementById('registrar').innerText = 'ACTUALIZAR';
    document.getElementById('registrar').setAttribute('data-id', datos.fecha_registro);
    let tipo_agenda = document.getElementById('tipo_agenda');
    if(datos.agendados.length == 1){
        tipo_agenda.selectedIndex = 1;
    }else{
        tipo_agenda.selectedIndex = 2;
    }
    tipo_agenda.disabled = true;
}



function evento_actualizar_agenda(){
    let btn_actualizar = document.getElementById('registrar');
    let inputs = document.querySelectorAll('.campo');

    btn_actualizar.addEventListener('click', ()=>{
        let validos = true;

        inputs.forEach(input => {
            if(!input.checkValidity()){
                input.reportValidity();
                validos = false;
                return;
            }
        });

        if(validos){
            let titulo = document.getElementById('titulo_agenda');
            let fecha_agenda = document.getElementById('fecha_agenda');
            let motivo = document.getElementById('motivo_visita_a');
            let id_agenda = btn_actualizar.getAttribute('data-id');

            let datos = new FormData();

            datos.append('titulo_agenda', titulo.value);
            datos.append('fecha_agenda', fecha_agenda.value);
            datos.append('motivo', motivo.value);
            datos.append('fecha_registro', id_agenda);

            microservicio_actualizar_agenda(datos).then(respuesta=>{
                if(respuesta.cod == 400 || respuesta.cod == 500){
                    alert_registro_fallido(respuesta.msj);
                }else if(respuesta.cod == 200){
                    alert_registro_exitoso(respuesta.msj);
                    cerrar_modal_principal();
                    dibujar_card_agendas();
                }
            })
        }
    })
}

async function microservicio_actualizar_agenda(datos) {
    try {
        const response = await fetch('../../microservicios/actualizar_agenda.php', {
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

async function microservicio_registrar_agenda(datos) {
    try {
        const response = await fetch('../../servicios/reg_agenda.php', {
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

function dibujar_card_agendas(documento='vacio'){
    let tabla = document.getElementById('contenedor-cards');
    let fecha = document.getElementById('fecha').value
    
    microservicio_buscar_agendas(fecha, documento).then(respuesta=>{
        if(respuesta.cod == 200){
            let iconos;
            tabla.innerHTML = '';
            respuesta.datos.forEach(agenda => {
                if(respuesta.cargo == 'coordinador' || respuesta.cargo == 'subdirector'){
                    iconos = `<i data-id="${agenda.fecha_registro}" class='eliminar bx bx-trash'></i>
                                <i data-id="${agenda.fecha_registro}" class='editar bx bxs-edit'></i>
                                <i data-id="${agenda.fecha_registro}" class='ver bx bx-list-ul'></i>`
                }else if(respuesta.cargo == 'jefe'){
                    iconos = `<i data-id="${agenda.fecha_registro}" class='ver bx bx-list-ul'></i>`;
                }
                tabla.innerHTML += `
                    <article class="card">
                        <h1>${convertir_mayuscula(agenda.titulo)}</h1>
                        <p class="nombre">${convertir_mayuscula(agenda.nombres)} ${convertir_mayuscula(agenda.apellidos)}</p>
                        <p class="fecha-registro">Fecha Agenda:</p>
                        <div class="contenedor-fecha">
                            <p>${agenda.fecha}</p>
                            <p>${agenda.hora}</p>
                        </div>
                        <div class="contenedor-icons">
                            ${iconos}
                                    
                        </div>
                    </article>`
                       
            });
            ver_detalle();
            editar();
            eliminar();
        } else{
            tabla.innerHTML = '';
            tabla.innerHTML += `
                <h2 id="mensaje_respuesta">${respuesta.msj}</h2>
            `
        }
    })
}

function ver_detalle() {
    const ver_mas = document.querySelectorAll('.ver');
    const contenedor_modal = document.getElementById('contenedor_modal');
    ver_mas.forEach(function(button) {
        button.addEventListener('click', function() {
            const id_agenda = button.getAttribute('data-id');
            microservicio_detalle_agenda(id_agenda).then(respuesta=>{
                if(respuesta.cod == 200){
                    const datos = respuesta.datos;
                    const agendados = datos.agendados.map(agendado => `<p>${convertir_mayuscula(agendado.nombres)} ${convertir_mayuscula(agendado.apellidos)}</p>`).join('');
                    contenedor_modal.innerHTML=` 
                    <div class="modal-body">
                            <article id="contenedor_titulo_icon">
                                <h1 id="titulo_modal">${convertir_mayuscula(datos.titulo)}</h1>
                                <i id="cerrar" class='cerrar_modal bx bx-x'></i>
                            </article>
                            <div>
                                <h3>Agendados:</h3>
                                <div id="cont_personas">${agendados} </div>
                                

                                <h3>Responsable:</h3>
                                <p>${datos.documento_usr} - ${convertir_mayuscula(datos.nombres_usr)} ${convertir_mayuscula(datos.apellidos_usr)}</p>
                            
                                <h3 class="fecha-agenda">Fecha Agenda:</h3>
                                <div class="modal-bloque">
                                    <p>${datos.fecha}</p>
                                    <p>${datos.hora}</p>
                                </div>
                                <h3>Motivo:</h3>
                                <p>${datos.motivo}</p>
                            </div>
                    </div>`
                    contenedor_modal.style.display = 'flex';
                    evento_cerrar_modal();

                    // evento_cerrar_modal()
                }else if(respuesta.cod == 400 || respuesta.cod == 500){
                    alert_registro_fallido(respuesta.msj)
                }
            })
        });
    });
}

function editar(){
    const editar = document.querySelectorAll('.editar');
    editar.forEach(button => {
        button.addEventListener('click', ()=>{
            let id_agenda = button.getAttribute('data-id');
            microservicio_detalle_agenda(id_agenda).then(respuesta=>{
                if(respuesta.cod == 200){
                    modal_agenda(respuesta.datos);
                }
            })
        })
    });
}

function eliminar(){
    const eliminar = document.querySelectorAll('.eliminar');
    eliminar.forEach(button => {
        button.addEventListener('click', ()=>{
            let id_agenda = button.getAttribute('data-id');
            let mensaje = "¿Esta seguro que desea eliminar esta agenda?"
            alert_confirmacion(mensaje, id_agenda);
        })
    });
}

async function microservicio_detalle_agenda(id){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/detalle_agenda.php?fecha_registro=' + encodeURI(id));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function microservicio_buscar_agendas(fecha, documento){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/buscar_agendas.php?fecha=' + encodeURI(fecha) + '&documento=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function microservicio_eliminar_agenda(id){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../microservicios/eliminar_agenda.php?fecha_registro=' + encodeURI(id));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const respuesta = await response.json();
        
        if(respuesta.cod == 400 || respuesta.cod == 500){
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 200){
            alert_registro_exitoso(respuesta.msj);
            dibujar_card_agendas();
        }

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

function limitar_fecha_agenda(){
    let fecha_minima = new Date();
    
    let año = fecha_minima.getFullYear();
    let mes = (fecha_minima.getMonth() + 1).toString().padStart(2, '0');
    let dia = fecha_minima.getDate().toString().padStart(2, '0');
    let horas = fecha_minima.getHours().toString().padStart(2, '0');
    let minutos = fecha_minima.getMinutes().toString().padStart(2, '0');
    
    // document.getElementById('fecha').min = fechaActual;
    document.getElementById('fecha_agenda').min = `${año}-${mes}-${dia} ${horas}:${minutos}:00`;
}



function inicializar_fecha() {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${year}-${month}-${day}`; // Construir en formato YYYY-MM-DD
    
    document.getElementById('fecha').min = fechaActual;
    document.getElementById('fecha').value = fechaActual;

}

function alert_confirmacion(mensaje, id){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Eliminar Agenda</h2>
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
            microservicio_eliminar_agenda(id);
        }
    })

}

document.addEventListener('DOMContentLoaded', function() {
    const btn_agenda = document.getElementById('agregar_agenda');

    if(btn_agenda){
        btn_agenda.addEventListener('click', ()=>{
            modal_agenda();
        });
    }

    inicializar_fecha();
    select_fecha();
    dibujar_card_agendas();
    busqueda_agendas();
});
