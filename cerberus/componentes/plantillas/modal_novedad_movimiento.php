<form action="" method="post" id="forma_registro" name="forma_registro">
    
    <article id="contenedor_titulo_icon">
        <h1>Registrar Novedad</h1>
        <i id="cerrar" class=' cerrar_modal bx bx-x'></i>
    </article>

    <article id="contenedor_inputs">
        <div id="fila_novedad">
            <div>
                <label for="tipo_novedad">Tipo Novedad:</label>
                <input type="text" id="tipo_novedad" name="tipo_novedad" required disabled>
            </div>
           
            <div>
                <label for="numero_documento">Número Documento:</label>
                <input type="text" id="numero_documento" name="numero_documento" required disabled>
            </div>
            
        </div>
        
    
        <div id="fila_select">
            <div class="fila-opciones" id="fila_acont">
                <label for="puerta_acontecimiento">Puerta Acontecimiento:</label>
                <select name="puerta_acontecimiento" id="puerta_acontecimiento" tabindex="2" required>
                    <option value="" disabled selected></option>
                    <option value="ganaderia">Ganaderia</option>
                    <option value="principal">Principal</option>
                    <option value="peatonal">Peatonal</option>
                </select>
            </div>
            
            <div class="fila_opciones" id="fila_registro">
                <label for="puerta_registro">Puerta Registro:</label>
                <select name="puerta_registro" id="puerta_registro" tabindex="2" required>
                    <option value="" disabled selected></option>
                    <option value="ganaderia">Ganaderia</option>
                    <option value="principal">Principal</option>
                    <option value="peatonal">Peatonal</option>
                </select>
            </div>
           
        </div>
       

        

        <label for="descripcion">Descripcion:</label>
        <input type="text" id="descripcion" name="descripcion" pattern="[A-Z0-9a-z\s]{5,}"
           minlength="5" title="Solo se aceptan letras." required tabindex="3">

    </article>

    <div class="contenedor_btn">
            <button type="button" class="btn" id="btn_registrar_novedad">REGISTRAR</button>
    </div>
</form>