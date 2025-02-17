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
        // Obtener el parámetro 'tipo' desde la URL
        $tipo = $_GET['tipo'] ?? 'vacio';

        // Validar que se haya proporcionado un tipo
        if ($tipo == 'vacio') {
            $vector_respuesta = [
                'titulo' => "CONSULTA FALLIDA",
                'msj' => "El parámetro 'tipo' es obligatorio",
                'cod' => 400
            ];
        } else {
            
            $buscar_vehiculo = "SELECT DISTINCT(`placa`), `tipo` FROM `vehiculos` WHERE `tipo` = '$tipo' limit 9;";

            
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
                    
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "No se encontraron vehículos con el tipo especificado",
                        'cod' => 404
                    ];
                } else {
                    
                    $resultado_busqueda = $consulta->fetch_all(MYSQLI_ASSOC);
                    $datos = [];

                    
                    foreach ($resultado_busqueda as $vehiculo) {
                        $datos[] = [
                            'placa' => $vehiculo['placa'],
                            'tipo' => $vehiculo['tipo']
                        ];
                    }

                    // Respuesta con los datos de los vehículos
                    $vector_respuesta = [
                        'titulo' => "CONSULTA EXITOSA",
                        'msj' => "Se encontraron vehiculos",
                        'cod' => 200,
                        'datos' => $datos
                    ];
                    
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