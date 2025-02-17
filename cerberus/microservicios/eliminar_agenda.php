<?php
if ($_SERVER['REQUEST_METHOD'] != "GET") {
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
        
        $id_agenda = $_GET['fecha_registro'] ?? 'vacio';

        if ($id_agenda == 'vacio') {
            $vector_respuesta = [
                'titulo' => "ERROR",
                'msj' => "Parametros no validos",
                'cod' => 400
            ];
        
        } else {
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
                            'msj' => "¡No puedes eliminar esta agenda, porque no fuiste quien la creo!",
                            'cod' => 400
                        ];

                    }else{
                        
                        $sentencia_eliminar = "DELETE FROM `agendas` WHERE `fecha_registro` = '$id_agenda';";

                        $eliminar_agenda = $conexion01->query($sentencia_eliminar);

                        if (!$eliminar_agenda) {
                            $vector_respuesta = [
                                'titulo' => "ERROR",
                                'msj' => "Error al eliminar la agenda. Intenta nuevamente.",
                                'cod' => 500
                            ];
                        } else {
                            $vector_respuesta = [
                                'titulo' => "ELIMINACION EXITOSA",
                                'msj' => "La agenda se elimino correctamente.",
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
