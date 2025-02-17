<?php
   
    try {
        $conexion01 = new mysqli("localhost" , "root" , "" , "cerberus");
    } catch (Exception $e) {
        // Código para manejar la excepción
        $vector_respuesta = [
            'titulo' => 'ERROR',
            'cod' => 500,
            'msj' => '¡No se pudo establecer conexion con la base de datos'
        ];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vector_respuesta);
        exit;
    }
