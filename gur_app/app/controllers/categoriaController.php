<?php
    namespace app\controllers;
	use app\models\mainModel;

	class CategoriaController extends mainModel{
        /* -----------Controlador Categorias ---------------- */
        public function registrarCategoria(){
            if(!isset($_POST['nombre'], $_POST['ubicacion'], $_POST['emoji']) || $_POST['nombre'] == '' || $_POST['ubicacion'] == '' || $_POST['emoji'] == ''){
                $resultado = [
                    'tipo' => "ERROR",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatorios.",
                    'icono' => "warning",
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
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['ubicacion']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['emoji']
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

            $nombre = $this->limpiarDatos($_POST['nombre']);
            $ubicacion = $this->limpiarDatos($_POST['ubicacion']);
            $emoji = $this->limpiarDatos($_POST['emoji']);

            $respuesta = $this->validarCategoriaExistente(false, $nombre);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $fechaRegistro = date('Y-m-d H:i:s', time());
                $usrSistema = $_SESSION['datos_usuario']['num_identificacion_persona'];
                $sentenciaInsertar = "INSERT INTO categorias(nombre, ubicacion, fk_usr_sistema, fecha_registro, emoji) VALUES ('$nombre', '$ubicacion', '$usrSistema', '$fechaRegistro', $emoji);";

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
                $resultado = [
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Registro exitóso",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "La categoria se registro correctamente",
                    "url"=>"categorias"
                ];
                return $resultado;
            }
        }
        public function actualizarCategoria(){
            if(!isset($_POST['nombre'], $_POST['codigo'], $_POST['ubicacion'], $_POST['emoji']) || $_POST['nombre'] == '' || $_POST['codigo'] == '' || $_POST['ubicacion'] == '' || $_POST['emoji'] == ''){
                $resultado = [
                    'tipo' => "ERROR",
                    'titulo' => "Campos Requeridos",
                    'mensaje' => "Lo sentimos, es necesario que llenes todos los campos que son obligatosios.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];

                return $resultado;
            }

            $datos = [
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['codigo']
                ],
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['nombre']
                ],
                [
                    'filtro' => "[A-Za-zÑñ0-9\s]+",
                    'cadena' => $_POST['ubicacion']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['emoji']
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
            $ubicacion = $this->limpiarDatos($_POST['ubicacion']);
            $emoji = $this->limpiarDatos($_POST['emoji']);

            $respuesta = $this->validarCategoriaExistente($codigo, $nombre);
            if(!$respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $sentenciaActualizar = "UPDATE categorias SET nombre = '$nombre', ubicacion = '$ubicacion', emoji = $emoji WHERE contador = $codigo";
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
                    "titulo" => "Actualizacion Exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "La categoria se actualizo correctamente",
                    "url"=>"categorias"
                ];
                return $resultado;
            }   
        }

        public function validarCategoriaExistente($codigo, $nombre=false){
            if(!$nombre){
                $respuesta = $this->consultarCategoria($codigo);
            }elseif(!$codigo){
                $respuesta = $this->consultarCategoria(false, $nombre);
            }elseif($codigo && $nombre){
                $respuesta = $this->consultarCategoria($codigo, $nombre);
            }

            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $datosCategoria = $respuesta['categoria'];
                if($datosCategoria){
                    $resultado = [
                        "tipo"=>"ERROR",
                        "cod_error" => "350",
                        "titulo" => "Categoria existente",
                        "mensaje" => "El nombre de la categoria que proporcionaste, ya le pertenece a otra categoria.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }
                
                $resultado = [
                    "tipo" => "OK",
                    "cod_error" => "250",
                    "titulo" => "Categoria no existente",
                    "mensaje" => "La categoria no se encuentra registrada",
                ];
                return $resultado;
            }   
        }

        public function validarUsoCategoria($codigo){
            $sentenciaBuscar = "SELECT c.nombre FROM categorias c WHERE c.contador = $codigo AND (EXISTS(SELECT p.nombre FROM productos p WHERE fk_categoria = c.contador) OR EXISTS(SELECT i.nombre FROM ingredientes i WHERE fk_categoria = c.contador));";

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

            if($respuesta->num_rows > 0){
                $mensaje = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Categoria en Uso",
                    'mensaje' => "La categoria no se puede eliminar porque hace parte de un producto o un ingrediente.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $mensaje;
            }

            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Categoria sin uso",
                'mensaje' => "La categoria no esta asociada a ningún producto o ingrediente"
            ];
            return $resultado;
        }

        public function eliminarCategoria($codigo){
            
            $respuesta = $this->validarUsoCategoria($codigo);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $sentenciaEliminar = "DELETE FROM categorias WHERE contador = '$codigo';";
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
                    "tipo"=>"OK",
                    "cod_error" => "250",
                    "titulo" => "Eliminacion Exitósa",
                    'icono' => "success",
                    'alerta'=> "normal",
                    "mensaje" => "La categoria se elimino correctamente",
                ];
                return $resultado;
            }
        }

        public function consultarCategoriasProductos($estado){
            $sentenciaBuscar = "SELECT c.contador, c.nombre, COUNT(p.codigo_producto) AS cantidad_productos, c.emoji FROM categorias c LEFT JOIN productos p ON c.contador = p.fk_categoria AND p.estado = '$estado' AND (p.tipo = 'estand' OR (p.tipo = 'cocina' AND EXISTS(SELECT 1 FROM ingredientes_productos WHERE estado = 'ACTIVO' AND fk_producto = p.codigo_producto))) GROUP BY c.contador, c.nombre, c.emoji ORDER BY c.nombre;";

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

            $categorias = $respuesta->fetch_all(MYSQLI_ASSOC);
            $total_productos = 0;

            foreach ($categorias as $categoria) {
                $total_productos += $categoria['cantidad_productos'];
            };

            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'categorias' => $categorias,
                'total_productos' => $total_productos
            ];
            return $resultado;
        }

        public function consultarCategorias(){
            $sentenciaBuscar = "SELECT c.contador, c.nombre, c.ubicacion, c.emoji FROM categorias c ORDER BY c.nombre;";
            
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
                
            $categorias = $respuesta->fetch_all(MYSQLI_ASSOC);
            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'categorias' => $categorias
            ];
            return $resultado; 
        }

        public function consultarCategoria($codigo, $nombre=false){
            if(!$nombre){
                $sentenciaBuscar = "SELECT nombre, ubicacion, emoji FROM categorias WHERE contador = $codigo;";
            }else if(!$codigo){
                $sentenciaBuscar = "SELECT nombre, ubicacion FROM categorias WHERE nombre = '$nombre';";
            }else if($nombre && $codigo){
                $sentenciaBuscar = "SELECT nombre, ubicacion FROM categorias WHERE nombre = '$nombre' AND contador <> $codigo;";
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
            $categoria = $respuesta->fetch_assoc();
            $resultado = [
                'tipo' => "OK",
                'titulo' => "Consulta Exitósa",
                'cod_error' => "250",
                'mensaje' => "La consulta se ejecuto con exitó",
                'categoria' => $categoria
            ];
            return $resultado;
        } 
    }