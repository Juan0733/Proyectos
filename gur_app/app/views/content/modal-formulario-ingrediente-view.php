<form action="" method="post" id="forma_registro">

    <input type="hidden" id="url" name="url" value="<?php echo $url_variable; ?>">
    
    <div id="contenedor_codigo">
        <label for="codigo">Código del Ingrediente:</label>
        <input type="text" name="codigo" id="codigo" pattern="[A-Za-z0-9]{11,16}" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)."  maxlength="16" minlength="11" placeholder="Ej: 123456789012">
       
    </div>
   
    <div>
        <label for="nombre">Nombre del Ingrediente:</label>
        <input type="text" name="nombre" id="nombre" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="50" minlength="3" placeholder="Ej: Cocacola Mini" required>
    </div>
    
    <div>
        <label for="categoria">Categoria del Ingrediente:</label>
        <select name="categoria" id="categoria" required>
            <option value="" selected disabled>Seleccionar</option>
        </select>
    </div>

    <div>
        <label for="presentacion">Presentacion del Ingrediente:</label>
        <input type="text" name="presentacion" id="presentacion" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="50" minlength="4" placeholder="Ej: Botella 200ml" required>
    </div>

    
        <div>
            <label for="unidad_medida"> Unidad de Medidad del Ingrediente</label>
            <select name="unidad_medida" id="unidad_medida" class="campo-estand" required>
                <option value="" selected disabled>Seleccionar</option>
                <option value="centimetro cubico">Centimetro Cubico</option>
                <option value="gramo">Gramo</option>
                <option value="kilogramo">Kilogramo</option>
                <option value="litro">Litro</option>
                <option value="mililitro">Mililitro</option>
                <option value="unidad">Unidad</option>
            </select>
        </div>
        
        <div>
            <label for="stock_actual">Stock Actual del Ingrediente:</label>
            <input type="number" name="stock_actual" id="stock_actual" class="campo-estand" step="0.1" min="0.5" value="1" required>
        </div>
        

        <div>
            <label for="stock_minimo">Stock Minimo del Ingrediente:</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="campo-estand" step="0.1" min="0.5" value="1" required>
        </div>
        
        <div>
            <label for="precio_compra">Precio de Compra del Ingrediente:</label>
            <input type="number" name="precio_compra" id="precio_compra" class="campo-estand" pattern="[0-9\.]" min="50" step="0.01" placeholder="$1000" required>
        </div>

    <button type="submit">GUARDAR</button>
</form>
