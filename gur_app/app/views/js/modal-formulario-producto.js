import { consultarCategorias } from './categorias-api.js'
import { consultarIngredientes } from './ingredientes-api.js';
import { registrarProducto, actualizarProducto, consultarProducto, restaurarProducto } from './productos-api.js'

let contenedorModal;
let urlBase;
let accion;
let formulario;
let codigo;
let tipoProducto;
let nombre;
let categoria;
let foto;
let presentacion;
let precioVenta;
let unidadMedida;
let stockActual;
let stockMinimo;
let precioCompra;
let contenedorCodigo;
let contenedorCamposEstand;
let contenedorIngredientes;
let ingredientesSeleccionados = [];

async function modalFormularioProducto(operacion, url, idProducto=''){
    try{
        const response = await fetch(url+'app/views/content/modal-formulario-producto-view.php')

        if(!response.ok) throw new Error("Hubo un error en la solicitud");
        
        const modal = await response.text();
        contenedorModal = document.getElementById('contenedor_modal');

        contenedorModal.innerHTML = modal;

        urlBase = url;
        accion = operacion;
        formulario = document.getElementById('forma_registro');
        codigo = document.getElementById('codigo');
        tipoProducto = document.getElementById('tipo_producto');
        nombre = document.getElementById('nombre');
        categoria = document.getElementById('categoria');
        foto = document.getElementById('foto');
        presentacion = document.getElementById('presentacion');
        precioVenta = document.getElementById('precio_venta');
        unidadMedida = document.getElementById('unidad_medida');
        stockActual = document.getElementById('stock_actual');
        stockMinimo = document.getElementById('stock_minimo');
        precioCompra = document.getElementById('precio_compra');
        contenedorCodigo = document.getElementById('contenedor_codigo');
        contenedorCamposEstand = document.getElementById('contenedor_campos_estand');
        contenedorIngredientes = document.getElementById('contenedor_ingredientes');
        if(accion != 'registrar_producto'){
            codigo.value = idProducto;
        }
        dibujarCategorias();
        eventoFormulario();
        eventoSelect();

        contenedorModal.style.display = 'flex';

    }catch(error){
        console.error('Hubo un error:', error);
    }
}
export{modalFormularioProducto}



function dibujarProducto(){
    consultarProducto(codigo.value, urlBase).then((datos)=>{
        if(datos.tipo == 'OK' && datos.cod_error == 250){
            
            nombre.value = datos.producto.nombre;
            tipoProducto.value = datos.producto.tipo;
            tipoProducto.disabled = true;
            categoria.value = datos.producto.fk_categoria;
            presentacion.value = datos.producto.presentacion;
            precioVenta.value = datos.producto.precio_venta;
            if(datos.producto.tipo == 'estand'){
                codigo.disabled = true;
                unidadMedida.value = datos.producto.unidad_medida;
                stockActual.value = datos.producto.stock_actual;
                if(accion == 'editar_producto'){
                    stockActual.disabled = true;
                }
                stockMinimo.value = datos.producto.stock_minimo;
                precioCompra.value = datos.producto.precio_compra;
                contenedorCodigo.style.display = 'block';
                contenedorCamposEstand.style.display = 'block';
                contenedorCamposEstand.querySelectorAll('.campo-estand').forEach(input => {
                    input.setAttribute('required', '');
                });
            }else if(datos.producto.tipo == 'cocina'){
                dibujarIngredientes(datos.ingredientes);
            }
        }
    })
}

