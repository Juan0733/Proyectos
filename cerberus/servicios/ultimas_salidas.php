<?php


if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../", true);
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "jefe") {
        header("Location: ../", true);
    } else {
        $sentencia_salidas = "SELECT documento, fecha_registro FROM `movimientos` WHERE `tipo_movimiento` = 'SALIDA' ORDER BY `fecha_registro` DESC  LIMIT 4;";
        
        $conexion = require '../servicios/conexion.php';

        if (!$conexion) {
            $vector_respuesta = [
                'titulo' => "ERROR",
                'msj' => "Datos incorrectos para la operación indicada, Error de conexión",
                'cod' => 500
            ];
        } else {
            $consulta = $conexion01->query($sentencia_salidas);

            if ($consulta->num_rows == 0) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se encontraron registros de salidas",
                    'cod' => 404
                ];
            } else {
                // Almacenamos los resultados
                $salidas = $consulta->fetch_all();
                
                 //Creo Arreglo de tablas
                $tablas = ['vigilantes', 'visitantes', 'funcionarios', 'aprendices'];

                $todos_los_resultados = [];

                foreach ($salidas as $salida) {
                    
                    foreach($tablas as $tabla){

                        $sentencia_buscar = "SELECT v.nombres, v.apellidos, v.tipo_documento, v.documento, m.tipo_movimiento, m.fecha_registro, m.vehiculo FROM `$tabla` v INNER JOIN `movimientos` m ON v.documento = m.documento WHERE v.documento = '$salida[0]' AND m.fecha_registro = '$salida[1]';";

                        $buscar = $conexion01->query($sentencia_buscar);

                        if ($buscar->num_rows != 0) {
                            $resultado_busqueda = $buscar->fetch_assoc();
                            if($tabla == 'aprendices'){
                                $tipo_usuario = 'aprendiz';
                            }else{
                                $tipo_usuario = substr($tabla, 0, strlen($tabla)-1);
                            }
                            
                            $resultado_busqueda['tipo_usuario']=$tipo_usuario;
                            $todos_los_resultados[] = $resultado_busqueda;

                            break;
                        }
                            
                    }  
                }

                $vector_respuesta = [
                    'titulo' => "CONSULTA EXITOSA",
                    'msj' => "Se encontraron registros de salidas",
                    'cod' => 200,
                    'salidas' => $todos_los_resultados
                ];
               
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vector_respuesta);
    }
}