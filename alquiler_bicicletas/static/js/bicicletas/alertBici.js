document.addEventListener('DOMContentLoaded', function() {
  
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

  configurarAlertas()
});