<form action="" method="post" id="forma_registro" name="forma_registro">
    
    <article id="contenedor_titulo_icon">
        <h1>Registrar Aprendiz</h1>
        <i id="cerrar" class='cerrar_modal bx bx-x'></i>
    </article>

    <article id="contenedor_inputs">
        <div class="fila_input">
            <select name="tipo_documento" id="tipo_documento" class="select_r" tabindex="1" required>
                <option value="CC">CC</option>
                <option value="TI">TI</option>
                <option value="CE">CE</option>
                <option value="PEP">PEP</option>
                <option value="PAS">PAS</option>
            </select>
            <div class="bloque-input">
                <label for="numero_documento">Número Documento:</label>
                <input class="input" type="text" id="numero_documento" name="numero_documento" pattern="[A-Z0-9]{6,15}" title="Solo se aceptan números y letras." maxlength="15" minlength="6" tabindex="2" required placeholder="ej: 1114339035">
            </div>
            
        </div>

        <div class="fila_input">
            <div class="bloque-input">
                <label for="nombres">Nombres:</label>
                <input class="input" type="text" id="nombres" name="nombres" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" title="Solo se aceptan letras." tabindex="3" required placeholder="ej: juan">
            </div>
            
            <div class="bloque-input">
                <label for="apellidos">Apellidos:</label>
                <input class="input" type="text" id="apellidos" name="apellidos" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" title="Solo se aceptan letras." tabindex="4" required placeholder="ej: soto">

            </div>
        </div>
    
        <div class="fila_input">

            <div class="bloque-input correo">
                <label for="correo">Correo Electrónico:</label>
                <input class="input" type="email" id="correo" name="correo" maxlength="64" minlength="2" title="Ingrese un correo electrónico válido" required tabindex="6" placeholder="ej: juansoto@gmail.com">
            </div>
            
            <div class="bloque-input movil">
                <label for="telefono">Móvil:</label>
                <input class="input" type="text" id="telefono" name="telefono" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" minlength="10" title="Ingrese el número de teléfono (solo números)" required tabindex="5" placeholder="ej: 3217568090">
            </div>
            
        </div>

        

        <div class="fila_input">
            <div class="bloque-input">
                <label for="programa">Programa:</label>
                <input class="input" type="text" id="programa" name="nombres" list="programas" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" title="Solo se aceptan letras" tabindex="3" required placeholder="ej: multimedia">
                <datalist id="programas">
                </datalist>
            </div>
            <div class="bloque-input">
                <label for="ficha">ficha:</label>
                <input class="input" type="text" id="ficha" name="confirmar" pattern="[A-Za-z0-9]{7}" maxlength="7" minlength="7" title="Ingrese solo numeros" required tabindex="8" placeholder="eje:2714805">
            </div>
        </div>

        
    </article>

    <div class="contenedor_btn">
        <button id="registrarA" type="button" class="btn">REGISTRAR</button>
    </div>
</form>