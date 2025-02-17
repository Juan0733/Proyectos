<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        header("Location: ../", true);
    } else {
        if ($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "subdirector") {
            header("Location: ../", true);
        } else {
            // Control de parámetros iniciales
            $tipo = $_GET['tipo'] ?? 'ENTRADA';
            $puerta = $_GET['puerta'] ?? '';
            $fecha_inicio = $_GET['fecha_inicio'] ?? '';
            $fecha_fin = $_GET['fecha_fin'] ?? '';
            $documento = $_GET['documento'] ?? '';

            // Construimos la sentencia base
            $sentencia_informes_tabla = "
                SELECT 
                    fecha_registro,
                    tipo_movimiento,
                    documento,
                    vehiculo,
                    relacion_veh,
                    puerta_registro,
                    usr_sistem
                FROM movimientos
                WHERE tipo_movimiento = '$tipo' AND documento='$documento'";

            // Agregamos los filtros opcionales solo si existen
            if (!empty($puerta)) {
                $sentencia_informes_tabla .= " AND puerta_registro = '$puerta'";
            }

            if (!empty($fecha_inicio) && !empty($fecha_fin)) {
                // Para la fecha inicial usamos el inicio del día (00:00:00)
                // Para la fecha final usamos el final del día (23:59:59)
                $sentencia_informes_tabla .= " AND DATE(fecha_registro) BETWEEN DATE('$fecha_inicio') AND DATE('$fecha_fin')";
            } elseif (!empty($fecha_inicio)) {
                // Si solo hay fecha inicial, buscamos registros de ese día específico
                $sentencia_informes_tabla .= " AND DATE(fecha_registro) = DATE('$fecha_inicio')";
            }

            // Agregamos el orden y límite
            $sentencia_informes_tabla .= " ORDER BY fecha_registro DESC LIMIT 8";

            // Verificamos la conexión primero
            $conexion = require 'conexion.php';
    
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Datos incorrectos para la operación indicada, Error de conexión",
                    'cod' => 500
                ];
            } else {
                // Ejecutamos la consulta
                $informes_tabla = $conexion01->query($sentencia_informes_tabla);
    
                if (!$informes_tabla || $informes_tabla->num_rows == 0) {
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "No se encontraron registros de movimientos",
                        'cod' => 404
                    ];
                } else {
                    // Procesamos los resultados solo si hay datos
                    $movimientos = [];
                    while ($row = $informes_tabla->fetch_assoc()) {
                        $movimientos[] = [
                            'fecha' => date('Y-m-d', strtotime($row['fecha_registro'])),
                            'hora' => date('H:i:s', strtotime($row['fecha_registro'])),
                            'movimiento' => $row['tipo_movimiento'],
                            'usuario' => $row['documento'],
                            'vehiculo' => $row['vehiculo']?? "sin vehiculo",
                            'relacion' => $row['relacion_veh']?? "ninguna",
                            'puerta' => $row['puerta_registro'],
                            'responsable' => $row['usr_sistem']
                        ];
                    }

                    $vector_respuesta = [
                        'titulo' => "ÉXITO",
                        'msj' => "Datos recuperados correctamente",
                        'cod' => 200,
                        'datos' => $movimientos
                    ];
                } 
            }

            // Enviamos la respuesta
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($vector_respuesta);
        }
    }