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
    <link rel="stylesheet" href="../estilos_css/formato_informes_tablas.css">
    <link rel="stylesheet" href="../estilos_css/variables_globales.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/informes/redir_informes.js"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/informes/funcion_informe_tabla.js" type="module"></script>
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
                    <button id="btn-tabla" class="activo">Tabla</button>
                    <button id="btn-grafica">Gráfica</button>
                </div>
                <div id="contenedor-buscador">
                <i class='bx bx-search-alt-2'></i>
                    <form action="" method="GET">
                        <input type="text" name="buscar_informe" id="buscar_informe" pattern="[A-Z0-9ÁÉÍÓÚÑ\s]{2,64}" maxlength="64" minlength="2" placeholder="Buscar">
                    </form>
                </div>
            </article>

            <article id="contenedor-fila-3">
                <div id="contenedor-opciones">
                    <div id="tipo-opcion-container">
                        <select name="tipos_entsal" id="tipos_entsal">
                            
                            <option value="ENTRADA">Entrada</option>
                            <option value="SALIDA">Salida</option>
                        </select>
                    </div>

                    <div id="puerta-opcion-container">
                        <select name="puerta_entsal" id="puerta_entsal">
                            <option value="peatonal">Peatonal</option>
                            <option value="principal">Principal</option>
                            <option value="ganederia">Ganaderia</option>
                        </select>
                    </div>
                </div>

                <div id="contenedor-fechas">
                <label for="desde_calendario">Desde:</label>
                    <div id="fecha-desde-container">
                        <input type="date" name="desde_calendario" id="desde_calendario" placeholder="Desde">
                    </div>
                    <label for="hasta_calendario">Hasta:</label>
                    <div id="fecha-hasta-container">
                        <input type="date" name="hasta_calendario" id="hasta_calendario" placeholder="Hasta">
                    </div>
                </div>
            </article>

            <article id="contenedor_tabla_informes">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Movimiento</th>
                            <th>Usuario</th>
                            <th>Vehículo</th>
                            <th>Relación</th>
                            <th>Puerta</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo_tabla">
                        
                    </tbody>
                </table>

                <div id="informes-contenedor-cards">
                    
                    
                </div>
            </article>
        </section>
    </main>
    <script src="../funciones/limitar_fechas.js"></script>
</body>
</html>