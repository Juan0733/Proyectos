<?php

if ($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: ../componentes/plantillas/registro_funcionario.html");
} else {
        $bandera = 1;

        if(!isset($_POST['numero_documento']) || $_POST['numero_documento'] == "" || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
            $bandera = 0;
        }else if (!isset($_POST['movil']) || $_POST['movil'] == "" || strlen($_POST['movil']) != 10) {
            $bandera = 0;
        } else if (!isset($_POST['correo']) || $_POST['correo'] == "" || strlen($_POST['correo']) < 11 || strlen($_POST['correo']) > 40) {
            $bandera = 0;
        }else if (!isset($_POST['cargo']) || $_POST['cargo'] == "") {
            $bandera = 0;
        }

        if($_POST['cargo'] == 'coordinador'){
            if (!isset($_POST['contrasena']) || $_POST['contrasena'] == "" || strlen($_POST['contrasena']) < 8) {
                $bandera = 0;
            }
        }
        
        if ($bandera == 0) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "Datos incorrectos para la operación indicada.",
                'cod' => 400,
            ];
            
        } else {
            
            $documento = trim($_POST['numero_documento']);
            $movil = trim($_POST['movil']);
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
            $cargo = trim($_POST['cargo']);
            
            unset($_POST['numero_documento'], $_POST['movil'], $_POST['correo'],$_POST['cargo']);

            if($cargo == 'coordinador'){
                $contrasena_cifrada = md5(trim($_POST['contrasena']));
                unset($_POST['contrasena']);
                $sentencia_actualizar_funcionario = "UPDATE funcionarios SET email = '$correo', telefono = '$movil', cargo = '$cargo', codigo_pw = '$contrasena_cifrada', estado = 'ACTIVO' WHERE documento = '$documento';";
            }else{
                $sentencia_actualizar_funcionario = "UPDATE funcionarios SET email = '$correo', telefono = '$movil', cargo = '$cargo', codigo_pw = null, estado = null WHERE documento = '$documento';";
            }
            
            $conexion = require '../servicios/conexion.php';

            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ACTUALIZACION FALLIDA",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
                
            } else {    
                $actualizar_funcionario = $conexion01->query($sentencia_actualizar_funcionario);
                $filas_afectadas = $conexion01 -> affected_rows;
                if ($filas_afectadas != 1) {
                    $vector_respuesta = [
                        'titulo' => "ACTUALIZACION FALLIDA",
                        'msj' => "La actualizacion ha fallado por alguna razón.",
                        'cod' => 500,
                    ];

                        $conexion01->close();
                        
                } else {
                    $vector_respuesta = [
                        'titulo' => "ACTUALIZACION EXITOSA!",
                        'msj' => "¡Funcionario actualizado con éxito!",
                        'cod' => 200,
                    ];
                        
                    $conexion01->close();
                    unset($sentencia_actualizar_funcionario, $documento, $movil, $correo, $cargo, $contrasena_cifrada);
                }
            }
        }
        echo json_encode($vector_respuesta);
    }
