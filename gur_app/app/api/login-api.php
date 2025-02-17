<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\loginController;
	
	
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$insIniciar = new loginController();
		echo $insIniciar->iniciarSesionControlador();
	}else{
		echo "no post". $_SERVER['REQUEST_METHOD'];
	}