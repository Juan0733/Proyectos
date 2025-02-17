<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\CategoriaController;
	
	header('Content-Type: application/json; charset=utf-8');
	$insCategoria = new CategoriaController();
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accion = $insCategoria->limpiarDatos($_POST['accion']);
		if($accion == 'registrar_categoria'){
			echo json_encode($insCategoria->registrarCategoria());
		}else if($accion == 'editar_categoria'){
			echo json_encode($insCategoria->actualizarCategoria());
		}
	}elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
		$accion = $insCategoria->limpiarDatos($_GET['accion']);
		if($accion == 'listar_categorias'){
			echo json_encode($insCategoria->consultarCategorias());
		}elseif($accion == 'listar_categorias_productos'){
			$estado = $insCategoria->limpiarDatos($_GET['estado']);
			echo json_encode($insCategoria->consultarCategoriasProductos($estado));
		}else if($accion == 'consultar_categoria'){
			$codigo = $insCategoria->limpiarDatos($_GET['codigo']);
			echo json_encode($insCategoria->consultarCategoria($codigo));
		}else if($accion = 'eliminar_categoria'){
			$codigo = $insCategoria->limpiarDatos($_GET['codigo']);
			echo json_encode($insCategoria->eliminarCategoria($codigo));
		}
		
	}