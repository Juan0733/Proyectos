<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    header("Location: ../", true);
    exit;
}

// Verificar permisos de usuario
if ($_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "subdirector") {
    header("Location: ../", true);
    exit;
}

date_default_timezone_set("America/Bogota");

// Parámetros iniciales
$tipo = $_GET['tipo'] ?? 'peatonal';
$horario = strtoupper($_GET['horario'] ?? 'TARDE');
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$movimiento = $_GET['movimiento']?? 'ENTRADA';
// Definir rangos de horario por jornada
$rangos_horario = [
    'MAÑANA' => ['inicio' => '07:00:00', 'fin' => '11:59:59'],
    'TARDE' => ['inicio' => '12:00:00', 'fin' => '17:59:59'],
    'NOCHE' => ['inicio' => '18:00:00', 'fin' => '22:59:59']
];

// Validar que el horario sea válido
if (!array_key_exists($horario, $rangos_horario)) {
    $response = [
        'titulo' => "ERROR",
        'msj' => "Horario no válido. Debe ser: MAÑANA o TARDE",
        'cod' => 400
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    exit;
}

// Conexión a la base de datos
$conexion = require 'conexion.php';
if (!$conexion) {
    $response = [
        'titulo' => "ERROR",
        'msj' => "Error de conexión a la base de datos",
        'cod' => 500
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    exit;
}

// Dividir el rango horario en intervalos de una hora
$inicio = new DateTime($rangos_horario[$horario]['inicio']);
$fin = new DateTime($rangos_horario[$horario]['fin']);
$intervalos = [];
while ($inicio < $fin) {
    $inicio_intervalo = $inicio->format('H:i:s');
    $inicio->modify('+1 hour');
    $fin_intervalo = $inicio->format('H:i:s');
    $intervalos[] = ['inicio' => $inicio_intervalo, 'fin' => $fin_intervalo];
}

// Consultar movimientos por cada intervalo
$resultados = [];
foreach ($intervalos as $intervalo) {
    $sql = "SELECT COUNT(*) as cantidad
            FROM movimientos
            WHERE puerta_registro = '$tipo'
            AND tipo_movimiento = '$movimiento'
            AND DATE(fecha_registro) = '$fecha'
            AND TIME(fecha_registro) BETWEEN '{$intervalo['inicio']}' AND '{$intervalo['fin']}'";
    
    $resultado = $conexion01->query($sql);
    $fila = $resultado->fetch_assoc();

    $resultados[] = [
        'rango' => "{$intervalo['inicio']} - {$intervalo['fin']}",
        'cantidad' => $fila['cantidad']
    ];
}

// Respuesta final
$response = [
    'titulo' => "CONSULTA EXITOSA",
    'msj' => "Datos recuperados correctamente",
    'cod' => 200,
    'fecha' => $fecha,
    'horario' => $horario,
    'tipo_ingreso' => $tipo,
    'estadisticas' => $resultados
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

