<form action="" method="post" id="forma_registro">

   <div>
        <label for="tipo_producto">Tipo de Producto:</label>
        <select name="tipo_producto" id="tipo_producto" required>
            <option value="" selected disabled>Seleccionar</option>
            <option value="cocina">Producto de Cocina</option>
            <option value="estand">Producto de Estand</option>
        </select>
    </div>
    
    <div id="contenedor_codigo" style="display:none;">
        <label for="codigo">Código del Producto:</label>
        <input type="text" name="codigo" id="codigo" pattern="[A-Za-z0-9]{11,16}" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)."  maxlength="16" minlength="11" placeholder="Ej: 123456789012">
       
    </div>
   
    <div>
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" id="nombre" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="50" minlength="3" placeholder="Ej: Cocacola Mini" required>
    </div>
    
    <div>
        <label for="categoria">Categoria del Producto:</label>
        <select name="categoria" id="categoria" required>
            <option value="" selected disabled>Seleccionar</option>
        </select>
    </div>
    
    <div>
        <label for="foto">Foto Producto:</label>
        <input type="file" name="foto" id="foto" accept=".png, .jpg">
    </div>

    <div>
        <label for="presentacion">Presentacion del Producto:</label>
        <input type="text" name="presentacion" id="presentacion" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="50" minlength="4" placeholder="Ej: Botella 200ml" required>
    </div>

    <div id="contenedor_campos_estand" style="display:none;">
        <div>
            <label for="unidad_medida"> Unidad de Medidad del producto</label>
            <select name="unidad_medida" id="unidad_medida" class="campo-estand">
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
            <label for="stock_actual">Stock Actual del Producto:</label>
            <input type="number" name="stock_actual" id="stock_actual" class="campo-estand" step="0.1" min="0.5" value="1">
        </div>
        

        <div>
            <label for="stock_minimo">Stock Minimo del Producto:</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="campo-estand" step="0.1" min="0.5" value="1">
        </div>
        
        <div id="contenedor_precio_compra">
            <label for="precio_compra">Precio de Compra del Producto:</label>
            <input type="number" name="precio_compra" id="precio_compra" class="campo-estand" pattern="[0-9\.]" min="50" step="0.01" placeholder="$1000">
        </div>
    </div>
    
    <div>
        <label for="precio_venta">Precio de Venta del Producto:</label>
        <input type="number" name="precio_venta" id="precio_venta" pattern="[0-9\.]" min="50" step="0.01" 
        placeholder="$1000" required>
    </div>

    <div id="contenedor_ingredientes"></div>
    <button type="submit">GUARDAR</button>
</form>
