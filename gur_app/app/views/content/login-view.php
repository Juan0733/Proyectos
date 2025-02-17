<main id="cuerpo_login">
    <section id="contenedor_login">
        <header id="cont_logo" name="cont_logo">
            <div id="logo">
                <img src="app\views\img\logo\gurapp-logo-full.svg" alt="">
            </div>
            <div id="cont_title" name="cont_title">
                <span id="title_logo" class="title-form">Login</span>
            </div>
        </header>


        <form id="forma_acceso" action="app/api/login-api.php" method="">
            <div class="cont-campo">
                <label class="label-campo">Usuario</label>
                <div class="content-input-login">
                    <input type="text" name="nombre_usuario" id="nombre_usuario" class="campo" placeholder="Harper Adams"
                        pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,15}$" required>
                    <ion-icon name="person"></ion-icon>
                </div>
            </div>
            <div class="cont-campo">
                <label class="label-campo">Contraseña</label>
                <div class="content-input-login">
                    <input type="password" name="pw_usuario" class="campo" placeholder="••••••••••••••••" required>
                    <ion-icon name="lock-closed"></ion-icon>
                </div>
            </div>
            <a href="home">ABRIR HOME</a>
            <button type="submit" class="btn-login">Iniciar sesión</button>
        </form>

        <div class="tabs">
            <button class="tab active" onclick="mostrarLogin()">
                <span>Login</span>
                <ion-icon name="checkmark-outline"></ion-icon>
            </button>
            <button class="tab" onclick="mostrarRegistro()">
                <span>Registro</span>
            </button>
        </div>
        <p class="forgot-password">
                Olvidé mi contraseña, <a href="#" class="recover-link">recuperar</a>
            </p>
    </section>

    <section id="contenedor_registro">
    <header id="cont_logo" name="cont_logo">
        <div id="logo">
            <img src="app\views\img\logo\gurapp-logo-full.svg" alt="">
        </div>
        <div id="cont_title" name="cont_title">
            <span id="title_logo" class="title-form">Registro</span>
        </div>
    </header>

    <form id="forma_registro">
        <div class="cont-campo">
            <label class="label-campo">Usuario</label>
            <div class="content-input-login">
                <input type="text" name="nombre_registro" id="nombre_registro" class="campo" placeholder="Harper Adams"
                    pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,15}$" required>
                <ion-icon name="person"></ion-icon>
            </div>
        </div>

        <div class="cont-campo">
            <label class="label-campo">Correo electrónico</label>
            <div class="content-input-login">
                <input type="email" name="email_registro" id="email_registro" class="campo" placeholder="ejemplo@correo.com"
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required>
                <ion-icon name="mail"></ion-icon>
            </div>
        </div>

        <div class="cont-campo">
            <label class="label-campo">Dirección</label>
            <div class="content-input-login">
                <input type="text" name="direccion_registro" id="direccion_registro" class="campo" placeholder="Calle 123, Ciudad"
                    pattern="^[A-Za-z0-9\s,.'-]{3,}$" required>
                <ion-icon name="location"></ion-icon>
            </div>
        </div>

        <div class="cont-campo">
            <label class="label-campo">Contraseña</label>
            <div class="content-input-login">
                <input type="password" name="contraseña_registro" id="contraseña_registro" class="campo" placeholder="••••••••••••••••"
                    pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$" required>
                <ion-icon name="lock-closed"></ion-icon>
            </div>
        </div>

        <button type="submit" class="btn-login">Registrar</button>
    </form>

    <div class="tabs">
        <button class="tab" onclick="mostrarLogin()">
            <span>Login</span>
        </button>
        <button class="tab active" onclick="mostrarRegistro()">
            <span>Registro</span>
            <ion-icon name="checkmark-outline"></ion-icon>
        </button>
    </div>
</section>


<section class="messages-positives">
    <div class="content">
        <h2>Tu tiempo es valioso</h2>
        <p>Con nuestra app, pedir y gestionar comida es rápido y sencillo.</p>
    </div>
</section>


<div class="bg_img">
    <div class="overlay"></div>
    <div class="slider">
        <div class="slide"><img src="app\views\img\login\bg_img1.jpg" alt="" class="active"></div>
        <div class="slide"><img src="app\views\img\login\bg_img2.jpg" alt=""></div>
        <div class="slide"><img src="app\views\img\login\bg_img3.jpg" alt=""></div>
    </div>
</div>
</main>

<script src="<?php echo $url_variable; ?>app/views/js/login-api.js"></script>
<script src="<?php echo $url_variable; ?>app/views/js/alternar-login.js"></script>
<script src="<?php echo $url_variable; ?>app/views/js/slider-img-login.js"></script>
