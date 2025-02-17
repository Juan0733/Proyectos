<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\empresaController;
	
	
	
	if (isset($_POST['accion_empresa'])) {
		$insIniciar = new empresaController();
		if ($_POST['accion_empresa'] == 'registrar') {
			echo $insIniciar->registrarEmpresaControlador();
		}
	}

	if (isset($_POST['filtro'])) {
		$insVisitante = new empresaController();
		echo $insVisitante->ListarVisitanteController();
	}
	/* if (!isset($_POST['filtro']) && !isset($_POST['modulo_visitante'])) {
		session_destroy();
		header("Location: login/");
	} */