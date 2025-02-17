<?php
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: ../", true);
    } else {
        session_start();
        if($_SESSION['inicio_sesion']['cargo'] != "subdirector" && $_SESSION['inicio_sesion']['cargo'] != "jefe" && $_SESSION['inicio_sesion']['cargo'] != "coordinador"){
            header("Location: ../", true);
        } else {
            $sentencia_eliminar = "DELETE FROM $tabla WHERE `documento` = '$documento';";

            $conexion = require '../servicios/conexion.php';
    
            if (!$conexion) {
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "Error conexion",
                    'cod' => 500 //Error
                ];
            } else {
                $resultado_sentencia = $conexion01->query($sentencia_eliminar);
                $eliminado = $conexion01 -> affected_rows;
    
                if ($eliminado == 0) {
                    $vector_respuesta=[
                        'titulo' => "No eliminado",
                        'msj' => "Usuario no eliminado por alguna razón",
                        'cod' => 400,
                    ];
                    
                } else {
                    $vector_respuesta=[
                        'titulo' => "Eliminado",
                        'msj' => "Usuario eliminado exitosamente!",
                        'cod' => 200,
                    ];
                }             
            }
        } 
    }