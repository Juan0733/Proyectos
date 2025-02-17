<form action="" method="post" id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1 id="titulo_modal"></h1>
        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
    </article>

    <label for="numero_documento">Número Identificacion:</label>
    <input type="text" id="numero_documento" name="numero_documento" pattern="[A-Z0-9]{6,}" title="Solo se aceptan numeros y letras." minlenght="6" required>

    <article class="contenedor_btn">
        <button type="submit" class="btn" id="btn_registrar">REGISTRAR</button>
    </article>
</form>