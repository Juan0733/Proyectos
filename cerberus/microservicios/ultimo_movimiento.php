<?php
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
            header("Location: ../");
    } else {
        session_start();
        if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe") {
            header("Location: ../");
        } else {
            $documento = $_GET['documento'] ?? 'vacio';

            $conexion = require '../servicios/conexion.php';
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
            } else {
                $sentencia = "SELECT MAX(fecha_registro) as fecha_registro FROM `movimientos` WHERE `documento` ='$documento';";
                    
                $buscar = $conexion01->query($sentencia);
    
                if($buscar->num_rows == 0) {
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "Ultimo movimiento no encontrado",
                        'cod' => 400,
                    ];
                }else{
                    $vector_respuesta = [
                        'titulo' => "CONSULTA EXITOSA!",
                        'msj' => "Ultimo movimiento encontrado",
                        'cod' => 200,
                        'fecha_registro' => $buscar->fetch_assoc()['fecha_registro']
                    ];
                }

            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($vector_respuesta);
        }

    }