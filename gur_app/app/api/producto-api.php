<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\ProductoController;
	
	header('Content-Type: application/json; charset=utf-8');
	$insProducto = new ProductoController();
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accion = $insProducto->limpiarDatos($_POST['accion']);
		if($accion == 'registrar_producto_estand'){
			echo json_encode($insProducto->registrarProductoEstand());
		}elseif($accion == 'registrar_producto_cocina'){
			echo json_encode($insProducto->registrarProductoCocina());
		}elseif($accion == 'editar_producto_estand'){
			echo json_encode($insProducto->actualizarProductoEstand());
		}elseif($accion == 'editar_producto_cocina'){
			echo json_encode($insProducto->actualizarProductoCocina());
		}elseif($accion == 'restaurar_producto_estand'){
			echo json_encode($insProducto->restaurarProductoEstand());
		}elseif($accion == 'restaurar_producto_cocina'){
			echo json_encode($insProducto->restaurarProductoCocina());
		}
		
	}else if($_SERVER['REQUEST_METHOD'] == 'GET'){
		$accion = $insProducto->limpiarDatos($_GET['accion']);
		if($accion == 'listar_productos'){
			$categoria = $insProducto->limpiarDatos($_GET['categoria']);
			$estado = $insProducto->limpiarDatos($_GET['estado']);
			$limite = $insProducto->limpiarDatos($_GET['limite']);
			echo json_encode($insProducto->consultarProductos($categoria, $estado, $limite));
		}else if($accion == 'eliminar_producto'){
			$codigo = $insProducto->limpiarDatos($_GET['codigo']);
			echo json_encode($insProducto->eliminarProducto($codigo));
		}else if($accion == 'consultar_producto'){
			$codigo = $insProducto->limpiarDatos($_GET['codigo']);
			echo json_encode($insProducto->consultarProducto($codigo));
		}else if($accion == 'consultar_receta'){
			$codigo = $insProducto->limpiarDatos($_GET['codigo']);
			echo json_encode($insProducto->consultarReceta($codigo));
		}
	}