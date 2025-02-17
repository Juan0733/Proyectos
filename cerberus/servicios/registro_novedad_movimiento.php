<?php
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: ../componentes/plantillas/registro_visitantes.html");
    } else {
        $bandera = 1;
        if (!isset($_POST['tipo_novedad']) || $_POST['tipo_novedad'] == "" || strlen($_POST['tipo_novedad']) < 20 || strlen($_POST['tipo_novedad']) > 21) {
            $bandera = 0;
        } elseif (empty($_POST['numero_documento']) || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
            $bandera = 0;
        } else if (!isset($_POST['puerta_acontecimiento']) || $_POST['puerta_acontecimiento'] == "" || strlen($_POST['puerta_acontecimiento']) < 8 || strlen($_POST['puerta_acontecimiento']) > 9) {
            $bandera = 0;
        } else if (!isset($_POST['descripcion']) || $_POST['descripcion'] == "" || strlen($_POST['descripcion']) < 5) {
            $bandera = 0;
        } else if (!isset($_POST['puerta_registro']) || $_POST['puerta_registro'] == "" || strlen($_POST['puerta_registro']) < 8 || strlen($_POST['puerta_registro']) > 9) {
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

            $tipo_novedad = strtolower(trim($_POST['tipo_novedad']));
            $numero_documento = trim(filter_var($_POST['numero_documento']));
            $puerta_acontecimiento = strtolower(trim(filter_var($_POST['puerta_acontecimiento'])));
            $puerta_registro = strtolower(trim(filter_var($_POST['puerta_registro'])));
            $descripcion = strtolower(trim(filter_var($_POST['descripcion'])));
            

            unset($_POST['tipo_novedad'], $_POST['numero_documento'], $_POST['puerta_acontecimiento'], $_POST['fecha_acontecimiento'], $_POST['descripcion'], $_POST['puerta_registro']);

            date_default_timezone_set('America/Bogota');
            $sys_momento = time();
            $momento_registro = date("Y-m-d H:i:s", $sys_momento);
            $usr_sistema = $_SESSION['inicio_sesion']['documento'];
           
            $sentencia_insertar = "INSERT INTO `novedades_entrada_salida`(`puerta_suceso`, `puerta_registro`, `fecha_registro`,`tipo_novedad`, `observacion`, `documento`, `usr_sistem`) VALUES ('$puerta_acontecimiento','$puerta_registro','$momento_registro', '$tipo_novedad', '$descripcion','$numero_documento','$usr_sistema');";

            $conexion = require 'conexion.php';
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
            } else {
                $registrar_novedad = $conexion01->query($sentencia_insertar);
                $filas_afectadas = $conexion01->affected_rows;
                if ($filas_afectadas != 1) {
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "El registro ha fallado por alguna razón.",
                        'cod' => 400,
                    ];
                } else {
                    $tablas = ['aprendices', 'funcionarios', 'visitantes', 'vigilantes'];
                    if($tipo_novedad == 'entrada no registrada'){
                        $ubicacion = 'ADENTRO';
                    }else if($tipo_novedad == 'salida no registrada'){
                        $ubicacion = 'AFUERA';
                    }
                    
                    foreach ($tablas as $tabla) {
                        $sentencia_buscar = "SELECT `ubicacion` FROM `$tabla` WHERE `documento` = '$numero_documento';";
                        $buscar_usr = $conexion01->query($sentencia_buscar);

                        if ($buscar_usr && $buscar_usr->num_rows > 0) {
                          
                            $sentencia_update = "UPDATE `$tabla` SET `ubicacion` = '$ubicacion' WHERE `documento` = '$numero_documento';";
                            break;
                        }
                    }

                    $actualizar_ubicacion = $conexion01->query($sentencia_update);
                    $filas_afectadas = $conexion01->affected_rows;
                    if ($filas_afectadas != 1) {
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "El registro ha fallado por alguna razón.",
                            'cod' => 400,
                        ];
                    } else {
                        $vector_respuesta = [
                            'titulo' => "REGISTRO",
                            'msj' => "¡La novedad se registro con exitó!",
                            'cod' => 200,
                        ];
                    }
                }
                $conexion01->close();
            }
        }
    }

echo json_encode($vector_respuesta);