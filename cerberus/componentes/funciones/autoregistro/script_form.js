// funcion para el cambio de inputs en pantalla, aplicado en los registros: Aprendiz, Funcionarios, Vigilante


document.querySelectorAll('.btn_continuar').forEach(button => {
    button.addEventListener('click', function() {
        const bloque1 = this.closest('.contenedor');
        const bloque2 = bloque1.nextElementSibling;

        if (bloque1 && bloque2) {
            bloque1.style.display = 'none';
            bloque2.style.display = 'block';
        }
    });
});


// funcion para (volver) al bloque1, por si la persona cometio algun error en la informacion


document.querySelectorAll('.btn_volver').forEach(button => {
    button.addEventListener('click', function() {
        const bloque2 = this.closest('.contenedor');
        const bloque1 = bloque2.previousElementSibling;

        if (bloque2 && bloque1) {
            bloque2.style.display = 'none';
            bloque1.style.display = 'block';
        }
    });
});


// habilitar boton de CONTINUAR cuando los primeros 4 inputs no esten vacios y validos para continuar con el REGISTRO

let forms = document.querySelectorAll("#form_visitantes, #form_funcionario, #form_aprendiz");
let btn = document.querySelector("#btn_continuar");

function validar() {
    let deshabilitar = false;

    forms.forEach(form => {

        if (form.querySelector("select[name='tipo_documento']") && form.querySelector("select[name='tipo_documento']").value == "") {
            deshabilitar = true;
        }

        if (form.querySelector("input[name='numero_documento']") && form.querySelector("input[name='numero_documento']").value == "") {
            deshabilitar = true;
        }

        if (form.querySelector("input[name='nombres']") && form.querySelector("input[name='nombres']").value == "") {
            deshabilitar = true;
        }

        if (form.querySelector("input[name='apellidos']") && form.querySelector("input[name='apellidos']").value == "") {
            deshabilitar = true;
        }
    });

    btn.disabled = deshabilitar;
}

forms.forEach(form => {
    form.querySelectorAll("input").forEach(input => {
        input.addEventListener("keyup", validar);
    });

    form.querySelectorAll('select').forEach(select => {
        select.addEventListener("change", validar)
    });
});



// redireccion de los registros despues de presionar REGISTRAR a registro_final donde se da la bienvenida al CAB


//document.getElementById('btn_registrar').addEventListener('click', function() {
//    window.location.href = 'registro_final.html';
//});
