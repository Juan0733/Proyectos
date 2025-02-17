<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
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
        // Obtener el parámetro 'placa' desde la URL
        $placa = $_GET['placa'] ?? 'vacio';

        // Validar que se haya proporcionado una placa
        if ($placa == 'vacio') {
            $vector_respuesta = [
                'titulo' => "CONSULTA FALLIDA",
                'msj' => "El parámetro 'placa' es obligatorio",
                'cod' => 400
            ];
        } else {
            // Consulta SQL a la tabla 'vehiculos', filtrando por placa
            $buscar_vehiculo = "SELECT DISTINCT(`placa`), `tipo` FROM `vehiculos` WHERE `placa` = '$placa';";

            // Conexión a la base de datos
            $conexion = require '../servicios/conexion.php';

            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                    'cod' => 500
                ];
            } else {
                // Ejecutar la consulta
                $consulta = $conexion01->query($buscar_vehiculo);

                if ($consulta->num_rows == 0) {
                    // Si no se encuentra el vehículo, devolver error
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "No se encontró el vehículo con la placa especificada",
                        'cod' => 404
                    ];
                } else {
                    // Obtener el primer (y único) resultado
                    $vehiculo = $consulta->fetch_assoc();

                    // Respuesta con los datos del vehículo
                    $vector_respuesta = $vehiculo;
                }

                $conexion01->close();
            }
        }
    }

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($vector_respuesta);
}
?>