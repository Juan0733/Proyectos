

document.addEventListener('DOMContentLoaded', ()=>{

    let filtro_region = document.getElementById('filtro_region')
    let filtro_estado = document.getElementById('filtro_estado') 

    configurarModalEditar();
    configurarAlertas()
    function validarFiltros(){
        if(!filtro_region.value){
            let patternEstado = filtro_estado.value == 'alquilada' || filtro_estado.value == 'disponible'
            if(patternEstado){
                consultar_bicicletas(filtro_estado.value)
            }
        }else{
            let patternEstado = filtro_estado.value == 'alquilada' || filtro_estado.value == 'disponible'
            let patternRegion = /^[0-9]{1,2}$/.test(filtro_region.value)
            if(patternEstado && patternRegion){
                consultar_bicicletas(filtro_estado.value, filtro_region.value)
            } 
        }
    }

    filtro_region.addEventListener('change', validarFiltros)

    filtro_estado.addEventListener('change', validarFiltros)

    async function consultar_bicicletas(estado, region=false) {
        let response = await fetch(`/consultarBicicletas/${estado}/${region}`)
        let data = await response.json()

        document.getElementById("card-container").innerHTML = ''

        if(data.titulo == 'ERROR'){
            document.getElementById("card-container").innerHTML = `<h1>${data.texto}</h1>`;
        }else if(data.titulo == 'OK'){
            data.bicicletas.forEach(bicicleta => {
                document.getElementById("card-container").innerHTML+=`
                <article id="card_bici">
                    <img src="uploads/${bicicleta.foto}" alt="">
                    <h3>Marca: <span class="sp">${bicicleta.marca}</span></h3>
                    <h3>Color: <span class="sp">${bicicleta.color}</span></h3>
                    <h3>Precio: <span class="sp">$${bicicleta.precio_alquiler}x1km</span></h3>
                    <h3>region: <span class="sp">${bicicleta.region}</span></h3>
                    ${data.rol == 'Usuario' ? bicicleta.estado == 'disponible'? `<button class="btn btn-primary alertButton" data-id="${bicicleta.pk_bicicleta}">ALQUILAR</button> `: ` <button class="btn-primary devolver" data-id="${bicicleta.pk_bicicleta}">DEVOLVER</button>` : bicicleta.estado == 'disponible' ? `<div class="icons">
                        <i class="fa-regular fa-pen-to-square ion-edit" 
                            data-bs-toggle="modal" data-bs-target="#editarModal"
                            data-marca="${bicicleta.marca}" data-precio="${bicicleta.precio_alquiler}" 
                            data-color="${bicicleta.color}" data-id="${bicicleta.pk_bicicleta} data-regional = "${bicicleta.region}" role="button">
                        </i>
                        <a href=/eliminarBicicleta/${bicicleta.pk_bicicleta}>
                            <i class="fa-solid fa-trash icon-delete"></i>
                        </a>
                    </div>` : `<div>
                        <i></i>
                        <h3>${bicicleta.nombres} ${bicicleta.apellidos}</h3>
                    </div>`}
                            
                </article>`
                
            });
            devolverAlerta()
            configurarAlertas()
        }       
        
    }
   
})

function devolverAlerta(){
  const alertButton = document.querySelectorAll('.devolver')
  alertButton.forEach(function(button) {
    button.addEventListener('click', function(){
      let bicicletaId = this.getAttribute('data-id');
      async function devolucion(id) {
        let response = await fetch(`/devolucionBicicleta/${id}`);
        if (response.ok) {
          let data = await response.json();
          Swal.fire({
            title: 'Devolucion de Bicicleta',
            icon: 'info',
            html: `
                  <p id="modal">Fecha de alquiler: ${data.datos.fecha_alquiler}</p>
                  <p id="modal">Horas de alquiler: ${data.datos.numero_horas}</p>
                  <p id="modal">Total inicial: $${data.datos.total_inicial}</p>
                  <p id="modal">Descuento: ${data.datos.descuento_aplica}%</p>
                  <p id="modal">Valor del descuento: $${data.datos.valor_descuento}</p>
                  <p id="modal">Total a pagar: $${data.datos.total_final}</p>     
            `,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'confirmar devolucion',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              async function confirmarpago() {
                let response = await fetch(`/registrarDevolucion`);
                if (response.ok) {
                  let data = await response.json();
                  if (data.titulo == "OK") {
                    Swal.fire(
                      'ENTREGA EXITOSA',
                      data.texto,
                      'success'
                    ).then(() => {
                      // Recargar la página al dar OK
                      window.location.reload();
                    });
                  }
                }else{

                }
              }
              confirmarpago()
            }

          })
          
        }
      }
      devolucion(bicicletaId)
      
    })

  })
}

