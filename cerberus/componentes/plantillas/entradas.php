<?php
session_start();

    if($_SESSION['inicio_sesion']['cargo'] != "jefe"){
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
    <link rel="stylesheet" href="../estilos_css/formato_movimiento.css">
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/entradas/funcion_modales_entrada.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">
    <title>Entradas | Cerberus</title>
</head>
<body>
    <main id="contenedor-ppl">
        <?php include 'menu.php'; ?> 

        <article id="contenedor_modal">
        </article>

        <section id="contenedor-info">
            <?php include 'notificacion.php';?>
            <article id="contenedor-fila-1">
                <h1 id="titulo">Entradas</h1>
                <?php include 'multitud.php';?>
            </article>
            <article id="contenedor-btns" class="contenedor">
                <button class="btn btn-entrada" id="btn_peatonal"> 
                    <figure>
                        <img src="../img/peaton.png" alt="">
                    </figure>
                    <h3>Peatonal</h3>
                </button>
                <button class="btn btn-entrada" id="btn_vehicular"> 
                    <figure>
                        <img src="../img/carro_blanco.png" alt="">
                    </figure>
                    <h3>Vehicular</h3>
                </button>
            </article>
            <article id="contenedor_tabla" class="contenedor">
                <h2 id="titulo_tabla">Ultimas Entradas</h2>
                <table id>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Doc.</th>
                            <th>Identificacion</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Tipo</th>
                            <th>Vehiculo</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo_tabla">
                        
                    </tbody>
                </table>
            </article>
            
        </section>
    </main>
</body>
</html>