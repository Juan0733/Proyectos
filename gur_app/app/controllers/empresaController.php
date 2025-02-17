<?php

	namespace app\controllers;
	use app\models\mainModel;

	class empresaController extends mainModel{
        public  function registrarEmpresaControlador(){
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
				header("location:./");
				exit;
            }else{
                
                header('Content-Type: application/json; charset=utf-8');
                if (!isset(
                    $_POST['num_id_empresa'],
                    $_POST['tipo_identificacion'],
                    $_POST['nombre_empresa'],
                    $_POST['tipo_empresa'],
                    $_POST['departamento'],
                    $_POST['ciudad'],
                    $_POST['calle'],
                    $_POST['correo_empresa'],
                    $_POST['telefono_empresa'],
                    $_POST['tipo_id_representante'],
                    $_POST['num_id_representante'],
                    $_POST['nombre_representante'],
                    $_POST['apellidos_representante'],
                    $_POST['telefono_representante']
                    ) ||
                    $_POST['num_id_empresa'] == "" ||
                    $_POST['tipo_identificacion'] == "" ||
                    $_POST['nombre_empresa'] == "" ||
                    $_POST['tipo_empresa'] == "" ||
                    $_POST['departamento'] == "" ||
                    $_POST['ciudad'] == "" ||
                    $_POST['calle'] == "" ||
                    $_POST['correo_empresa'] == "" ||
                    $_POST['telefono_empresa'] == "" ||
                    $_POST['tipo_id_representante'] == "" ||
                    $_POST['num_id_representante'] == "" ||
                    $_POST['nombre_representante'] == "" ||
                    $_POST['apellidos_representante'] == "" ||
                    $_POST['telefono_representante'] == ""
                ) {
                    $resultado = [
                        'tipo' => "error",
                        'titulo' => "Campos Requeridos",
                        'mensaje' => "Lo sentimos, es necesario que ingreses todos los datos para lograr registrar la empresa.",
                        'icono' => "warning",
                        'alerta'=> "normal"
                    ];
                    echo json_encode($resultado);
                    exit();
                }else {
                    $campos_invalidos = [];
                    if ($this->verificarDatos('[0-9]{6,15}', $_POST['num_id_empresa'])) {
                        array_push($campos_invalidos, 'Número de Identificación');
                    }else {
                        $num_id_empresa = $_POST['num_id_empresa'];
                    }
                    
                    if ($this->verificarDatos('[A-Z]{2,3}', $_POST['tipo_identificacion'])) {
                        array_push($campos_invalidos, 'Tipo de Identificación');
                    }else {
                        $tipo_identificacion= $_POST['tipo_identificacion'];
                    }
                    
                    
                    if ($this->verificarDatos('[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s]{3,100}', $_POST['nombre_empresa'])) {
                        array_push($campos_invalidos, 'Nombre de la Empresa');
                    }else {
                        $nombre_empresa = $_POST['nombre_empresa'];
                    }

                    if ($this->verificarDatos('[A-Z]{2}', $_POST['tipo_empresa'])) {
                        array_push($campos_invalidos, 'Tipo de Empresa');
                    }else {
                        $tipo_empresa = $_POST['tipo_empresa'];
                    }

                    if ($this->verificarDatos('[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s]{3,60}', $_POST['departamento'])) {
                        array_push($campos_invalidos, 'Departamento');
                    }else {
                        $departamento = $_POST['departamento'];
                    }

                    if ($this->verificarDatos('[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s]{3,60}', $_POST['ciudad'])) {
                        array_push($campos_invalidos, 'Ciudad');
                    }else {
                        $ciudad = $_POST['ciudad'];
                    }

                    if ($this->verificarDatos('[A-Za-z0-9#\-\s]{5,100}', $_POST['calle'])) {
                        array_push($campos_invalidos, 'Dirección');
                    }else {
                        $calle = $_POST['calle'];
                    }

                    if ($this->verificarDatos('[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$', $_POST['correo_empresa'])) {
                        array_push($campos_invalidos, 'correo_empresa');
                    }else {
                        $correo_empresa = $_POST['correo_empresa'];
                    }

                    if ($this->verificarDatos('\d{7,10}', $_POST['telefono_empresa'])) {
                        array_push($campos_invalidos, 'Teléfono');
                    }else {
                        $telefono_empresa = $_POST['telefono_empresa'];
                    }

                    /* REPRESENTANTE */


                    

                    if ($this->verificarDatos('[A-Z]{2}', $_POST['tipo_id_representante'])) {
                        array_push($campos_invalidos, 'Tipo de Identificación representante');
                    }else {
                        $tipo_id_representante = $_POST['tipo_id_representante'];
                    }

                    if ($this->verificarDatos('\d{5,15}', $_POST['num_id_representante'])) {
                        array_push($campos_invalidos, 'Número de Identificación representante');
                    }else {
                        $num_id_representante = $_POST['num_id_representante'];
                    }

                    if ($this->verificarDatos('[A-Za-zÁÉÍÓÚáéíóúñÑ0 ]{3,64}', $_POST['nombre_representante'])) {
                        array_push($campos_invalidos, 'Nombre representante');
                    }else {
                        $nombre_representante = $_POST['nombre_representante'];
                    }

                    if ($this->verificarDatos('[A-Za-zÁÉÍÓÚáéíóúñÑ0 ]{3,64}', $_POST['apellidos_representante'])) {
                        array_push($campos_invalidos, 'Apellidos');
                    }else {
                        $apellidos_representante = $_POST['apellidos_representante'];
                    }

                    if ($this->verificarDatos('\d{7,10}', $_POST['telefono_representante'])) {
                        array_push($campos_invalidos, 'Teléfono representante');
                    }else {
                        $telefono_representante = $_POST['telefono_representante'];
                    }

                    if (count($campos_invalidos) > 0) {
                        $invalidos = "";
                        foreach ($campos_invalidos as $campos) {
                            if ($invalidos == "") {
                                $invalidos = $campos;
                            }else {
                                $invalidos = $invalidos.", ".$campos;
                            }
                        }
                        $mensaje=[
                            "titulo"=>"Campos incompletos",
                            "mensaje"=>"Lo sentimos, los campos ".$invalidos." no cumplen con el formato solicitado.",
                            "icono"=> "error",
                            "tipoMensaje"=>"normal"
                        ];
                        echo json_encode($mensaje);
                        exit();
                    }else {

                        $sentencia_existencia = "SELECT * FROM `usuarios_clientes` WHERE num_identificacion_empresa = '$num_id_empresa' ";
                        $respuesta_existencia = $this->ejecutarConsulta($sentencia_existencia);
                        if (!$respuesta_existencia) {
                            
                            $resultado = [
                                'tipo' => "error",
                                'titulo' => "Error al Consultar",
                                'mensaje' => "Lo sentimos, ocurrió un error al procesar tu solicitud. Por favor, verifica tus datos e inténtalo nuevamente. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                                'icono' => "warning",
                                'alerta'=> "normal"
                            ];
                            echo json_encode($resultado);
                        }else {
                            if ($respuesta_existencia->num_rows > 0) {
                                
                            
                                $resultado = [
                                    'tipo' => "error",
                                    'titulo' => "Empresa Existente",
                                    'mensaje' => "Lo sentimos, ya existe una empresa registrada. Por favor, verifica tus datos e inténtalo nuevamente. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                                    'icono' => "warning",
                                    'alerta'=> "normal"
                                ];
                                echo json_encode($resultado);
                            }else {

                                $direccion_empresa = $ciudad . ' '. $departamento . ' ' . $calle;
                                $sentencia_registro = "
                                    INSERT INTO `usuarios_clientes`(
                                    `num_identificacion_empresa`,
                                    `tipo_documento`,
                                    `nombre_empresa`,
                                    `tipo_empresa`,
                                    `logo_empresa`,
                                    `direccion_empresa`, 
                                    `correo_empresa`, 
                                    `telefono_empresa`, 
                                    `num_identificacion_representante`, 
                                    `tipo_documento_representante`, 
                                    `nombre_representante`, 
                                    `apellidos_representante`, 
                                    `telefono_representante`, 
                                    `bdd_empresa`, 
                                    `pw_bdd_empresa`, 
                                    `fecha_registro_empresa`) 
                                    VALUES (
                                    '$num_id_empresa',
                                    '$tipo_identificacion',
                                    '$nombre_empresa',
                                    '$tipo_empresa',
                                    'ninguno',
                                    '$direccion_empresa',
                                    '$correo_empresa',
                                    '$telefono_empresa',
                                    '$tipo_id_representante',
                                    '$num_id_representante',
                                    '$nombre_representante',
                                    '$apellidos_representante',
                                    '$telefono_representante',
                                    'ninguno',
                                    'ninguna', 
                                    NOW() );
                                ";

                                $respuesta_registro = $this->ejecutarConsulta($sentencia_registro);
                                if (!$respuesta_registro) {
                                    
                                    $resultado = [
                                        'tipo' => "error",
                                        'titulo' => "Error al registrar",
                                        'mensaje' => "Lo sentimos, algo salio mal con el registro por favor intentalo de nuevo mas tarde. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                                        'icono' => "warning",
                                        'alerta'=> "normal"
                                    ];
                                    echo json_encode($resultado);
                                    exit();
                                }else {

                                    $base_de_datos = $this->generarBdd();

                                    if ($base_de_datos == 'error') {

                                        $resultado = [
                                            'tipo' => "error",
                                            'titulo' => "Error al Crear BDD",
                                            'mensaje' => "Lo sentimos, algo salio mal al crear la base de datos por favor intentalo de nuevo mas tarde. Si el problema persiste, contacta con el soporte de Zobyte Soluciones.",
                                            'icono' => "warning",
                                            'alerta'=> "normal"
                                        ];
                                        echo json_encode($resultado);
                                        exit();
                                    }else {
                                        $logo_empresa = $this->guardarImagen($base_de_datos);


                                        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
                                        
                                        // Crear la contraseña aleatoria
                                        $contrasena = '';
                                        for ($i = 0; $i < 32; $i++) {
                                            $contrasena .= $caracteres[random_int(0, strlen($caracteres) - 1)];
                                        }
                                        
                                        $sentencia_update = "UPDATE `usuarios_clientes` SET `logo_empresa`='$logo_empresa',`bdd_empresa`='$base_de_datos',`pw_bdd_empresa`='$contrasena' WHERE num_identificacion_empresa = '$num_id_empresa' ";

                                        $respuesta_update = $this->ejecutarConsulta($sentencia_update);

                                        if (!$respuesta_update) {

                                            $resultado = [
                                                'tipo' => "error",
                                                'titulo' => "Error al enlazar",
                                                'mensaje' => "Lo sentimos, algo salio mal al enlazar la base de datos a el cliente. contacta con el soporte de Zobyte Soluciones.",
                                                'icono' => "warning",
                                                'alerta'=> "normal"
                                            ];
                                            echo json_encode($resultado);
                                            exit();
                                        }else {
                                            

                                            $resultado = [
                                                'tipo' => "OK",
                                                'titulo' => "Empresa Registrada",
                                                'mensaje' => "Registro exitoso la empresa a sido registrada en GurApp.",
                                                'icono' => "warning",
                                                'alerta'=> "normal"
                                            ];
                                            echo json_encode($resultado);
                                            exit();
                                        }
                                    }
                                }
                            }
                        }

                    }
                } 
            }
        }

        public function generarBdd(){
            if (isset($_POST['num_id_empresa']) || $_POST['num_id_empresa'] != '') {
                $nombre_bdd = $this->limpiarDatos($_POST['num_id_empresa']) . '_user_'.date('YmdHis');
                $archivo_sql = "../../DB/gur_app.sql"; 

                if ($this->crearBaseDeDatos($nombre_bdd)) {
                    if ($this->importarTablas($archivo_sql, $nombre_bdd)) {
                        return $nombre_bdd;
                    } else {
                        return "error";
                    }
                } else {
                    return "error";
                }
            }
        }

        public function guardarImagen($nombre_base): string {    
            if (!isset($_FILES['logo_empresa']) || $_FILES['logo_empresa']['name'] == '') {
                $nombre_imagen = 'img-empresa-defoult.jpg';
            } else {
                $capeta_empresa = "../views/img/".$nombre_base."/";
                if (!is_dir($capeta_empresa)) {
                    mkdir($capeta_empresa);
                }
                $extencion_imagen = pathinfo($_FILES['logo_empresa']['name'], PATHINFO_EXTENSION);
                $nombre_imagen = 'IM'. date('YmdHis') . '.' . $extencion_imagen ;
                $capeta_empresa .= $nombre_imagen;
                
                if (!file_exists($capeta_empresa)) {
                    move_uploaded_file($_FILES['logo_empresa']['tmp_name'], $capeta_empresa);
                }
            }
            return $nombre_imagen;
        }



        public function ListarVisitanteController(){

            header('Content-Type: application/json'); 

            $columnas = [
                'num_identificacion_empresa', 
                'tipo_documento', 
                'nombre_empresa', 
                'tipo_empresa', 
                'logo_empresa', 
                'direccion_empresa', 
                'correo_empresa', 
                'telefono_empresa', 
                'num_identificacion_representante', 
                'tipo_documento_representante', 
                'nombre_representante', 
                'apellidos_representante', 
                'telefono_representante', 
                'bdd_empresa', 
                'pw_bdd_empresa', 
                'fecha_registro_empresa'
                ];

            $tabla = "usuarios_clientes";
            $id = 'num_identificacion_empresa';
            
            $tipo_listado = $this->limpiarDatos($_POST['tipoListado']);
            unset($_POST['tipoListado']);
            
            $filtro = '';
            if (isset($_POST['filtro']) && $_POST['filtro'] !== '') {
                $filtro = $this->limpiarDatos($_POST['filtro']);
            }

            /* Filtro Like */
            $sentenciaCondicionada = '';

            if ($filtro != '' ) {
                $sentenciaCondicionada = "WHERE (";
                $contadorColumas = count($columnas);
                for ($i=0; $i < $contadorColumas; $i++) { 
                    $sentenciaCondicionada .= $columnas[$i] . " LIKE '%".$filtro."%' OR ";
                }

                $sentenciaCondicionada = substr_replace($sentenciaCondicionada, "", -3);
                $sentenciaCondicionada .= ")";
            }
            /* Filtro Limit */
            $limit = 3;
            if (isset($_POST['registros']) && $_POST['registros'] !== '') {
                $limit = $this->limpiarDatos($_POST['registros']);
            }
            $pagina = 0;
            if (isset($_POST['pagina']) && $_POST['pagina'] !== '') {
                $pagina = $this->limpiarDatos($_POST['pagina']);
            }

            if (!$pagina) {
                $inicio = 0;
                $pagina = 1;
            }else {
                $inicio = ($pagina - 1) * $limit;
            }


            $sLimit = "LIMIT $inicio , $limit";

            $sentencia = "SELECT  SQL_CALC_FOUND_ROWS ". implode(', ', $columnas). " 
            FROM $tabla 
            $sentenciaCondicionada 
            $sLimit";
            $buscar_clientes = $this->ejecutarConsulta($sentencia);
            $numero_registros = $buscar_clientes->num_rows;

            
            /*  Consulta total registros*/

            $sentencia_filtro = "SELECT FOUND_ROWS()";
            $busqueda_filtro = $this->ejecutarConsulta($sentencia_filtro);
            $registros_filtro = $busqueda_filtro->fetch_array();
            $total_filtro = $registros_filtro[0];

            /*  Consulta total registros*/

            $sentencia_total = "SELECT count($id) FROM $tabla";
            $busqueda_total = $this->ejecutarConsulta($sentencia_total);
            $registros_total = $busqueda_total->fetch_array();
            $total_registros = $registros_total[0];




            $output = [];
            $output['total_registros'] = $total_registros;
            $output['total_filtro'] = $total_filtro;
            $output['data'] = '';
            $output['paginacion'] = '';
            if (!$buscar_clientes){
                $output['data'] = $tipo_listado == 'tabla' 
                ? '
                                <table class="table">
                                    <thead class="head-table">
                                        <tr>
                                            <th>Tipo de Documento</th>
                                            <th>Número de Identificación</th>
                                            <th>Nombre Empresa</th>
                                            <th>Numero id representante</th>
                                            <th>Correo empresa</th>
                                            <th>Teléfono representante</th>
                                            <th>Fecha y Hora registro</th>
                                        </tr>
                                    </thead>
                                    <tbody class="body-table">
                                    <tr><td colspan="7">Error al cargar los listados</td></tr>
                                    </tbody>
                                    </table>' 
                : '
                <div class="document-card">
                    <div class="card-header">
                        <div>
                            <p class="document-title">Error en el listado</p>
                            <p class="document-meta">Error al listar los reportes</p>
                        </div>
                        <span class="toggle-icon"><ion-icon name="chevron-down-outline"></ion-icon></span> 
                    </div>
                </div>';
            } else{
                if ($buscar_clientes->num_rows < 1) {
                    $output['data'] = $tipo_listado == 'tabla' 
                    ? '
                                    <table class="table">
                                        <thead class="head-table">
                                            <tr>
                                                <th>Tipo de Documento</th>
                                                <th>Número de Identificación</th>
                                                <th>Nombre Empresa</th>
                                                <th>Numero id representante</th>
                                                <th>Correo empresa</th>
                                                <th>Teléfono representante</th>
                                                <th>Fecha y Hora registro</th>
                                            </tr>
                                        </thead>
                                        <tbody class="body-table" >
                                            <tr><td colspan="7">No se encontraron Clientes</td></tr>
                                        </tbody>
                                        </table>'
                    :'
                    <div class="document-card">
                        <div class="card-header">
                            <div>
                                <p class="document-meta">No se encontro Clientes</p>
                            </div>
                        </div>
                    </div>';
                } else{
                    if ($tipo_listado == 'tabla') {
                        $output['data'] = '
                                    <table class="table">
                                        <thead class="head-table">
                                            <tr>
                                                <th>Tipo de Documento</th>
                                                <th>Número de Identificación</th>
                                                <th>Nombre Empresa</th>
                                                <th>Numero id representante</th>
                                                <th>Correo empresa</th>
                                                <th>Teléfono representante</th>
                                                <th>Fecha y Hora registro</th>
                                            </tr>
                                        </thead>
                                        <tbody class="body-table" id="listado_visitantes">
                                    ';
    
                        while ($datos = $buscar_clientes->fetch_object()) {
                            $output['data'].='
                                <tr >
                                    <td>'.$datos->tipo_documento.'</td>
                                    <td>'.$datos->num_identificacion_empresa.'</td>
                                    <td>'.$datos->nombre_empresa.'</td>
                                    <td>'.$datos->num_identificacion_representante.'</td>
                                    <td>'.$datos->correo_empresa.'</td>
                                    <td>'.$datos->telefono_representante.'</td>
                                    <td>'.$datos->fecha_registro_empresa.'</td>
                                </tr>
                            ';
                        }
                        $output['data'] .= '</tbody></table>';
                    }
                }
            } 
            return json_encode($output, JSON_UNESCAPED_UNICODE);
        }
    }