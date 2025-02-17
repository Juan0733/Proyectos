let paginaActual = 1

getData(paginaActual)

document.getElementById("filtro").addEventListener("keyup", function() {
    getData(1)    
}, false)
document.getElementById("num_registros").addEventListener("change", function() {
    getData(paginaActual)    
}, false)




function getData(pagina) {
    let input = document.getElementById("filtro").value
    let num_registros = document.getElementById("num_registros").value


    let cuerpo = document.getElementById("contenedor_tabla")
    let url = document.getElementById('url').value
    let formData = new FormData()
    
    formData.append('filtro', input)
    formData.append('registros', num_registros)
    formData.append('pagina', pagina)
    
    if (window.innerWidth > 780) {
        formData.append('tipoListado', 'tabla')
    }else{
        formData.append('tipoListado', 'card')
    }
    
    

    fetch(url,{
        method:"POST",
        body: formData
    }).then(response => {
        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
    })
    .then(data => {
        console.log(data)
        cuerpo.innerHTML = data.data
    }).catch(err => console.log(err))

    
}