<?php
    //verificamos el intento de operación
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
       
    } else {
        session_start();
        if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
            $vector_respuesta=[
                'titulo'=>"ERROR",
                'msj'=>"Intento de operacion incorrecta",
                'cod'=>500
            ];
        } else {

            $json = file_get_contents('php://input');
            $datos = json_decode($json);

            $propietario = trim(filter_var($datos->propietario));
            $placa = trim(filter_var($datos->placa));
            $tipo = trim(filter_var($datos->tipo));

            $bandera = 1;
            //filtramos los datos recibidos
            if ($propietario == "" || strlen($propietario) < 6 || strlen($propietario) > 15) {
                $bandera = 0;
            } else if($placa == "" || strlen($placa) < 5 || strlen($placa) > 6){
                $bandera = 0;
            } else if($tipo == "" || strlen($tipo) < 4 || strlen($tipo) > 5) {
                $bandera = 0;
            }
            //Verificamos que los datos sean los esperados
            if ($bandera == 0) {
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "Datos incorrectos para la operación indicada.",
                    'cod' => 400,
                ];
            } else {
                date_default_timezone_set('America/Bogota'); 
                $sys_momento = time(); 
                $momento_registro = date("Y-m-d H:i:s", $sys_momento);


                $sentencia_buscar_visitantes = "SELECT `nombres` FROM `visitantes` WHERE `documento` = '$propietario';";
                $sentencia_buscar_vigilantes = "SELECT `nombres` FROM `vigilantes` WHERE `documento` = '$propietario';";
                $sentencia_buscar_funcionarios = "SELECT `nombres` FROM `funcionarios` WHERE `documento` = '$propietario';";
                $sentencia_buscar_aprendices = "SELECT `nombres` FROM `aprendices` WHERE `documento` = '$propietario';";
                $sentencia_insertar_vehiculo = "INSERT INTO `vehiculos`(`placa`, `documento`, `tipo`, `fecha_registro`) VALUES ('$placa','$propietario', '$tipo', '$momento_registro');";
               

                $conexion = require 'conexion.php';
                if (!$conexion) {
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                        'cod' => 500,
                    ];
                } else {
                    $existe = "";
                    $buscar_usr = $conexion01 -> query($sentencia_buscar_vigilantes);
                    if ($buscar_usr -> num_rows != 0) {
                    $existe = "vigilante";
                    }
                    if ($existe == "") {
                        $buscar_usr = $conexion01 -> query($sentencia_buscar_visitantes);
                        if ($buscar_usr -> num_rows != 0) {
                            $existe = "visitante";
                        }
                    }
                    if ($existe == "") {
                        $buscar_usr = $conexion01 -> query($sentencia_buscar_funcionarios);
                        if ($buscar_usr -> num_rows != 0) {
                            $existe = "funcionario";
                        }
                    }
                    if ($existe == "") {
                        $buscar_usr = $conexion01 -> query($sentencia_buscar_aprendices);
                        if ($buscar_usr -> num_rows != 0) {
                            $existe = "aprendiz";
                        }
                    }
                    if ($existe == "") {
                        $vector_respuesta = [
                            'titulo' => "PROPIETARIO NO EXISTE",
                            'msj' => "El propietario no se encuentra registrado.",
                            'cod' => 404,
                            'ruta' => "../componentes/plantillas/registro_visitantes.html"
                        ];
                    } else {
                        $registro_vehiculo = $conexion01->query($sentencia_insertar_vehiculo);
                        $filas_afectadas = $conexion01->affected_rows;
                        if ($filas_afectadas != 1) {
                            $vector_respuesta = [
                                'titulo' => "REGISTRO FALLIDO",
                                'msj' => "El registro ha fallado por alguna razón.",
                                'cod' => 500,
                                'ruta' => "../componentes/plantillas/registro_visitantes.html"
                            ];
                        } else {
                            $vector_respuesta = [
                                'titulo' => "REGISTRO EXITOSO!",
                                'msj' => "¡Vehiculo registrado con exitó!",
                                'cod' => 200,
                                'ruta' => "../componentes/plantillas/registro_visitantes.html"
                            ];
                        }
                    }
                            
                    $conexion01 -> close();

                    unset($sentencia_insertar_vehiculo, $sentencia_buscar_aprendices, $sentencia_buscar_funcionarios, $sentencia_buscar_vigilantes, $sentencia_buscar_visitantes, $buscar_usr);

                    header('Content-Type: application/json');

                    
                }
            }
        }
    }

    echo json_encode($vector_respuesta);