<?php
if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../");
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
        header("Location: ../");
    } else {

        date_default_timezone_set('America/Bogota');

        $fecha_actual = new DateTime();
        $fecha_limite = $fecha_actual->modify('-24 hours');

        $sentencia_buscar_persona = "SELECT documento FROM aprendices WHERE ubicacion = 'ADENTRO' UNION SELECT documento FROM visitantes WHERE ubicacion = 'ADENTRO' UNION SELECT documento FROM funcionarios WHERE ubicacion = 'ADENTRO' UNION SELECT documento FROM vigilantes WHERE ubicacion = 'ADENTRO'";

        $conexion = require '../servicios/conexion.php';
        if (!$conexion) {
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                'cod' => 500,
            ];
        } else {
            $buscar_persona = $conexion01->query($sentencia_buscar_persona);
            if (!$buscar_persona) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Error en la consulta: ",
                    'cod' => 500,
                ];
            } else if ($buscar_persona->num_rows == 0) {
                $vector_respuesta = [
                    'titulo' => "CONSULTA FALLIDA",
                    'msj' => "¡No se encontraron personas, dentro del cab",
                    'cod' => 404,
                ];
            } else {
                $resultados = $buscar_persona->fetch_all();
                $personas = [];
                foreach ($resultados as $usuario) {
                    // Buscar el último movimiento de cada usuario
                    $sentencia_buscar_fechas = "SELECT fecha_registro FROM movimientos WHERE documento = '$usuario[0]' ORDER BY fecha_registro DESC LIMIT 1";
                   
                    $buscar_fecha = $conexion01->query($sentencia_buscar_fechas);

                    if (!$buscar_fecha) {
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "Error en la consulta: ",
                            'cod' => 500,
                        ];

                        $conexion01->close();
                        echo json_encode($vector_respuesta);
                        exit;
                    }else if($buscar_fecha->num_rows == 0){
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "No se encontraron movimientos para esta persona",
                            'cod' => 500,
                        ];

                        $conexion01->close();
                        echo json_encode($vector_respuesta);
                        exit;
                    }else{
                        $resultado_fecha = $buscar_fecha->fetch_assoc()['fecha_registro'];

                        $fecha_ultimo_movimiento = new DateTime($resultado_fecha);

                            // Comparar si el último movimiento es mayor o igual a 24 horas
                        if ($fecha_ultimo_movimiento <= $fecha_limite) {
                            $personas[] = $usuario[0];
                        }
                    }

                }
                $vector_respuesta = [
                    'titulo' => "EXITOSO",
                    'msj' => "¡Datos encontrados!",
                    'cod' => 200,
                    'datos' => $personas,
                    
                ];
                $conexion01->close();
            }
        }
        header('Content-Type: application/json');
        echo json_encode($vector_respuesta);
    }
}