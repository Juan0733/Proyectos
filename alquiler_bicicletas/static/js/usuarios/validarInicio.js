
const formIniciar = document.querySelector('#forma_iniciar');

const enviarSesion = (event) =>{
    event.preventDefault();

    const {correo, contrasena} = event.target

    document.getElementById('error-correo').textContent = '';
    document.getElementById('error-contrasena').textContent = '';
    document.getElementById('contrasena').style.border = "2px solid #3F3F3F";
    document.getElementById('correo').style.border = "2px solid #3F3F3F";
    document.getElementById('error').innerText = "";

    let patternCorreo = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(correo.value.trim());
    let patternContrasena = /^[a-zA-Z0-9]{2,24}$/.test(contrasena.value.trim());

    let validoForm = true;

    if (!patternCorreo) {
        document.getElementById('error-correo').textContent = "ingrese un correo valido";
        document.getElementById('correo').style.border = "2px solid red";
        validoForm = false;
    } 

    if (!patternContrasena) {
        document.getElementById('error-contrasena').textContent = "la contraseña solo debe contener letras y tener entre 2 y 24 caracteres";
        document.getElementById('contrasena').style.border = "2px solid red";
        validoForm = false;
    } 

    if (validoForm){
        fetch('/sanitizarCredenciales', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                correo: correo.value,
                contrasena: contrasena.value
            })
        })
        .then(response => response.json())
        .then(data =>{
            if (data.titulo == "ERROR" || data.codigo == 100){
                document.getElementById('error').innerText = data.texto
            }else{
                window.location.replace("/mostrarBicicletas")
            }
        })
        .catch(error => console.error("Error:",error))
    }

}

formIniciar.addEventListener("submit", enviarSesion);

const boton = document.getElementById('btn_registro');
const enlace = document.getElementById('enlace_registro');

    //  pasar sobre el enlace
enlace.addEventListener('mouseover', function() {
    boton.style.backgroundColor = 'grey'; 
    boton.style.color = 'white'; 
    boton.style.boxShadow = '2px 2px grey'; 
});

    // se sale del enlace
enlace.addEventListener('mouseout', function() {
    boton.style.backgroundColor = '#39A900'; 
    boton.style.color = 'white'; 
    boton.style.boxShadow = '2px 2px #39A900'; 
});