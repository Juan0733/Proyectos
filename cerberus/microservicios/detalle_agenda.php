<?php

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    header("Location: ../", true);
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador") {
        header("Location: ../", true);
    } else {
        $fecha_registro = $_GET['fecha_registro'] ?? 'vacio';
        
        if ($fecha_registro == 'vacio') {
            $vector_respuesta = [
                'titulo' => "CONSULTA FALLIDA",
                'msj' => "Parametros incorrectos.",
                'cod' => 404,
            ];
        } else {
            $buscar_agenda = "SELECT 
                                    a.titulo,
                                    a.fecha_a,
                                    a.fecha_registro,
                                    a.motivo_a,
                                    a.usr_sistema as documento_usr,
                                    COALESCE(f.nombres, v.nombres, vi.nombres, ap.nombres) AS nombres,
                                    COALESCE(f.apellidos, v.apellidos, vi.apellidos, ap.apellidos) AS apellidos,
                                    sys.nombres as nombres_usr,
                                    sys.apellidos as apellidos_usr
                                FROM 
                                    agendas a
                                LEFT JOIN funcionarios f ON a.documento = f.documento
                                LEFT JOIN visitantes v ON a.documento = v.documento
                                LEFT JOIN vigilantes vi ON a.documento = vi.documento
                                LEFT JOIN aprendices ap ON a.documento = ap.documento
                                LEFT JOIN funcionarios sys ON a.usr_sistema = sys.documento
                                WHERE a.fecha_registro = '$fecha_registro'";
            // Conexión a la base de datos
            $conexion = require '../servicios/conexion.php';
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR DE CONEXION",
                    'msj' => "No se pudo conectar con la base de datos.",
                    'cod' => 500,
                ];
            } else {
                $consulta = $conexion01 -> query($buscar_agenda);
                if ($consulta -> num_rows == 0) {
                    $vector_respuesta = [
                        'titulo' => "CONSULTA FALLIDA",
                        'msj' => "Agenda no encontrada.",
                        'cod' => 404,
                    ];
                } else {
                    $resultado_busqueda = $consulta -> fetch_all(MYSQLI_ASSOC);
                    $meses = [
                        'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
                        'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
                        'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
                        'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
                    ];
                    $fecha = new DateTime($resultado_busqueda[0]['fecha_a']);
                    $mes = $fecha->format('F'); 
                    $mesEspanol = $meses[$mes]; 
                    $fecha_formateada = $fecha->format('j') . ' de ' . $mesEspanol;

                    $hora_formateada = $fecha->format('g:iA');
                    $hora_formateada = strtolower($hora_formateada); 

                    $datos = [
                        'titulo' => $resultado_busqueda[0]['titulo'],
                        'fecha_agenda' => $resultado_busqueda[0]['fecha_a'],
                        'fecha_registro' => $resultado_busqueda[0]['fecha_registro'],
                        'fecha'=> $fecha_formateada,
                        'hora'=> $hora_formateada,
                        'nombres_usr'=> $resultado_busqueda[0]['nombres_usr'],
                        'apellidos_usr'=> $resultado_busqueda[0]['apellidos_usr'],
                        'documento_usr'=> $resultado_busqueda[0]['documento_usr'],
                        'motivo' => $resultado_busqueda[0]['motivo_a'],
                        'agendados' => []
                    ];
                        // Recorremos todos los resultados
                    foreach($resultado_busqueda as $agenda) {
                        $datos['agendados'][] = ['nombres' => $agenda['nombres'], 'apellidos' => $agenda['apellidos']];
                    }
                    $vector_respuesta = [
                        'titulo' => "AGENDA ENCONTRADA",
                        'msj' => "Agenda encontrada con exito!",
                        'cod' => 200,
                        'datos' => $datos
                    ];
                }
            }
            // Enviar respuesta en formato JSON
           
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vector_respuesta);
    }
}