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
            $sentencia_funcionarios = "SELECT `documento` FROM `funcionarios` WHERE `ubicacion` = 'ADENTRO';";
            $sentencia_aprendices = "SELECT `documento` FROM `aprendices` WHERE `ubicacion` = 'ADENTRO';";
            $sentencia_vigilantes = "SELECT `documento` FROM `vigilantes` WHERE `ubicacion` = 'ADENTRO';";

            $conexion = require '../servicios/conexion.php';
            if (!$conexion) {
                $vector_respuesta=[
                    'titulo'=>"ERROR",
                    'msj'=> "Datos incorrectos para la operacion indicada,Error conexion",
                    'cod'=> 500 //Error
                ];
            } else {
                $resultado_visitantes = $conexion01 -> query($sentencia_visitantes);
                $resultado_funcionarios = $conexion01 -> query($sentencia_funcionarios);
                $resultado_aprendices = $conexion01 -> query($sentencia_aprendices);
                $resultado_vigilantes = $conexion01 -> query($sentencia_vigilantes);
                $total = $resultado_visitantes -> num_rows + $resultado_funcionarios -> num_rows + $resultado_aprendices -> num_rows + $resultado_vigilantes -> num_rows;
                
                $vector_respuesta=[
                    'titulo'=> "EXITO",
                    'msj'=> "Conteo realizado",
                    'cod'=> 200,
                    'conteo' => $total
                ];
                
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($vector_respuesta);
        }
    }