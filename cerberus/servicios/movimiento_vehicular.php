<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $vector_respuesta = [
        'titulo' => "ERROR",
        'msj' => "Intento de operacion incorrecta",
        'cod' => 500
    ];
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe") {
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "No autorizado",
            'cod' => 500
        ];
    } else {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $propietario = $datos['propietario'];
        $placa = $datos['placa'];
        $puerta = $datos['puerta'];
        $pasajeros = $datos['pasajeros'];
        $tipo_movimiento = $datos['tipo_movimiento'];
        $usr_sistem = $_SESSION['inicio_sesion']['documento'];
        
        date_default_timezone_set('America/Bogota'); 
        $sys_momento = time();
        $momento_registro = date("Y-m-d H:i:s", $sys_momento);
        
        $sentencia_buscar_propietario = "SELECT `documento`, `placa` FROM `vehiculos` WHERE `documento` = '$propietario' AND `placa` = '$placa';";
        $sentencia_insertar_propietario = "INSERT INTO `vehiculos`(`placa`, `documento`, `tipo`, `fecha_registro`) VALUES ('$placa','$propietario', (SELECT v.tipo FROM vehiculos v WHERE v.placa = '$placa' LIMIT 1), '$momento_registro');";
        $sentencia_insertar_movimiento = "INSERT INTO `movimientos`(`documento`, `relacion_veh`, `vehiculo`, `fecha_registro`, `usr_sistem`, `puerta_registro`, `tipo_movimiento`) VALUES ('$propietario','propietario','$placa', '$momento_registro', '$usr_sistem', '$puerta', '$tipo_movimiento');";

        $conexion = require 'conexion.php';
        if (!$conexion) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                'cod' => 500,
            ];
        } else {
            $conexion01->begin_transaction();
            
            try {
                $propietario_identificado = true;
                $buscar_propietario = $conexion01->query($sentencia_buscar_propietario);
                if ($buscar_propietario->num_rows == 0) {
                    if($tipo_movimiento == "SALIDA"){
                        $vector_respuesta = [
                            'titulo' => "REGISTRO FALLIDO",
                            'msj' => "Propietario no identificado",
                            'cod' => 404
                        ];
                        $propietario_identificado = false;
                    } else {
                        $agregar_propietario = $conexion01->query($sentencia_insertar_propietario);
                        if ($conexion01->affected_rows == 0) {
                            throw new Exception("Error al registrar el propietario del vehículo");
                        }
                    }
                    
                }
                
                if ($propietario_identificado != false) {
                    $agregar_movimiento = $conexion01->query($sentencia_insertar_movimiento);
                    if($conexion01->affected_rows == 0) {
                        throw new Exception("Error al registrar el movimiento del propietario");
                    }

                    $ubicacion_actual_propietario = obtenerUbicacionPersona($propietario);
                    if($ubicacion_actual_propietario !== false) {
                        if(!actualizarUbicacionPersona($propietario, $ubicacion_actual_propietario, $conexion01)) {
                            throw new Exception("Error al actualizar ubicación del propietario");
                        }
                    }

                    foreach ($pasajeros as $pasajero) {
                        // Actualizar ubicación del pasajero
                        $ubicacion_actual_pasajero = obtenerUbicacionPersona($pasajero);
                        if($ubicacion_actual_pasajero !== false) {
                            if(!actualizarUbicacionPersona($pasajero, $ubicacion_actual_pasajero, $conexion01)) {
                                throw new Exception("Error al actualizar ubicación del pasajero: " . $pasajero);
                            }
                        }

                        $sentencia_insertar_movimiento = "INSERT INTO `movimientos`
                            (`documento`, `relacion_veh`, `vehiculo`, `fecha_registro`, `usr_sistem`, `puerta_registro`, `tipo_movimiento`) 
                            VALUES ('$pasajero','pasajero','$placa', '$momento_registro', '$usr_sistem', '$puerta','$tipo_movimiento');";

                        $agregar_movimiento = $conexion01->query($sentencia_insertar_movimiento);
                        if($conexion01->affected_rows == 0) {
                            throw new Exception("Error al registrar movimiento del pasajero: " . $pasajero);
                        }
                    }

                    $conexion01 -> commit();
                    if($tipo_movimiento == 'ENTRADA'){
                        $mensaje = '¡Entrada registrada con exitó!';
                    }else{
                        $mensaje = '¡Salida registrada con exitó!';
                    }

                    $vector_respuesta = [
                        'titulo' => "REGISTRO EXITOSO",
                        'msj' => $mensaje,
                        'cod' => 200
                    ]; 
                }
            } catch (Exception $e) {
                $conexion01 -> rollback(); // Aqui revertimos los cambios
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => $e->getMessage(),
                    'cod' => 400
                ];
            }
            
            $conexion01->close();
        }
    }
}

echo json_encode($vector_respuesta);

function obtenerUbicacionPersona($documento) {
    $sentencia_buscar_visitantes = "SELECT `documento`, `ubicacion` FROM `visitantes` WHERE `documento` = '$documento';";
    $sentencia_buscar_vigilantes = "SELECT `documento`, `ubicacion` FROM `vigilantes` WHERE `documento` = '$documento';";
    $sentencia_buscar_funcionarios = "SELECT `documento`, `ubicacion` FROM `funcionarios` WHERE `documento` = '$documento';";
    $sentencia_buscar_aprendices = "SELECT `documento`, `ubicacion` FROM `aprendices` WHERE `documento` = '$documento';";

    global $conexion01;

    $tablas = [
        ['query' => $sentencia_buscar_vigilantes, 'tabla' => 'vigilantes'],
        ['query' => $sentencia_buscar_visitantes, 'tabla' => 'visitantes'],
        ['query' => $sentencia_buscar_funcionarios, 'tabla' => 'funcionarios'],
        ['query' => $sentencia_buscar_aprendices, 'tabla' => 'aprendices']
    ];
    
    foreach ($tablas as $tabla) {
        $buscar_usr = $conexion01->query($tabla['query']);
        if ($buscar_usr->num_rows != 0) {
            $datos = $buscar_usr->fetch_assoc();
            return [
                'ubicacion' => $datos['ubicacion'],
                'tabla' => $tabla['tabla']
            ];
        }
    }
    return false;
}

function actualizarUbicacionPersona($documento, $datos_ubicacion, $conexion) {
    if ($datos_ubicacion['ubicacion'] == "ADENTRO") {
        $nueva_ubicacion = "AFUERA";
    } else if ($datos_ubicacion['ubicacion'] == "AFUERA") {
        $nueva_ubicacion = "ADENTRO";
    } else {
        return false;
    }
    
    $sentencia_actualizar = "UPDATE `" . $datos_ubicacion['tabla'] . "` SET `ubicacion`='$nueva_ubicacion' WHERE `documento`='$documento'";
    return $conexion->query($sentencia_actualizar);
}