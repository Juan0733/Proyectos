<?php

	namespace app\controllers;
    use app\controllers\ProductoController;

    class PedidoController extends ProductoController{
        public function registrarPedido(){
            date_default_timezone_set('America/Bogota');
            if (!isset($_POST['pedido']) || $_POST['pedido'] == '') {
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatorios.",'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $pedido = json_decode($_POST['pedido'], true);

            $datos = [
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $pedido['mesa']
                ]
            ];

            $respuesta = $this->verificarDatos($datos);
            if(!$respuesta){
                $resultado = [
                    'tipo'=>"ERROR",
                    'cod_error' => "350",
                    'titulo' => "Formato Invalido",
                    'mensaje' => "Lo sentimos, los datos no cumplen con la estructura requerida.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $mesa = $this->limpiarDatos($pedido['mesa']);
            $fechaRegistro = date('Y-m-d H:i:s');
            $usrSistema = $_SESSION['datos_usuario']['num_identificacion_persona'];

            $respuesta = $this->consultarPedidosMesa($mesa);
            if($respuesta['tipo'] == "ERROR"){
                return $respuesta;
            }elseif($respuesta['tipo'] == "OK"){
                $cantidadPedidos = count($respuesta['pedidos']) + 1;
                $tituloPedido = "Pedido No".$cantidadPedidos;
                $sentenciaInsertar = "INSERT INTO pedidos(titulo, fk_mesa, fk_usr_sistema, total, fecha_registro) VALUES('$tituloPedido', $mesa, '$usrSistema', 0, '$fechaRegistro')"; 

                $respuesta = $this->ejecutarConsulta($sentenciaInsertar);
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

                $sentenciaBuscar = "SELECT contador FROM pedidos WHERE fecha_registro = '$fechaRegistro' AND fk_mesa = '$mesa';";

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

                $idPedido = $respuesta->fetch_assoc()['contador'];
                $pedido['id'] = $idPedido;

                $respuesta = $this->registrarItems($pedido);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $resultado = [
                        "tipo"=>"OK",
                        "cod_error" => "250",
                        "titulo" => "Registro exitóso",
                        'icono' => "success",
                        'alerta'=> "normal",
                        "mensaje" => "El pedido se registro correctamente",
                        "url" => "mesas/".$mesa
                    ];
                    return $resultado;
                }  
            }
        }

        public function registrarItems($datosPedido=false){
            date_default_timezone_set('America/Bogota');
            if(!$datosPedido){
                if (!isset($_POST['pedido']) || $_POST['pedido'] == ''){
                    $resultado = [
                        'tipo' => "ERROR",
                        'cod_error' => "350",
                        'titulo' => "Campos Requeridos",
                        'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatorios.",'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }
    
                $pedido = json_decode($_POST['pedido'], true);

            }elseif($datosPedido){
                $pedido = $datosPedido;
            }
            
            $datos = [
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $pedido['id']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $pedido['mesa']
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

            if(count($pedido['items']) < 1){
                $resultado = [
                    'tipo'=>"ERROR",
                    'cod_error' => "350",
                    'titulo' => "Formato Invalido",
                    'mensaje' => "Lo sentimos, los datos no cumplen con la estructura requerida.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];

                return $resultado;
            }
           
            $idPedido = $this->limpiarDatos($pedido['id']);
            $mesa = $this->limpiarDatos($pedido['mesa']);
            $items = $pedido['items'];
            $fechaRegistro = date('Y-m-d H:i:s');
            $total = 0;

            foreach ($items as $item) {
                $datos = [
                    [
                        'filtro' => "[A-Za-z0-9]+",
                        'cadena' => $item['id']
                    ],
                    [
                        'filtro' => "[0-9]+",
                        'cadena' => $item['cantidad']
                    ],
                    [
                        'filtro' => "$^|[A-Za-zÑñ0-9\s]+",
                        'cadena' => $item['observacion']
                    ]

                ];

                $respuesta = $this->verificarDatos($datos);
                if(!$respuesta){
                    $resultado = [
                        'tipo'=>"ERROR",
                        'cod_error' => "350",
                        'titulo' => "Formato Invalido",
                        'mensaje' => "Lo sentimos, los datos no cumplen con la estructura requerida.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                   return $resultado;
                }

                $idProducto = $this->limpiarDatos($item['id']);
                $cantidad = $this->limpiarDatos($item['cantidad']);
                $ingredientesEliminados = $item['ingredientes_eliminados'];
                $observacion = $this->limpiarDatos($item['observacion']);
               
                $respuesta = $this->consultarProducto($idProducto);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $tipoProducto = $respuesta['producto']['tipo'];
                    $precioVenta = $respuesta['producto']['precio_venta'];
                    $precioCompra = $respuesta['producto']['precio_compra'];
                    $subtotal = $precioVenta * $cantidad;
                    $total += $subtotal;
    
                    if($tipoProducto == 'estand'){
                        $sentenciaInsertar = "INSERT INTO detalles_pedidos(fk_pedido, fk_producto, cantidad,cantidad_sin_entregar, precio_compra, precio_venta, total, fecha_registro, observacion) VALUES($idPedido, '$idProducto', $cantidad, $cantidad, $precioCompra, $precioVenta, $subtotal, '$fechaRegistro', '$observacion');";
                    }elseif($tipoProducto == 'cocina'){
                        $sentenciaInsertar = "INSERT INTO detalles_pedidos(fk_pedido, fk_producto, cantidad, cantidad_sin_entregar, precio_venta, total, fecha_registro, observacion) VALUES($idPedido, '$idProducto', $cantidad, $cantidad, $precioVenta, $subtotal, '$fechaRegistro', '$observacion');";
                    }
    
                    $respuesta = $this->ejecutarConsulta($sentenciaInsertar);
                    if(!$respuesta){
                        $resultado = [
                            'tipo' => "ERROR",
                            'cod_error' => "350",
                            'titulo' => "Error al Consultar",
                            'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.".$precioCompra,
                            'icono' => "warning",
                            'alerta'=> "normal"
                        ];
                        return $resultado;
                    }

                    if(count($ingredientesEliminados) > 0){
                        $sentenciaBuscar = "SELECT contador FROM detalles_pedidos WHERE fk_pedido = $idPedido ORDER BY contador DESC LIMIT 1;";

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

                        $idItem = $respuesta->fetch_assoc()['contador'];

                        $respuesta = $this->registrarIngredientesEliminados($idItem, $ingredientesEliminados);
                        if($respuesta['tipo'] == "ERROR"){
                            return $respuesta;
                        }
                    }
                }
            }

            $respuesta = $this->actualizarTotalPedido('sumar', $idPedido, $total);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $resultado = [
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Registro exitóso",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "Los productos fueron agregados correctamente al pedido.",
                    "url"=> "mesas/".$mesa
                ];
                return $resultado;
            }
        }

        private function registrarIngredientesEliminados($idItem, $ingredientesEliminados){
            foreach ($ingredientesEliminados as $ingrediente) {
                $datos = [
                    [
                        'filtro' => "[A-Za-z0-9]+",
                        'cadena' => $ingrediente
                    ]
                ];

                $respuesta = $this->verificarDatos($datos);
                if(!$respuesta){
                    $resultado=[
                        'tipo'=>"ERROR",
                        'cod_error' => "350",
                        'titulo' => "Formato Invalido",
                        'mensaje' => "Lo sentimos, los datos no cumplen con la estructura requerida.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                   return $resultado;
                }

                $sentenciaInsertar = "INSERT INTO ingredientes_eliminados(fk_item, fk_ingrediente) VALUES($idItem, '$ingrediente');";

                $respuesta = $this->ejecutarConsulta($sentenciaInsertar);
                if(!$respuesta){
                    $resultado = [
                        'tipo' => "ERROR",
                        'cod_error' => "350",
                        'titulo' => "Error al Consultar",
                        'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema
                        persiste, contacta con el soporte de Zobyte Soluciones.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }
            }

            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Registro Exitóso",
                'mensaje' => "Ingredientes eliminados, registrados con exitó."
            ];
            return $resultado;
        }
        private function actualizarTotalPedido($operacion, $idPedido, $valor){
            if($operacion == 'sumar'){
                $sentenciaActualizar = "UPDATE pedidos SET total = total + $valor WHERE contador = $idPedido;";
            }elseif($operacion == 'restar'){
                $sentenciaActualizar = "UPDATE pedidos SET total = total - $valor WHERE contador = $idPedido;"; 
            }

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
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Actualizacion Exitosa",
                'mensaje' => "El total del pedido se actualizo correctamente",
            ];
            return $resultado;
        }

        public function consultarPedidosGenerales(){
            $sentenciaBuscar = "SELECT contador, titulo, total, COALESCE(mesa.nombre, 'Domicilio') AS tipo FROM pedidos LEFT JOIN mesas numero = fk_mesa WHERE estado = 'ACTIVO';";
    
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

            $pedidos = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitósa",
                'mensaje' => "La consulta se ejecuto correctamente",
                'pedidos' => $pedidos
            ];
            return $resultado;
        }

    
        public function consultarPedidosMesa($mesa){
            $sentenciaBuscar = "SELECT contador, titulo, total FROM pedidos WHERE estado = 'ACTIVO' AND fk_mesa = '$mesa';";
    
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

            $pedidos = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitósa",
                'mensaje' => "La consulta se ejecuto correctamente",
                'pedidos' => $pedidos
            ];
            return $resultado;
        }

        public function consultarItemsMesero($idPedido){
            $sentenciaBuscar = $sentenciaBuscar = "SELECT p.nombre, p.foto, d.contador, d.fk_producto, d.cantidad, d.cantidad_entregada, d.cantidad_sin_entregar, d.estado_cocina, d.total, d.observacion, TIME(d.fecha_registro) AS hora FROM detalles_pedidos d INNER JOIN productos p ON p.codigo_producto = d.fk_producto WHERE d.estado = 'ACTIVO' AND d.fk_pedido = $idPedido;";

            $respuesta = $this->ejecutarConsulta($sentenciaBuscar);
            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, yu ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste,contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $items = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitósa",
                'mensaje' => "La consulta se ejecuto correctamente",
                'items' => $items
            ];
            return $resultado;

        }
        
        public function consultarItem($idItem){
            $sentenciaBuscar = "SELECT fk_pedido, precio_venta, cantidad, total FROM detalles_pedidos WHERE contador = $idItem;";

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

            $item = $respuesta->fetch_assoc();
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitósa",
                'mensaje' => "La consulta se ejecuto correctamente",
                'item' => $item
            ];
            return $resultado;
        }


        public function informacionPedidosMesa($mesa){
            $respuesta = $this->consultarPedidosMesa($mesa);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $datosPedidos = $respuesta['pedidos'];
                $pedidos = [];
                foreach ($datosPedidos as $pedido) {
                    $idPedido = $pedido['contador'];
                    $tituloPedido = $pedido['titulo'];
                    $total = $pedido['total'];
    
                    $respuesta = $this->consultarItemsMesero($idPedido);
                    if($respuesta['tipo'] == 'ERROR'){
                        return $respuesta;
                    }elseif($respuesta['tipo'] == 'OK'){
                        $datosItems = $respuesta['items'];
                        $productos = [];
                        foreach($datosItems as $item) {
                            $idItem = $item['contador'];
                            $idProducto = $item['fk_producto'];
                            $nombreProducto = $item['nombre'];
                            $fotoProducto = $item['foto'];
                            $cantidad = $item['cantidad'];
                            $cantidadSinEntregar = $item['cantidad_sin_entregar'];
                            $cantidadEntregada = $item['cantidad_entregada'];
                            $hora = $item['hora'];
                            $totalDetalle = $item['total'];
                            $observacion = $item['observacion'];
                            $productoExistente = false;

                            $respuesta = $this->consultarIngredientesEliminados($idItem);
                            if($respuesta['tipo'] == 'ERROR'){
                                return $respuesta;
                            }elseif($respuesta['tipo'] == 'OK'){
                                $ingredientesEliminados = $respuesta['ingredientes_eliminados'];
                                foreach($productos as &$producto){ 
                                    if($producto['id'] == $idProducto){
                                        $producto['items'][] = [
                                            'id' => $idItem,
                                            'cantidad_sin_entregar' => $cantidadSinEntregar,
                                            'cantidad_entregada' => $cantidadEntregada,
                                            'hora' => $hora,
                                            'observacion' => $observacion,
                                            'ingredientes_eliminados' => $ingredientesEliminados
                                        ];
                                        $producto['cantidad'] += $cantidad;
                                        $producto['subtotal'] += $totalDetalle;
                                        $producto['cantidad_sin_entregar'] += $cantidadSinEntregar;
                                        $productoExistente = true;
                                        break;
                                    }
                                }
        
                                if(!$productoExistente){
                                    $productos[] = [
                                        'id' => $idProducto,
                                        'nombre' => $nombreProducto,
                                        'foto' => $fotoProducto,
                                        'items' => [
                                            [
                                                'id' => $idItem,
                                                'cantidad_sin_entregar' => $cantidadSinEntregar,
                                                'cantidad_entregada' => $cantidadEntregada,
                                                'hora' => $hora,
                                                'observacion' => $observacion,
                                                'ingredientes_eliminados' => $ingredientesEliminados
                                            ]
                                        ],
                                        'cantidad' => $cantidad,
                                        'cantidad_sin_entregar' => $cantidadSinEntregar,
                                        'subtotal' => $totalDetalle
                                    ];
                                }
                            }
                        }
                        $pedidos[] = [
                            'id' => $idPedido,
                            'titulo' => $tituloPedido,
                            'productos' => $productos,
                            'total' => $total,
                        ];
                    }
                }
            }
            
            $carpeta = $_SESSION['datos_usuario']['bdd_empresa'];
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitosa",
                'mensaje' => "La consulta se ejecuto correctamente.",
                'pedidos' => $pedidos,
                'carpeta' => $carpeta
            ];
            return $resultado;
        }

        public function consultarIngredientesEliminados($idItem){
            $sentenciaBuscar = "SELECT nombre FROM ingredientes_eliminados INNER JOIN ingredientes ON codigo_ingrediente = fk_ingrediente WHERE fk_item = $idItem;";

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

            $ingredientesEliminados = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitosa",
                'mensaje' => "La consulta se ejecuto correctamente.",
                'ingredientes_eliminados' => $ingredientesEliminados,
            ];
            return $resultado;
        }
        
        public function eliminarPedido($pedido){
            $sentenciaActualizar = "UPDATE pedido SET estado = 'INACTIVO' WHERE contador = $pedido;";
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
            $sentenciaActualizar = "UPDATE detalles_pedidos SET estado = 'INACTIVO' WHERE fk_pedido = $pedido;";
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
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Eliminacion Exitosa",
                'mensaje' => "El pedido de la mesa fue eliminado correctamente.",
                'icono' => "success",
                'alerta'=> "normal"
            ];
            return $resultado;    
        }

      
        public function eliminarItem($idItem, $estadoMesero=false){
            $respuesta = $this->consultarItem($idItem);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $idPedido = $respuesta['item']['fk_pedido'];
                $cantidad = $respuesta['item']['cantidad'];
                $precioUnitario = $respuesta['item']['precio_venta'];

                if($cantidad > 1){
                    if($estadoMesero == 'sin_entregar'){
                        $sentenciaActualizar = "UPDATE detalles_pedidos SET cantidad = cantidad - 1, cantidad_sin_entregar = cantidad_sin_entregar - 1, total = total - $precioUnitario WHERE contador = $idItem;";
                    }elseif($estadoMesero == 'entregado'){
                        $sentenciaActualizar = "UPDATE detalles_pedidos SET cantidad = cantidad - 1, cantidad_entregada = cantidad_entregada - 1, total = total - $precioUnitario WHERE contador = $idItem;";
                    }elseif(!$estadoMesero){
                        $sentenciaActualizar = "UPDATE detalles_pedidos SET cantidad = cantidad - 1, total = total - $precioUnitario WHERE contador = $idItem;";
                    }
                }elseif($cantidad == 1){
                    $sentenciaActualizar = "UPDATE detalles_pedidos SET estado = 'INACTIVO' WHERE contador = $idItem;";
                }

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

                $respuesta = $this->actualizarTotalPedido('restar', $idPedido, $precioUnitario);
                if($respuesta['tipo'] == 'ERROR'){
                    return $respuesta;
                }elseif($respuesta['tipo'] == 'OK'){
                    $resultado = [
                        'tipo' => "OK",
                        'cod_error' => "250",
                        'titulo' => "Eliminacion Exitosa",
                        'mensaje' => "El producto fue eliminado correctamente del pedido.",
                        'icono' => "success",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }
            }
        }

        public function entregarItem($item){
            $sentenciaActualizar = "UPDATE detalles_pedidos SET cantidad_entregada = cantidad_entregada + 1, cantidad_sin_entregar = cantidad_sin_entregar - 1 WHERE contador = $item;";

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
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Entrega Exitosa",
                'mensaje' => "La entrega del item se registro correctamente.",
                'icono' => "success",
                'alerta'=> "normal"
            ];
            return $resultado;
        }

        public function entregarItems($pedido, $producto){
            $sentenciaActualizar = "UPDATE detalles_pedidos SET cantidad_entregada = cantidad, cantidad_sin_entregar = 0 WHERE fk_pedido = $pedido AND fk_producto = '$producto';";

            $respuesta = $this->ejecutarConsulta($sentenciaActualizar);

            if(!$respuesta){
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Error al Consultar",
                    'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Porfavor, verifica tus datos e inténtalo nuevamente. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Entrega Exitosa",
                'mensaje' => "La entrega del producto se registro correctamente.",
                'icono' => "success",
                'alerta'=> "normal"
            ];
            return $resultado;
        }
    }
       