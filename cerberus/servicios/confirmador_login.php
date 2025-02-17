<?php
    if($_SERVER['REQUEST_METHOD'] != "POST"){
        $vector_respuesta=[
            'titulo'=>"ERROR",
            'msj'=>"Intento de operacion incorrecta",
            'cod'=>500
        ];
    }else{
        $bandera=1;
        //filtro
        if(!isset($_POST['documento']) || $_POST['documento']=="" ||strlen($_POST['documento'])< 6 || strlen($_POST['documento']) > 15){
            $bandera=0;
        }else if(!isset($_POST['codigo_pw']) ||$_POST['codigo_pw']=="" || strlen($_POST['codigo_pw']) < 8){
            $bandera=0;
        }
        //verificacion que los datos sean los esperados.
        if($bandera != 1){
            $vector_respuesta=[
                'titulo'=>"ERROR",
                'msj'=> "Datos incorrectos para la operacion indicada",
                'cod'=> 400
            ];
        }else{
            //Sanitizacion
            $documento = trim(filter_var($_POST['documento']));
            $palabra = trim(filter_var($_POST['codigo_pw']));
            $cifrada = md5($palabra);

            // //Eliminamos variables.
            unset($_POST['documento'], $_POST['codigo_pw']);

            //Sentencias Preparadas.
            $buscar_vigilante = "SELECT `documento`, `cargo`, `nombres` FROM `vigilantes` WHERE `documento` = '$documento' AND `codigo_pw` = '$cifrada' AND `estado` = 'ACTIVO' AND `cargo` = 'jefe';";

            $buscar_funcionario = "SELECT `documento`, `cargo`, `nombres` FROM `funcionarios` WHERE `documento` = '$documento' AND `codigo_pw` = '$cifrada' AND `estado` = 'ACTIVO' AND (`cargo` = 'coordinador' OR `cargo` = 'subdirector');";

            $conexion = require 'conexion.php';
            
            if(!$conexion) {
                $vector_respuesta=[
                    'titulo'=>"ERROR",
                    'msj'=> "Datos incorrectos para la operacion indicada,Error conexion",
                    'cod'=> 500 //Error
                ];
            } else {
                // Condicional que verifica que el usr existe en vigilantes o funcionarios
                $existe = ""; // Variable estado usr

                $resultado_vigilante = $conexion01 ->query($buscar_vigilante);
               
                if ($resultado_vigilante->num_rows != 0) {
                    $existe = "vigilantes";
                    $info_usuario = $resultado_vigilante->fetch_assoc();
                } 

                if($existe == ""){
                    $resultado_funcionario = $conexion01 ->query($buscar_funcionario);
                    if ($resultado_funcionario->num_rows != 0) {
                        $existe = "funcionarios";
                        $info_usuario = $resultado_funcionario->fetch_assoc();
                    }
                }
                    
                
                // Luego de encontrar a el usr continuamos a manejar la session
                if ($existe == "") {
                    $vector_respuesta=[
                        'titulo'=>"ERROR",
                        'msj'=> "¡El usuario o contraseña son incorrectos!",
                        'cod'=> 400 //Error
                    ];
                } else {
                   

                    session_start();
                    //Valido para guardar los datos del array, los datos del usuario.
                    
                    if(isset($_SESSION['inicio_sesion'])){
                        $url = 'componentes/plantillas/principal.php';
                        //Luego de actualizar la session creamos la respuesta
                        $vector_respuesta=[
                            'titulo' => "usuario encontrado",
                            'msj' => "Usuario encontrado con exito",
                            'cod' => "200",//Este error no lo ve el cliente
                            'panel' => $url
                        ];
                    }else{
                        $_SESSION['inicio_sesion'] = $info_usuario;

                        date_default_timezone_set('America/Bogota');
                        $hora_momento = time();//Capturo hora del sistema.
                        $ultima_session = date("Y-m-d H:i:s",$hora_momento);//formateo hora.

                        //Sentencia vigilante.
                        $actua_session_vigilante = "UPDATE `vigilantes` SET `ult_fecha_sesion`= '$ultima_session' WHERE `documento` = '$documento';";

                        //Sentencia Funcionario.
                        $actua_session_funcionario = "UPDATE `funcionarios` SET `ult_fecha_sesion`= '$ultima_session' WHERE `documento` = '$documento';";

                        if($existe == "vigilantes"){
                            $actualizar = $conexion01->query($actua_session_vigilante);
                        }else{
                            $actualizar = $conexion01->query($actua_session_funcionario);
                        }
                        
                        $url = 'componentes/plantillas/principal.php';
                        //Luego de actualizar la session creamos la respuesta
                        $vector_respuesta=[
                            'titulo' => "usuario encontrado",
                            'msj' => "Usuario encontrado con exito",
                            'cod' => "200",//Este error no lo ve el cliente
                            'panel' => $url
                        ];
                    }

                    
                    $conexion01->close();
                    unset($conexion, $existe, $resultado_final, $resultado_funcionario, $resultado_vigilante, $actualizar, $actua_session_funcionario, $bandera);
                }
            }
        }

        echo json_encode($vector_respuesta);

    }

