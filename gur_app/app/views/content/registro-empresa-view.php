<form action="app/api/empresa-api.php" method="post" id="formulario-api" enctype="multipart/form-data" >
    <input type="hidden" name="accion_empresa" value="registrar">

    <label for="num_id_empresa">Número de Identificación:</label>
    <input type="text" name="num_id_empresa" id="num_id_empresa" pattern="[0-9]{6,15}" title="Debe ser un número de 5 a 15 dígitos" required>


    <label for="tipo_identificacion">Tipo de Identificación:</label>
    <select name="tipo_identificacion" id="tipo_identificacion" required>
        <option value="NIT">NIT</option>
        <option value="CC">Cédula de Ciudadanía</option>
    </select>

    <label for="nombre_empresa">Nombre de la Empresa:</label>
    <input type="text" name="nombre_empresa" id="nombre_empresa" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s]{3,100}" title="Debe contener entre 3 y 100 caracteres alfanuméricos" required>

    <label for="tipo_empresa">Tipo de Empresa:</label>
    <select name="tipo_empresa" id="tipo_empresa" required>
        <option value="PQ">Pequeña</option>
        <option value="MD">Mediana</option>
        <option value="GD">Grande</option>
    </select>

    <label for="departamento">Departamento:</label>
    <select name="departamento" id="departamento" required>
        <option value="" disabled selected>Seleccione un departamento</option>
    </select>

    <label for="ciudad">Ciudad:</label>
    <select name="ciudad" id="ciudad" required>
        <option value="" disabled selected>Seleccione una ciudad</option>
    </select>

    <label for="calle">Dirección (Calle, Carrera, etc.):</label>
    <input type="text" name="calle" id="calle" pattern="[A-Za-z0-9#\-\s]{5,100}" title="Debe contener entre 5 y 100 caracteres. Ejemplo: Calle 123 #45-67" required>


    <label for="correo_empresa">Correo Electrónico:</label>
    <input type="email" name="correo_empresa" id="correo_empresa" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Ingrese un correo electrónico válido" required>

    <label for="telefono_empresa">Teléfono:</label>
    <input type="text" name="telefono_empresa" id="telefono_empresa" pattern="\d{7,10}" title="Debe ser un número de teléfono de 7 a 10 dígitos" required>

    <h3>Datos del Representante</h3>

    <label for="tipo_id_representante">Tipo de Identificación:</label>
    <select name="tipo_id_representante" id="tipo_id_representante" required>
        <option value="NIT">NIT</option>
        <option value="CC">Cédula de Ciudadanía</option>
    </select>

    <label for="num_id_representante">Número de Identificación:</label>
    <input type="text" name="num_id_representante" id="num_id_representante" pattern="\d{5,15}" title="Debe ser un número de 5 a 15 dígitos" required>

    <label for="nombre_representante">Nombre:</label>
    <input type="text" name="nombre_representante" id="nombre_representante" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{3,64}" title="Debe contener entre 3 y 64 caracteres alfabéticos" required>

    <label for="apellidos_representante">Apellidos:</label>
    <input type="text" name="apellidos_representante" id="apellidos_representante" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{3,50}" title="Debe contener entre 3 y 50 caracteres alfabéticos" required>

    <label for="telefono_representante">Teléfono:</label>
    <input type="text" name="telefono_representante" id="telefono_representante" pattern="\d{7,10}" title="Debe ser un número de teléfono de 7 a 10 dígitos" required>

    <label for="logo_empresa">Logo de la Empresa (Formato permitido: JPG, PNG):</label>
    <input type="file" name="logo_empresa" id="logo_empresa" accept="image/jpeg, image/png" title="Solo se aceptan imágenes en formato JPG o PNG" required>

    <button type="submit">Registrar</button>
</form>