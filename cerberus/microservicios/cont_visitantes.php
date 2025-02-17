<?php
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        header("Location: ../", true);
    } else {
        session_start();
        if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
            header("Location: ../", true);
        } else {
            // realizamos la consulta para el conteo de visitantes
            $sentencia_visitantes = "SELECT `documento` FROM `visitantes` WHERE `ubicacion` = 'ADENTRO';";
            $conexion = require '../servicios/conexion.php';
            if (!$conexion) {
                $vector_respuesta=[
                    'titulo'=>"ERROR",
                    'msj'=> "Datos incorrectos para la operacion indicada,Error conexion",
                    'cod'=> 500 //Error
                ];
            } else {
                $resultado_visitantes = $conexion01 -> query($sentencia_visitantes);
                $total_visitantes = $resultado_visitantes -> num_rows;
               
                $vector_respuesta=[
                    'titulo'=> "EXITO",
                    'msj'=> "Conteo realizado",
                    'cod'=> 200,
                    'conteo' => $total_visitantes
                ];
                
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($vector_respuesta);
        }
    }