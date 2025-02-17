<?php
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: ../componentes/plantillas/registro_visitantes.html");
    } else {
        $bandera = 1;
        if (!isset($_POST['tipo_novedad']) || $_POST['tipo_novedad'] == "" || strlen($_POST['tipo_novedad']) != 17) {
            $bandera = 0;
        } elseif (empty($_POST['numero_documento']) || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
            $bandera = 0;
        } else if (!isset($_POST['numero_placa']) || $_POST['numero_placa'] == "" || strlen($_POST['numero_placa']) != 6) {
            $bandera = 0;
        }else if (!isset($_POST['descripcion']) || $_POST['descripcion'] == "" || strlen($_POST['descripcion']) < 5) {
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
            $vehiculo = strtolower(trim(filter_var($_POST['numero_placa'])));
            $puerta_registro = strtolower(trim(filter_var($_POST['puerta_registro'])));
            $descripcion = strtolower(trim(filter_var($_POST['descripcion'])));
            

            unset($_POST['tipo_novedad'], $_POST['numero_documento'], $_POST['numero_placa'], $_POST['descripcion'], $_POST['puerta_registro']);

            date_default_timezone_set('America/Bogota');
            $sys_momento = time();
            $momento_registro = date("Y-m-d H:i:s", $sys_momento);
            $usr_sistema = $_SESSION['inicio_sesion']['documento'];
           
            $sentencia_insertar_novedad = "INSERT INTO `novedades_vehiculo_prest`(`puerta_registro`, `fecha_registro`, `observacion`, `documento`, `vehiculo`, `usr_sistema`) VALUES ('$puerta_registro','$momento_registro', '$descripcion','$numero_documento', '$vehiculo', '$usr_sistema');";

            $sentencia_insertar_propietario = "INSERT INTO `vehiculos`(`placa`, `documento`, `tipo`, `fecha_registro`) VALUES ('$vehiculo','$numero_documento', (SELECT v.tipo FROM vehiculos v WHERE v.placa = '$vehiculo' LIMIT 1), '$momento_registro');";

            $conexion = require 'conexion.php';
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500,
                ];
            } else {
                $registrar_novedad = $conexion01->query($sentencia_insertar_novedad);
                $filas_afectadas = $conexion01->affected_rows;
                if ($filas_afectadas != 1) {
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "El registro ha fallado por alguna razón.",
                        'cod' => 400,
                    ];
                } else {
                    $registrar_propieatario = $conexion01->query($sentencia_insertar_propietario);
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