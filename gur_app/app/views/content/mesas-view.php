<?php
    if($_SESSION['datos_usuario']['cargo_persona'] == 'MS'){
        echo '<button class="disponibilidad" data-id="todas">Todas</button>
            <button class="disponibilidad" data-id="libres">Libres</button>
            <button class="disponibilidad" data-id="ocupadas">Ocupadas</button>';
    }elseif($_SESSION['datos_usuario']['cargo_persona'] == 'AD'){
        echo '<button id="agregar_mesa">AGREGAR</button>';
    }

    if(count($url) == 2){
        echo '<input type="hidden" id="numero_mesa" value="'.$url[1].'">';
    }


?>

<input type="hidden" id="url" value="<?php echo $url_variable; ?>">

<article id="contenedor_mesas">

</article>

<article id="contenedor_modal" style="display:none;"></article>

<script type="module" src="<?php echo $url_variable ?>app/views/js/listar-mesas.js"></script>