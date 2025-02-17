<?php
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../");
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
        header("Location: ../");
    } else {
        $placa = $_GET['placa'] ?? 'vacio';

        $sentencia_buscar_veh = "SELECT `placa`, `tipo`, `documento` FROM `vehiculos` WHERE `placa` = '$placa'";

        $conexion = require '../servicios/conexion.php';
        if (!$conexion) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                'cod' => 500,
            ];
        } else {
            $buscar_veh = $conexion01->query($sentencia_buscar_veh);
            if (!$buscar_veh) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Error en la consulta: " . $conexion01->error,
                    'cod' => 500,
                ];
            } else if ($buscar_veh->num_rows == 0) {
                $vector_respuesta = [
                    'titulo' => "CONSULTA FALLIDA",
                    'msj' => "Vehículo no encontrado",
                    'cod' => 404,
                ];
            } else {
                $propietarios = [];
                while ($doc = $buscar_veh->fetch_assoc()) {
                    $documento = $doc['documento'];
                    $tipo=$doc['tipo'];
                    $existe = 0;
                    $sentencia_buscar_vigilantes = "SELECT `nombres`, `apellidos`, `documento` FROM `vigilantes` WHERE `documento` = '$documento'";
                    $sentencia_buscar_visitantes = "SELECT `nombres`, `apellidos`, `documento` FROM `visitantes` WHERE `documento` = '$documento'";
                    $sentencia_buscar_funcionarios = "SELECT `nombres`, `apellidos`, `documento` FROM `funcionarios` WHERE `documento` = '$documento'";
                    $sentencia_buscar_aprendices = "SELECT `nombres`, `apellidos`, `documento` FROM `aprendices` WHERE `documento` = '$documento'";

                    $buscar_usr = $conexion01->query($sentencia_buscar_vigilantes);
                    if ($buscar_usr && $buscar_usr->num_rows > 0) {
                        $propietarios[] = $buscar_usr->fetch_assoc();
                        $existe = 1;
                    }
                    if ($existe == 0) {
                        $buscar_usr = $conexion01->query($sentencia_buscar_visitantes);
                        if ($buscar_usr && $buscar_usr->num_rows > 0) {
                            $propietarios[] = $buscar_usr->fetch_assoc();
                            $existe = 1;
                        }
                    }
                    if ($existe == 0) {
                        $buscar_usr = $conexion01->query($sentencia_buscar_funcionarios);
                        if ($buscar_usr && $buscar_usr->num_rows > 0) {
                            $propietarios[] = $buscar_usr->fetch_assoc();
                            $existe = 1;
                        }
                    }
                    if ($existe == 0) {
                        $buscar_usr = $conexion01->query($sentencia_buscar_aprendices);
                        if ($buscar_usr && $buscar_usr->num_rows > 0) {
                            $propietarios[] = $buscar_usr->fetch_assoc();
                            $existe = 1;
                        }
                    }
                }
                if (empty($propietarios)) {
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "No se encontraron propietarios",
                        'cod' => 400,
                    ];
                } else {
                    $vector_respuesta = [
                        'titulo' => "CONSULTA EXITOSA!",
                        'msj' => "Propietarios encontrados",
                        'cod' => 200,
                        'propietarios' => $propietarios,
                        'placa'=> $placa,
                        'tipo'=> $tipo
                    ];
                }
                $conexion01->close();
            }
        }
        unset($sentencia_buscar_aprendices, $sentencia_buscar_funcionarios, 
              $sentencia_buscar_vigilantes, $sentencia_buscar_visitantes, 
              $existe, $json, $datos, $placa, $conexion, $conexion01, 
              $buscar_veh, $nombres);
        header('Content-Type: application/json');
        echo json_encode($vector_respuesta);
    }
}