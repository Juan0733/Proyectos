<?php

    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: ../componentes/plantillas/registro_funcionarios.html");
    } else {
        $bandera = 1;

        if (!isset($_POST['tipo_documento']) || $_POST['tipo_documento'] == "" || strlen($_POST['tipo_documento']) < 2 || strlen($_POST['tipo_documento']) > 4) {
            $bandera = 0;
        } else if (!isset($_POST['numero_documento']) || $_POST['numero_documento'] == "" || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
            $bandera = 0;
        } else if (!isset($_POST['nombres']) || $_POST['nombres'] == "" || strlen($_POST['nombres']) < 3 || strlen($_POST['nombres']) > 25) {
            $bandera = 0;
        } else if (!isset($_POST['apellidos']) || $_POST['apellidos'] == "" || strlen($_POST['apellidos']) < 3 || strlen($_POST['apellidos']) > 25) {
            $bandera = 0;
        } else if (!isset($_POST['movil']) || $_POST['movil'] == "" || strlen($_POST['movil']) != 10) {
            $bandera = 0;
        } else if (!isset($_POST['correo']) || $_POST['correo'] == "" || strlen($_POST['correo']) < 11 || strlen($_POST['correo']) > 40) {
            $bandera = 0;
        } else if (!isset($_POST['cargo']) || $_POST['cargo'] == "") {
            $bandera = 0;
        }
        
        
        $tipo_registro = $_POST['tipo_registro'] ?? '';

        if($tipo_registro != 'autoregistro' && $_POST['cargo'] == 'coordinador'){
            if (!isset($_POST['contrasena']) || $_POST['contrasena'] == "" || strlen($_POST['contrasena']) < 8) {
                $bandera = 0;
            }
        }

        if ($bandera == 0) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "Datos incorrectos para la operación indicada.",
                'cod' => 400,
                'ruta' => "../componentes/plantillas/registro_funcionarios.html"
            ];
            
        } else {
            
            $tipo_documento = trim($_POST['tipo_documento']);
            $documento = trim($_POST['numero_documento']);
            $nombres = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $movil = trim($_POST['movil']);
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
            $cargo = trim($_POST['cargo']);
            $ubicacion = 'AFUERA';
            
            unset($_POST['tipo_documento'], $_POST['numero_documento'], $_POST['nombres'], $_POST['apellidos'], $_POST['movil'], $_POST['correo'], $_POST['cargo']);

            date_default_timezone_set('America/Bogota');
            $sys_momento = time();
            $momento_registro = date("Y-m-d H:i:s", $sys_momento);
            
            $sentencia_buscar_visitantes = "SELECT ubicacion FROM visitantes WHERE documento = '$documento';";
            $sentencia_buscar_vigilantes = "SELECT ubicacion FROM vigilantes WHERE documento = '$documento';";
            $sentencia_buscar_funcionarios = "SELECT ubicacion FROM funcionarios WHERE documento = '$documento';";
            $sentencia_buscar_aprendices = "SELECT ubicacion FROM aprendices WHERE documento = '$documento';";


            $conexion = require 'conexion.php';

            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                    'ruta' => "../componentes/plantillas/registro_funcionarios.html"
                ];
                
            } else {
                $tabla = "";
                $buscar_usr = $conexion01 -> query($sentencia_buscar_vigilantes);
                if ($buscar_usr -> num_rows != 0) {

                   $tabla = "vigilantes";
                   $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];

                }
                if ($tabla == "") {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_visitantes);
                    if ($buscar_usr -> num_rows != 0) {
                        $tabla = "visitantes";
                        $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
                    }
                }
                if ($tabla == "") {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_funcionarios);
                    if ($buscar_usr -> num_rows != 0) {
                        $tabla = "funcionarios";
                    }
                }
                if ($tabla == "") {
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_aprendices);
                    if ($buscar_usr -> num_rows != 0) {
                        $tabla = "aprendices";
                        $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
                    }
                }
                
                if ($tabla) {
                    if($tabla == 'funcionarios'){
                        $vector_respuesta = [
                            'titulo' => "REGISTRO FALLIDO",
                            'msj' => "El usuario ya existe como funcionario, no es posible registrarlo.",
                            'cod' => 400,
                            'ruta' => "../componentes/plantillas/registro_funcionarios.html"
                        ];

                        $conexion01->close();

                        if ($tipo_registro != "autoregistro") {
                            echo json_encode($vector_respuesta);
                        } else {
                            include "../componentes/plantillas/mensajero.php";
                        }

                        exit;
            
                    }else{
                        require '../microservicios/eliminar_usuario.php';
                        
                        if($vector_respuesta['cod'] != 200) {
                            $vector_respuesta = [
                                'titulo' => "REGISTRO FALLIDO",
                                'msj' => "El registro ha fallado por alguna razón.",
                                'cod' => 500,
                                'ruta' => "../componentes/plantillas/registro_funcionarios.html"
                            ];
    
                            $conexion01->close();

                            if ($tipo_registro != "autoregistro") {
                                echo json_encode($vector_respuesta);
                            } else {
                                include "../componentes/plantillas/mensajero.php";
                            }

                            exit;
                        }
                    }
                    
                } 
                
                if($tipo_registro != 'autoregistro' && $cargo == 'coordinador'){
                    $contrasena_cifrada = md5(trim($_POST['contrasena']));
                    unset($_POST['contrasena']);
                
                    $sentencia_insertar = "INSERT INTO funcionarios(documento, tipo_documento, nombres, apellidos, cargo, telefono, email, fecha_registro, codigo_pw, estado, ubicacion) 
                    VALUES ('$documento', '$tipo_documento', '$nombres', '$apellidos', '$cargo', '$movil', '$correo', '$momento_registro', '$contrasena_cifrada', 'ACTIVO', '$ubicacion');";
    
                }else{
                    $sentencia_insertar = "INSERT INTO funcionarios(documento, tipo_documento, nombres, apellidos, cargo, telefono, email, fecha_registro, ubicacion) 
                    VALUES ('$documento', '$tipo_documento', '$nombres', '$apellidos', '$cargo', '$movil', '$correo', '$momento_registro', '$ubicacion');";
                }

                $registro_funcionario = $conexion01->query($sentencia_insertar);
                $filas_afectadas = $conexion01 -> affected_rows;
                if ($filas_afectadas != 1) {
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "El registro ha fallado por alguna razón.",
                        'cod' => 500,
                        'ruta' => "../componentes/plantillas/registro_funcionarios.html"
                    ];

                    $conexion01->close();
                        
                } else {
                    $vector_respuesta = [
                        'titulo' => "REGISTRO EXITOSO!",
                        'msj' => "¡Funcionario registrado con éxito!",
                        'cod' => 200,
                        'ruta' => "../componentes/plantillas/registro_funcionarios.html"
                    ];
                        
                    $conexion01->close();
                    unset($sentencia_buscar_vigilantes, $sentencia_buscar_visitantes, $sentencia_buscar_funcionarios, $sentencia_aprendices, $sentencia_insertar, $conexion, $buscar_usr, $registro_funcionario, $tipo_documento, $documento, $nombres, $apellidos, $movil, $correo, $cargo, $contrasena_cifrada, $momento_registro, $sys_momento);
                }
                
            }
        }

        if ($tipo_registro != "autoregistro") {
            echo json_encode($vector_respuesta);
        } else {
            include "../componentes/plantillas/mensajero.php";
        }
    }

