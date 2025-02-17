<?php

	namespace app\controllers;
	use app\models\mainModel;

	class loginController extends mainModel{
        /* -----------Controlador Iniciar Sesion ---------------- */
        public function iniciarSesionControlador(){
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
				header("location:./");
				exit;
            }else{
                header('Content-Type: application/json; charset=utf-8');
                if (!isset($_POST['nombre_usuario'], $_POST['pw_usuario']) || $_POST['nombre_usuario'] == '' || $_POST['pw_usuario'] == '') {
                    $resultado = [
                        'tipo' => "error",
                        'cod_error' => "350",
                        'titulo' => "Campos Requeridos",
                        'mensaje' => "Lo sentimos, es necesario que ingreses tu usuario y contraseña para acceder al sistema.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    echo json_encode($resultado);
                    exit();
                }else {
                    $datos = [
                        [
                            'filtro' => "^(?=.*[a-z])(?=.*\d).{6,15}$",
                            'cadena' =>  $_POST['nombre_usuario']
                        ],
                        [
                            'filtro' => "^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%.*?&#]).{8,}$",
                            'cadena' => $_POST['pw_usuario']
                        ]
                    ];

                    $respuesta = $this->verificarDatos($datos);
                    if(!$respuesta){
                        $mensaje=[
                            'tipo'=>"ERROR",
                            'cod_error' => "350",
                            'titulo' => "Formato Invalido",
                            'mensaje' => "Lo sentimos, los datos no cumplen con la estructura requerida.",
                            'icono' => "warning",
                            'alerta'=> "normal"
                        ];
                        return $mensaje; 
                    }
					
                    $nombre_usuario = $this->limpiarDatos($_POST['nombre_usuario']);
                    $pw_usuario = $this->limpiarDatos($_POST['pw_usuario']);
                    
                    $sentencia_login = "SELECT 
                                            ue.num_identificacion_persona,
                                            ue.tipo_documento_persona,
                                            ue.nombre_persona,
                                            ue.apellidos_persona,
                                            ue.telefono_persona,
                                            ue.correo_persona,
                                            ue.cargo_persona,
                                            ue.fecha_hora_ultima_sesion,
                                            ue.fecha_registro,
                                            ue.num_identificacion_empresa,
                                            uc.bdd_empresa,
                                            uc.pw_bdd_empresa
                                        FROM 
                                            usuarios_empresa as ue
                                        JOIN 
                                            usuarios_clientes as uc
                                        ON 
                                            ue.num_identificacion_empresa = uc.num_identificacion_empresa
                                        WHERE 
                                            ue.nombre_usuario = MD5('$nombre_usuario') AND ue.pw_persona = MD5('$pw_usuario');";

                    $respuesta_login = $this->ejecutarConsulta($sentencia_login);
                    unset($nombre_usuario, $pw_usuario);
                    if (!$respuesta_login) {
                        $resultado = [
                            'tipo' => "error",
                            'cod_error' => "350",
                            'titulo' => "Error al Consultar",
                            'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Por favor, verifica tus datos e inténtalo nuevamente. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                            'icono' => "warning",
                            'alerta'=> "normal"
                        ];
                        echo json_encode($resultado);
                    }else {
                        if ($respuesta_login->num_rows < 1) {
                            $resultado = [
                                'tipo' => "error",
                                'cod_error' => "350",
                                'titulo' => "Usuario no encontrado",
                                'mensaje' => "Lo sentimos, no hemos podido encontrar tu información en nuestra base de datos. Por favor, verifica tus datos e inténtalo nuevamente",
                                'icono' => "warning",
                                'alerta'=> "normal"
                            ];
                            echo json_encode($resultado);
                        }else {
                            $datos_usuario = $respuesta_login->fetch_assoc();
                            unset($respuesta_login);
                            $datos_de_el_usuario = [
                                'num_identificacion_persona' => $datos_usuario['num_identificacion_persona'],
                                'tipo_documento_persona' => $datos_usuario['tipo_documento_persona'],
                                'nombre_persona' => $datos_usuario['nombre_persona'],
                                'apellidos_persona' => $datos_usuario['apellidos_persona'],
                                'telefono_persona' => $datos_usuario['telefono_persona'],
                                'correo_persona' => $datos_usuario['correo_persona'],
                                'cargo_persona' => $datos_usuario['cargo_persona'],
                                'fecha_hora_ultima_sesion' => $datos_usuario['fecha_hora_ultima_sesion'],
                                'num_identificacion_empresa' => $datos_usuario['num_identificacion_empresa'],
                                'bdd_empresa' => $datos_usuario['bdd_empresa'],
                                'fecha_registro' => $datos_usuario['fecha_registro']
                            ];
                            $_SESSION['datos_usuario'] = $datos_de_el_usuario;
                            if ($_SESSION['datos_usuario']['cargo_persona'] == 'SY') {
                                
                                $resultado = [
                                    "titulo"=>"OK",
                                    "url"=>"dashboard-saiyajin",
                                    "cod_error"=> "250",
                                ];
                                echo json_encode($resultado);
                                exit();
                            }else {
                                $resultado = [
                                    "titulo"=>"OK",
                                    "url"=>"dashboard",
                                    "cod_error"=> "250",
                                ];
                                echo json_encode($resultado);
                                exit();
                            }

                        }
                    }
                }
            }
        }
        public function consultaCambio($dato){
            $sentencia_login = "CREATE DATABASE `gur_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";

            $respuesta_mesas = $this->ejecutarConsulta($sentencia_login);
            
            $datos_mesas = $respuesta_mesas->fetch_assoc();


            $resultado = [
                'contador' => $datos_mesas['nombre'],
                'datos_user' => $_SESSION['datos_usuario']['bdd_empresa']
            ];
            echo json_encode($resultado);


        }
    }