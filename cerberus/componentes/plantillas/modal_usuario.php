
<?php
    session_start();
    $cargo_usuario = $_SESSION['inicio_sesion']['cargo'];
?>

<form action="" method="post" id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1 id="titulo_modal">Registrar Usuario</h1>
        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
    </article>

    <label for="tipo_usuario">Tipo de Usuario:</label>
    <select name="tipo_usuario_s" id="tipo_usuario_s" required>
        <?php
        
        if ($cargo_usuario == "jefe") {
            echo '<option value="visitante">Visitante</option>';
            echo '<option value="vigilante">Vigilante</option>';
        } elseif ($cargo_usuario == "coordinador") {
            echo '<option value="funcionario">Funcionario</option>';
            echo '<option value="visitante">Visitante</option>';
            echo '<option value="aprendiz">Aprendiz</option>';
        } else {
            echo '<option value="visitante">Visitante</option>';
            echo '<option value="aprendiz">Aprendiz</option>';
            echo '<option value="vigilante">Vigilante</option>';
            echo '<option value="funcionario">Funcionario</option>';
        } 
        ?>
    </select>

    <article class="contenedor_btn">
        <button type="button" class="btn" id="continuar">CONTINUAR</button>
    </article>
</form>