document.addEventListener('DOMContentLoaded', ()=>{
    let form = document.querySelector('#registro_evento')

    const validarDatos = (event) =>{
        event.preventDefault();

        const {nombre, descripcion, fecha, lugar, foto_evento} = event.target

        document.getElementById('error-nombre').textContent = '';
        document.getElementById('error-descripcion').textContent = '';
        document.getElementById('error-lugar').innerText = "";

        let patternNombre = /^[a-zA-ZñÑ0-9 ]{2,30}$/.test(nombre.value.trim());
        let patternDescripcion = /^[a-zA-ZñÑ ]{2,100}$/.test(descripcion.value.trim());
        let patternLugar = /^[a-zA-ZñÑ ]{2,30}$/.test(lugar.value.trim());

        let fecha_actual = new Date();
        let fecha_formateada = fecha.value.replace('T', ' ') + ':00'
        let objeto_fecha = new Date(fecha_formateada)
        


        let validoForm = true;

        if (!patternNombre) {
            document.getElementById('error-nombre').textContent = "Solo se permite texto y números.";
            document.getElementById('nombre').style.border = "2px solid red";
            validoForm = false;
        } 

        if (!patternDescripcion) {
            document.getElementById('error-descripcion').textContent = "El color no es válido.";
            document.getElementById('descripcion').style.border = "2px solid red";
            validoForm = false;
        } 

        if (!patternLugar) {
            document.getElementById('error-lugar').textContent = "Solo se permiten letras.";
            document.getElementById('lugar').style.border = "2px solid red";
            validoForm = false;
        } 

        if(objeto_fecha <= fecha_actual ){
            document.getElementById('error-fecha').textContent = "Debes agregar una fecha valida, que sea mayor a la fecha actual."
            validoForm = false;
        }

        if (!foto_evento.files && foto_evento.files.length == 0) {
            document.getElementById('error-foto_evento').textContent = "Debes agregar una foto.";
            document.getElementById('foto_evento').style.border = "2px solid red";
            validoForm = false;
        }

        if (validoForm){
            const formData = new FormData();
            formData.append('nombre', nombre.value);
            formData.append('descripcion', descripcion.value);
            formData.append('lugar', lugar.value)
            formData.append('fecha', fecha_formateada)
            formData.append('foto_evento', foto_evento.files[0]);
            
            registrar_evento(formData)
        }

    }

    async function registrar_evento(datos) {
        let response = await fetch('/sanitizarEvent', {
            method: 'POST',
            body: datos
        })
        
        if(response.ok){
            let data = await response.json()
            if(data.titulo == 'ERROR'){
                document.getElementById('error_evento').innerText = data.texto
            }else if(data.titulo == "OK"){
                location.reload()
            }

        }else{
            console.error('Error al enviar el codigo.')
        }
    }

    form.addEventListener('submit', validarDatos)
})