function configurarAlertas() {
    const alertButtons = document.querySelectorAll('.alertButton');
    
    alertButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        // Primera alerta 
        Swal.fire({
          title: '¿Estás seguro de que quieres alquilar esta bicicleta?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, alquilar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          // Cuando le presionan sí
          if (result.isConfirmed) {
            let bicicletaId = this.getAttribute('data-id');
            
            async function alquilarBicicleta(id) {
              let response = await fetch(`/alquilarBicicleta/${id}`);
              
              if (response.ok) {
                let data = await response.json();
                
                if (data.titulo != 'OK') {
                  Swal.fire(
                    'Error!',
                    'No se pudo alquilar la bicicleta. Inténtalo de nuevo.',
                    'error'
                  );
                } else {
                  // Alerta de exitoso
                  Swal.fire(
                    'Alquilado!',
                    data.texto,
                    'success'
                  ).then(() => {
                    // Recargar la página al dar OK
                    window.location.reload();
                  });
                }
              } else {
                Swal.fire(
                  'Error!',
                  'No se pudo alquilar la bicicleta.',
                  'error'
                );
              }
            }
            alquilarBicicleta(bicicletaId);
          }
        });
      });
    });
  }



function configurarModalEditar() {
    let editarModal = document.getElementById('editarModal');
    
    
    editarModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget; 
        
        let marca = button.getAttribute('data-marca');
        let precio = button.getAttribute('data-precio');
        let color = button.getAttribute('data-color');
        let id = button.getAttribute('data-id');
        let regional = button.getAttribute('data-regional')

        console.log(regional);
        
        
        // Seleccionar los elementos del modal
        let modalMarca = editarModal.querySelector('#modal-marca');
        let modalPrecio = editarModal.querySelector('#modal-precio');
        let modalColor = editarModal.querySelector('#modal-color');
        let modalId = editarModal.querySelector('#modal-id');
        let modalRegional = editarModal.querySelector('#modal-regional')

        // Llenar los campos del modal con los datos actuales
        modalMarca.value = marca;
        modalPrecio.value = precio;
        modalColor.value = color; 
        modalId.value = id;
        modalRegional.value = regional
        
        
    });

    guardarCambiosBtn.addEventListener('click', function () {
        let modalMarca = editarModal.querySelector('#modal-marca').value;
        let modalPrecio = editarModal.querySelector('#modal-precio').value;
        let modalColor = editarModal.querySelector('#modal-color').value;
        let modalId = editarModal.querySelector('#modal-id').value;
        let modalRegional = editarModal.querySelector("#modal-regional").value
        console.log("Marca al guardar:", modalMarca);
        console.log("Precio al guardar:", modalPrecio);
        console.log("Color al guardar:", modalColor);
        console.log("ID al guardar:", modalId);
    
        let datos = {
            id: modalId,
            marca: modalMarca,
            precio: modalPrecio,
            color: modalColor,
            regional: modalRegional
        };
    
        async function registrar_bicicleta() {
            let response = await fetch('/actualizarBicicleta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });
    
            if (response.ok) {
                let data = await response.json();
                if (data.titulo === 'ERROR') {
                    // Manejar el error si es necesario
                } else if (data.titulo === 'OK') {
                    
                        
                    window.location.reload();
                
                }
            } else {
                console.error('Error al enviar la solicitud.');
            }
        }
    
        registrar_bicicleta();
    
    });


    

}