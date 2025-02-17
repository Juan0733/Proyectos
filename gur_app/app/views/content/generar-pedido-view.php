<article  aria-labelledby="productos-title">
    <input type="hidden" id="numero" value="<?php echo $url[1]; ?>">
    <input type="hidden" id="url" value="<?php echo $url_variable; ?>">
    <?php
        if(count($url) == 3){
            echo '<input type="hidden" id="pedido" value="'.$url[2].'">';
        };
    ?>

    <header class="info-view">
        <div class="content-left">
            <h1 id="productos-title" class="title-view">Lista de productos</h1>
            <div id="total_productos" class="count-items" aria-label="Cantidad total de productos">
            </div>
        </div>
        
        <div class="content-right">
            <form class="search-area" role="search" aria-label="Buscar productos">
                <input type="text" name="buscar_producto" id="buscar_producto" placeholder="Buscar producto" aria-label="Buscar producto">
                    <ion-icon name="search-outline"></ion-icon>
            </form>
        </div>
    </header>

    <section class="content-categories-products" aria-labelledby="categorias-title">
        <ul id="lista_categorias" class="categories-list">
            
        </ul>
    </section>

    <section id="contenedor_productos" class="content-all-products">
        
    </section>

</article>

<article id="contenedor_items">
        
</article>

<article id="contenedor_modal" style="display:none;"></article>

<?php 
    if(count($url) == 2){
        echo '<button type="button" id="generar_pedido">Generar Pedido</button>';
    }elseif(count($url) == 3){
        echo '<button type="button" id="registrar_items">Confirmar</button>';
    }
?>


<script type="module" src="<?php echo $url_variable;?>app/views/js/generar-pedido.js"></script>