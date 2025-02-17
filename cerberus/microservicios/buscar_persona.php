<?php
    //verificamos el intento de operación
    if ($_SERVER['REQUEST_METHOD'] != "GET" && $_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: ../");
    } else {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            session_start();
        }
        
        if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
            header("Location: ../");
        } else {
            if($_SERVER['REQUEST_METHOD'] != 'POST'){
                $documento = $_GET['documento'] ?? 'vacio';
            }
            $sentencia_buscar_visitantes = "SELECT `documento`, `ubicacion`, `nombres`, `apellidos`, `tipo_documento` FROM `visitantes` WHERE `documento` = '$documento';";
            $sentencia_buscar_vigilantes = "SELECT `documento`, `ubicacion`, `nombres`, `apellidos`, `tipo_documento`, `estado`, `cargo` FROM `vigilantes` WHERE `documento` = '$documento';";
            $sentencia_buscar_funcionarios = "SELECT `documento`, `ubicacion`, `nombres`, `apellidos`, `tipo_documento`, `estado`, `cargo` FROM `funcionarios` WHERE `documento` = '$documento';";
            $sentencia_buscar_aprendices = "SELECT `documento`, `ubicacion`, `nombres`, `apellidos`, `tipo_documento` FROM `aprendices` WHERE `documento` = '$documento';";

            if($_SERVER['REQUEST_METHOD'] != 'POST'){
                $conexion = require '../servicios/conexion.php';
            }
            
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
            } else {
                $existe = 0;
                $tabla_origen = "";
                $buscar_usr = $conexion01 -> query($sentencia_buscar_vigilantes);
                if ($buscar_usr -> num_rows != 0) {
                    $existe = 1;
                    $tabla_origen = 'vigilante';
                }
                if ($existe == 0) {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_visitantes);
                    if ($buscar_usr -> num_rows != 0) {
                        $existe = 1;
                        $tabla_origen = 'visitante';
                    }
                }
                if ($existe == 0) {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_funcionarios);
                    if ($buscar_usr -> num_rows != 0) {
                        $existe = 1;
                        $tabla_origen = 'funcionario';
                    }
                }
                if ($existe == 0) {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_aprendices);
                    if ($buscar_usr -> num_rows != 0) {
                        $existe = 1;
                        $tabla_origen = 'aprendiz';
                    }
                }
                if ($existe != 1) {
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "Persona no encontrada",
                        'cod' => 404,
                    ];
                } else {
                   
                    $datosPersona = $buscar_usr->fetch_assoc();
                    
                    $doc_persona = $datosPersona['documento'];
                    $ubi_persona = $datosPersona['ubicacion'];
                    $tipo_doc = $datosPersona['tipo_documento'];
                    $nombres = $datosPersona['nombres'];
                    $apellidos = $datosPersona['apellidos'];
                    $estado = $datosPersona['estado'] ?? "vacio";
                    $cargo = $datosPersona['cargo'] ?? "vacio";
                    $usr = $_SESSION['inicio_sesion']['documento'];
                    $vector_respuesta = [
                        'titulo' => "CONSULTA EXITOSA!",
                        'msj' => "Persona encontrada",
                        'cod' => 200,
                        'persona' => $doc_persona,
                        'ubicacion' => $ubi_persona,
                        'tipo_usuario'=> $tabla_origen,
                        'nombres'=>$nombres,
                        'apellidos'=> $apellidos,
                        'tipo_doc' => $tipo_doc,
                        'estado'=>$estado,
                        'cargo' =>$cargo,
                        'usr' => $usr
                    ];
                    
                    
                }
            }
            
            if($_SERVER['REQUEST_METHOD'] != 'POST'){
                $conexion01 -> close();
                unset($sentencia_buscar_aprendices, $sentencia_buscar_funcionarios, $sentencia_buscar_vigilantes, $sentencia_buscar_visitantes, $existe, $json, $datos, $documento, $conexion, $conexion01, $buscar_usr, $doc_persona);
                header('Content-Type: application/json');
                echo json_encode($vector_respuesta);
            }
            
        }
    }
    
    