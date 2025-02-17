<?php
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        header("Location: ../", true);
    } else {
        session_start();
        if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
            header("Location: ../", true);
        } else {
            // realizamos la consulta para el conteo de visitantes
            $sentencia_funcionarios = "SELECT `documento` FROM `funcionarios` WHERE `ubicacion` = 'ADENTRO';";
            $sentencia_vigilantes = "SELECT `documento` FROM `vigilantes` WHERE `ubicacion` = 'ADENTRO';";

            $conexion = require '../servicios/conexion.php';
            if (!$conexion) {
                $vector_respuesta=[
                    'titulo'=>"ERROR",
                    'msj'=> "Datos incorrectos para la operacion indicada,Error conexion",
                    'cod'=> 500 //Error
                ];
            } else {
                $resultado_funcionarios = $conexion01->query($sentencia_funcionarios);
                $resultado_vigilantes = $conexion01->query($sentencia_vigilantes);
                $total_funcionarios = $resultado_funcionarios->num_rows + $resultado_vigilantes->num_rows;
            
                $vector_respuesta=[
                    'titulo'=> "EXITO",
                    'msj'=> "Conteo realizado",
                    'cod'=> 200,
                    'conteo' => $total_funcionarios
                ];
                
            }
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($vector_respuesta);