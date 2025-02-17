const regiones = {
    "Amazonas": [-69.935, -3.433],
    "Antioquia": [-75.563, 6.255],
    "Arauca": [-70.759, 7.083],
    "Atlántico": [-74.796, 10.986],
    "Bolívar": [-75.563, 8.102],
    "Boyacá": [-73.123, 5.533],
    "Caldas": [-75.550, 5.066],
    "Caquetá": [-72.644, 1.616],
    "Casanare": [-72.644, 5.533],
    "Cauca": [-76.621, 2.466],
    "Cesar": [-73.625, 10.483],
    "Chocó": [-77.027, 5.677],
    "Córdoba": [-75.877, 8.754],
    "Cundinamarca": [-73.925, 4.598],
    "Guainía": [-67.923, 3.775],
    "Guaviare": [-72.639, 2.826],
    "Huila": [-75.287, 2.986],
    "La Guajira": [-72.886, 11.241],
    "Magdalena": [-74.217, 11.241],
    "Meta": [-73.633, 4.533],
    "Nariño": [-77.542, 1.216],
    "Norte de Santander": [-72.750, 7.133],
    "Putumayo": [-76.616, 0.646],
    "Quindío": [-75.563, 4.566],
    "Risaralda": [-75.616, 4.816],
    "San Andrés y Providencia": [-75.695, 12.536],
    "Santander": [-73.123, 6.533],
    "Sucre": [-74.597, 9.233],
    "Tolima": [-74.783, 4.083],
    "Valle del Cauca": [-76.528, 3.420],
    "Vaupés": [-70.250, 1.000],
    "Vichada": [-67.000, 4.500]
  };
  
function CargarMapa(){
    map = new ol.Map({
        layers: [ new ol.layer.Tile({
            source: new ol.source.XYZ({
                url: 'https://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}'
            })
        
        })],
        target: 'map',
        view: new ol.View({
            center: ol.proj.fromLonLat([-74.2973, 4.5709]),
            zoom: 5.35,
            minZoom: 5.35 
        })
    })

    mostrar_bicicletas()


    async function mostrar_bicicletas() {
        let response = await fetch('/bicicletasRegion')
        if (response.ok){
            let data = await response.json()
            if (data.titulo == 'OK'){
                data.datos.forEach(bicicleta => {
                    const marcador = new ol.layer.Vector({
                        source: new ol.source.Vector({
                            features: [
                                new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.fromLonLat(regiones[bicicleta.region]))
                                })
                            ]
                        }),
                        style: new ol.style.Style({
                            image: new ol.style.Icon({
                                src: 'static/img/bicicleta.png',
                                scale: 0.08
                                
                            })
                        })
                   })
                
                   map.addLayer(marcador)
                });

            }
        }
    }

    
   
   
}

document.addEventListener('DOMContentLoaded', CargarMapa)