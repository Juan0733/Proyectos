<?php
    session_start();    
    if($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "coordinador"){
        header("Location: /GRUPO-A-CERBERUS/cerberus/index.html");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link rel="stylesheet" href="../estilos_css/formato_agendas.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link id="css_modal" rel="stylesheet" href="../estilos_css/formato_registro_agendas.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/agenda/funcion_agendas.js" type="module"></script>
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">
    <title>Agendas | cerberus</title>
</head>
<body>
    <main id="contenedor-ppl">

    <article id="contenedor_modal_principal">
    </article>

    <article id="contenedor_modal">
    </article>

        <?php include 'menu.php'; ?> 
        
        <section id="contenedor-info">
            <?php include 'notificacion.php';?>
            <article id="contenedor-fila-1">
                <h1 id="titulo">Agendas</h1>
                <?php include 'multitud.php';?>
            </article>

            
            <article id="contenedor-filtros">
                
                <input type="date"  name="fecha" id="fecha" class="filtro" >
                
                <div id="cont_busqueda_add">
                    <div id="cont_busqueda_agenda" class="barra">
                        <i class='bx bx-search-alt-2'></i>
                        <input type="text" name="busqueda_agenda" id="busqueda_agenda"  pattern="[0-9]{6,15}"  maxlength="15" minlength="6" placeholder="Buscar agenda" title="Debe ingresar entre 6 y 15 números">
                    </div>
                    <?php 
                        if($_SESSION['inicio_sesion']['cargo'] == 'coordinador' || $_SESSION['inicio_sesion']['cargo'] == 'subdirector'){
                            echo "<div id='agregar_agenda'>
                                    <i class='bx bx-calendar-plus'></i>
                                </div>";
                        }
                     ?>
                    
                </div>
            </article>

            <article id="contenedor-cards">
            </article>
            
        </section>
    </main>
</body>
</html>
