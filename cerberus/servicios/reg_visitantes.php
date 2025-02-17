<?php
// Verifica si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../componentes/plantillas/registro_visitantes.html");
} else {
    $bandera = 1;  // Inicializa una bandera para la validación de datos
    
    // Validación de los datos recibidos en el formulario
    if (!isset($_POST['tipo_documento']) || $_POST['tipo_documento'] == "" || strlen($_POST['tipo_documento']) < 2 || strlen($_POST['tipo_documento']) > 3) {
        $bandera = 0;
    } else if (!isset($_POST['numero_documento']) || $_POST['numero_documento'] == "" || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
        $bandera = 0;
    } else if (!isset($_POST['nombres']) || $_POST['nombres'] == "" || strlen($_POST['nombres']) < 2 || strlen($_POST['nombres']) > 30) {
        $bandera = 0;
    } else if (!isset($_POST['apellidos']) || $_POST['apellidos'] == "" || strlen($_POST['apellidos']) < 2 || strlen($_POST['apellidos']) > 30) {
        $bandera = 0;
    } else if (!isset($_POST['movil']) || $_POST['movil'] == "" || strlen($_POST['movil']) < 10 || strlen($_POST['movil']) > 10) {
        $bandera = 0;
    } else if (!isset($_POST['correo']) || $_POST['correo'] == "" || strlen($_POST['correo']) < 11 || strlen($_POST['correo']) > 64) {
        $bandera = 0;
    } else if (!isset($_POST['motivo_visita']) || $_POST['motivo_visita'] == "" || strlen($_POST['motivo_visita']) < 2 || strlen($_POST['motivo_visita']) > 100) {
        $bandera = 0;
    }
    
    $tipo_registro = $_POST['tipo_registro'] ?? '';

    // Si la bandera es 0, los datos no son válidos
    if ($bandera == 0) {
        $vector_respuesta = [
            'titulo' => "REGISTRO FALLIDO",
            'msj' => "Datos incorrectos para la operación indicada.",
            'cod' => 400,
            'ruta' => "../componentes/plantillas/registro_visitantes.html"
        ];
        
        // Verifica el tipo de registro y envía la respuesta adecuada
        
    } else {
        // Sanitización de variables para mayor seguridad
        $tipo_documento = trim($_POST['tipo_documento']);
        $documento = trim(filter_var($_POST['numero_documento']));
        $nombres = trim(filter_var($_POST['nombres']));
        $apellidos = trim(filter_var($_POST['apellidos']));
        $movil = trim(filter_var($_POST['movil']));
        $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
        $motivo = trim(filter_var($_POST['motivo_visita']));
        $ubicacion = 'AFUERA';

        date_default_timezone_set('America/Bogota');
        $momento_registro = date("Y-m-d H:i:s");

        // Sentencias SQL para verificar existencia del usuario y para insertar nuevo registro
        $sentencia_buscar_visitantes = "SELECT ubicacion FROM visitantes WHERE documento = '$documento';";
        $sentencia_buscar_vigilantes = "SELECT ubicacion FROM vigilantes WHERE documento = '$documento';";
        $sentencia_buscar_funcionarios = "SELECT ubicacion FROM funcionarios WHERE documento = '$documento';";
        $sentencia_buscar_aprendices = "SELECT ubicacion FROM aprendices WHERE documento = '$documento';";
       

        $conexion = require 'conexion.php';
        if (!$conexion) {
            // Error de conexión a la base de datos
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                'cod' => 500,
                'ruta' => "../componentes/plantillas/registro_visitantes.html"
            ];
            
        } else {
            // Verificación de existencia del usuario en diferentes tablas
            $tabla = "";
            $buscar_usr = $conexion01 -> query($sentencia_buscar_vigilantes);
            if ($buscar_usr -> num_rows != 0) {
                $tabla = "vigilantes";
                $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
            }
            if ($tabla == "") {
                $buscar_usr = $conexion01 -> query($sentencia_buscar_visitantes);
                if ($buscar_usr -> num_rows != 0) {
                    $tabla = "visitantes";
                }
            }
            if ($tabla == "") {
                $buscar_usr = $conexion01 -> query($sentencia_buscar_funcionarios);
                if ($buscar_usr -> num_rows != 0) {
                    $tabla = "funcionarios";
                    $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
                }
            }
            if ($tabla == "") {
                $buscar_usr = $conexion01 -> query($sentencia_buscar_aprendices);
                if ($buscar_usr -> num_rows != 0) {
                    $tabla = "aprendices";
                    $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
                }
            }
                
            if ($tabla) {
                if($tabla == 'visitantes'){
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "El usuario ya existe como visitante, no es posible registrarlo.",
                        'cod' => 400,
                        'ruta' => "../componentes/plantillas/registro_visitantes.html"
                    ];

                    $conexion01->close();

                    if ($tipo_registro != "autoregistro") {
                        echo json_encode($vector_respuesta);
                    } else {
                        include "../componentes/plantillas/mensajero.php";
                    }
                    exit;
            
                }else{
                    require '../microservicios/eliminar_usuario.php';
                        
                    if($vector_respuesta['cod'] != 200) {
                        $vector_respuesta = [
                            'titulo' => "REGISTRO FALLIDO",
                            'msj' => "El registro ha fallado por alguna razón.",
                            'cod' => 500,
                            'ruta' => "../componentes/plantillas/registro_visitantes.html"
                        ];

                        $conexion01->close();

                        if ($tipo_registro != "autoregistro") {
                            echo json_encode($vector_respuesta);
                        } else {
                            include "../componentes/plantillas/mensajero.php";
                        }

                        exit;
                    }
                }
                    
            }

            $sentencia_insertar = "INSERT INTO visitantes(documento, tipo_documento, nombres, apellidos, telefono, email, fecha_registro, motivo, ubicacion) VALUES ('$documento', '$tipo_documento', '$nombres', '$apellidos', '$movil', '$correo', '$momento_registro', '$motivo', '$ubicacion');";
            // Inserción del registro si no existe en la base de datos
            $registro_visitante = $conexion01 -> query($sentencia_insertar);
            if ($conexion01 -> affected_rows != 1) {
                // Error en la inserción del registro
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "El registro ha fallado por alguna razón.",
                    'cod' => 500,
                    'ruta' => "../componentes/plantillas/registro_visitantes.html"
                ];

                $conexion01 -> close();  
            } else {
                // Registro exitoso
                $vector_respuesta = [
                    'titulo' => "REGISTRO EXITOSO!",
                    'msj' => "Visitante registrado con éxito!",
                    'cod' => 200,
                    'ruta' => "../componentes/plantillas/registro_visitantes.html"
                ];

                $conexion01 -> close();  
            }
        }
    }

    if ($tipo_registro != "autoregistro") {
        echo json_encode($vector_respuesta);
    } else {
        include "../componentes/plantillas/mensajero.php";
    }
}
