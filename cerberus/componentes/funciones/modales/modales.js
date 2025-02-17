let datos_movimiento = {
    placa: '',
    propietario: '',
    puerta: '',
    pasajeros: [],
    tipo_movimiento: ''
};

let datos_vehiculo = {
    placa: '',
    tipo: '',
    propietario: ''
}

let propietarios = '';


function controlador_css(modal){
    let link = document.getElementById('css_modal');
    if(link){
        document.head.removeChild(link);
    }

    if(modal == 'peatonal'){
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_modal_peatonal.css">';
    }else if(modal == "vehicular"){
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_modal_vehicular.css">';
    } else if (modal == "visitante") {
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_modal_visitante.css">';
    } else if (modal == "modal_usuario_e") {
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_elegir_u.css">'
    }else if(modal == 'agendas'){
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_registro_agendas.css">'
    }else if(modal == 'vehiculo'){
        document.head.innerHTML += '<link id="css_modal" rel="stylesheet" href="../estilos_css/formato_modal_grande.css">'
    }
}
export{controlador_css}

function evento_cerrar_modal(){
    document.getElementById('cerrar').addEventListener('click', ()=>{
        console.log('cerrar')
        cerrar_modal(); 
        vaciar_objetos();
    })
}
export{evento_cerrar_modal}

function cerrar_modal(){
    let contenedor_modal = document.getElementById('contenedor_modal');
    let contenedor_modal_agenda = document.getElementById('contenedor_modal_principal');
    contenedor_modal.innerHTML = "";
    contenedor_modal.style.display = 'none';
    
    if(contenedor_modal_agenda){
        if(contenedor_modal_agenda.style.display == 'flex'){
            controlador_css('agendas');
            contenedor_modal_agenda.querySelector('#forma_registro').style.display = 'block';
        }
        
    }
}
export{cerrar_modal}

function vaciar_objetos(){
    datos_movimiento.placa = '';
    datos_movimiento.propietario = '';
    datos_movimiento.puerta = '';
    datos_movimiento.pasajeros = [];
    datos_movimiento.tipo_movimiento = '';
    datos_vehiculo.placa = '';
    datos_vehiculo.propietario = '';
    datos_vehiculo.tipo = '';
    propietarios = '';
}

function convertir_mayuscula(cadena) { 
    return cadena.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '); 
}

export{convertir_mayuscula}

function insertar_tablas(lugar){
    let cuerpo_tabla = document.getElementById('cuerpo_tabla');
    if(lugar == "entrada"){
        ultimas_entradas().then(respuesta=>{
            if(respuesta.cod == 200){
                cuerpo_tabla.innerHTML = '';
                respuesta.entradas.forEach(entrada => {
                    cuerpo_tabla.innerHTML += `
                    <tr>
                        <td>${entrada.fecha_registro}</td>
                        <td>${entrada.tipo_documento}</td>
                        <td>${entrada.documento}</td>
                        <td>${convertir_mayuscula(entrada.nombres)}</td>
                        <td>${convertir_mayuscula(entrada.apellidos)}</td>
                        <td>${convertir_mayuscula(entrada.tipo_usuario)}</td>
                        <td>${entrada.vehiculo == null ? 'Sin Vehiculo': entrada.vehiculo}</td>
                    </tr>
                    `
                });
            }
        })
    }else if(lugar == "salida"){
        ultimas_salidas().then(respuesta=>{
            if(respuesta.cod == 200){
                cuerpo_tabla.innerHTML = '';
                respuesta.salidas.forEach(salida => {
                    cuerpo_tabla.innerHTML += `
                    <tr>
                        <td>${salida.fecha_registro}</td>
                        <td>${salida.tipo_documento}</td>
                        <td>${salida.documento}</td>
                        <td>${convertir_mayuscula(salida.nombres)}</td>
                        <td>${convertir_mayuscula(salida.apellidos)}</td>
                        <td>${convertir_mayuscula(salida.tipo_usuario)}</td>
                        <td>${salida.vehiculo == null ? 'Sin Vehiculo': salida.vehiculo}</td>
                    </tr>
                    `
                });
            }
        })
    }
}
export{insertar_tablas}

