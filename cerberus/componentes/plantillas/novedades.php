<?php
    session_start();    
    if($_SESSION['inicio_sesion']['cargo'] != "jefe"  && $_SESSION['inicio_sesion']['cargo'] != "subdirector"){
        header("Location: /GRUPO-A-CERBERUS/cerberus/index.html");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../estilos_css/formato_novedades.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/novedades/funcion_novedades.js" type="module"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">

    <title>Novedades | Cerberus</title>
</head>
<body>
    <main id="contenedor-ppl">
        <?php include 'menu.php'; ?> 
        <article id="contenedor_modal">
        </article>

        <section id="contenedor-info">
            <?php include 'notificacion.php';?>
            <article id="contenedor-fila-1">
                <h1 id="titulo">Novedades</h1>
                <?php include 'multitud.php';?>
            </article>
            
            <article id="contenedor-filtros">
                <select name="movimiento" id="movimiento" class="filtro">
                    <option value="EN">Entradas no registradas</option>
                    <option value="SA">Salidas no registradas</option>
                </select>

                <input type="date" name="fecha" id="fecha" class="filtro" >

                
                <div id="cont_busqueda_usuario" class="barra">
                    <i class='bx bx-search-alt-2'></i>
                        
                    <input type="text" name="busqueda_novedad" id="busqueda_novedad"  pattern="[0-9]{6,15}"  maxlength="15" minlength="6" placeholder="Buscar novedad" title="Debe ingresar entre 6 y 15 números">
                </div>

                 
            </article>

            <article id="contenedor-cards">
                
            </article>

            

        </section>
    </main>
</body>
</html>