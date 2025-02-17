<?php

    namespace app\controllers;
    use app\models\mainModel;

    class MesaController extends mainModel{
        public function registrarMesa(){
            date_default_timezone_set('America/Bogota');
            if(!isset($_POST['numero'], $_POST['capacidad']) || $_POST['numero'] == '' || $_POST['capacidad'] == ''){
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
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['numero']
                ],
                [
                    'filtro' => "[0-9]+",
                    'cadena' => $_POST['capacidad']
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

            $numero = $this->limpiarDatos($_POST['numero']);
            $capacidad = $this->limpiarDatos($_POST['capacidad']);
            $nombre = 'M'.$numero;

            $respuesta = $this->consultarMesa($numero);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                if($respuesta['mesa']){
                    $respuesta = $this->restaurarMesa($numero, $capacidad);
                    if($respuesta['tipo'] == 'ERROR'){
                        return $respuesta;
                    }

                }elseif(!$respuesta['mesa']){
                    $fechaRegistro = date('Y-m-d H:i:s');
                    $sentenciaInsertar = "INSERT INTO mesas(numero, nombre, capacidad, fecha_registro) VALUES('$numero', '$nombre', '$capacidad', '$fechaRegistro');";
    
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
                }
                $resultado = [
                    'tipo' => "OK",
                    'cod_error' => "250",
                    'titulo' => "Registro Exitóso",
                    'mensaje' => "La mesa se registro correctamente.",
                    'icono' => "success",
                    'alerta'=> "normal",
                    'url' => "mesas"
                ];
                return $resultado;
            }
        }

        public function consultarMesas($disponibilidad){
            
            if($disponibilidad == 'todas'){
                $sentenciaBuscar = "SELECT DISTINCT m.numero, m.nombre, COALESCE(p.disponibilidad, 'libre') AS disponibilidad FROM mesas m LEFT JOIN (SELECT fk_mesa, 'ocupada' AS disponibilidad FROM pedidos WHERE estado = 'ACTIVO') p ON m.numero = p.fk_mesa WHERE m.estado = 'ACTIVO';";

            }else if($disponibilidad == 'libres'){
                $sentenciaBuscar = "SELECT m.numero, m.nombre, 'libre' AS disponibilidad FROM mesas m LEFT JOIN (SELECT fk_mesa, 'ocupada' AS disponibilidad FROM pedidos WHERE estado = 'ACTIVO') p ON m.numero = p.fk_mesa WHERE m.estado = 'ACTIVO' AND p.disponibilidad IS NULL;";

            }else if($disponibilidad == 'ocupadas'){
                $sentenciaBuscar = "SELECT DISTINCT m.numero, m.nombre, p.disponibilidad FROM mesas m INNER JOIN (SELECT fk_mesa, 'ocupada' AS disponibilidad FROM pedidos WHERE estado = 'ACTIVO') p ON m.numero = p.fk_mesa WHERE m.estado = 'ACTIVO';";
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
            $mesas = $respuesta->fetch_all(MYSQLI_ASSOC);
            $cargo = $_SESSION['datos_usuario']['cargo_persona'];

            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitosa",
                'mensaje' => "La consulta se ejecuto correctamente.",
                'mesas' => $mesas,
                'cargo' => $cargo
            ];
            return $resultado;
        }

        public function consultarMesa($codigo){
            $sentenciaBuscar = "SELECT COALESCE(p.disponibilidad, 'libre') AS disponibilidad FROM mesas m LEFT JOIN (SELECT fk_mesa, fk_usr_sistema, 'ocupada' AS disponibilidad FROM pedidos WHERE estado = 'ACTIVO') p ON p.fk_mesa = m.numero WHERE m.numero = '$codigo' LIMIT 1;";
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
            $mesa = $respuesta->fetch_assoc();
                
            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Consulta Exitosa",
                'mensaje' => "La consulta se ejecuto correctamente.",
                'mesa' => $mesa
            ];
            return $resultado;
        }

        public function validarUsuarioMesa($codigo){
            $sentenciaBuscar = "SELECT fk_usr_sistema FROM pedidos WHERE fk_mesa = '$codigo' AND estado = 'ACTIVO' LIMIT 1;";

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

            $usuarioActual = $_SESSION['datos_usuario']['num_identificacion_persona'];
            $usuarioMesa = $respuesta->fetch_assoc()['fk_usr_sistema'];

            if($usuarioActual != $usuarioMesa){ 
                $resultado = [
                    'tipo' => "ERROR",
                    'cod_error' => "350",
                    'titulo' => "Usuario Denegado",
                    'mensaje' => "Esta Mesa se encuentra atendida por otro usuario.",
                    'icono' => "warning",
                    'alerta'=> "normal"
                ];
                return $resultado;
            }

            $resultado = [
                'tipo' => "OK",
                'cod_error' => "250",
                'titulo' => "Usuario Autorizado",
                'mensaje' => "Usuario autorizado para operacion solicitada"
            ];
            return $resultado;
        }

        private function validarMesaLibre($codigo){
            $respuesta = $this->consultarMesa($codigo);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $disponibilidadMesa = $respuesta['mesa']['disponibilidad'];
                if($disponibilidadMesa == 'ocupada'){
                    $resultado = [
                        'tipo'=>"ERROR",
                        'cod_error' => "350",
                        'titulo' => "Mesa Ocupada",
                        'mensaje' => "Lo sentimos, la mesa en este momento se encuentra con un pedido activo.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    return $resultado;
                }elseif($disponibilidadMesa == 'libre'){
                    $resultado = [
                        'tipo' => "OK",
                        'titulo' => "Mesa libre",
                        'mensaje' => "La mesa no tiene pedidos activos."
                    ];
                    return $resultado;
                }
            }
        }

        public function eliminarMesa($codigo){

            $respuesta = $this->validarMesaLibre($codigo);
            if($respuesta['tipo'] == 'ERROR'){
                return $respuesta;
            }elseif($respuesta['tipo'] == 'OK'){
                $sentenciaActualizar = "UPDATE mesas SET estado = 'INACTIVO' WHERE numero = '$codigo';";
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
                    'mensaje' => "La mesa se elimino correctamente.",
                    'icono' => "success",
                    'alerta'=> "normal",
                ];
                return $resultado;
            }
        }

        private function restaurarMesa($codigo, $capacidad){
            $sentenciaActualizar = "UPDATE mesas SET capacidad = '$capacidad', estado = 'ACTIVO' WHERE numero = '$codigo';";
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
                'titulo' => "Restauracion Exitósa",
                'mensaje' => "La mesa se restauro correctamente.",
            ];
            return $resultado;
        }
    }