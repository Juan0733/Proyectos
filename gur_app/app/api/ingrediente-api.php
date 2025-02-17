<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\IngredienteController;

    header('Content-Type: application/json; charset=utf-8');
    $insIngrediente = new IngredienteController();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $accion = $insIngrediente->limpiarDatos($_POST['accion']);
        if($accion == 'registrar_ingrediente'){
            echo json_encode($insIngrediente->registrarIngrediente());
        }else if($accion == 'editar_ingrediente'){
            echo json_encode($insIngrediente->actualizarIngrediente());
        }else if($accion == 'restaurar_ingrediente'){
			echo json_encode($insIngrediente->restaurarIngrediente());
		}
    }else if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $accion = $insIngrediente->limpiarDatos($_GET['accion']);
        if($accion == 'listar_ingredientes'){
            $estado = $insIngrediente->limpiarDatos($_GET['estado']);
            echo json_encode($insIngrediente->consultarIngredientes($estado));
        }else if($accion == 'consultar_ingrediente'){
            $codigo = $insIngrediente->limpiarDatos($_GET['codigo']);
            echo json_encode($insIngrediente->consultarIngrediente($codigo));
        }else if($accion == 'eliminar_ingrediente'){
            $codigo = $insIngrediente->limpiarDatos($_GET['codigo']);
            echo json_encode($insIngrediente->eliminarIngrediente($codigo));
        }
    }