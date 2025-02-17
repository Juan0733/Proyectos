document.addEventListener('DOMContentLoaded', ()=>{
    let filtro_mes = document.getElementById('filtro_mes')
    let filtro_anio = document.getElementById('filtro_anio')
    let contenedorPrincipal = document.getElementById('contenedor_principal')

    function validarFiltros(){
        let patternMes = /^[0-9]{1,2}$/.test(filtro_mes.value)
        let patternAnio = /^[0-9]{4}$/.test(filtro_anio.value)
        if(patternMes && patternAnio){
            consultar_informes(filtro_mes.value, filtro_anio.value)
        }
    }

    filtro_mes.addEventListener('change', validarFiltros)

    filtro_anio.addEventListener('change', validarFiltros)

    async function consultar_informes(mes, anio) {
        let response = await fetch(`/consultarInformes/${mes}/${anio}`)
        let data = await response.json()
        
       contenedorPrincipal.innerHTML = ''

        if(data.titulo == 'ERROR'){
            contenedorPrincipal.innerHTML = `<h1 id="msg">${data.texto}</h1>`
        }else if(data.titulo == 'OK'){
            contenedorPrincipal.innerHTML = `

            <div class="container">
                <div class="card shadow">
                    <div class="card-body" id="contenedor_tabla">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha Alquiler</th>
                                    <th>Fecha Devolución</th>
                                    <th>Usuario</th>
                                    <th>Bicicleta</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="body_tabla">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>`

            const tbody = document.getElementById('body_tabla');

            data.informes.items.forEach(item => {
                

                // Insertar una nueva fila al final del tbody
                let nuevaFila = tbody.insertRow();

                // Insertar nuevas celdas en la fila
                let fechaAlquiler = nuevaFila.insertCell(0);
                let fechaDevolucion = nuevaFila.insertCell(1);
                let usuario = nuevaFila.insertCell(2);
                let bicicleta = nuevaFila.insertCell(3);
                let total = nuevaFila.insertCell(4);

                // Añadir contenido a las celdas
                fechaAlquiler.textContent = item.fecha_alquiler;
                fechaDevolucion.textContent = item.fecha_devolucion;
                usuario.textContent = item.nombres + " " + item.apellidos;
                bicicleta.textContent = item.marca;
                total.textContent = "$" + item.total;
                
            });

            document.getElementById('contenedor_tabla').innerHTML += `<p><strong>Total General:</strong> $${data.informes.total_general}</p>`

            

        }
    }
})