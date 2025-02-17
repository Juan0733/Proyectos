const form = document.querySelector('#forma_registro');
const form_codigo = document.querySelector('#cuerpo_codigo');


const enviarFormulario = (event) =>{
    event.preventDefault();

    const {nombres, apellidos, estrato, correo, contrasena, confirmar} = event.target

    //limpiar error
    document.getElementById('error-nombres').textContent = '';
    document.getElementById('error-apellidos').textContent = '';
    document.getElementById('error-correo').textContent = '';
    document.getElementById('error-contrasena').textContent = '';
    document.getElementById('error-confirmar-contrasena').textContent = '';
    
    //limpiar bordes de inputs errores
    document.getElementById('nombres').style.border = "none";
    document.getElementById('apellidos').style.border = "none";
    document.getElementById('correo').style.border = "none";
    document.getElementById('contrasena').style.border = "none";
    document.getElementById('confirmar').style.border = "none";
    
    let patternNombres = /^[a-zA-ZñÑ ]{2,24}$/.test(nombres.value.trim());
    let patternApellidos = /^[a-zA-ZñÑ ]{2,24}$/.test(apellidos.value.trim());
    let patternEstrato = /^[1-6]{1,1}/.test(estrato.value.trim())
    let patternCorreo = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(correo.value.trim());
    let patternContrasena = /^[a-zA-Z0-9]{2,24}$/.test(contrasena.value.trim());
    

    let validoForm = true

    if (!patternNombres) {
        document.getElementById('error-nombres').textContent = "El nombre solo debe contener letras y tener entre 2 y 24 caracteres.";
        document.getElementById('nombres').style.border = "2px solid red";
        validoForm = false;
    }

    if (!patternApellidos) {
        document.getElementById('error-apellidos').textContent = "El apellido solo debe contener letras y tener entre 2 y 24 caracteres.";
        document.getElementById('apellidos').style.border = "2px solid red";
        validoForm = false;
    }

    if (!patternEstrato){
        document.getElementById('error-estrato').textContent = "El estrato socioeconomico debe estar entre 1 y 6";
        document.getElementById('estrato').style.border = "2px solid red";
        validoForm = false;
    }

    if (!patternCorreo) {
        document.getElementById('error-correo').textContent = "Ingrese un correo valido";
        document.getElementById('correo').style.border = "2px solid red";
        validoForm = false;
    } 

    if (!patternContrasena) {
        document.getElementById('error-correo').textContent = "la contraseña solo debe contener letras y tener entre 2 y 24 caracteres";
        document.getElementById('contrasena').style.border = "2px solid red";
        validoForm = false;
    }

    if (contrasena.value.trim() != confirmar.value.trim()) {
        document.getElementById('error-confirmar-contrasena').textContent = 'las contraseñas no coinciden, vuelva a escribir tu contraseña';
        document.getElementById('confirmar').style.border = "2px solid red";
        validoForm = false;
    }

    if (validoForm) {
        fetch('/sanitizarInfoUsuario', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(
                {
                    nombres: nombres.value,
                    apellidos: apellidos.value,
                    estrato: estrato.value,
                    correo: correo.value,
                    contrasena: contrasena.value
                }
            )
        })
        .then(response => response.json())
        .then(data => {
            if (data.titulo == 'OK' && data.codigo == 200) {
                window.location.replace('/')
            } else {
                alert(data.titulo + data.texto);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

}

form.addEventListener('submit', enviarFormulario);
