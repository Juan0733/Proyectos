<?php

    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "Intento de operación incorrecta",
            'cod' => 500
        ];
    } else {
        session_start();
        if ($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador" && $_SESSION['inicio_sesion']['cargo'] != "subdirector") {
            header("Location: ../", true);
        } else {    
            $fecha = $_GET['fecha'] ?? 'vacio';
            $documento = $_GET['documento'] ?? 'vacio';

            if ($fecha == 'vacio' && $documento == 'vacio') {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Parametros no válidos.",
                    'cod' => 400
                ];
            } else {
                $conexion  = require '../servicios/conexion.php';
                if (!$conexion) {
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "No se pudo conectar con la base de datos.",
                        'cod' => 500
                    ];
                } else {
                    if ($documento == 'vacio') {

                        $sentencia_buscar_fechas = "SELECT fecha_registro FROM agendas WHERE DATE(fecha_a) = '$fecha' GROUP BY fecha_registro ;";

                        $consulta = $conexion01->query($sentencia_buscar_fechas);

                        if (!$consulta->num_rows > 0) {
                            $vector_respuesta = [
                                'titulo' => "ERROR",
                                'msj' => 'No se encontraron agendas registradas.',
                                'cod' => 400
                            ];

                            $conexion01->close();
                            echo json_encode($vector_respuesta);
                            exit;

                        }else{
                            $ids_agendas = $consulta->fetch_all();
                            $resultado_busqueda = [];
                            foreach ($ids_agendas as $id) {
                                $sentencia_buscar_agendas = "
                                SELECT 
                                    a.titulo,
                                    a.fecha_a,
                                    a.fecha_registro,
                                    COALESCE(f.nombres, v.nombres, vi.nombres, ap.nombres) AS nombres,
                                    COALESCE(f.apellidos, v.apellidos, vi.apellidos, ap.apellidos) AS apellidos
                                FROM 
                                    agendas a
                                LEFT JOIN funcionarios f ON a.documento = f.documento
                                LEFT JOIN visitantes v ON a.documento = v.documento
                                LEFT JOIN vigilantes vi ON a.documento = vi.documento
                                LEFT JOIN aprendices ap ON a.documento = ap.documento
                                WHERE a.fecha_registro = '$id[0]'
                                LIMIT 1;";
                                $consulta = $conexion01->query($sentencia_buscar_agendas);
                                if (!$consulta->num_rows > 0) {
                                    $vector_respuesta = [
                                        'titulo' => "ERROR",
                                        'msj' => "Hubo un error, intetalo nuevamente",
                                        'cod' => 500
                                    ];
        
                                    $conexion01->close();
                                    echo json_encode($vector_respuesta);
                                    exit;
        
                                }else{
                                    $resultado_busqueda[] = $consulta->fetch_assoc();
                                }
                            }
                        }

                    } else {
                        $sentencia_buscar_agendas = "
                        SELECT 
                            a.titulo,
                            a.fecha_a,
                            a.fecha_registro,
                            COALESCE(f.nombres, v.nombres, vi.nombres, ap.nombres) AS nombres,
                            COALESCE(f.apellidos, v.apellidos, vi.apellidos, ap.apellidos) AS apellidos
                        FROM 
                            agendas a
                        LEFT JOIN funcionarios f ON a.documento = f.documento
                        LEFT JOIN visitantes v ON a.documento = v.documento
                        LEFT JOIN vigilantes vi ON a.documento = vi.documento
                        LEFT JOIN aprendices ap ON a.documento = ap.documento
                        WHERE DATE(a.fecha_a) = '$fecha' 
                        AND a.documento = '$documento';";

                        $consulta = $conexion01->query($sentencia_buscar_agendas);

                        if (!$consulta->num_rows != 0) {
                            $vector_respuesta = [
                                'titulo' => "ERROR",
                                'msj' => "No se encontraron agendas registradas.",
                                'cod' => 400
                            ];

                            $conexion01->close();
                            echo json_encode($vector_respuesta);
                            exit;
                        } else {
                            $resultado_busqueda = $consulta->fetch_all(MYSQLI_ASSOC);
                        }
                    }
                    $cargo = $_SESSION['inicio_sesion']['cargo'];
                    $datos = [];
                    $meses = [
                        'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
                        'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
                        'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
                        'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
                    ];
                        
                        // Recorremos todos los resultados
                    foreach($resultado_busqueda as $agenda) {
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                            
                        $fecha = new DateTime($agenda['fecha_a']);
                        $mes = $fecha->format('F'); 
                        $mesEspanol = $meses[$mes]; 
                        $fecha_formateada = $fecha->format('j') . ' de ' . $mesEspanol;

                        $hora_formateada = $fecha->format('g:iA');
                        $hora_formateada = strtolower($hora_formateada); 

                        $datos[] = [
                            'titulo' => $agenda['titulo'],
                            'fecha'=> $fecha_formateada,
                            'hora'=> $hora_formateada,
                            'fecha_registro' => $agenda['fecha_registro'],
                            'nombres'=> $agenda['nombres'],
                            'apellidos'=> $agenda['apellidos'],
                        ];
                    }

                    $vector_respuesta = [
                        'titulo' => "RESULTADO",
                        'msj' => "Se encontraron agendas",
                        'cod' => 200,
                        'cargo' => $cargo,
                        'datos' => $datos

                    ];

                    $conexion01->close();
                }
            }
        }
    }
echo json_encode($vector_respuesta);
