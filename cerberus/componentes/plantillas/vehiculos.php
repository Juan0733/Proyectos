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
    <title>vehiculos | cerberus</title>
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link rel="stylesheet" href="../estilos_css/formato_vehiculos.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../estilos_css/variables_globales.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/vehiculos/funcion_vehiculos.js"></script>
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
                <h1 id="titulo">Vehiculos</h1>
                <?php include 'multitud.php';?>
            </article>

            

            <article id="contenedor_busqueda_veh_fil">
                <select name="filtro_veh" id="filtro_veh">
                    <option value="moto">moto</option>
                    <option value="carro">carro</option>
                </select>
                <div id="cont_busqueda_veh">
                    <i class='bx bx-search-alt-2'></i>       
                    <input type="text" name="busqueda_veh" id="busqueda_veh" pattern="[A-Z0-9ÁÉÍÓÚÑ\s]{2,64}" maxlength="6" minlength="5" placeholder="Buscar">
                </div>
            </article>

            <article id="contenedor_tabla">
                
                <table >
                    <thead>
                        <tr>
                            <th>placa</th>
                            <th>tipo</th>
                            <th>acciones</th>
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