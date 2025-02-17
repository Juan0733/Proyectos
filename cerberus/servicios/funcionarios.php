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
                if ($_SESSION['inicio_sesion']['cargo']=="subdirector" || $_SESSION['inicio_sesion']['cargo']=="coordinador") {
                    $cc_jefe= $_SESSION['inicio_sesion']['documento'];
                    $buscar_funcionario = "SELECT `documento`, `tipo_documento`, `nombres`, `apellidos`, `ubicacion`, `cargo`, `estado`  FROM `funcionarios` WHERE documento<> '$cc_jefe' ORDER BY `fecha_registro` DESC LIMIT 8;";
                }else{
                    $buscar_funcionario = "SELECT `documento`, `tipo_documento`, `nombres`, `apellidos`, `ubicacion`, `cargo`, `estado`  FROM `funcionarios` ORDER BY `fecha_registro` DESC LIMIT 8;";
                }                
                $conexion = require 'conexion.php';

                if(!$conexion){
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "Datos incorrectos para la operación indicada, Error de conexión",
                        'cod' => 500
                    ];
                } else {
                    $consulta = $conexion01->query($buscar_funcionario);

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
                        foreach($resultado_busqueda as $funcionario) {
                            $datos[] = [
                                'numero' => $funcionario['documento'],
                                'tipo_documento' => $funcionario['tipo_documento'],
                                'nombres' => $funcionario['nombres'],
                                'apellidos' => $funcionario['apellidos'],
                                'ubicacion' => $funcionario['ubicacion'],
                                'cargo' => $funcionario['cargo'],
                                'estado' => $funcionario['estado']
                            ];
                        }
                        $vector_respuesta = [
                            'titulo' => "EXITO",
                            'msj' => "¡Consulta ejecutada con exitó!",
                            'cod' => 200,
                            'funcionarios' => $datos
                        ];
                    }   
                }    
            }    
        
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($vector_respuesta);