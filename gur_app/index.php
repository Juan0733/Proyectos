<?php
    require_once "./config/app.php";
    require_once "./autoload.php";

    /* --------- Inicio sesion ---------- */
    require_once "./app/views/inc/session_start.php";
    /* echo session_name(); */

    if (isset($_GET['views'])) {
        $url = explode("/", $_GET['views']);
    }else{
        $url = ["inicio-ppal"];
    }

    if ($url[0] == 'login') {
        $url_variable = '';
    }else{
        if (count($url) > 2) {
            $url_variable = '../../';
        }elseif(count($url) < 2){
            $url_variable = '';
        }else {
            $url_variable = '../';
        }
    }


    use app\controllers\viewsController;

    $viewsController = new viewsController();

    $vista = $viewsController->obtenerVistasControlador($url[0]);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    
    <?php require_once "app/views/inc/head.php"  ?>
</head>

<body>
    <?php

        if(isset($vista['code'])){
            if ($vista['code'] == 835) {
                session_destroy();
                include "app/views/content/".$vista['vista']."-view.php";
            }
        }elseif ($vista == "404") {
            include "app/views/content/".$vista."-view.php";
        }else{
            include "app/views/inc/header-top.php"; 
        ?>
    <main id="cuerpo" class="cuerpo-contenedor">
        
        <?php
            include "app/views/inc/header.php";
        ?>

        <section class="contenedor-pagina" id="contenedor_pagina_principal">
            <?php
                include $vista;
            ?>
        </section>
    </main>
    <?php
        }
        include "./app/views/inc/scripts.php";
    ?>
</body>



</html>

