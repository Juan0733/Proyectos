<?php
    session_start();    
    if($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "coordinador"){
        header("Location: /GRUPO-A-CERBERUS/cerberus/index.html");
    }


    $cargo = isset($_SESSION['inicio_sesion']['cargo']) ? $_SESSION['inicio_sesion']['cargo'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link rel="stylesheet" href="../estilos_css/formato_usuarios.css">
    <link rel="stylesheet" href="../estilos_css/variables_globales.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/usuarios/funcion_agregar_u.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">
    <title>usuarios | cerberus</title>
</head>
<body>
    <main id="contenedor-ppl">

        <input type="hidden" id="userCargo" value="<?php echo $cargo; ?>">
        <?php include 'menu.php'; ?> 

        <article id="contenedor_modal">
        </article>

        <section id="contenedor-info">
            <?php include 'notificacion.php';?>
            <article id="contenedor-fila-1">
                <h1 id="titulo">Usuarios</h1>
                <?php include 'multitud.php';?>
            </article>

            <article id="contenedor_usuarios">
                <div id="btn_usuarios">
                    <button id="aprendices" type="submit">Aprendices</button>
                    <button id="visitantes" type="submit">Visitantes</button>
                    <button id="funcionarios" type="submit">Funcionarios</button>
                    <button id="vigilantes" type="submit">Vigilantes</button>
                </div>

                <select name="filtro_usuarios" id="filtro_usuarios">
                    <option value="aprendices">aprendices</option>
                    <option value="visitantes">visitantes</option>
                    <option value="funcionarios">funcionarios</option>
                    <option value="Vigilantes">vigilantes</option>
                </select>

                <div id="cont_busqueda_add">
                    <div id="cont_busqueda_usuario">
                        <i class='bx bx-search-alt-2'></i>
                        
                        <input type="text" name="busqueda_usuarios" id="busqueda_usuarios" pattern="[A-Z0-9ÁÉÍÓÚÑ\s]{6,15}" maxlength="15" minlength="6" placeholder="Buscar cedula">
                    </div>

                    <div id="agregar_usuario">
                        <i class='bx bxs-user-plus'></i>
                    </div>
                    
                </div>
            </article>

            <article id="contenedor_tabla">
                
                <table id="tabla_usuarios">
                    <thead>
                        <tr>
                            <th>Doc.</th>
                            <th>Identificacion</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>tipo</th>
                            <th>Ubicacion</th>
                            <th>Acciones</th>
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