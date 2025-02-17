<form action="" method="post" id="forma_registro">

    <div>
        <label for="nombre">Nombre de la Categoria:</label>
        <input type="text" id="nombre" name="nombre" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="20" minlength="3" placeholder="Ej: Bebidas Calientes" required>
    </div>

    <div>
        <label for="ubicacion">Ubicacion de la categoria:</label>
        <input type="text" id="ubicacion" name="ubicacion" pattern="[^\s][A-Za-zÑñ0-9\s]+" title="Solo se aceptan números y letras sin espacios ni caracteres especiales(como @, $, !, %, *, ?, &, #)." maxlength="20" minlength="3" placeholder="Ej: Cocina Principal" required>
    </div>

    <div>
        <label for="emoji">Emoji para la categoria:</label>
        <select name="emoji" id="emoji" required>
            <option value="" selected disabled>Seleccionar</option>
            <option value="1">🍕</option>
            <option value="2">🍔</option>
            <option value="3">🍣</option>
            <option value="4">🍎</option>
            <option value="5">🥑</option>
            <option value="6">🍦</option>
            <option value="7">🍇</option>
            <option value="8">🍫</option>
            <option value="9">🍿</option>
            <option value="10">🥗</option>
            <option value="11">🍉</option>
            <option value="12">🍌</option>
            <option value="13">🍒</option>
            <option value="14">🍪</option>
            <option value="15">🍩</option>
            <option value="16">🌭</option>
            <option value="17">🌮</option>
            <option value="18">🥟</option>
            <option value="19">🍔</option>
            <option value="20">🍁</option>
            <option value="21">🍱</option>
            <option value="22">🍜</option>
            <option value="23">🥖</option>
            <option value="24">🍞</option>
            <option value="25">🥨</option>
            <option value="26">🧇</option>
            <option value="27">🥯</option>
            <option value="28">🧀</option>
            <option value="29">🥙</option>
            <option value="30">🌯</option>
            <option value="31">☕</option>
            <option value="32">🍵</option>
            <option value="33">🍶</option>
            <option value="34">🍺</option>
            <option value="35">🍷</option>
            <option value="36">🍸</option>
            <option value="37">🥤</option>
            <option value="38">🧃</option>
            <option value="39">🥛</option>
            <option value="40">🧋</option>
            <option value="41">🍗</option>
            <option value="42">🍖</option>
            <option value="43">🥩</option>
            <option value="44">🥓</option>
            <option value="45">🍱</option>
        </select>
    </div>

    <button type="submit">GUARDAR</button>

</form>