async function ultimas_entradas(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/ultimas_entradas.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function ultimas_salidas(){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/ultimas_salidas.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const datos = await response.json();

        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


//-------------------------------------MODAL DE REGISTRO DE MOVIMIENTO PEATONAL Y SU LOGICA ----------------------------
async function modal_peatonal(lugar, documento=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_peatonal.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('peatonal');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
        contenedor_modal.innerHTML = modal;

        if (lugar == 'entrada peatonal') {
            document.getElementById('titulo_modal').textContent = 'Entrada Peatonal';
        } else if(lugar == 'salida peatonal') {
            document.getElementById('titulo_modal').textContent = 'Salida Peatonal';
        }

        if(documento){
            document.getElementById('numero_documento').value = documento;
        }
        
        contenedor_modal.style.display = 'flex';
        evento_registrar_movimiento_peatonal(lugar)
        evento_cerrar_modal();
        evento_input_documento();


    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 
export{modal_peatonal}

function evento_registrar_movimiento_peatonal(lugar){
    let formulario = document.getElementById('forma_registro');
    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();
        let numero_documento = document.getElementById('numero_documento');
        if(!numero_documento.checkValidity()){
            numero_documento.reportValidity();
        }else{
            let datos = new FormData();
            datos.append('numero_documento', numero_documento.value);
            if(lugar == "entrada peatonal"){
                registrar_entrada_peatonal(datos).then(respuesta=>{
                    if(respuesta.cod == 500 || respuesta.cod == 400){
                        alert_registro_fallido(respuesta.msj)
                    }else if(respuesta.cod == 404){
                        alert_persona_existente(lugar, numero_documento.value)
                    }else if(respuesta.cod == 402){
                        sal_no_registrada(lugar, numero_documento.value)
                    }else if(respuesta.cod == "200"){
                        alert_registro_exitoso(respuesta.msj);
                        cerrar_modal();
                        insertar_tablas('entrada');
                    }
                }) 
            }else if(lugar == "salida peatonal"){
                registrar_salida_peatonal(datos).then(respuesta=>{
                    if(respuesta.cod == 500 || respuesta.cod == 400){
                        alert_registro_fallido(respuesta.msj)
                    }else if(respuesta.cod == 404){
                        alert_persona_existente(lugar, numero_documento.value)
                    }else if(respuesta.cod == 402){
                        ent_no_registrada(lugar, numero_documento.value)
                    }else if(respuesta.cod == "200"){
                        alert_registro_exitoso(respuesta.msj);
                        cerrar_modal();
                        insertar_tablas('salida');
                    }
                })   
            }
        }
    })
}

function evento_input_documento(){
    let input_documento = document.getElementById('numero_documento');
    input_documento.focus();
    input_documento.addEventListener('change', ()=>{
        if(input_documento.value.length > 15){
            let numeros = input_documento.value.match(/\d+/g);
            input_documento.value=numeros[0];
        }
    })
    
}


async function registrar_entrada_peatonal(dato){
    try {
        const response = await fetch('../../servicios/entrada_peatonal.php', {
            method: 'POST',
            body: dato
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data;
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

async function registrar_salida_peatonal(dato){
    try {
        const response = await fetch('../../servicios/salida_peatonal.php', {
            method: 'POST',
            body: dato
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data;
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


//------------------------------------- MODAL DE REGISTRO DE MOVIMIENTO VEHICULAR Y SU LOGICA ----------------------------
async function modal_vehicular(lugar, placa=false, documento_propietario=false, documento_pasajero=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_vehicular.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('vehicular');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';
        if (lugar == 'entrada vehicular') {
            document.getElementById('titulo_modal').textContent = 'Entrada Vehicular';
        } else if(lugar == "salida vehicular"){
            document.getElementById('titulo_modal').textContent = 'Salida Vehicular';
        }

        if(placa){
            document.getElementById('numero_placa').value = placa;
        }

        if(documento_propietario){
            document.getElementById('grupo_1').style.display = 'none';
            document.getElementById('grupo_2').style.display = 'block';
            document.getElementById('documento_propietario').value = documento_propietario;
            mostrar_pasajeros();
            setTimeout(()=>{
                buscar_propietario(lugar);
            }, 1000)
        }else if(documento_pasajero){
            document.getElementById('grupo_1').style.display = 'none';
            document.getElementById('grupo_2').style.display = 'block';
            document.getElementById('documento_pasajero').value = documento_pasajero;
             document.getElementById('documento_propietario').value = datos_movimiento.propietario;
            setTimeout(()=>{
                agregar_pasajero(lugar);
            }, 1000)
        }
 
        document.getElementById('forma_registro').addEventListener('submit', (e)=>{
            e.preventDefault();
        })

        document.getElementById('numero_placa').focus();
        /* evento_input_placa() */
        evento_continuar(lugar);
        evento_input_propietario(lugar);
        evento_input_pasajero(lugar);
        evento_cerrar_modal();
        evento_registrar_movimiento(lugar);

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 
export{modal_vehicular}

function agregar_pasajero(lugar){
    
    let documento_pasajero = document.getElementById('documento_pasajero');

    if(!documento_pasajero.checkValidity()){
        documento_pasajero.reportValidity();
    }else{
        if(!datos_movimiento.pasajeros.includes(documento_pasajero.value) && documento_pasajero.value != datos_movimiento.propietario){
            buscar_persona(documento_pasajero.value).then(respuesta=>{
                
                if(respuesta.cod == 500){
                    alert_registro_fallido(respuesta.msj)
                }else if(respuesta.cod == 404){
                    if(lugar == 'entrada vehicular'){
                        alert_persona_existente('entrada vehicular pasajero', documento_pasajero.value);
                    }else if(lugar == 'salida vehicular'){
                        alert_persona_existente('salida vehicular pasajero', documento_pasajero.value);
                    }
                }else if(respuesta.cod == 200){
                    if(lugar == "entrada vehicular"){
                        if(respuesta.ubicacion == 'ADENTRO'){
                                sal_no_registrada('entrada vehicular pasajero', documento_pasajero.value);
                        }else{
                            datos_movimiento.pasajeros.push(documento_pasajero.value);
                            documento_pasajero.value = '';
                            mostrar_pasajeros();
                        }  
                    }else if(lugar == "salida vehicular"){
                        if(respuesta.ubicacion == 'AFUERA'){
                            ent_no_registrada('salida vehicular pasajero', documento_pasajero.value);
                        }else{
                            datos_movimiento.pasajeros.push(documento_pasajero.value);
                            documento_pasajero.value = '';
                            mostrar_pasajeros();
                        } 
                     }
                }
            })
            
        }
    }
}

function evento_input_pasajero(lugar){
    let input_pasajero = document.getElementById('documento_pasajero');
    let timeoutId;
    let evento_change = false;

    let btn_agregar = document.getElementById('btn_agregar');

    btn_agregar.addEventListener('click', ()=>{
        if(!evento_change){
            agregar_pasajero(lugar);
        }else{
            evento_change = false;
        }
        
    })

    input_pasajero.addEventListener('change', ()=>{
        evento_change = true;
        clearTimeout(timeoutId);
        if(input_pasajero.value.length > 15){
            let numeros = input_pasajero.value.match(/\d+/g);
            input_pasajero.value = numeros;
        }
        agregar_pasajero(lugar);
    });
}

function evento_continuar(lugar){
    let btn_continuar = document.getElementById('btn_continuar');
    let grupo_1 = document.getElementById('grupo_1');
    let grupo_2 = document.getElementById('grupo_2');
    let numero_placa = document.getElementById('numero_placa');
    let tipo_puerta = document.getElementById('tipo_puerta');
    btn_continuar.addEventListener('click', ()=>{
        if (!numero_placa.checkValidity()) {
            numero_placa.reportValidity();
        }else if(!tipo_puerta.checkValidity()){
            tipo_puerta.reportValidity();
        }else{
            buscar_vehiculo(numero_placa.value).then(respuesta=>{
                if (respuesta.cod == 500 || respuesta.cod == 400){
                    alert_registro_fallido(respuesta.msj);
                }else if(respuesta.cod == 404){
                    alert_vehiculo_existente(lugar, numero_placa.value.toUpperCase())
                }else if (respuesta.cod == 200){
                    let datalist = document.getElementById('propietarios');
                    propietarios = respuesta.propietarios;
                    datos_movimiento.placa = numero_placa.value.toUpperCase();
                    datos_movimiento.puerta = tipo_puerta.value;
                    propietarios.forEach(propietario => {
                        datalist.innerHTML += `<option value="${propietario.documento}">${propietario.nombres} ${propietario.apellidos}</option>`
                    });
                    grupo_1.style.display = 'none';
                    grupo_2.style.display = 'block';
                    console.log(datos_movimiento.placa)
                    document.getElementById('documento_propietario').focus();
                }
            });
        }
    })
}

function evento_eliminar_pasajero(){
    let btns_eliminar = document.getElementById('contenedor_pasajeros').querySelectorAll('i');
    btns_eliminar.forEach(btn => {
        btn.addEventListener('click', ()=>{
            datos_movimiento.pasajeros.splice(btn.id, 1);
            mostrar_pasajeros();
        })
    });
}

function mostrar_pasajeros(){
    let contenedor_pasajeros = document.getElementById("contenedor_pasajeros");
    contenedor_pasajeros.innerHTML = '';
    for(let i=0; i<datos_movimiento.pasajeros.length; i++){
        contenedor_pasajeros.innerHTML += `
            <div>
                <p>${datos_movimiento.pasajeros[i]}</p>
                <i id="${i}" class='bx bx-trash'></i>
            </div>`;
    };

    evento_eliminar_pasajero();
}


function evento_input_propietario(lugar){
    let input_propietario = document.getElementById('documento_propietario');
    let timeoutId;
    input_propietario.addEventListener('input', ()=>{
        clearTimeout(timeoutId);

        if(input_propietario.value.length > 15){
            let numeros = input_propietario.value.match(/\d+/g);
            input_propietario.value=numeros[0];
        }

        timeoutId = setTimeout(()=>{
            buscar_propietario(lugar)
        }, 1000)
    })

}

function buscar_propietario(lugar){
    let input_propietario = document.getElementById('documento_propietario');
    if(!input_propietario.checkValidity()){
            input_propietario.reportValidity()
    }else{
        if (!datos_movimiento.pasajeros.includes(input_propietario.value)){
            buscar_persona(input_propietario.value).then(respuesta=>{
                if(respuesta.cod == 500){
                    alert_registro_fallido(respuesta.msj);
                }else if(respuesta.cod == 404){
                    if(lugar == 'entrada vehicular'){
                        alert_persona_existente('entrada vehicular propietario', input_propietario.value);
                    }else if (lugar == 'salida vehicular'){
                        alert_persona_existente('salida vehicular propietario', input_propietario.value);
                    }
                }else if(respuesta.cod == 200){
                    if(lugar == "entrada vehicular"){
                            if(respuesta.ubicacion == 'ADENTRO'){
                            sal_no_registrada('entrada vehicular propietario', input_propietario.value);
                        }else{
                            datos_movimiento.propietario = input_propietario.value;
                        }  
                    }else if(lugar == "salida vehicular"){
                        if(respuesta.ubicacion == 'AFUERA'){
                                ent_no_registrada('salida vehicular propietario', input_propietario.value);
                        }else{
                            datos_movimiento.propietario = input_propietario.value;
                        }  
                    }
                           
                }
            })
        } 
    }         
}

function evento_registrar_movimiento(lugar){
    let btn_registrar_movimiento = document.getElementById('btn_registrar_movimiento')
    btn_registrar_movimiento.addEventListener('click', ()=>{
        if(datos_movimiento.placa && datos_movimiento.propietario && datos_movimiento.puerta){
            if(lugar == 'entrada vehicular'){
                datos_movimiento.tipo_movimiento = 'ENTRADA';
            }else if(lugar == 'salida vehicular'){
                datos_movimiento.tipo_movimiento = 'SALIDA';
            }

           registrar_movimiento_vehicular(datos_movimiento).then(respuesta=>{
                if(lugar == "entrada vehicular"){
                    if (respuesta.cod == 500 || respuesta.cod == 400){
                        alert_registro_fallido(respuesta.msj);
                    }else if (respuesta.cod == 200){
                        vaciar_objetos();
                        cerrar_modal();
                        alert_registro_exitoso(respuesta.msj);
                        insertar_tablas('entrada');
                    }
                }else if(lugar == "salida vehicular"){
                    if (respuesta.cod == 500){
                        alert_registro_fallido(respuesta.msj);
                    }else if (respuesta.cod == 404){
                        alert_propetario(lugar, datos_movimiento.propietario, datos_movimiento.placa);
                    }else if (respuesta.cod == 200){
                        vaciar_objetos();
                        cerrar_modal();
                        alert_registro_exitoso(respuesta.msj);
                        insertar_tablas('salida');
                    }
                }
           })
            
        }
    })
}

async function buscar_vehiculo(placa) {
    try {
        const response = await fetch('../../microservicios/buscar_vehiculo.php?placa=' + encodeURI(placa));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data;

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

async function registrar_movimiento_vehicular(datos) {
    try {
        const response = await fetch('../../servicios/movimiento_vehicular.php', {
            method: 'POST',
            body: JSON.stringify(datos),
            headers: {"Content-Type": "application/json"}
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}


//-------------------------------------MODAL DE REGISTRO DE VISITANTE Y SU LOGICA -----------------------------------
async function modal_visitante(lugar, documento=false, tabla_visitantes=false, alert_confirmacion=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_registro_visitante.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('visitante');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
       
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';

        if(documento){
            document.getElementById('numero_documento').value = documento;
            document.getElementById('numero_documento').setAttribute('disabled', '')
        } 
        evento_cerrar_modal();
        evento_registrar_visitante(lugar, tabla_visitantes, alert_confirmacion);
    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

export {modal_visitante}

function evento_registrar_visitante(lugar, tabla_visitantes, alert_confirmacion){
    let btnformulario = document.getElementById('registrarV');
    btnformulario.addEventListener('click', ()=>{
        let tipoDocumento = document.getElementById('tipo_documento');
        let numeroDocumento = document.getElementById('numero_documento');
        let nombres = document.getElementById('nombres'); 
        let apellidos = document.getElementById('apellidos');
        let telefono = document.getElementById('telefono');
        let correoElec = document.getElementById('correo');
        let motivoV = document.getElementById('motivo_de_visita');

        if (!tipoDocumento.checkValidity()) {
            tipoDocumento.reportValidity();
        } else if (!numeroDocumento.checkValidity()) {
            numeroDocumento.reportValidity();
        } else if (!nombres.checkValidity()) {
            nombres.reportValidity();
        } else if (!apellidos.checkValidity()) {
            apellidos.reportValidity();
        } else if (!telefono.checkValidity()) {
            telefono.reportValidity();
        } else if (!correoElec.checkValidity()) {
            correoElec.reportValidity();
        } else if (!motivoV.checkValidity()) {
            motivoV.reportValidity();
        } else {
            let datos = new FormData();
            datos.append('tipo_documento', tipoDocumento.value);
            datos.append('numero_documento', numeroDocumento.value);
            datos.append('nombres', nombres.value);
            datos.append('apellidos', apellidos.value);
            datos.append('movil', telefono.value);
            datos.append('correo', correoElec.value);
            datos.append('motivo_visita', motivoV.value);


            if(lugar == 'usuarios'){
                buscar_persona(numeroDocumento.value).then(respuesta=>{
                    if(respuesta.cod == 200){
                        if(respuesta.tipo_usuario == 'visitante'){
                            let mensaje = '¡El usuario ya existe como visitante, no es posible registrarlo!';
                            alert_registro_fallido(mensaje);
                        }else{
                            let mensaje = `Esta persona ya se encuentra registrada como ${respuesta.tipo_usuario}, ¿Desea hacer el cambio a visitante?`
                            alert_confirmacion(mensaje, datos, registrar_visitante, lugar, tabla_visitantes);
                        }
                    }else if(respuesta.cod == 404){
                        registrar_visitante(datos, lugar, tabla_visitantes);
                    }
                })
            }else{
                registrar_visitante(datos, lugar);
            }
        } 
    })
}

// logica de agregar visitante
async function registrar_visitante(datos, lugar, tabla_visitantes=false) {
    
    try {
        const response = await fetch("../../servicios/reg_visitantes.php", {
            method: 'POST',
            body: datos
        });
        if (!response.ok) throw new Error('Error en la solicitud');

        let respuesta = await response.json(); 
        
        if (respuesta.cod == 500 || respuesta.cod == 400) {
            alert_registro_fallido(respuesta.msj);
         }else if(respuesta.cod == 200){
            console.log('registro visitante');
             if (lugar == "usuarios") {
                cerrar_modal();
                tabla_visitantes();
             }else if(lugar == 'registro vehiculo salida'){
                 modal_registro_vehiculo('registro usuario salida');
             }else if(lugar == 'registro vehiculo entrada'){
                 modal_registro_vehiculo('registro usuario entrada');
             }else if(lugar == 'entrada peatonal' || lugar == 'salida peatonal'){
                 modal_peatonal(lugar, datos.get('numero_documento'));
             }else if(lugar == 'entrada vehicular propietario' ){
                 modal_vehicular('entrada vehicular', false, datos.get('numero_documento'), false);
             }else if(lugar == 'entrada vehicular pasajero'){
                 modal_vehicular('entrada vehicular', false, false, datos.get('numero_documento'))
             }else if(lugar == 'salida vehicular propietario' ){
                 modal_vehicular('salida vehicular', false, datos.get('numero_documento'), false);
             }else if(lugar == 'salida vehicular pasajero'){
                 modal_vehicular('salida vehicular', false, false, datos.get('numero_documento'))
             }else if(lugar == 'agendas'){
                modal_registro_vehiculo('registro usuario agenda');
             }
             alert_registro_exitoso(respuesta.msj);
         }
        
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
//-------------------------------------MODAL DE REGISTRO DE VEHICULO Y SU LOGICA -----------------------------------
async function modal_registro_vehiculo(lugar, placa=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_registro_vehiculo.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('vehiculo');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
        
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';
        if(lugar == "entrada vehicular" || lugar == "salida vehicular"){
            document.getElementById('numero_placa').value = placa;
            document.getElementById('numero_placa').setAttribute('disabled', '')
        }else if(lugar == "registro usuario salida" || lugar == "registro usuario entrada" || lugar == 'registro usuario agenda'){
            document.getElementById('numero_placa').value = datos_vehiculo.placa;
            document.getElementById('numero_documento').value = datos_vehiculo.propietario;
            document.getElementById('tipo_vehiculo').value = datos_vehiculo.tipo;
            document.getElementById('numero_placa').setAttribute('disabled', '')
        }

        
        
        document.getElementById('forma_registro').addEventListener('submit', (e)=>{
            e.preventDefault();
        })

        evento_cerrar_modal();
        evento_registrar_vehiculo(lugar);
        evento_input_documento();

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

function evento_registrar_vehiculo(lugar){
    let btn_registrar = document.getElementById('btn_registrar_vehiculo');
    btn_registrar.addEventListener('click', ()=>{
        let numero_documento = document.getElementById('numero_documento');
        let numero_placa = document.getElementById('numero_placa');
        let tipo_vehiculo = document.getElementById('tipo_vehiculo');

        if(!numero_documento.checkValidity()){
            numero_documento.reportValidity();
        }else if(!numero_placa.checkValidity()){
            numero_placa.reportValidity;
        }else if(!tipo_vehiculo.checkValidity()){
            tipo_vehiculo.reportValidity()
        }else{
            datos_vehiculo.placa = numero_placa.value;
            datos_vehiculo.propietario = numero_documento.value;
            datos_vehiculo.tipo = tipo_vehiculo.value;

            if(lugar == 'agendas'){
                buscar_vehiculo(numero_placa.value).then(respuesta=>{
                    if(respuesta.cod == 200){
                        let mensaje = '¡Este vehiculo ya existe en el sistema, no es necesario registrarlo!'
                        alert_registro_fallido(mensaje);
                    }else if(respuesta.cod == 500 || respuesta.cod == 400){
                        alert_registro_fallido(respuesta.msj);
                        
                    }else if(respuesta.cod == 404){
                        console.log('registro')
                        registrar_vehiculo(lugar, datos_vehiculo);
                    }
                })
            }else{
                registrar_vehiculo(lugar, datos_vehiculo)
            }
        }
    })
}

async function registrar_vehiculo(lugar, datos) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/registro_vehiculo.php', {
            method: 'POST',
            body: JSON.stringify(datos),
            headers: {"Content-Type": "application/json"}

        });
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        const respuesta = await response.json();
        
        if(respuesta.cod == 500 || respuesta.cod == 400){
            alert_registro_fallido(respuesta.msj);
        }else if(respuesta.cod == 404){
            if(lugar == 'entrada vehicular'){
                alert_persona_existente('registro vehiculo entrada', numero_documento.value);
            }else if(lugar == 'salida vehicular'){
                alert_persona_existente('registro vehiculo salida', numero_documento.value);
            }else if(lugar == 'agendas'){
                alert_persona_existente(lugar, numero_documento.value);
            }
        
        }else if(respuesta.cod == 200){
            if(lugar == "registro usuario entrada" ){
                modal_vehicular('entrada vehicular', numero_placa.value)
            }else if(lugar == "registro usuario salida"){
                modal_vehicular('salida vehicular', numero_placa.value)
            }else if(lugar == 'entrada vehicular' || lugar == 'salida vehicular'){
                modal_vehicular(lugar, numero_placa.value);
            }else if(lugar == 'agendas' || lugar == 'registro usuario agenda'){
                cerrar_modal();
                controlador_css('agendas');
                document.getElementById('contenedor_modal_principal').querySelector('#forma_registro').style.display = 'block';
            }
            vaciar_objetos();
            alert_registro_exitoso(respuesta.msj)
        }

    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

export{modal_registro_vehiculo}


//-------------------------------------MODAL DE REGISTRO DE NOVEDAD VEHICULAR -----------------------------------
async function modal_registro_novedad_veh(lugar, documento, placa) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_novedad_vehiculo.php');
        if (!response.ok) throw new Error('Error en la solicitud');

        // Obtenemos el contenido como texto
        controlador_css('visitante');
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');

        // Insertamos el HTML en el modal y lo mostramos
        
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';

        document.getElementById('numero_documento').value = documento;
        document.getElementById('numero_documento').setAttribute('disabled', '');

        document.getElementById('numero_placa').value = placa;
        document.getElementById('numero_placa').setAttribute('disabled', '');
        
        evento_registrar_novedad_vehiculo(lugar);
        evento_cerrar_modal();

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

function evento_registrar_novedad_vehiculo(lugar){
    let btn_registrar_novedad = document.getElementById('btn_registrar_novedad_vehiculo');

    btn_registrar_novedad.addEventListener('click', ()=>{
        let tipo_novedad = document.getElementById('tipo_novedad');
        let documento = document.getElementById('numero_documento');
        let vehiculo = document.getElementById('numero_placa');
        let descripcion = document.getElementById('descripcion');
        let puerta_registro = datos_movimiento.puerta;

        if(!tipo_novedad.checkValidity()){
            tipo_novedad.reportValidity();
        }else if(!documento.checkValidity()){
            documento.reportValidity();
        }else if(!vehiculo.checkValidity()){
            vehiculo.reportValidity();
        }else if(!descripcion.checkValidity()){
            descripcion.reportValidity();
        }else{
            let datos = new FormData();
            datos.append('tipo_novedad', tipo_novedad.value);
            datos.append('numero_documento', documento.value);
            datos.append('numero_placa',vehiculo.value);
            datos.append('descripcion', descripcion.value);
            datos.append('puerta_registro', puerta_registro)
            
            registrar_novedad_vehiculo(datos).then(respuesta=>{
                if(respuesta.cod == 500 || respuesta.cod == 400){
                    alert_registro_fallido(respuesta.msj)
                }else if(respuesta.cod == 200){
                    modal_vehicular(lugar, false, documento.value, false);
                    alert_registro_exitoso(respuesta.msj);                
                }
            })
        }

        
    })
}

async function registrar_novedad_vehiculo(datos) {
    try {
        const response = await fetch('../../servicios/registro_novedad_vehiculo.php', {
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


//-------------------------------------MODAL DE REGISTRO DE NOVEDAD ENTRADA Y SALIDA CON SU LOGICA -----------------------------------
async function modal_registro_novedad(lugar, documento, funcion=false) {
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../plantillas/modal_novedad_movimiento.php');
        if (!response.ok) throw new Error('Error en la solicitud');
        controlador_css('peatonal')
        // Obtenemos el contenido como texto
        const modal = await response.text();
        const contenedor_modal = document.getElementById('contenedor_modal');
        
        
    // Insertamos el HTML en el modal y lo mostramos
        contenedor_modal.innerHTML = modal;
        contenedor_modal.style.display = 'flex';

        if (lugar == "entrada peatonal" || lugar == "entrada vehicular pasajero" || lugar == 'entrada vehicular propietario' || lugar == 'notificaciones') {
            document.getElementById('tipo_novedad').value = "Salida no registrada";

        }else if(lugar == "salida peatonal" || lugar == "salida vehicular pasajero" || lugar == 'salida vehicular propietario'){
            document.getElementById('tipo_novedad').value = "Entrada no registrada";
        }

        let puerta_registro = document.getElementById('puerta_registro');
        let cont_registo = document.getElementById('fila_registro')
        if(lugar == 'entrada peatonal' || lugar == 'salida peatonal'){
            puerta_registro.selectedIndex = 3;
            cont_registo.style.display="none"
        }else if(lugar == 'entrada vehicular propietario' || lugar == 'salida vehicular propietario' || lugar == 'entrada vehicular pasajero' || lugar == 'salida vehicular pasajero'){
            if(datos_movimiento.puerta == 'principal'){
                puerta_registro.selectedIndex = 2;
                puerta_registro.disabled = true;
            }else{
                puerta_registro.selectedIndex = 1;
                puerta_registro.disabled = true;
            }
        }


        
        document.getElementById('numero_documento').value = documento;
        document.getElementById('numero_documento').setAttribute('disabled', '')

        evento_registrar_novedad(lugar, funcion)
        evento_cerrar_modal();

    } catch (error) {
        console.error('Hubo un error:', error);
    }
} 

function evento_registrar_novedad(lugar, funcion){
    let btn_registrar_novedad = document.getElementById('btn_registrar_novedad');

    btn_registrar_novedad.addEventListener('click', ()=>{
        let tipo_novedad = document.getElementById('tipo_novedad');
        let documento = document.getElementById('numero_documento');
        let puerta_acontecimiento = document.getElementById('puerta_acontecimiento');
        let puerta_registro = document.getElementById('puerta_registro');
        
        let descripcion = document.getElementById('descripcion');

        if(!tipo_novedad.checkValidity()){
            tipo_novedad.reportValidity();
        }else if(!documento.checkValidity()){
            documento.reportValidity();
        }else if(!puerta_acontecimiento.checkValidity()){
            puerta_acontecimiento.reportValidity();
        }else if(!puerta_registro.checkValidity()){
            puerta_registro.reportValidity();
        }else if(!descripcion.checkValidity()){
            descripcion.reportValidity();
        }else{
            let datos = new FormData();
            
            datos.append('tipo_novedad', tipo_novedad.value);
            datos.append('numero_documento', documento.value);
            datos.append('puerta_acontecimiento', puerta_acontecimiento.value);
            datos.append('descripcion', descripcion.value);
            datos.append('puerta_registro', puerta_registro.value)
            
            registrar_novedad(datos).then(respuesta=>{
                if(respuesta.cod == 500 || respuesta.cod == 400){
                    alert_registro_fallido(respuesta.msj)
                }else if(respuesta.cod == 200){
                    if(lugar == 'entrada peatonal' || lugar == 'salida peatonal'){
                        modal_peatonal(lugar, documento.value);
                    }else if(lugar == 'entrada vehicular propietario' ){
                        modal_vehicular('entrada vehicular', false, documento.value, false);
                    }else if(lugar == 'entrada vehicular pasajero'){
                        modal_vehicular('entrada vehicular', false, false, documento.value)
                    }else if(lugar == 'salida vehicular propietario' ){
                        modal_vehicular('salida vehicular', false, documento.value, false);
                    }else if(lugar == 'salida vehicular pasajero'){
                        modal_vehicular('salida vehicular', false, false, documento.value)
                    }else if(lugar == 'notificaciones'){
                        cerrar_modal();
                        funcion();
                    }
                    alert_registro_exitoso(respuesta.msj);                
                }
            })
        }

        
    })
}

async function registrar_novedad(datos) {
    try {
        const response = await fetch('../../servicios/registro_novedad_movimiento.php', {
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

export {modal_registro_novedad}
       
async function buscar_persona(documento) {
    try {
        const response = await fetch('../../microservicios/buscar_persona.php?documento=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
export{buscar_persona}

async function ultimo_movimiento(documento) {
    try {
        const response = await fetch('../../microservicios/ultimo_movimiento.php?documento=' + encodeURI(documento));
        if (!response.ok) throw new Error('Error en la solicitud');

        const data = await response.json();

        return data
       
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}
     


function limitar_fecha(documento){
    // let input_fecha = document.getElementById('fecha_acontecimiento');
    let fecha_actual = new Date();
    let fecha_registro;

    ultimo_movimiento(documento).then(respuesta=>{
        if(respuesta.cod == 200){
            fecha_registro = new Date(respuesta.fecha_registro);

            let año_actual = fecha_actual.getFullYear();
            let mes_actual = (fecha_actual.getMonth() + 1).toString().padStart(2, '0');
            let dia_actual = fecha_actual.getDate().toString().padStart(2, '0');
            let horas_actual = fecha_actual.getHours().toString().padStart(2, '0');
            let minutos_actual = fecha_actual.getMinutes().toString().padStart(2, '0');

            let año_registro = fecha_registro.getFullYear();
            let mes_registro = (fecha_registro.getMonth() + 1).toString().padStart(2, '0');
            let dia_registro = fecha_registro.getDate().toString().padStart(2, '0');
            let horas_registro = fecha_registro.getHours().toString().padStart(2, '0');
            let minutos_registro = fecha_registro.getMinutes().toString().padStart(2, '0');

            // input_fecha.max = `${año_actual}-${mes_actual}-${dia_actual} ${horas_actual}:${minutos_actual}:00`;
            // input_fecha.min = `${año_registro}-${mes_registro}-${dia_registro} ${horas_registro}:${minutos_registro}:00`;
        }
    })

    
}



// modal de alert de persona no registrada
function alert_persona_existente(lugar, documento){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Persona no registrada</h2>
            <i class='icon-info bx bx-info-circle'></i>
            <p class="alert-mensaje">¡Parece que la persona con numero de documento <strong>${documento}</strong> no se encuentra registrada en el sistema!</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'REGISTRAR VISITANTE',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-button' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            modal_visitante(lugar, documento)
        }
    })

}
export{alert_persona_existente}

// modal de alert de vehiculo no registrado
function alert_vehiculo_existente(lugar, placa){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Vehiculo no registrado</h2>
            <i class='icon-info bx bx-info-circle'></i>
            <p class="alert-mensaje">¡Parece que el vehiculo de placas <strong>${placa}</strong> no encuentra registrado en el sistema!</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'REGISTRAR VEHICULO',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-button' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            modal_registro_vehiculo(lugar, placa)
        }
    })

}

export{alert_vehiculo_existente}

//modal de alert dee propetario no registrado
function alert_propetario(lugar, documento, placa){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Propietario no registrado</h2>
            <i class='icon-pel bx bx-error-alt'></i>
            <p class="alert-mensaje">¡Parece que la persona con numero de documento <strong>${documento}</strong> no es propietaria del vehiculo de placas <strong>${placa}</strong>!</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'REGISTRAR NOVEDAD',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-pel' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            modal_registro_novedad_veh(lugar, documento, placa)
        }
    })

}

export{alert_propetario}

// modal de salidad no registrada 
function sal_no_registrada(lugar, documento){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Salida no registrada</h2>
            <i class='icon-pel bx bx-error-alt'></i>
            <p class="alert-mensaje">¡Parece la persona con numero de documento <strong>${documento}</strong> no se le registro la salida, porque el sistema indica que aún se encuentra <strong>Dentro</strong> del CAB!</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'REGISTRAR NOVEDAD',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-pel' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            modal_registro_novedad(lugar, documento)
        }
    })

}
//metodo para poderlo llamar en otro archivo js
export{sal_no_registrada}

//modal alert de entrada no registrada
function ent_no_registrada(lugar, documento){
    Swal.fire({
        html: `
            <h2 class="alert-titulo">Entrada no registrada</h2>
            <i class='icon-pel bx bx-error-alt'></i>
            <p class="alert-mensaje">¡Parece la persona con numero de documento <strong>${documento}</strong> no se le registro la entrada, porque el sistema indica que se encuentra <strong>Afuera</strong> del CAB!</p>
        `,
        background: "#E7E7E7",
        confirmButtonText: 'REGISTRAR NOVEDAD',
        showCloseButton: true, // Muestra el botón de cerrar en la esquina
        customClass: {
            popup: 'contenedor-alert', // Personalización adicional del modal
            confirmButton: 'alert-pel' // Personalización del botón
        }
     
    }).then((result) => {
        if (result.isConfirmed) {
            modal_registro_novedad(lugar, documento)
        }
    })

}
export {ent_no_registrada}

function alert_registro_exitoso(mensaje){
    Swal.fire({
        toast: true, 
        position: 'top-end', 
        icon: 'success',
        title: mensaje,
        showConfirmButton: false, 
        timer: 5000, 
        background: '#ffffff', 
        color: '#28a745', 
        timerProgressBar: true,
        customClass: {
            title: 'texto-alert-u',
            popup: 'contenedor-exitosa'
        }
    })
}
export{alert_registro_exitoso}

function alert_registro_fallido(mensaje){
    Swal.fire({
        toast: true, 
        position: 'top-end', 
        icon: "warning",
        title: mensaje,
        showConfirmButton: false, 
        timer: 5000, 
        background: '#ffffff', 
        color: '#414043', 
        timerProgressBar: true,
        customClass: {
            title: 'texto-alert-u',
            popup: 'contenedor-exitosa'
        }
    })
}
export{alert_registro_fallido}
            