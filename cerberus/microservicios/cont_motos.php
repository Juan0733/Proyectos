<?php
 
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        header("Location: ../", true);
    }else{
        session_start();
        if($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador"){
            header("Location: ../", true);
        }else{
            $sentencia_carros = "SELECT  DISTINCT `placa` FROM `vehiculos` INNER JOIN  `movimientos` m ON  `placa` =  m.vehiculo WHERE  m.tipo_movimiento = 'ENTRADA' AND  `tipo` = 'moto' AND m.fecha_registro= (SELECT MAX(`fecha_registro`) FROM `movimientos` WHERE `vehiculo` = `placa`);
";


            $conexion = require '../servicios/conexion.php';
    
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Datos incorrectos para la operacion indicada,Error conexion",
                    'cod' => 500 //Error
                ];
                
            } else {
                $resultado_carros = $conexion01->query($sentencia_carros);
                $total_carros = $resultado_carros->num_rows;
    
                //  array para almacenar todos los resultados
                $vector_respuesta=[
                    'titulo' => "CONTEO",
                    'msj' => "La cantidad es:",
                    'cod' => 200,
                    'conteo'=>$total_carros
                ];
            }
        } 
    }
header('Content-Type: application/json; charset=utf-8');
echo json_encode($vector_respuesta);
