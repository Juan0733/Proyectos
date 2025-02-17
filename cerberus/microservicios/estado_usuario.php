<?php
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $vector_respuesta = [
        'titulo' => "ERROR",
        'msj' => "Intento de operación incorrecta",
        'cod' => 500
    ];
    header("Location: ../", true);
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != 'jefe' && $_SESSION['inicio_sesion']['cargo'] != 'subdirector') {
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "No autorizado",
            'cod' => 500
        ];
        header("Location: ../", true);
    } else {
        // Obtener el parámetro 'placa' desde la URL
        $tabla = $_GET['tabla'] ?? 'vacio';
        $documento = $_GET['documento'] ?? 'vacio';
        $estado = $_GET['estado'] ?? 'vacio';

        // Validar que se haya proporcionado una placa
        if ($tabla == 'vacio' || $documento == 'vacio' || $estado == 'vacio') {
            $vector_respuesta = [
                'titulo' => "CONSULTA FALLIDA",
                'msj' => "Parametros incompletos",
                'cod' => 400
            ];
        } else {
            // Consulta SQL a la tabla 'vehiculos', filtrando por placa
            $sentencia = "UPDATE $tabla SET estado = '$estado' WHERE documento = '$documento';";

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
                $desactiva_usuario = $conexion01->query($sentencia);
                $filas_afectadas = $conexion01 -> affected_rows;
                if ($filas_afectadas != 1) {
                    
                    $vector_respuesta = [
                        'titulo' => "OPERACION FALLIDA",
                        'msj' => "No se pudo hacer la operacion el usuario",
                        'cod' => 404
                    ];
                } else {
                    if($estado == 'ACTIVO'){
                        $mensaje = '¡El usuario fue activado!';
                    }else{
                        $mensaje = '¡El usuario fue desactivado!';
                    }
                    $vector_respuesta = [
                        'titulo' => "OPERACION EXITOSA",
                        'msj' => $mensaje,
                        'cod' => 200
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