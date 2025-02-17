<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\PedidoController;

   header('Content-Type: application/json; charset=utf-8');
   $insPedido = new PedidoController();
   if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $accion = $insPedido->limpiarDatos($_POST['accion']);
      if($accion == 'registrar_pedido'){
         echo json_encode($insPedido->registrarPedido());
      }elseif($accion == 'registrar_items'){
         echo json_encode($insPedido->registrarItems());
      }
   }else if($_SERVER['REQUEST_METHOD'] == 'GET'){
      $accion = $insPedido->limpiarDatos($_GET['accion']);
      if($accion == "consultar_pedidos_mesa"){
         $mesa = $insPedido->limpiarDatos($_GET['mesa']);
         echo json_encode($insPedido->informacionPedidosMesa($mesa));
      }elseif($accion == "eliminar_item"){
         $item = $insPedido->limpiarDatos($_GET['item']);
         $estadoMesero = $insPedido->limpiarDatos($_GET['estado_mesero']);
         if($estadoMesero != ''){
            echo json_encode($insPedido->eliminarItem($item, $estadoMesero));
         }elseif($estadoMesero == ''){
            echo json_encode($insPedido->eliminarItem($item));
         }
      }elseif($accion == "entregar_item"){
         $item = $insPedido->limpiarDatos($_GET['item']);
         echo json_encode($insPedido->entregarItem($item));
      }elseif($accion == "entregar_items"){
         $pedido = $insPedido->limpiarDatos($_GET['pedido']);
         $producto = $insPedido->limpiarDatos($_GET['producto']);
         echo json_encode($insPedido->entregarItems($pedido, $producto));
      }
   }