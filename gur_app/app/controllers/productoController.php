<?php

	namespace app\controllers;
	use app\models\mainModel;

	class ProductoController extends mainModel{
        /* -----------Controlador Productos ---------------- */
        public function registrarProductoEstand(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['tipo_producto'], $_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_venta'], $_POST['codigo'], $_POST['unidad_medida'], $_POST['stock_actual'], $_POST['stock_minimo'], $_POST['precio_compra']) || $_POST['tipo_producto'] == '' || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_venta'] == '' || $_POST['codigo'] == '' || $_POST['unidad_medida'] == '' || $_POST['stock_actual'] == '' || $_POST['stock_minimo'] == '' || $_POST['precio_compra'] == '') {
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
                    'filtro' => "(estand)",
                    'cadena' => $_POST['tipo_producto']
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
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_venta']
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
            $tipoProducto = $this->limpiarDatos($_POST['tipo_producto']);
            $categoria = $this->limpiarDatos($_POST['categoria']);
            $presentacion = $this->limpiarDatos($_POST['presentacion']);
            $unidadMedida = $this->limpiarDatos($_POST['unidad_medida']);
            $stockActual = $this->limpiarDatos($_POST['stock_actual']);
            $stockMinimo = $this->limpiarDatos($_POST['stock_minimo']);
            $precioCompra = $this->limpiarDatos($_POST['precio_compra']);
            $precioVenta = $this->limpiarDatos($_POST['precio_venta']);

            $respuesta = $this->guardarImagen();
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $foto = $respuesta['nombre_foto'];

                $respuesta = $this->validarProductoExistente($codigo);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){

                    $respuesta = $this->validarProductoExistente(false, $nombre);
                    if($respuesta['tipo'] == 'ERROR'){
                        return $respuesta;
                    }elseif($respuesta['tipo'] == 'OK'){
                        $fechaRegistro = date('Y-m-d H:i:s', time());
                        $usrSistema = $_SESSION['datos_usuario']['num_identificacion_persona'];
                
                        $sentenciaInsertar = "INSERT INTO `productos`(`codigo_producto`, `nombre`,`fk_categoria`, `tipo`, `foto`, `stock_actual`, `stock_minimo`, `fecha_registro`,`presentacion`, `unidad_medida`, `fk_usr_sistema`, `precio_compra`,`precio_venta`) VALUES ('$codigo', '$nombre', $categoria, '$tipoProducto', '$foto', $stockActual,$stockMinimo, '$fechaRegistro', '$presentacion', '$unidadMedida', '$usrSistema',$precioCompra, $precioVenta)";
                                
                                    
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
                            "mensaje" => "El producto se registro correctamente",
                            "url"=>"productos"
                        ];
                        return $resultado;
                    }
                }
            }
        }

        public function registrarProductoCocina(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['tipo_producto'], $_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_venta']) || $_POST['tipo_producto'] == '' || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_venta'] == '' ) {
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
                    'filtro' => "(cocina)",
                    'cadena' => $_POST['tipo_producto']

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
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_venta']
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

            $codigo =  $codigo = "PC" . date('YmdHis');
            $nombre = $this->limpiarDatos($_POST['nombre']);
            $tipoProducto = $this->limpiarDatos($_POST['tipo_producto']);
            $categoria = $this->limpiarDatos($_POST['categoria']);
            $presentacion = $this->limpiarDatos($_POST['presentacion']);
            $precioVenta = $this->limpiarDatos($_POST['precio_venta']);

            $respuesta = $this->guardarImagen();
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $foto = $respuesta['nombre_foto'];

                $respuesta = $this->validarProductoExistente(false, $nombre);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){

                    $fechaRegistro = date('Y-m-d H:i:s', time());
                    $usrSistema = $_SESSION['datos_usuario']['num_identificacion_persona'];
                    $sentenciaInsertar = "INSERT INTO `productos`(`codigo_producto`, `nombre`,`fk_categoria`, `tipo`, `foto`, `fecha_registro`,`presentacion`, `fk_usr_sistema`, `precio_venta`) VALUES ('$codigo', '$nombre', $categoria, '$tipoProducto', '$foto', '$fechaRegistro', '$presentacion', '$usrSistema', $precioVenta)";
                                
                                    
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

                    $ingredientes = json_decode($_POST['ingredientes'], true);
                    if(count($ingredientes) < 1){
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
                    
                    $respuesta = $this->registrarReceta($codigo, $ingredientes);
                    if($respuesta['tipo'] == "ERROR"){
                        return $respuesta;
                    }elseif($respuesta['tipo'] == 'OK'){
                        $resultado = [
                            "tipo"=>"OK",
                            "cod_error" => "250",
                            "titulo" => "Registro exitóso",
                            'icono' => "success",
                            'alerta'=> "normal",
                            "mensaje" => "El producto se registro correctamente",
                            "url"=>"productos"
                        ];
                        return $resultado;
                    }
                }
            }
        }
        public function actualizarProductoEstand(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['tipo_producto'], $_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_venta'], $_POST['codigo'], $_POST['unidad_medida'], $_POST['stock_actual'], $_POST['stock_minimo'], $_POST['precio_compra']) || $_POST['tipo_producto'] == '' || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_venta'] == '' || $_POST['codigo'] == '' || $_POST['unidad_medida'] == '' || $_POST['stock_actual'] == '' || $_POST['stock_minimo'] == '' || $_POST['precio_compra'] == '') {
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
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_venta']
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
            $precioVenta = $this->limpiarDatos($_POST['precio_venta']);

            if(!isset($_FILES['foto']) || $_FILES['foto']['name'] == ''){
                $foto = false;
            }elseif(isset($_FILES['foto']) || $_FILES['foto']['name'] != ''){
                $respuesta = $this->guardarImagen();
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $foto = $respuesta['nombre_foto'];
                }
            }
            
           
            $respuesta = $this->validarProductoExistente($codigo, $nombre);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                if(!$foto){
                    $sentenciaActualizar = "UPDATE `productos` SET `nombre` = '$nombre', `fk_categoria` = $categoria, `stock_minimo` = $stockMinimo, `presentacion` = '$presentacion', `unidad_medida` = '$unidadMedida', `precio_compra` = $precioCompra, `precio_venta` = $precioVenta WHERE codigo_producto = '$codigo';";
                }elseif($foto){
                    $sentenciaActualizar = "UPDATE `productos` SET `nombre` = '$nombre', `fk_categoria` = $categoria, `foto` = '$foto', `stock_minimo` = $stockMinimo, `presentacion` = '$presentacion', `unidad_medida` = '$unidadMedida', `precio_compra` = $precioCompra,`precio_venta` = $precioVenta WHERE codigo_producto = '$codigo';";
                }
                
                $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
                if(!$respuesta) {
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
                    "titulo" => "Actualizacion exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "El producto se actualizo correctamente",
                    "url"=>"productos"
                ];
                return $resultado;
            }
        }

        public function actualizarProductoCocina(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['codigo'], $_POST['tipo_producto'], $_POST['nombre'], $_POST['categoria'], $_POST['presentacion'], $_POST['precio_venta']) || $_POST['codigo'] == '' || $_POST['tipo_producto'] == '' || $_POST['nombre'] == '' || $_POST['categoria'] == '' || $_POST['presentacion'] == '' || $_POST['precio_venta'] == '' ) {
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
                    'filtro' => "[0-9\.]+",
                    'cadena' => $_POST['precio_venta']
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

            $codigo =  $this->limpiarDatos($_POST['codigo']);
            $nombre = $this->limpiarDatos($_POST['nombre']);
            $categoria = $this->limpiarDatos($_POST['categoria']);
            $presentacion = $this->limpiarDatos($_POST['presentacion']);
            $precioVenta = $this->limpiarDatos($_POST['precio_venta']);

            if(!isset($_FILES['foto']) || $_FILES['foto']['name'] == ''){
                $foto = false;
            }elseif(isset($_FILES['foto']) || $_FILES['foto']['name'] != ''){
                $respuesta = $this->guardarImagen();
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $foto = $respuesta['nombre_foto'];
                }
            }
            
            
            $respuesta = $this->validarProductoExistente($codigo, $nombre);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                
                if(!$foto){
                    $sentenciaActualizar = "UPDATE `productos` SET `nombre` = '$nombre', `fk_categoria` = $categoria, `presentacion` = '$presentacion', `precio_venta` = $precioVenta WHERE codigo_producto = '$codigo';";
                }elseif($foto){
                    $sentenciaActualizar = "UPDATE `productos` SET `nombre` = '$nombre', `fk_categoria` = $categoria, `foto` = '$foto', `presentacion` = '$presentacion', `precio_venta` = $precioVenta WHERE codigo_producto = '$codigo';";
                }
                
                $respuesta = $this->ejecutarConsulta($sentenciaActualizar);
                if(!$respuesta) {
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

                $ingredientes = json_decode($_POST['ingredientes'], true);
                if(count($ingredientes) < 1){
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

                $respuesta = $this->eliminarReceta($codigo);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $respuesta = $this->registrarReceta($codigo, $ingredientes);
                    if($respuesta['tipo'] == 'ERROR'){
                        return $respuesta;
                    }elseif($respuesta['tipo'] == 'OK'){
                        $resultado = [
                            "tipo"=>"OK",
                            "cod_error" => "250",
                            "titulo" => "Actualizacion exitósa",
                            'icono' => "success",
                            'alerta'=> "normal",
                            "mensaje" => "El producto se actualizo correctamente",
                            "url"=>"productos"
                        ];
                        return $resultado;
                    }
                }
            }
        }
            
        public function validarProductoExistente($codigo, $nombre=false){
            if(!$nombre){
                $respuesta = $this->consultarProducto($codigo);
            }elseif(!$codigo){
                $respuesta = $this->consultarProducto(false, $nombre);
            }elseif($codigo && $nombre){
                $respuesta = $this->consultarProducto($codigo, $nombre);
            }

            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $datosProducto = $respuesta['producto'];
                if($datosProducto){
                    if($datosProducto['estado'] == 'ACTIVO'){
                        if(!$nombre){
                            $resultado = [
                                "tipo"=>"ERROR",
                                "cod_error" => "350",
                                "titulo" => "Producto existente",
                                "mensaje" => "El codigo del producto que proporcionaste, ya le pertenece a otro producto.",
                                'icono' => "warning",
                                'alerta'=> "normal"
                            ];
                        }elseif(!$codigo || $codigo && $nombre){
                            $resultado = [
                                "tipo"=>"ERROR",
                                "cod_error" => "350",
                                "titulo" => "Producto existente",
                                "mensaje" => "El nombre del producto que proporcionaste, ya le pertenece a otro producto.",
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
                    "titulo" => "Producto no existente",
                    "mensaje" => "El producto no se encuentra registrado",
                ];
                return $resultado;
            }   
        }
        
        public function guardarImagen(){

            if (!isset($_FILES['foto']) || $_FILES['foto']['name'] == '') {
                $resultado = [
                    "tipo" => 'OK',
                    "nombre_foto" => 'sin_foto.jpg'
                ];
                return $resultado;
            }else{
                $carpetaFotos = "../views/img/".$_SESSION['datos_usuario']['bdd_empresa']."/";

                if (!file_exists($carpetaFotos)) {
                    mkdir($carpetaFotos);
                }
                    
                $nombreFoto = $_FILES['foto']['name'];
                $rutaTmp = $_FILES['foto']['tmp_name'];
                $extension = strtolower(pathinfo($nombreFoto, PATHINFO_EXTENSION));
                $nombreFoto = "IM" . date('YmdHis') . '.' . $extension;
                $rutaFoto = $carpetaFotos . $nombreFoto;
                            
                if(!file_exists($rutaFoto)){
                    move_uploaded_file($rutaTmp, $rutaFoto);
                    if(!file_exists($rutaFoto)){
                        $resultado = [
                            'tipo'=>"ERROR",
                            'cod_error' => "350",
                            'titulo' => "Formato Invalido",
                            'mensaje' => "Lo sentimos, hubo un error al cargar la imagen",
                            'icono' => "warning",
                            'alerta'=> "normal"
                        ];
                        return $resultado;
                    }
                    $resultado = [
                        "tipo" => 'OK',
                        "nombre_foto" => $nombreFoto
                    ];
                    return $resultado;
                }
            }
        }

        public function consultarProducto($codigo, $nombre=false){

            if(!$nombre){
                $sentenciaBuscar = "SELECT * FROM productos WHERE codigo_producto = '$codigo'";
            }else if(!$codigo){
                $sentenciaBuscar = "SELECT * FROM productos WHERE nombre = '$nombre'";
            }else if($nombre && $codigo){
                $sentenciaBuscar = "SELECT * FROM productos WHERE nombre = '$nombre' AND codigo_producto <> '$codigo'";
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
            $producto = $respuesta->fetch_assoc();

            $respuesta = $this->consultarReceta($codigo);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $ingredientes = $respuesta['ingredientes'];
                $carpeta = $_SESSION['datos_usuario']['bdd_empresa'];

                $resultado = [
                    'tipo' => "OK",
                    'titulo' => "Consulta Exitósa",
                    'cod_error' => "250",
                    'mensaje' => "La consulta se ejecuto con exitó",
                    'producto' => $producto,
                    'ingredientes' => $ingredientes,
                    'carpeta' => $carpeta
                ];
                return $resultado;
            }
            
        }

        public function consultarProductos($categoria, $estado, $limite): array{
            
            $sentenciaBuscar = "SELECT p.codigo_producto, p.nombre,  p.foto, p.precio_venta, c.nombre AS categoria FROM productos p INNER JOIN categorias c ON p.fk_categoria = c.contador WHERE p.fk_categoria = $categoria AND p.estado = '$estado' AND (p.tipo = 'estand' OR (p.tipo = 'cocina' AND EXISTS(SELECT fk_ingrediente FROM ingredientes_productos WHERE estado = 'ACTIVO' AND fk_producto = p.codigo_producto))) LIMIT $limite;";

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
            
            $productos = $respuesta->fetch_all(MYSQLI_ASSOC);
            $carpeta = $_SESSION['datos_usuario']['bdd_empresa'];
            $cargo = $_SESSION['datos_usuario']['cargo_persona'];
                
            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'productos' => $productos,
                'carpeta' => $carpeta,
                'cargo' => $cargo

            ];
                
            return $resultado;
        }

        public function eliminarProducto($codigo): array{
            $sentenciaActualizar = "UPDATE productos SET estado = 'INACTIVO' WHERE codigo_producto = '$codigo'";
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
                "mensaje" => "El producto se elimino correctamente",
            ];
            return $resultado;
        }
        public function restaurarProductoEstand(){
            if(!isset($_POST['codigo'], $_POST['stock_actual']) || $_POST['codigo'] == '' || $_POST['stock_actual'] == '') {
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
            $sentenciaActualizar = "UPDATE productos SET stock_actual = $stockActual, estado = 'ACTIVO' WHERE codigo_producto = '$codigo';";
            
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
            $respuesta = $this->actualizarProductoEstand();
            
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $resultado = [
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Restauracion Exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "El producto se restauro correctamente",
                    "url" => 'productos'
                ];
                return $resultado;
            }
        }
        
        public function restaurarProductoCocina(){
            if(!isset($_POST['codigo']) || $_POST['codigo'] == '' ) {
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
            $sentenciaActualizar = "UPDATE productos SET  estado = 'ACTIVO' WHERE codigo_producto = '$codigo';";
            
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
            
            $respuesta = $this->actualizarProductoCocina();
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $resultado = [
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Restauracion Exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "El producto se restauro correctamente",
                    "url" => 'productos'
                ];
                return $resultado;
            }
        }

        private function registrarReceta($producto, $ingredientes){
            foreach($ingredientes as $ingrediente){

                $datos = [
                    [
                        'filtro' => "[A-Za-z0-9]+",
                        'cadena' => $ingrediente
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

                $sentenciaInsertar = "INSERT INTO `ingredientes_productos`(`fk_producto`, `fk_ingrediente`) VALUES ('$producto', '$ingrediente')";
        
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
            } 

            $resultado = [
                'tipo' => "OK",
                'titulo' => "Registro Exitóso", 
                'mensaje' => "La receta se registro correctamente",
            ];
            return $resultado;
        }

        public function consultarReceta($codigo){
            $sentenciaBuscar = "SELECT i.nombre, i.codigo_ingrediente FROM ingredientes_productos INNER JOIN ingredientes i ON i.codigo_ingrediente = fk_ingrediente WHERE fk_producto = '$codigo';";

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
                'mensaje' => "La receta se registro correctamente",
                'ingredientes' => $ingredientes
            ];
            return $resultado;
        }
        private function eliminarReceta($producto){
            $sentenciaEliminar = "DELETE FROM ingredientes_productos WHERE fk_producto = '$producto' AND estado = 'ACTIVO';";
            $respuesta = $this->ejecutarConsulta($sentenciaEliminar);
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
                'tipo'=>"OK",
                'mensaje' => "La receta se elimino correctamente",
            ];
            return $resultado;
        }
    }
      
    