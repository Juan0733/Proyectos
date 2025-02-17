<?php
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../", true);
} else {
    session_start();
    if ($_SESSION['inicio_sesion']['cargo'] != "coordinador" && $_SESSION['inicio_sesion']['cargo'] != "subdirector") {
        $vector_respuesta = [
            'titulo' => "ERROR",
            'msj' => "Usuario no autorizado",
            'cod' => 500
        ];
    } else {
        
        $bandera = 1;

        if (!isset($_POST['titulo_agenda']) || $_POST['titulo_agenda'] == "") {
            $bandera = 0;
        } else if (!isset($_POST['fecha_agenda']) || $_POST['fecha_agenda'] == "") {
            $bandera = 0;
        } else if (!isset($_POST['fecha_registro']) || $_POST['fecha_registro'] == "" ){
            $bandera = 0;
        }else if(!isset($_POST['motivo']) || $_POST['motivo']== "" ){
            $bandera == 0;
        }

        if ($bandera == 0) {
            $vector_respuesta = [
                'titulo' => "ERROR",
                'msj' => "Datos inválidos",
                'cod' => 400
            ];
        
        } else {
            $id_agenda = $_POST['fecha_registro'];
            $titulo_agenda = trim($_POST['titulo_agenda']);
            $fecha_agenda = trim($_POST['fecha_agenda']);
            $motivo = trim($_POST['motivo']);

            $conexion = require '../servicios/conexion.php';

            if(!$conexion){
                $vector_respuesta = [
                    'titulo' => "ERROR",
                    'msj' => "No se pudo conectar a la base de datos",
                    'cod' => 400
                ];
            }else{
                // Comprobar el usuario
                $sentencia_buscar = "SELECT `usr_sistema` FROM `agendas` WHERE `fecha_registro` = '$id_agenda' LIMIT 1;";
                $resultado = $conexion01->query($sentencia_buscar);

                if ($resultado->num_rows == 0) {
                    $vector_respuesta = [
                        'titulo' => "ERROR",
                        'msj' => "¡Hubo un error, intentalo nuevamente!",
                        'cod' => 500
                    ];
                } else {
                    $usr_sistema = $resultado->fetch_assoc();
    
                    if($usr_sistema['usr_sistema'] != $_SESSION['inicio_sesion']['documento']){
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "¡No puedes actualizar esta agenda, porque no fuiste quien la creo!",
                            'cod' => 400
                        ];

                    }else{
                        
                        $sentencia_update = "UPDATE `agendas` SET `titulo`='$titulo_agenda', `fecha_a`='$fecha_agenda', `motivo_a`='$motivo' WHERE `fecha_registro` = '$id_agenda'";

                        $actualizar_agenda = $conexion01->query($sentencia_update);

                        if (!$actualizar_agenda) {
                            $vector_respuesta = [
                                'titulo' => "ERROR",
                                'msj' => "Error al actualizar la agenda. Intenta nuevamente.",
                                'cod' => 500
                            ];
                        } else {
                            $vector_respuesta = [
                                'titulo' => "ACTUALIZACIÓN EXITOSA",
                                'msj' => "La agenda se actualizó correctamente.",
                                'cod' => 200
                            ];
                        }
                    }
                    
                }
            }
           
        }

        echo json_encode($vector_respuesta);
    }
}
