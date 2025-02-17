<header id="contenedor_menu">

    <figure id="logo_sena_movil">
        <img src="../img/logo.png" alt="logo-sena">
    </figure>

    <nav>
        <i id="notificacion_menu" class='bx bx-bell'></i>
        <i class='bx bx-menu' id="icon_menu"></i>
        <ul id="contenedor_lista">
            <div class="contenedor-logo">
                <img src="../img/logo.png" alt="" id="logo-sena">
            </div>

            
            <a href="principal.php" class="contenedor-opciones" id="principal">
                <i class='icon bx bx-home'></i>
                <h2 class="link">Principal</h2>
            </a>

            <?php
            $cargo = $_SESSION['inicio_sesion']['cargo'];
            
            
            if ($cargo === 'jefe') {
                echo '
                    <a href="entradas.php" class="contenedor-opciones" id="entradas">
                        <i class="icon bx bx-log-in"></i>
                        <h2 class="link">Entrada</h2>
                    </a>
                    <a href="salidas.php" class="contenedor-opciones" id="salidas">
                        <i class="icon bx bx-log-out"></i>
                        <h2 class="link">Salida</h2>
                    </a>
                ';
            }

            
            if (in_array($cargo, ['jefe', 'subdirector', 'coordinador'])) {
                echo '
                    <a href="agendas.php" class="contenedor-opciones" id="agendas">
                        <i class="icon bx bx-calendar"></i>
                        <h2 class="link">Agendas</h2>
                    </a>
                    <a href="usuarios.php" class="contenedor-opciones" id="usuarios">
                        <i class="icon bx bx-user"></i>
                        <h2 class="link">Usuarios</h2>
                    </a>
                ';
            }

            if ($cargo === 'jefe' || $cargo === 'subdirector') {
                echo '
                    <a href="informes_tablas.php" class="contenedor-opciones" id="informes">
                        <i class="icon bx bx-bar-chart-alt"></i>
                        <h2 class="link">Informes</h2>
                    </a>
                    <a href="vehiculos.php" class="contenedor-opciones" id="vehiculos">
                        <i class="icon bx bx-car"></i>
                        <h2 class="link">Vehiculos</h2>
                    </a>
                    <a href="novedades.php" class="contenedor-opciones" id="novedades">
                        <i class="icon bx bx-bell"></i>
                        <h2 class="link">Novedades</h2>
                    </a>
                ';
            }
            ?>

            

            
            <a href="#" class="contenedor-opciones" id="cerrar_menu">
                <i class='icon bx bx-x'></i>
                <h2 class="link">Cerrar</h2>
            </a>

            <a class="cerrar-sesion" id="cerrar_sesion" href="../../servicios/cerrar_session.php">
                <i class='icon bx bx-exit'></i>
            </a>
        </ul>
    </nav>

</header>