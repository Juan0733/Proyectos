<form action="" method="post" id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1>Registrar Novedad</h1>
        <i id="cerrar" class=' cerrar_modal bx bx-x'></i>
    </article>

    <article id="contenedor_inputs">

        <label for="tipo_novedad">Tipo Novedad:</label>
        <input type="text" id="tipo_novedad" name="tipo_novedad" value="Vehiculo prestado"  required disabled>

        <label for="numero_documento">Número Documento:</label>
        <input type="text" id="numero_documento" name="numero_documento" required disabled>
        
        <label for="numero_placa">Número Placa:</label>
        <input type="text" id="numero_placa" name="numero_placa" required disabled>

        <label for="descripcion">Descripcion:</label>
        <input type="text" id="descripcion" name="descripcion" pattern="[A-Za-z\s]{5,}"
        minlength="5" title="Solo se aceptan letras." required tabindex="1">
    </article>

    <div class="contenedor_btn">
        <button type="button" class="btn" id="btn_registrar_novedad_vehiculo">REGISTRAR</button>
    </div>
</form>