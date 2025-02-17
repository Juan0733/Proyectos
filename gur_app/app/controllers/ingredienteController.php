<?php

	namespace app\controllers;
	use app\models\mainModel;

    class IngredienteController extends mainModel{

        public function registrarIngrediente(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_compra'], $_POST['unidad_medida'], $_POST['stock_actual'], $_POST['stock_minimo']) || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_compra'] == '' || $_POST['unidad_medida'] == '' || $_POST['stock_actual'] == '' || $_POST['stock_minimo'] == '') {
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatosios.",                        'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $datos = [
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['nombre']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['categoria']
                ],
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['presentacion']
                ],
                [
                    'filtro' => "[A-Za-z\s]+",
                    'cadena' => $_POST['unidad_medida']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['stock_actual']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['stock_minimo']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_compra']
                ]
            ];
                
            if($_POST['codigo'] != ''){
                $datos[] = [
                    'filtro' => "[A-Za-z0-9]+",
                    'cadena' => $_POST['codigo']
                ];
            }

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

            $nombre = $this->limpiarDatos($_POST['nombre']);
            $categoria = $this->limpiarDatos($_POST['categoria']);
            $presentacion = $this->limpiarDatos($_POST['presentacion']);
            $unidadMedida = $this->limpiarDatos($_POST['unidad_medida']);
            $stockActual = $this->limpiarDatos($_POST['stock_actual']);
            $stockMinimo = $this->limpiarDatos($_POST['stock_minimo']);
            $precioCompra = $this->limpiarDatos($_POST['precio_compra']);
    
            if(!isset($_POST['codigo']) || $_POST['codigo'] == ''){
                $codigo = "IN" . date('YmdHis');
            }elseif($_POST['codigo'] != ''){
                $codigo = $this->limpiarDatos($_POST['codigo']);
            }

            $respuesta = $this->validarIngredienteExistente($codigo);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $respuesta = $this->validarIngredienteExistente(false, $nombre);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $fechaRegistro = date('Y-m-d H:i:s', time());
                    $usrSistema = $_SESSION['datos_usuario']['num_identificacion_persona'];
                    $sentenciaInsertar = "INSERT INTO `ingredientes`(`codigo_ingrediente`, `nombre`,`fk_categoria`,  `stock_actual`, `stock_minimo`, `fecha_registro`,`presentacion`, `unidad_medida`, `fk_usr_sistema`, `precio_compra`) VALUES ('$codigo', '$nombre', $categoria, '$stockActual','$stockMinimo', '$fechaRegistro', '$presentacion', '$unidadMedida', '$usrSistema','$precioCompra')";

                    $respuesta = $this->ejecutarConsulta($sentenciaInsertar);
                    if (!$respuesta) {
                        $resultado = [
                            'tipo' => "ERROR",
                            'cod_error' => "350",
                            'titulo' => "Error al Consultar",
                            'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                            'icono' => "warning",
                            'alerta'=> "normal"
                        ];
                        return $resultado;
                    }
                    $resultado = [
                        "tipo"=>"OK",
                        "cod_error" => "250",
                        "titulo" => "Registro exitóso",
                        'icono' => "success",
                        'alerta'=> "normal",
                        "mensaje" => "El ingrediente se registro correctamente",
                        "url"=>"ingredientes"
                    ];
                    return $resultado;   
                }
            }    
        }

        public function actualizarIngrediente(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['codigo'], $_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_compra'], $_POST['unidad_medida'], $_POST['stock_actual'], $_POST['stock_minimo']) || $_POST['codigo'] == '' || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_compra'] == '' || $_POST['unidad_medida'] == '' || $_POST['stock_actual'] == '' || $_POST['stock_minimo'] == '') {
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatosios.",                        'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $datos = [
                [
                    'filtro' => "[A-Za-z0-9]+",
                    'cadena' => $_POST['codigo']
                ],
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['nombre']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['categoria']
                ],
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['presentacion']
                ],
                [
                    'filtro' => "[A-Za-z\s]+",
                    'cadena' => $_POST['unidad_medida']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['stock_minimo']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_compra']
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

            $codigo = $this->limpiarDatos($_POST['codigo']);
            $nombre = $this->limpiarDatos($_POST['nombre']);
            $categoria = $this->limpiarDatos($_POST['categoria']);
            $presentacion = $this->limpiarDatos($_POST['presentacion']);
            $unidadMedida = $this->limpiarDatos($_POST['unidad_medida']);
            $stockMinimo = $this->limpiarDatos($_POST['stock_minimo']);
            $precioCompra = $this->limpiarDatos($_POST['precio_compra']);

            $respuesta = $this->validarIngredienteExistente($codigo, $nombre);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $sentenciaActualizar = "UPDATE ingredientes SET nombre = '$nombre', fk_categoria = $categoria, presentacion = '$presentacion', precio_compra = '$precioCompra', unidad_medida = '$unidadMedida', stock_minimo = $stockMinimo WHERE codigo_ingrediente = '$codigo';";
                $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
                if (!$respuesta) {
                    $resultado = [
                        'tipo' => "ERROR",
                        'cod_error' => "350",
                        'titulo' => "Error al Consultar",
                        'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }
                $resultado = [
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Actualizacion Exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "El ingrediente se actualizo correctamente",
                    "url"=>"ingredientes"
                ];
                return $resultado;
            }
        }

        public function validarIngredienteExistente($codigo, $nombre=false){
            if(!$nombre){
                $respuesta = $this->consultarIngrediente($codigo);
            }elseif(!$codigo){
                $respuesta = $this->consultarIngrediente(false, $nombre);
            }elseif($codigo && $nombre){
                $respuesta = $this->consultarIngrediente($codigo, $nombre);
            }

            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $datosIngrediente = $respuesta['ingrediente'];
                if($datosIngrediente){
                    if($datosIngrediente['estado'] == 'ACTIVO'){
                        if(!$nombre){
                            $resultado = [
                                "tipo"=>"ERROR",
                                "cod_error" => "350",
                                "titulo" => "Ingrediente existente",
                                "mensaje" => "El codigo del ingrediente que proporcionaste, ya le pertenece a otro ingrediente.",
                                'icono' => "warning",
                                'alerta'=> "normal"
                            ];
                        }elseif(!$codigo || $codigo && $nombre){
                            $resultado = [
                                "tipo"=>"ERROR",
                                "cod_error" => "350",
                                "titulo" => "Producto existente",
                                "mensaje" => "El nombre del ingrediente que proporcionaste, ya le pertenece a otro ingrediente.",
                                'icono' => "warning",
                                'alerta'=> "normal"
                            ];
                        }
                        return $resultado;
                    }
                }
                $resultado = [
                    "tipo" => "OK",
                    "cod_error" => "250",
                    "titulo" => "Ingrediente no existente",
                    "mensaje" => "El ingrediente no se encuentra registrado",
                ];
                return $resultado;
            }   
        }

        public function eliminarIngrediente($codigo): array{
            $sentenciaActualizar= "UPDATE ingredientes SET estado = 'INACTIVO' WHERE codigo_ingrediente = '$codigo'";
            $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $sentenciaActualizar = "UPDATE ingredientes_productos SET estado = 'INACTIVO' WHERE fk_ingrediente = '$codigo';";
            $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $resultado = [
                "tipo"=>"OK",
                "cod_error" => "250",
                "titulo" => "Eliminacion Exitósa",
                'icono' => "success",
                'alerta'=> "normal",
                "mensaje" => "El ingrediente se elimino correctamente",
            ];
            return $resultado;  
        }

        public function consultarIngrediente($codigo, $nombre=false): array|bool{
            if(!$nombre){
                $sentenciaBuscar = "SELECT * FROM ingredientes WHERE codigo_ingrediente = '$codigo'";
            }else if(!$codigo){
                $sentenciaBuscar = "SELECT * FROM ingredientes WHERE nombre = '$nombre'";
            }else if($nombre && $codigo){
                $sentenciaBuscar = "SELECT * FROM ingredientes WHERE nombre = '$nombre' AND codigo_ingrediente <> '$codigo'";
            }
            
            $respuesta = $this->ejecutarConsulta($sentenciaBuscar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $ingrediente = $respuesta->fetch_assoc();

            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'ingrediente' => $ingrediente
            ];
            return $resultado;            
        }


        public function consultarIngredientes($estado){
            $sentenciaBuscar = "SELECT * FROM ingredientes WHERE estado = '$estado';";
            $respuesta = $this->ejecutarConsulta($sentenciaBuscar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
               
            $ingredientes = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'ingredientes' => $ingredientes
            ];
            return $resultado;
        }

        public function restaurarIngrediente(){
            if (!isset($_POST['codigo'], $_POST['stock_actual']) || $_POST['codigo'] == '' || $_POST['stock_actual'] == '') {
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatorios.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $datos = [
                [
                    'filtro' => "[A-Za-z0-9]+",
                    'cadena' => $_POST['codigo']
                ],
                [
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['stock_actual']
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

            $codigo = $this->limpiarDatos($_POST['codigo']);
            $stockActual = $this->limpiarDatos($_POST['stock_actual']);
            $sentenciaActualizar = "UPDATE ingredientes SET stock_actual = $stockActual, estado = 'ACTIVO' WHERE codigo_ingrediente = '$codigo';";

            $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
                
            }
            $sentenciaActualizar = "UPDATE ingredientes_productos SET estado = 'ACTIVO' WHERE fk_ingrediente = '$codigo';";
            $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $respuesta = $this->actualizarIngrediente();
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }
            $resultado = [
                'tipo' => "OK",
                'titulo' => "Restauracion Exitósa",
                'cod_error' => "250",
                'mensaje' => "El ingrediente se restauro correctamente.",
                'icono' => "success",
                'alerta'=> "normal",
                'url' => 'ingredientes'
            ];
            return $resultado;
        }
    }