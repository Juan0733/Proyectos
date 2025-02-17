<form action="" method="post" id="forma_registro" name="forma_registro">
    <article id="contenedor_titulo_icon">
        <h1 id="titulo_modal">Registrar Agenda</h1>
        <i id="cerrar_principal" class="cerrar_modal bx bx-x"></i>
    </article>

    <article id="contenedor_inputs">
        
        <section class="input_fila">
            <div class="input_grupo">
                <label for="titulo_agenda">Título Agenda:</label>
                <input type="text" id="titulo_agenda" name="titulo_agenda" class="campo" required tabindex="1">
            </div>
            <div class="input_grupo">
                <label for="fecha_agenda">Fecha de agenda:</label>
                <input type="datetime-local" id="fecha_agenda" name="fecha_agenda" class="campo" required tabindex="2">
            </div>
            <div class="input_grupo">
                <label for="motivo_visita_a">Motivo de visita:</label>
                <input type="text" id="motivo_visita_a" name="motivo_visita_a" class="campo" pattern="[A-Za-z0-9ÁÉÍÚÑáeiou\s]{4,50}" maxlength="50" minlength="4" required tabindex="11">
            </div>
        </section>

        <section class="input_fila">
            <div class="input_grupo">
                <label for="tipo_agenda">Tipo de Agenda:</label>
                <select name="tipo_agenda" id="tipo_agenda" class="campo" required tabindex="4">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="individual">Individual</option>
                    <option value="carga_masiva">Carga Masiva</option>
                </select>
            </div>

            <div class="input_grupo carga_masiva" id="contenedor_carga_masiva">
                <div id="cont_input_c">
                    <label for="carga_masiva_e">Carga Masiva:</label>
                    <input type="file" id="carga_masiva_e" name="carga_masiva_e" class="campo_masiva campo" accept=".xlsx, .xls">
                   
                </div>
                
                
                <a href="../../descargable/Formato_Agenda.xlsx" download="formato.xlsx" class="descargar_excel">Descargar Formato</a>
            </div>

            <div class="input_grupo" id="contenedor_tipo_doc">
                <label for="tipo_documento_a">Tipo de Documento:</label>
                <select name="tipo_documento_a" id="tipo_documento_a" class="campo_individual campo" tabindex="5">
                    <option value="" disabled selected></option>
                    <option value="CC">CC</option>
                    <option value="TI">TI</option>
                    <option value="CE">CE</option>
                    <option value="PEP">PEP</option>
                    <option value="PAS">PAS</option>
                </select>
            </div>

            <div class="input_grupo" id="contenedor_numero_doc">
                    <label for="numero_documento_a">Número de Documento:</label>
                    <input type="text" id="numero_documento_a" name="numero_documento_a" class="campo_individual campo" pattern="[A-Za-z0-9]{6,15}" maxlength="15" minlength="6" tabindex="6">
            </div>
        </section>

        <section id="individual_section" class="input_fila">
            <div class="input_fila">
                <div class="input_grupo">
                    <label for="nombres_a">Nombres:</label>
                    <input type="text" id="nombres_a" name="nombres_a" class="campo_individual campo" pattern="[A-Za-zÁÉÍÚÑáeiou\s]{3,25}" maxlength="25" minlength="3" tabindex="7">
                </div>

                <div class="input_grupo" id="apellidos_g">
                    <label for="apellidos_a">Apellidos:</label>
                    <input type="text" id="apellidos_a" name="apellidos_a" class="campo_individual campo" pattern="[A-Za-zÁÉÍÚÑáeiou\s]{3,30}" maxlength="30" minlength="3" tabindex="8">
                </div>

                <div class="input_grupo">
                    <label for="movil_a">Móvil:</label>
                    <input type="text" id="movil_a" name="movil_a" class="campo_individual campo" inputmode="tel" maxlength="10" minlength="10" tabindex="9">
                </div>
            </div>

            <div class="input_fila">
                <div class="input_grupo">
                    <label for="correo_a">Correo Electrónico:</label>
                    <input type="email" id="correo_a" name="correo_a" class="campo_individual campo" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" maxlength="64" minlength="2" tabindex="10">
                </div>
            </div>
        </section>
    </article>

    <div class="contenedor_btn">
        <button type="button" class="btn" id="agregar_vehiculo">AGREGAR VEHÍCULO</button>
        <button type="button" class="btn" id="registrar">REGISTRAR</button>
    </div>
</form>
