<button id="agregar_ingrediente">AGREGAR</button>
<button class="estado" data-id="activo"><span>ACTIVOS</span></button>

<button class="estado" data-id="inactivo"><span>INACTIVOS</span></button>

<input type="hidden" id="url" name="url" value="<?php echo $url_variable; ?>">

<article id="contenedor_ingredientes">

</article>

<article id="contenedor_modal"></article>
<script type="module" src="<?php echo $url_variable; ?>app/views/js/listar-ingredientes.js"></script>