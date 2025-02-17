
<div class="contenedor-listados  listado-clientes">

<nav class="nav_gestion_listado">
    <div id="cont_opciones_listado">
    
        <div class="filtro">
            <label for="num_registros">Ver</label>
            <div class="cont_select">
            <select name="num_registros" id="num_registros" class="form-select">
                <option value="1">1</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>
            </div>
            <label for="num_registros">Registros</label>
        </div>

        <div class="cont_buscar_persona">
            <div class="buscar">
                <ion-icon name="search-outline"></ion-icon>
                <input type="text" name="filtro" id="filtro" placeholder="Buscar Empresa">
                <input type="hidden" id="url" value="app/api/empresa-api.php" >
            </div>
            <a href="registro-empresa">Registro de Empresa</a>

        </div>


    </div>


</nav>


<div id="contenedor_tabla">

</div>

</div>




<script src="app/views/js/listado-clientes-zobyte.js"></script>





