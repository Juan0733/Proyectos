<?php

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "Intento de operación incorrecta",
            'cod' => 500,
        ];
    } else {
        $bandera = 1;
        if (!isset($_POST['numero_documento']) || $_POST['numero_documento'] == "" || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
            $bandera = 0;
        }
        
        if ($bandera == 0) {
            $vector_respuesta = [
                'titulo' => "ERROR",
                'msj' => "Datos incorrectos para la operación indicada.",
                'cod' => 400,
            ];
        } else {
            session_start();
            $numero_documento = trim(filter_var($_POST['numero_documento']));
            unset($_POST['numero_documento']);
            date_default_timezone_set('America/Bogota');
            $sys_momento = time();
            $momento_registro = date("Y-m-d H:i:s", $sys_momento);
            $usr_sistema = $_SESSION['inicio_sesion']['documento'];


            $conexion = require 'conexion.php';
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
            } else {
                $tablas = ['vigilantes', 'visitantes', 'funcionarios', 'aprendices'];
                $documento_encontrado = false;
                $ubicacion_correcta = false;

                foreach ($tablas as $tabla) {
                    $sentencia_buscar = "SELECT `ubicacion` FROM `$tabla` WHERE `documento` = '$numero_documento';";
                    $buscar_usr = $conexion01->query($sentencia_buscar);

                    if ($buscar_usr && $buscar_usr->num_rows > 0) {
                        $ubicacion = $buscar_usr->fetch_assoc();
                        if($ubicacion['ubicacion'] == "ADENTRO"){
                            $ubicacion_correcta = true;
                        }
                        $documento_encontrado = true; 
                        $sentencia_update = "UPDATE `$tabla` SET `ubicacion` = 'AFUERA' WHERE `documento` = '$numero_documento';";
                        break;
                    }
                }

                if(!$documento_encontrado){
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "¡Parece que esta persona no se encuentra registrada en el sistema!",
                        'cod' => 404,
                    ];
                } else {
                    if(!$ubicacion_correcta){
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "¡Parece que esta persona no se le registro una entrada!",
                            'cod' => 402,
                        ];
                    }else{
                        $sentencia_insertar_movimiento = "INSERT INTO `movimientos`(`documento`, `fecha_registro`, `usr_sistem`, `puerta_registro`, `tipo_movimiento`) VALUES ('$numero_documento','$momento_registro','$usr_sistema','peatonal','SALIDA');";

                        $registro_entrada = $conexion01->query($sentencia_insertar_movimiento);
                        $filas_afectadas = $conexion01->affected_rows;

                        if($filas_afectadas != 1){
                            $vector_respuesta = [
                                'titulo' => "ERROR",
                                'msj' => "El registro ha fallado por alguna razón.",
                                'cod' => 500,
                            ];
                        }else{
                            $actualizar_ubicacion = $conexion01->query($sentencia_update);
                            $filas_afectadas = $conexion01->affected_rows;
                            if($filas_afectadas != 1){
                                $vector_respuesta = [
                                    'titulo' => "ERROR",
                                    'msj' => "El registro ha fallado por alguna razón.",
                                    'cod' => 500,
                                ];
                            }else{
                                $vector_respuesta = [
                                    'titulo' => "REGISTRO",
                                    'msj' => "¡Salida registrada con éxito!",
                                    'cod' => 200,
                                ];
                            }
                            
                        }
                    }
                    
                }

                $conexion01->close();
                // unset($sentencia_buscar, $sentencia_update, $conexion, $conexion01, $buscar_usr, $registro_entrada, $numero_documento, $momento_registro, $sys_momento, $tablas, $bandera, $documento_encontrado,$ubicacion_correcta, $ubicacion);
            }
        }
    }

    echo json_encode($vector_respuesta);
