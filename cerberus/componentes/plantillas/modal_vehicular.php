<form action="" method="post" id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1 id="titulo_modal"></h1>
        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
    </article>

    <article id="grupo_1">
        <label for="numero_placa">Número Placa:</label>
        <input class="campos" type="text" id="numero_placa" name="numero_placa" pattern="[A-Za-z0-9]{5,6}" title="Solo se aceptan numeros y letras." maxlength="6" minlenght="5" required>

        <label for="tipo_puerta">Tipo Puerta:</label>
        <select class="campos"  name="tipo_puerta" id="tipo_puerta" tabindex="2" required>
            <option value="" disabled selected></option>
            <option value="ganaderia">Ganaderia</option>
            <option value="principal">Principal</option>
        </select>

        <div class="contenedor_btn">
            <button type="button" class="btn" id="btn_continuar">CONTINUAR</button>
        </div>
    </article>

    <article id="grupo_2">

        <div id="fila_1">
            <div id="bloque_1">
                <label for="documento_propietario">Documento Propietario:</label>
                <input class="campos" type="text" list="propietarios" id="documento_propietario" name="documento_propietario" pattern="[A-Z0-9]{6,}" title="Solo se aceptan numeros y letras."  minlenght="6" required>
                <datalist id="propietarios">
                </datalist>
            </div>
           

            <div id="contenedor_input_btn">


                <div>
                    <label for="documento_pasajero">Documento Pasajero:</label>
                    <input class="campos" type="text" id="documento_pasajero" name="documento_pasajero" pattern="[A-Z0-9]{6,}" title="Solo se aceptan numeros y letras."  minlenght="6" required>
                </div>
                
                <button type="button" id="btn_agregar" class="btn">+</button>

            </div>

        </div>
        
        <div id="fila_2">
            <div class="contenedor_btn">
                <button type="button" class="btn" id="btn_registrar_movimiento">REGISTRAR</button>
            </div>
            <div id="contenedor_pasajeros">

            </div>

        </div>


        
    </article>
    
</form>