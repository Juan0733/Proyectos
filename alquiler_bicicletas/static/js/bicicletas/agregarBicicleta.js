document.addEventListener('DOMContentLoaded', ()=>{
    const form = document.querySelector('#registro_cicla');

    const validarDatos = (event) =>{
        event.preventDefault();

        const {marca, color, precio, regional, foto} = event.target

        document.getElementById('error-marca').textContent = '';
        document.getElementById('error-color').textContent = '';
        document.getElementById('error-precio').style.border = "none";
        document.getElementById('error-regional').innerText = "";

        document.getElementById('error').innerText = "";

        let colores = ['Rojo', 'Verde', 'Naranja', 'Amarillo', 'Azul', 'Blanco', 'Negro', 'Púrpura', 'Rosado']
        let patternMarca = /^[a-zA-ZñÑ0-9 ]{2,30}$/.test(marca.value.trim());
        let patternColor = colores.includes(color.value);
        let patternPrecio = /^[0-9]{3,}$/.test(precio.value.trim())
        let patternRegional = /^[0-9]{1,2}$/.test(regional.value)

        let validoForm = true;

        if (!patternMarca) {
            document.getElementById('error-marca').textContent = "Solo se permite texto y números.";
            document.getElementById('marca').style.border = "2px solid red";
            validoForm = false;
        } 

        if (!patternColor) {
            document.getElementById('error-color').textContent = "El color no es válido.";
            document.getElementById('color').style.border = "2px solid red";
            validoForm = false;
        } 

        if (!patternPrecio) {
            document.getElementById('error-precio').textContent = "Solo se permiten números.";
            document.getElementById('precio').style.border = "2px solid red";
            validoForm = false;
        } 

        if (!foto.files && foto.files.length == 0) {
            document.getElementById('error-foto').textContent = "Debes agregar una foto.";
            document.getElementById('foto').style.border = "2px solid red";
            validoForm = false;
        }

        if (!patternRegional) {
            document.getElementById('error-regional').textContent = "La regional no es válida.";
            document.getElementById('regional').style.border = "2px solid red";
            validoForm = false;
        } 

        if(validoForm){
            const formData = new FormData();
            formData.append('marca', marca.value);
            formData.append('color', color.value);
            formData.append('precio', precio.value)
            formData.append('foto', foto.files[0]);
            formData.append('regional', regional.value)

            registrar_bicicleta(formData)
            
        }
    }

    async function registrar_bicicleta(datos) {
        let response = await fetch('/sanitizarInfoBici', {
            method: 'POST',
            body: datos
        })
        
        if(response.ok){
            let data = await response.json()
            if(data.titulo == 'ERROR'){
                document.getElementById('error').innerText = data.texto
            }else if(data.titulo == "OK"){
                location.reload()
            }

        }else{
            console.error('Error al enviar el codigo.')
        }
    }

    form.addEventListener("submit", validarDatos)
})

