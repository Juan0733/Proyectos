document.addEventListener('DOMContentLoaded', ()=>{
    //click del menu
    document.getElementById('icon_menu').addEventListener('click', controlMenu)

    //click de la x
    document.getElementById('cerrar_menu').addEventListener('click', controlMenu)

    //cambio de tamaño de pantalla
    window.addEventListener('resize', ajustarMenuSegunPantalla);
    
    ajustarMenuSegunPantalla();
    estadoMenu()
})

//funcio para el abril menu el movil
function controlMenu() {
    
    let opcs_menu = document.getElementById('contenedor_lista');

    if (opcs_menu.style.display == "none" || opcs_menu.style.display =="") {
        opcs_menu.style.display = "flex"
    } else{
        opcs_menu.style.display = "none"
    }

}

function ajustarMenuSegunPantalla() {
    let opcsMenu = document.getElementById('contenedor_lista');
    let anchoPantalla = window.innerWidth;
    
    if (anchoPantalla >= 1023) {
        opcsMenu.style.display = "flex";
    } else {
        if (opcsMenu.style.display == "flex") {
            opcsMenu.style.display = "none";
        }
    }
}

//funcion para marcar en el menu que interfaz se encuentra
function estadoMenu(){
    //ontener la url
    let url = window.location.href;

    //includes sirve para encontrar una palabra el un string como la url y devuelve el true si la encuentra y false si no
    if (url.includes("principal")) {
        document.getElementById('principal').style.background = "rgba(255, 255, 255, 0.2)"
        document.getElementById('principal').style.backdropFilter = "blur(15px)"
        document.getElementById('principal').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
    }else{
        if (url.includes("entradas")) {
            document.getElementById('entradas').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('entradas').style.backdropFilter = "blur(15px)"
            document.getElementById('entradas').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        }else if (url.includes("salidas")) {
            document.getElementById('salidas').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('salidas').style.backdropFilter = "blur(15px)"
            document.getElementById('salidas').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        } else if (url.includes("agendas")) {
            document.getElementById('agendas').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('agendas').style.backdropFilter = "blur(15px)"
            document.getElementById('agendas').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        } else if (url.includes("informes")) {
            document.getElementById('informes').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('informes').style.backdropFilter = "blur(15px)"
            document.getElementById('informes').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        } else if (url.includes("usuarios")) {
            document.getElementById('usuarios').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('usuarios').style.backdropFilter = "blur(15px)"
            document.getElementById('usuarios').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        }else if (url.includes("vehiculos")) {
            document.getElementById('vehiculos').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('vehiculos').style.backdropFilter = "blur(15px)"
            document.getElementById('vehiculos').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        }else if (url.includes("novedades")) {
            document.getElementById('novedades').style.background = "rgba(255, 255, 255, 0.2)"
            document.getElementById('novedades').style.backdropFilter = "blur(15px)"
            document.getElementById('novedades').style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.2)"
        }
    }
}