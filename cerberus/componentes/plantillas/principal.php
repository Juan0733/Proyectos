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
    <title>Principal | cerberus</title>
    <link rel="stylesheet" href="../estilos_css/formato_estandar.css">
    <link rel="stylesheet" href="../estilos_css/formato_menu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../estilos_css/formato_principal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../funciones/menu/funcion_menu.js"></script>
    <script src="../funciones/estandar/funcion_estandar.js" type="module"></script>
    <script src="../funciones/principal/funcion_principal.js"></script>
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
                <h1 id="titulo">Principal</h1>
                <?php include 'multitud.php';?>
            </article>

            <article id="contenedor-cards">
                <article id="contenedor-fila-2" class="contenedor-contador">
                    <article class="card card-person">
                        <h1>Aprendices</h1>
                        <div class="card-content">
                        <figure>
                            <img src="../img/persona.png" alt="persona">
                        </figure>  
                        <p id="cantidad_aprendices" class="cantidad"></p>
                        </div>
                    </article>

                    <article class="card card-person">
                        <h1>Visitantes</h1>
                        <div class="card-content">
                        <figure>
                            <img src="../img/persona.png" alt="persona">
                        </figure>
                        <p id="cantidad_visitantes" class="cantidad"></p>  
                        </div>
                    </article>

                    <article class="card card-person card-func">
                        <h1>Funcionarios</h1>
                        <div class="card-content">
                        <figure>
                            <img src="../img/persona.png" alt="persona">
                        </figure>  
                        <p id="cantidad_funcionarios" class="cantidad"></p>
                        </div>
                    </article>
                </article>

                <article id="contenedor-fila-3" class="contenedor-contador">
                    <article class="card card-veh">
                            <h1>Motos</h1>
                            <div class="card-content">
                            <figure>
                                <img src="../img/moto.png" alt="persona">
                            </figure> 
                            <p id="cantidad_motos" class="cantidad"></p> 
                            </div>
                        </article>

                        <article class= "card card-veh">
                            <h1>Carros</h1>
                            <div class="card-content">
                            <figure>
                                <img src="../img/carro.png" alt="persona">
                            </figure>  
                            <p id="cantidad_carros" class="cantidad"></p>
                            </div>
                        </article>

                </article>
            </article>
            
            
        </section>
    </main>
</body>
</html>