<?php
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../");
} else {
        
    $conexion = require '../servicios/conexion.php';
    if (!$conexion) {
        $vector_respuesta = [
            'titulo' => "REGISTRO FALLIDO",
            'msj' => "No se pudo conectar con la base de datos por alguna razón.",
            'cod' => 500,
        ];
    } else {
        $sentencia_buscar = "SELECT `nombre` FROM `programas`";
        $buscar_programas = $conexion01->query($sentencia_buscar);
        if (!$buscar_programas) {
            $vector_respuesta = [
                'titulo' => "ERROR",
                'msj' => "Error en la consulta: " . $conexion01->error,
                'cod' => 500,
            ];
        } else if ($buscar_programas->num_rows == 0) {
            $vector_respuesta = [
                'titulo' => "CONSULTA FALLIDA",
                'msj' => "Programas no encontrados",
                'cod' => 404,
            ];
        } else {
            $programas = $buscar_programas->fetch_all();
               
                $vector_respuesta = [
                    'titulo' => "CONSULTA EXITOSA!",
                    'msj' => "Programas encontrados",
                    'cod' => 200,
                    'programas' => $programas,
                ];
            $conexion01->close();
        }
    }
    header('Content-Type: application/json');
    echo json_encode($vector_respuesta);
}