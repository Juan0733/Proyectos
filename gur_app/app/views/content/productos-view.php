
<style>


</style>

<input type="hidden" id="url" name="url" value="<?php echo $url_variable; ?>">

<article  aria-labelledby="productos-title">
    <header class="info-view">
        <div class="content-left">
            <h1 id="productos-title" class="title-view">Lista de productos</h1>
            <?php 
                if($_SESSION['datos_usuario']['cargo_persona'] == 'AD'){
                    echo '<button class="estado" data-id="activo">
                                <span>ACTIVOS</span>
                            </button>

                           <button class="estado" data-id="inactivo">
                                    <span>INACTIVOS</span>
                            </button>';
                }elseif($_SESSION['datos_usuario']['cargo_persona'] == 'MS'){
                    echo '<div id="total_productos" class="count-items" aria-label="Cantidad total de productos">
                        </div>';
                }
            ?>
            
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

<article id="contenedor_modal" style="display:none;">
</article>

<script type="module" src="<?php echo $url_variable; ?> app/views/js/listar-productos.js" ></script>


