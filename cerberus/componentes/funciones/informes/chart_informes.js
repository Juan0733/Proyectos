
document.addEventListener('DOMContentLoaded', () => {
    inicializar_fecha()
    
    dibujar_graficos()
    
   select_fecha()
   select_horario()
   select_tipo()
   select_movimiento()
    

})


// servicio para alimentar el grafico
async function servicio_info_grafica(tipo, horario, fecha, movimiento){
    try {
        // Hacemos la solicitud usando fetch
        const response = await fetch('../../servicios/info_informes_grafica.php?tipo=' + encodeURI(tipo) +'&horario='+ encodeURI(horario)+'&fecha='+ encodeURI(fecha) + '&movimiento=' + encodeURI(movimiento));
        if (!response.ok) throw new Error('Error en la solicitud');
        // Obtenemos el contenido como texto
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error('Hubo un error:', error);
    }
}

let chartInstance = null;

function dibujar_graficos() {
    let puerta= document.getElementById('tipo_entrada')
    let horario = document.getElementById('tipo_horario')
    let fecha = document.getElementById('fecha_unica')
    let movimiento = document.getElementById('tipo_movimiento')
    let rango1;
    let rango2;
    let rango3;
    let rango4;
    let rango5;

    let cant1;
    let cant2;
    let cant3;
    let cant4;
    let cant5;

    

    servicio_info_grafica(puerta.value, horario.value, fecha.value, movimiento.value).then(respuesta=>{
        
        
        if (respuesta.cod==200) {
            const estadisticas = respuesta.estadisticas; // Datos de los rangos
            const rangos = [rango1, rango2, rango3, rango4, rango5];
            const cant = [cant1, cant2, cant3, cant4, cant5]

            // Recorrer el arreglo y asignar los valores
            for (let i = 0; i < estadisticas.length; i++) {
                if (i < rangos.length) {
                    rangos[i] = estadisticas[i].rango; // Almacenar la cantidad de cada rango
                    cant[i] = estadisticas[i].cantidad
                }
            }
            [rango1, rango2, rango3, rango4, rango5] = rangos;
            [cant1, cant2, cant3, cant4, cant5] = cant;
            const labels = [rango1, rango2, rango3, rango4, rango5];
            const data = [cant1, cant2, cant3, cant4, cant5];
           

            actualizar_grafico(labels, data)
        }
        
    })
   
    

    
    function actualizar_grafico(label, cant) {
        if (chartInstance) {
            chartInstance.destroy(); // Destruimos la instancia anterior para evitar errores
        }
    
        const labels = [label[0], label[1], label[2], label[3], label[4]];
        const data = [cant[0], cant[1], cant[2], cant[3], cant[4]];
        const backgroundColors = ['#007bff', '#6f42c1', '#E2451E', '#28a745', '#ffc107'];
    
        const config = {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cantidad',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => darkenColor(color, 0.2)),
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true, // Mostrar leyenda
                        position: getLegendPosition(), // Posición dinámica de la leyenda
                        labels: {
                            color: '#414043',
                            font: {
                                size: 14
                            },
                            generateLabels: function (chart) {
                                const dataset = chart.data.datasets[0];
                                const colors = dataset.backgroundColor;
                                const cantidades = dataset.data;
                                return cantidades.map((cantidad, index) => ({
                                    text: `Cantidad: ${cantidad}`,
                                    fillStyle: colors[index],
                                    strokeStyle: colors[index]
                                }));
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#f8f9fa',
                        titleColor: '#414043',
                        bodyColor: '#414043',
                        borderColor: '#ccc',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#333',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#414043',
                            font: {
                                size: 12
                            },
                            stepSize: 100,
                            beginAtZero: true
                        }
                    }
                }
            }
        };
    
        const ctx = document.getElementById('barChart').getContext('2d');
        chartInstance = new Chart(ctx, config);
    
        // Función para obtener la posición de la leyenda según el tamaño de la pantalla
        function getLegendPosition() {
            const width = window.innerWidth;
            if (width <= 828) {
                return 'bottom'; // En pantallas pequeñas, la leyenda se coloca abajo
            }
            return 'right'; // En pantallas grandes, la leyenda se coloca a la derecha
        }
    
        // Función para oscurecer colores
        function darkenColor(color, factor) {
            const rgb = hexToRgb(color);
            const darker = rgb.map(c => Math.max(0, c - c * factor));
            return `rgb(${darker.join(',')})`;
        }
    
        // Conversión de HEX a RGB
        function hexToRgb(hex) {
            const bigint = parseInt(hex.slice(1), 16);
            const r = (bigint >> 16) & 255;
            const g = (bigint >> 8) & 255;
            const b = bigint & 255;
            return [r, g, b];
        }
    
        // Evento para redibujar el gráfico cuando se cambia el tamaño de la ventana
        window.addEventListener('resize', () => {
            chartInstance.options.plugins.legend.position = getLegendPosition();
            chartInstance.update();
        });
    }

   
}

// funcion para inicializar el valor de input fecha del momento
function inicializar_fecha() {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${year}-${month}-${day}`; // Construir en formato YYYY-MM-DD
    
    document.getElementById('fecha_unica').value = fechaActual;
}

function select_tipo() {
    
    let puerta_select = document.getElementById('tipo_entrada')
    puerta_select.addEventListener('change', ()=>{
        dibujar_graficos()
    }) 
}

function select_horario() {
    
    let horario_select = document.getElementById('tipo_horario')
    horario_select.addEventListener('change', ()=>{
        dibujar_graficos()
    }) 
}

function select_fecha() {
    
    let horario_select = document.getElementById('fecha_unica')
    horario_select.addEventListener('change', ()=>{
        dibujar_graficos()
    }) 
}

function select_movimiento() {
    
    let m_select = document.getElementById('tipo_movimiento')
    m_select.addEventListener('change', ()=>{
        dibujar_graficos()
    }) 
}