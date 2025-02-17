import { registrarIngrediente, actualizarIngrediente, consultarIngrediente, restaurarIngrediente } from "./ingredientes-api.js";
import { consultarCategorias } from "./categorias-api.js";

let contenedorModal;
let urlBase;
let accion;
let codigo;
let nombre;
let categoria;
let presentacion;
let unidadMedida;
let stockActual;
let stockMinimo;
let precioCompra;

async function modalFormularioIngrediente(operacion, url, idIngrediente=''){
    try {
        const response = await fetch(url+'app/views/content/modal-formulario-ingrediente-view.php')

        if(!response.ok) throw new Error("Hubo un error en la solicitud");

        const modal = await response.text();
        contenedorModal = document.getElementById('contenedor_modal');

        contenedorModal.innerHTML = modal;

        urlBase =url;
        accion = operacion;
        codigo = document.getElementById('codigo');
        nombre = document.getElementById('nombre');
        categoria = document.getElementById('categoria');
        presentacion = document.getElementById('presentacion');
        unidadMedida = document.getElementById('unidad_medida');
        stockActual = document.getElementById('stock_actual');
        stockMinimo = document.getElementById('stock_minimo');
        precioCompra = document.getElementById('precio_compra');
        if(accion != 'editar_producto'){
            codigo.value = idIngrediente;
        }
        dibujarCategorias();
        eventoFormulario();

        contenedorModal.style.display = 'flex';
        
    } catch (error) {
        console.error('Hubo un error:', error)
    }
}
export{modalFormularioIngrediente};

function dibujarIngrediente() {

    consultarIngrediente(codigo.value, urlBase).then(datos=>{
        if(datos.tipo == 'OK' && datos.cod_error == 250){
            
            nombre.value = datos.ingrediente.nombre;
            categoria.value = datos.ingrediente.fk_categoria;
            presentacion.value = datos.ingrediente.presentacion;
            unidadMedida.value = datos.ingrediente.unidad_medida;
            stockActual.value = datos.ingrediente.stock_actual;
            stockMinimo.value = datos.ingrediente.stock_minimo;
            precioCompra.value = datos.ingrediente.precio_compra;
            if(accion == 'editar_ingrediente'){
                stockActual.disabled = true;
            }
            codigo.disabled = true;
        }
    })
}

async function dibujarCategorias() {
   
    consultarCategorias(urlBase).then(datos=>{
        if(datos.tipo == 'OK' && datos.cod_error == 250){
            let selectCategorias = document.getElementById('categoria');
            datos.categorias.forEach(categoria => {
                selectCategorias.innerHTML += `<option value="${categoria.contador}">${categoria.nombre}</option>`
            });

            if(accion != 'registrar_ingrediente'){
                dibujarIngrediente();
            }

        }
    })
}

function eventoFormulario(){
    let formulario = document.getElementById('forma_registro');

    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();

        let datos = new FormData();

        datos.append('accion', accion);
        datos.append('codigo', codigo.value);
        datos.append('nombre', nombre.value);
        datos.append('categoria', categoria.value);
        datos.append('presentacion', presentacion.value);
        datos.append('unidad_medida', unidadMedida.value)
        datos.append('stock_actual', stockActual.value);
        datos.append('stock_minimo', stockMinimo.value);
        datos.append('precio_compra', precioCompra.value);
        

        if(accion == 'registrar_ingrediente'){
            registrarIngrediente(datos, urlBase).then(datos=>{
                mensajero(datos);
            });
        }else if(accion == 'editar_ingrediente'){
            actualizarIngrediente(datos, urlBase).then(datos=>{
                mensajero(datos);
            });
        }else if(accion == 'restaurar_ingrediente'){
            restaurarIngrediente(datos, urlBase).then(datos=>{
                mensajero(datos);
            })        
        }
    })
}

function cerrarModal(){
    contenedorModal.innerHTML = '';
    contenedorModal.style.display = 'none';
}

function mensajero(respuesta){
    Swal.fire({
        icon: respuesta.icono,
        title: respuesta.titulo,
        text: respuesta.mensaje,
        confirmButtonText: 'Aceptar',
        customClass: {
            popup: 'alerta-contenedor',
            confirmButton: 'btn-confirmar'
        }
    }).then((result)=>{
        if (result.isConfirmed) {
            if(respuesta.tipo == 'OK'){
                window.location.replace(urlBase+respuesta.url);
            }
        }
    })
}


