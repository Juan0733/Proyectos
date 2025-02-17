<?php
session_start();
    if($_SERVER['REQUEST_METHOD']!= 'GET'){
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
                $tipo = $_GET['tipo'] ?? "entrada";
                $fecha = $_GET['fecha'] ?? null;
                $documento = $_GET['documento'] ?? null;
                $novedad = "
                        SELECT 
                            n.tipo_novedad AS tipo_novedad,
                            n.fecha_registro,
                            n.observacion,
                            COALESCE(f.nombres, v.nombres, vi.nombres, a.nombres) AS nombres,
                            COALESCE(f.apellidos, v.apellidos, vi.apellidos, a.apellidos) AS apellidos
                        FROM 
                            novedades_entrada_salida n
                        LEFT JOIN funcionarios f ON n.documento = f.documento
                        LEFT JOIN visitantes v ON n.documento = v.documento
                        LEFT JOIN vigilantes vi ON n.documento = vi.documento
                        LEFT JOIN aprendices a ON n.documento = a.documento
                        WHERE 
                        n.tipo_novedad = '$tipo'
                        AND DATE(n.fecha_registro) = '$fecha'
                        AND n.documento = '$documento';
                ";
                $conexion = require '../servicios/conexion.php';

                if(!$conexion){
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "Datos incorrectos para la operación indicada, Error de conexión",
                        'cod' => 500
                    ];
                } else {
                    $consulta = $conexion01->query($novedad);

                    if($consulta->num_rows == 0){
                        $vector_respuesta = [
                            'titulo' => "INFO",
                            'msj' => "No se encontron resultados",
                            'cod' => 500
                        ];
                    } else {
                        //Almaceno los resultados
                        $resultado_busqueda = $consulta->fetch_all(MYSQLI_ASSOC);
                        $datos = [];
                        $meses = [
                            'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
                            'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
                            'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
                            'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
                        ];
                        
                        // Recorremos todos los resultados
                        foreach($resultado_busqueda as $novedades) {
                            setlocale(LC_TIME, 'es_ES.UTF-8');
                            
                            $fecha = new DateTime($novedades['fecha_registro']);
                            $mes = $fecha->format('F'); 
                            $mesEspanol = $meses[$mes]; 
                            $fecha_formateada = $fecha->format('j') . ' de ' . $mesEspanol;


                            $hora_formateada = $fecha->format('g:iA');
                            $hora_formateada = strtolower($hora_formateada); 

                            $datos[] = [
                                'tipo_novedad' => $novedades['tipo_novedad'],
                                'fecha'=> $fecha_formateada,
                                'hora'=> $hora_formateada,
                                'nombres'=> $novedades['nombres'],
                                'apellidos'=> $novedades['apellidos'],
                                'observacion'=>$novedades['observacion'],
                                'cod' => 200
                            ];
                        }
                        $vector_respuesta = $datos;
                    }   
                }    
            }    
        
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($vector_respuesta);