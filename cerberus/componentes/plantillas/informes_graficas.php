<?php
    session_start();    
    if($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "subdirector"){
        header("Location: /GRUPO-A-CERBERUS/cerberus/index.html");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes | cerberus</title>
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link rel="stylesheet" href="../estilos_css/formato_informes_graficas.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/informes/redir_informes.js"></script>
    <script src="../funciones/informes/chart_informes.js"></script>
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">
</head>
<body>
    <main id="contenedor-ppl">
        <?php include 'menu.php'; ?> 
        <article id="contenedor_modal">
        </article>

        <section id="contenedor-info">
            <?php include 'notificacion.php';?>
            <article id="contenedor-fila-1">
                <h1 id="titulo">Informes</h1>
                <?php include 'multitud.php';?>
            </article>

            <article id="contenedor-fila-2">
                <div id="contenedor-botones">
                    <button id="btn-tabla">Tabla</button>
                    <button id="btn-grafica" class="activo">Gráfica</button>
                </div>
                <div id="contenedor-fecha">
                        <input type="date" name="fecha_unica" id="fecha_unica" placeholder="Unica">
                </div>
            </article>

            <article id="contenedor-fila-3">
                <div id="contenedor-opciones">
                    <div class="tipo-opcion-container">
                        <select name="tipo_movimiento" id="tipo_movimiento">
                            <option value="ENTRADA">Entradas</option>
                            <option value="SALIDA">Salidas</option>
                        </select>
                    </div>
                    <div class="tipo-opcion-container">
                        <select name="tipo_entrada" id="tipo_entrada">
                            <option value="peatonal">Peatonal</option>
                            <option value="principal">Vehicular principal</option>
                            <option value="ganaderia">Vehicular ganaderia</option>
                        </select>
                    </div>

                    <div class="tipo-opcion-container">
                        <select name="tipo_horario" id="tipo_horario">
                            <option value="MAÑANA">Mañana</option>
                            <option value="TARDE">Tarde</option>
                            <option value="NOCHE">Noche</option>
                        </select>
                    </div>
            </article>
            <div class="chart-container">
                <canvas id="barChart" ></canvas>
            </div>
        </section>
    </main>
</body>
</html>