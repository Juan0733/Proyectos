<?php
    if($_SERVER['REQUEST_METHOD']!="POST"){
        header("Location: ../",true);
    }else{
        session_start();
        if($_SESSION['inicio_sesion']['cargo']!="coordinador" && $_SESSION['inicio_sesion']['cargo']!="subdirector" && $_SESSION['inicio_sesion']['cargo']!="jefe"){
            $vector_respuesta=[
                'titulo'=>"ERROR",
                'msj'=>"Usuario no autorizado",
                'cod'=>500
            ];
            header("Location: ../",true);
        }else{
            $bandera = 1;
            if(!isset($_POST['titulo_agenda']) || $_POST['titulo_agenda']==""){
                $bandera= 0;

            }else if(!isset($_POST['fecha_agenda'])|| $_POST['fecha_agenda']==""){
                $bandera=0;
                
            }else if(!isset($_POST['motivo_visita']) ||$_POST['motivo_visita']=="" ){
                $bandera=0;
            }

                // Lógica de Carga Masiva
            if(isset($_FILES['carga_masiva_e'])){
                if($bandera==0){
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "Datos incorrectos para la operación indicada.",
                        'cod' => 400,
                    ];
                } else {

                    $titulo_agenda = trim($_POST['titulo_agenda']);
                    $fecha_agenda = trim($_POST['fecha_agenda']);
                    $motivo = trim($_POST['motivo_visita']);
                    $carpeta_plantilla = "../subido/";

                    if(!file_exists($carpeta_plantilla)){
                        mkdir($carpeta_plantilla);
                    }

                    $nombre_plantilla = time() . "_" . $_FILES['carga_masiva_e']['name'];
                    $ruta_tmp = $_FILES['carga_masiva_e']['tmp_name'];
                    $ruta_plantilla = $carpeta_plantilla . $nombre_plantilla;
                        
                    if(move_uploaded_file($ruta_tmp, $ruta_plantilla)){
                        $microservicio_excel = require '../microservicios/lector_excel.php';
                        if(!$microservicio_excel){
                            $vector_respuesta=[
                                'titulo'=>"ERROR",
                                'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                'cod'=>500
                            ];
                        }else{
                            $conexion = require 'conexion.php';
                            if(!$conexion){
                                $vector_respuesta=[
                                    'titulo'=>"ERROR",
                                    'msj'=>"¡Hubo un error al conectarse a la base de datos!",
                                    'cod'=>500
                                ];
                            }else{
                                // Usar la función leerArchivoExel para procesar el archivo
                                $resultados_excel = leerArchivoExel($ruta_plantilla);
                                
                                // Contadores para seguimiento
                                $agendados = 0;

                                date_default_timezone_set('America/Bogota');
                                $sys_momento = time();
                                $momento_registro = date("Y-m-d H:i:s", $sys_momento);

                                foreach($resultados_excel as $fila){
                                    // Ajustar según los encabezados del Excel
                                    $documento = $fila['Numero de Identificacion'];
                                    $tipo_documento = $fila['Tipo de Documento'];
                                    $nombres = $fila['Nombres'];
                                    $apellidos = $fila['Apellidos'];
                                    $movil = $fila['Movil'];
                                    $correo = $fila['Correo Electronico'];

                                    $microservicio_buscar = require '../microservicios/buscar_persona.php';

                                    if(!$microservicio_buscar){
                                        $vector_respuesta=[
                                            'titulo'=>"ERROR",
                                            'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                            'cod'=>500
                                        ];

                                        $conexion01->close();
                                        echo json_encode($vector_respuesta);
                                        exit;

                                    }else{
                                        if($vector_respuesta['cod'] == 404){
                                            $sentencia_visitante = "INSERT INTO `visitantes` 
                                            (`documento`, `tipo_documento`, `nombres`, `apellidos`, `telefono`, `email`,`fecha_registro`, `ubicacion`, `motivo`) 
                                            VALUES 
                                            ('$documento', '$tipo_documento', '$nombres', '$apellidos', '$movil', '$correo','$momento_registro', 'AFUERA', '$motivo')";

                                            $insertar_visitante = $conexion01->query($sentencia_visitante);
                                                                                    
                                            if(!$insertar_visitante){
                                                $vector_respuesta=[
                                                    'titulo'=>"ERROR",
                                                    'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                                    'cod'=>500
                                                ];

                                                $conexion01->close();
                                                echo json_encode($vector_respuesta);
                                                exit; 
                                            } 
                                            
                                        } else if( $vector_respuesta["cod"] == 400 || $vector_respuesta['cod'] == 500){
                                        
                                            $conexion01->close();
                                            echo json_encode($vector_respuesta);
                                            exit; 
                                        }
                                        
                                        $sentencia_agenda = "INSERT INTO `agendas` 
                                        (`titulo`, `fecha_a`, `fecha_registro`, `motivo_a`, `documento`,`usr_sistema`) 
                                        VALUES 
                                        ('$titulo_agenda', '$fecha_agenda', '$momento_registro', '$motivo', '$documento', '{$_SESSION['inicio_sesion']['documento']}')";
                                        
                                        $insertar_agenda = $conexion01->query($sentencia_agenda);
                                        if(!$insertar_agenda){
                                            $vector_respuesta=[
                                                'titulo'=>"ERROR",
                                                'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                                'cod'=>500
                                            ];
                                            $conexion01->close();
                                            echo json_encode($vector_respuesta);
                                            exit; 

                                        } else {
                                            $agendados++;
                                        }

                                    }
                                }

                                $microservicio_eliminar_excel = require '../microservicios/eliminar_excel.php';

                                if(!$microservicio_eliminar_excel){
                                    $vector_respuesta=[
                                        'titulo'=>"ERROR",
                                        'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                        'cod'=>500
                                    ];

                                }else{
                                    $respuesta = eliminar_excel($ruta_plantilla);
                                    if($respuesta != 200){
                                        $vector_respuesta=[
                                            'titulo'=>"ERROR",
                                            'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                            'cod'=>500
                                        ];
    
                                    }else{
                                        if($agendados == 0){
                                            $vector_respuesta = [
                                                'titulo' => "ERROR",
                                                'msj' => "¡El formato excel se encuentra vacio o esta incompleto!",
                                                'cod' => 500,
                                            ];
                                        }else{
                                            $vector_respuesta = [
                                                'titulo' => "CARGA MASIVA PROCESADA",
                                                'msj' => "¡$agendados personas agendadas correctamente!",
                                                'cod' => 200,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $vector_respuesta = [
                            'titulo' => "ERROR",
                            'msj' => "No se pudo subir el archivo",
                            'cod' => 500
                        ];
                    }
                }
            }else {
                
                if(!isset($_POST['numero_documento']) || $_POST['numero_documento']==""|| strlen($_POST['numero_documento'])<6 || strlen($_POST['numero_documento'])>15){
                    $bandera=0;
                }else if(!isset($_POST['nombres']) || $_POST['nombres'] == "" || strlen($_POST['nombres']) < 3 || strlen($_POST['nombres']) > 25) {
                    $bandera = 0;
                }else if(!isset($_POST['apellidos']) || $_POST['apellidos'] == "" || strlen($_POST['apellidos']) < 3 || strlen($_POST['apellidos']) > 30) {
                    $bandera = 0;
                }else if (!isset($_POST['correo']) || $_POST['correo'] == "" || strlen($_POST['correo']) < 2 || strlen($_POST['correo']) > 64) {
                    $bandera = 0;
                }else if (!isset($_POST['movil'])|| $_POST['movil']=="" || strlen($_POST['movil'])<10 || strlen($_POST['movil'])>10){
                    $bandera=0;
                }else if (!isset($_POST['tipo_documento'])|| $_POST['tipo_documento']==""){
                    $bandera=0;
                }

                if($bandera==0){
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "Datos incorrectos para la operación indicada",
                        'cod' => 400,
                    ];
                } else {
                    // Procesar registro individual
                    $titulo_agenda = trim($_POST['titulo_agenda']);
                    $fecha_agenda = trim($_POST['fecha_agenda']);
                    $motivo = trim($_POST['motivo_visita']);
                    $documento=trim($_POST['numero_documento']);
                    $nombres=trim($_POST['nombres']);
                    $apellidos=trim($_POST['apellidos']);
                    $correo=trim($_POST['correo']);
                    $movil=trim($_POST['movil']);
                    $tipo_documento=trim($_POST['tipo_documento']);


                    
                    //Momento creado
                    date_default_timezone_set('America/Bogota');
                    $sys_momento = time();
                    $momento_registro = date("Y-m-d H:i:s", $sys_momento);

                    $conexion = require 'conexion.php';
                    if(!$conexion){
                        $vector_respuesta=[
                            'titulo'=>"ERROR",
                            'msj'=>"¡Hubo un error al conectarse a la base de datos!",
                            'cod'=>500
                        ];
                    }else{
                        $microservicio_buscar = require '../microservicios/buscar_persona.php';

                        if(!$microservicio_buscar){
                            $vector_respuesta=[
                                'titulo'=>"ERROR",
                                'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                'cod'=>500
                            ];

                            $conexion01->close();
                            echo json_encode($vector_respuesta);
                            exit;

                        }else{
                            if($vector_respuesta['cod'] == 404){
                                $sentencia_visitante = "INSERT INTO `visitantes` 
                                (`documento`, `tipo_documento`, `nombres`, `apellidos`, `telefono`, `email`,`fecha_registro`, `ubicacion`, `motivo`) 
                                VALUES 
                                ('$documento', '$tipo_documento', '$nombres', '$apellidos', '$movil', '$correo','$momento_registro', 'AFUERA', '$motivo')";

                                $insertar_visitante = $conexion01->query($sentencia_visitante);
                                        
                                if(!$insertar_visitante){
                                    $vector_respuesta=[
                                        'titulo'=>"ERROR",
                                        'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                        'cod'=>500
                                    ];

                                    $conexion01->close();
                                    echo json_encode($vector_respuesta);
                                    exit; 
                                } 

                            } else if($vector_respuesta["cod"] == 400 || $vector_respuesta['cod'] == 500){
                                      
                                $conexion01->close();
                                echo json_encode($vector_respuesta);
                                exit; 
                            }
                            
                            $sentencia_agenda = "INSERT INTO `agendas` 
                            (`titulo`, `fecha_a`, `fecha_registro`, `motivo_a`, `documento`,`usr_sistema`) 
                            VALUES 
                            ('$titulo_agenda', '$fecha_agenda', '$momento_registro', '$motivo', '$documento', '{$_SESSION['inicio_sesion']['documento']}')";
                                    
                            $insertar_agenda = $conexion01->query($sentencia_agenda);
                            if(!$insertar_agenda){
                                $vector_respuesta=[
                                    'titulo'=>"ERROR",
                                    'msj'=>"¡Hubo un error, por favor intentalo nuevamente!",
                                    'cod'=>500
                                ];
                            } else {
                                $vector_respuesta = [
                                    'titulo' => "REGISTRO EXITOSO",
                                    'msj' => "¡La persona fue agenda correctamente!",
                                    'cod' => 200
                                ];      
                            }
                        } 
                        $conexion01->close(); 
                    }
                }
            } 
        }
    }

    header('Content-Type: application/json');       
    echo json_encode($vector_respuesta);