function dibujarIngredientes(ingredientesProducto=false){
    consultarIngredientes('activo', urlBase).then(datos=>{
        if(datos.tipo == 'OK'){
            if(datos.ingredientes.length > 0){
                datos.ingredientes.forEach(ingrediente => {
                    contenedorIngredientes.innerHTML += `<input type="checkbox" id="${ingrediente.codigo_ingrediente}" value="${ingrediente.codigo_ingrediente}">${ingrediente.nombre}`;
                });
                if(ingredientesProducto){
                    checkedIngredientes(ingredientesProducto);
                }
            }else if(datos.ingredientes.length < 1){
                contenedorIngredientes.innerHTML = '<h1>No se encontraron ingredientes</h1>';
            }
        }else if(datos.tipo == 'ERROR'){
            contenedorIngredientes.innerHTML = '<h1>Error al consultar ingredientes</h1>';
            mensajero(datos);
        }
    })
}

function checkedIngredientes(ingredientes){
    ingredientes.forEach(ingrediente => {
        document.getElementById(ingrediente.codigo_ingrediente).checked = true;
    });
}

function dibujarCategorias(){
    consultarCategorias(urlBase).then((datos)=>{
        if(datos.tipo == 'OK' && datos.cod_error == 250){
            let selectCategorias = document.getElementById('categoria');
            datos.categorias.forEach(categoria => {
                selectCategorias.innerHTML += `<option value="${categoria.contador}">${categoria.nombre}</option>`
            });

            if(accion != 'registrar_producto'){
                dibujarProducto();
            }
        }
    })
}

function capturarIngredientesSeleccionados(){
    const inputsIngredientes = formulario.querySelectorAll('input[type="checkbox"]');
    ingredientesSeleccionados = [];
    inputsIngredientes.forEach(input => {
        if(input.checked){
            ingredientesSeleccionados.push(input.value);
        }
    });
}

function eventoFormulario(){
    formulario.addEventListener('submit', (e)=>{
        e.preventDefault();
        let datos = new FormData();

        datos.append('accion', accion+'_'+tipoProducto.value)
        datos.append('tipo_producto', tipoProducto.value);
        datos.append('codigo', codigo.value);
        datos.append('nombre', nombre.value);
        datos.append('categoria', categoria.value);
        datos.append('presentacion', presentacion.value);
        datos.append('precio_venta', precioVenta.value);

        if(foto.files.length == 0){
            datos.append('foto', '');
        }else{
            datos.append('foto', foto.files[0]);
        }

        if(tipoProducto.value == 'estand'){
            datos.append('unidad_medida', unidadMedida.value);
            datos.append('stock_actual', stockActual.value);
            datos.append('stock_minimo', stockMinimo.value);
            datos.append('precio_compra', precioCompra.value);
        }else if(tipoProducto.value == 'cocina'){
            capturarIngredientesSeleccionados();
            let ingredientesJson = JSON.stringify(ingredientesSeleccionados);
            datos.append('ingredientes', ingredientesJson);
        }

        if(accion == 'registrar_producto'){
            registrarProducto(datos, urlBase).then((datos)=>{
                mensajero(datos);
            });
        }else if(accion == 'editar_producto'){
            actualizarProducto(datos, urlBase).then((datos)=>{
                mensajero(datos);
            })
        }else if(accion == 'restaurar_producto'){
            restaurarProducto(datos, urlBase).then((datos)=>{
                mensajero(datos);
            })
        }
    })
}

function eventoSelect(){
    let selectTipoProducto = document.getElementById('tipo_producto');
    selectTipoProducto.addEventListener('change', ()=>{
        if(selectTipoProducto.value == "cocina"){
            contenedorCodigo.style.display = 'none';
            codigo.removeAttribute('required');
            contenedorCamposEstand.style.display = 'none';
            contenedorCamposEstand.querySelectorAll('.campo-estand').forEach(input => {
                input.removeAttribute('required');
            });
            dibujarIngredientes();
        }else if(selectTipoProducto.value == "estand"){
            contenedorCodigo.style.display = 'block';
            codigo.setAttribute('required', '');
            contenedorCamposEstand.style.display = 'block';
            contenedorCamposEstand.querySelectorAll('.campo-estand').forEach(input => {
                input.setAttribute('required', '');
            });
            contenedorIngredientes.innerHTML = '';
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

