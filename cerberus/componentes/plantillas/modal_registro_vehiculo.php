<form action="" method="post"  id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1>Registrar Vehiculo</h1>
        <i id="cerrar" class=' cerrar_modal bx bx-x'></i>
    </article>

    <article id="contenedor_inputs">
        <label for="numero_documento">Documento Propietario:</label>
        <input type="text" class="campos" id="numero_documento" name="numero_documento" pattern="[A-Z0-9]{6,}" title="Solo se aceptan numeros y letras." minlenght="6" tabindex="2" required>
        
        <label for="numero_placa">Número Placa:</label>
        <input type="text" class="campos"  id="numero_placa" name="numero_placa" pattern="[A-Za-z0-9]{5,6}" title="Solo se aceptan numeros y letras." maxlength="6" minlenght="5" required>


        <label for="tipo_vehiculo">Tipo Vehiculo:</label>
        <select class="campos"  name="tipo_vehiculo" id="tipo_vehiculo" tabindex="3" required >
            <option value="" disabled selected></option>
            <option value="carro">Carro</option>
            <option value="moto">Moto</option>
        </select>
    </article>

    <div class="contenedor_btn">
            <button id="btn_registrar_vehiculo" type="button" class="btn">REGISTRAR</button>
    </div>

</form>