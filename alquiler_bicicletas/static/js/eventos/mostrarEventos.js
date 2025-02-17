document.addEventListener('DOMContentLoaded', ()=>{
    let filtro_evento = document.getElementById('filtro_evento')
    let contenedor = document.getElementById('card-container')

    filtro_evento.addEventListener('change', ()=>{
        consultar_eventos(filtro_evento.value)
    })
    
    alertParticipar()
    editarEvento()
    alertEliminar()
    async function consultar_eventos(filtro) {
        let response = await fetch(`consultarEventos/${filtro}`)

        if (response.ok){
            let data = await response.json()
            contenedor.innerHTML = ''
            if (data.titulo == 'ERROR'){
                contenedor.innerHTML = `<h1 class="msg">${data.texto}</h1>`
            }else{
                data.eventos.forEach(evento=>{
                    contenedor.innerHTML += `
                    <article id="card_evento">
                          
                        <img src="uploads/${evento.foto}">
                        <h3 id="titulo">${evento.nombre}</h3>
                        <h3 class="h3">Descripcion: <span class="sp">${evento.descripcion}</span></h3>
                        <h3 class="h3">Fecha: <span class="sp">${evento.fecha}</span></h3>
                        <h3 class="h3">Lugar: <span class="sp">${evento.lugar}</span></h3>
                        ${data.rol == 'Usuario' ? filtro == 'activos' ?  '<button class="participar">PARTICIPAR</button>' : filtro == 'participando' ? '<h3 class="participando">Participando</h3>' : '' : filtro == 'activos' ? 
                        `<div class="icons">
                            <i class="fa-regular fa-pen-to-square ion-edit editar" 
                                data-bs-toggle="modal" data-bs-target="#editar"
                                role="button" id="editarr" data-evento="${evento.pk_evento}">
                            </i>
                            
                            <i class="fa-solid fa-trash icon-delete" data-id="${evento.pk_evento}"></i>
                            
                        </div>` : ''}
                    </article>`
                })
              editarEvento()
              alertEliminar()
            }
        }
    }
})






function alertParticipar() {
    const alertButtons = document.querySelectorAll('.participar');
    
    alertButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        // Primera alerta 
        Swal.fire({
          title: '¿Quieres participar el este gran evento?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, participar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          // Cuando le presionan sí
          if (result.isConfirmed) {
            
            
            let eventoId = this.getAttribute('data-id');
            
            async function participarEvento(id) {
              let response = await fetch(`/registrarParticipante/${id}`);
              
              if (response.ok) {
                let data = await response.json();
                
                if (data.titulo != 'OK') {
                  Swal.fire(
                    'Error!',
                    'No se pudo completar la participacion. Inténtalo de nuevo.',
                    'error'
                  );
                } else {
                  // Alerta de exitoso
                  Swal.fire(
                    'Participando!',
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
                  'No se pudo completar la participacion.',
                  'error'
                );
              }
            }
            participarEvento(eventoId);
          }
        });
      });
    });
}

function editarEvento() {

  
  let idEvento;
  let form = document.querySelector('#editar_evento')
  let buttonEditar = document.querySelectorAll('.editar');
 
    
    buttonEditar.forEach(function(button) {
      button.addEventListener('click', function(){
        idEvento = this.getAttribute('data-evento')
        
        
        async function datosEvento(id) {
          let response = await fetch(`/consultarEvento/${id}`);
          if (response.ok) {
            let data = await response.json();
            console.log(data);
            
            if (data.titulo == "OK") {
              document.getElementById('nombre-editar').value = data.datos[0].nombre;
              document.getElementById('descripcion-editar').value = data.datos[0].descripcion;
              document.getElementById('lugar-editar').value = data.datos[0].lugar         
              document.getElementById('fecha-editar').value = data.datos[0].fecha
            }
            
          }else{
            window.location.reload
          } 
        }
        datosEvento(idEvento)
        
      })

    })

    const validarDatos = (event) =>{
      event.preventDefault();
      
      const {nombre, descripcion, fecha, lugar} = event.target

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

      

      if (validoForm){
          const formData = {
            nombre: nombre.value,
            descripcion: descripcion.value,
            lugar: lugar.value,
            fecha: fecha_formateada,
            id: idEvento  
          };
          
          modificar_evento(formData)
      }

    }

    async function modificar_evento(datos) {
      let response = await fetch('/modificarEvento', {
          headers: {
            'Content-Type': 'application/json'
          },
          method: 'POST',
          body: JSON.stringify(datos)
      })
      
      if(response.ok){
          let data = await response.json()
          if(data.titulo == 'ERROR'){
              document.getElementById('error_evento').innerText = data.texto
          }else if(data.titulo == "OK"){
            Swal.fire(
              'EDITADO!',
              data.texto,
              'success'
            ).then(() => {
              // Recargar la página al dar OK
              window.location.reload();
            });
          }

      }else{
          console.error('Error al enviar el codigo.')
      }
    }

    form.addEventListener('submit', validarDatos)
    

    
  
  
}

// function convertirFecha(fechaString) {
//   // Separa la fecha y la hora
//   const [fecha, hora] = fechaString.split(' ');
//   const [dia, mes, anio] = fecha.split('-'); 

//   // Convertimos la hora de formato 12 horas a 24 horas
//   let [horaCompleta, amPm] = hora.split(' ');
//   let [horas, minutos] = horaCompleta.split(':');

  
//   if (amPm === 'PM' && horas !== '12') {
//     horas = parseInt(horas) + 12;  
//   } else if (amPm === 'AM' && horas === '12') {
//     horas = '00';  
//   }

  
//   return `${anio}-${mes}-${dia}T${horas.padStart(2, '0')}:${minutos.padStart(2, '0')}`;
// }


function alertEliminar() {
  const alertButtons = document.querySelectorAll('.icon-delete');
  
  alertButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      // Primera alerta 
      Swal.fire({
        title: '¿Estás seguro de que quieres eliminar este evento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        // Cuando le presionan sí
        if (result.isConfirmed) {
          let eventoId = this.getAttribute('data-id');
          
          async function eliminar(id) {
            let response = await fetch(`/eliminarEvento/${id}`);
            
            if (response.ok) {
              let data = await response.json();
              
              if (data.titulo != 'OK') {
                Swal.fire(
                  'Error!',
                  data.texto,
                  'error'
                );
              } else {
                // Alerta de exitoso
                Swal.fire(
                  'eliminado!',
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
                'No se pudo eliminar el evento.',
                'error'
              );
            }
          }
          eliminar(eventoId);
        }
      });
    });
  });
}