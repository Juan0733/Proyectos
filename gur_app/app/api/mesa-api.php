<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\MesaController;

    header('Content-Type: application/json; charset=utf-8');
    $insMesa = new MesaController();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $accion = $insMesa->limpiarDatos($_POST['accion']);
        if($accion == 'registrar_mesa'){
            echo json_encode($insMesa->registrarMesa());
        }
    }else if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $accion = $insMesa->limpiarDatos($_GET['accion']);
        if($accion == 'listar_mesas'){
            $disponibilidad = $insMesa->limpiarDatos($_GET['disponibilidad']);
            echo json_encode($insMesa->consultarMesas($disponibilidad));
        }else if($accion == 'eliminar_mesa'){
            $codigo = $insMesa->limpiarDatos($_GET['codigo']);
            echo json_encode($insMesa->eliminarMesa($codigo));
        }else if($accion == 'consultar_mesa'){
            $codigo = $insMesa->limpiarDatos($_GET['codigo']);
            echo json_encode($insMesa->consultarMesa($codigo));
        }elseif($accion == 'validar_usuario_mesa'){
            $codigo = $insMesa->limpiarDatos($_GET['codigo']);
            echo json_encode($insMesa->validarUsuarioMesa($codigo));
        }
    }