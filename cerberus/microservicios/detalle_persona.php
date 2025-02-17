<?php

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../", true);
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
        header("Location: ../", true);
    } else {
        $documento = $_GET['documento'] ?? 'vacio';

        // Conexión a la base de datos
        $conexion = require '../servicios/conexion.php';
        
        if (!$conexion) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos.",
                'cod' => 500,
            ];
        } else {
            // Arreglo de tablas donde se buscará el documento
            $tablas = ['vigilantes', 'visitantes', 'funcionarios', 'aprendices'];
            $persona_encontrada = null;

            // Iteramos sobre cada tabla
            foreach ($tablas as $tabla) {
                $sentencia_buscar = "
                    SELECT *, '$tabla' as tabla 
                    FROM `$tabla` 
                    WHERE `documento` = '$documento';
                ";

                $buscar = $conexion01->query($sentencia_buscar);

                if ($buscar && $buscar->num_rows > 0) {
                    $persona_encontrada = $buscar->fetch_assoc();
                    unset($persona_encontrada['contador'], $persona_encontrada['codigo_pw'], $persona_encontrada['fecha_registro']);
                    
                    break; // Detenemos la búsqueda si se encuentra el registro
                }
            }

            if ($persona_encontrada) {
                $vector_respuesta = [
                    'titulo' => "CONSULTA EXITOSA!",
                    'msj' => "Persona encontrada",
                    'cod' => 200,
                    'datos' => $persona_encontrada 
                ];
            } else {
                $vector_respuesta = [
                    'titulo' => "CONSULTA FALLIDA",
                    'msj' => "Persona no encontrada en ninguna tabla",
                    'cod' => 404,
                ];
            }
            $conexion01->close();
        }

        // Enviar respuesta en formato JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vector_respuesta);
    }
}