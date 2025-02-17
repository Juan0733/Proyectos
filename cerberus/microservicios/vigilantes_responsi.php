<?php
session_start();
    if($_SERVER['REQUEST_METHOD']!= 'GET'){
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "Intento de operación incorrecta",
            'cod' => 500
        ];
        header("Location: ../", true);
    } else {
            
       
            if (!isset($_SESSION['inicio_sesion'])) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No autorizado",
                    'cod' => 500
                ];
                header("Location: ../", true);
            } else {
                $limite = $_GET['limite'] ?? 8;
                if($_SESSION['inicio_sesion']['cargo']=="jefe") {
                    $cc_jefe= $_SESSION['inicio_sesion']['documento'];
                    $buscar_vigilante = "SELECT `documento`, `tipo_documento`, `nombres`, `apellidos`, `ubicacion`, `estado`, `cargo`  FROM `vigilantes` WHERE documento <> '$cc_jefe'  ORDER BY `fecha_registro` DESC LIMIT $limite;";
                }else{
                    $buscar_vigilante = "SELECT `documento`, `tipo_documento`, `nombres`, `apellidos`, `ubicacion`, `estado`, `cargo`  FROM `vigilantes` ORDER BY `fecha_registro` DESC LIMIT $limite;";
                }
                
                
                $conexion = require '../servicios/conexion.php';

                if(!$conexion){
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "Datos incorrectos para la operación indicada, Error de conexión",
                        'cod' => 500
                    ];
                } else {
                    $consulta = $conexion01->query($buscar_vigilante);

                    if($consulta->num_rows == 0){
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "No fue posible realizar el conteo",
                            'cod' => 500
                        ];
                    } else {
                        //Almaceno los resultados
                        $resultado_busqueda = $consulta->fetch_all(MYSQLI_ASSOC);
                        $datos = [];

                        // Recorremos todos los resultados
                        foreach($resultado_busqueda as $vigilante) {
                            $datos[] = [
                                'numero' => $vigilante['documento'],
                                'nombres' => $vigilante['nombres'],
                                'apellidos' => $vigilante['apellidos'],
                                'ubicacion' => $vigilante['ubicacion'],
                                'estado'=> $vigilante['estado'],
                                'cargo'=> $vigilante['cargo'],
                            ];
                        }
                        $vector_respuesta = [
                            'titulo' => "EXITO",
                            'msj' => "¡Consulta ejecutada con exitó!",
                            'cod' => 200,
                            'vigilantes' => $datos
                        ];
                    }   
                }    
            }    
        
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($vector_respuesta);