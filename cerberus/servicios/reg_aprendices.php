<?php

// Verificamos el intento de operación
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../componentes/plantillas/registro_aprendices.html");
} else {
    $bandera = 1;
    // Filtramos los datos recibidos
    if (!isset($_POST['tipo_documento']) || $_POST['tipo_documento'] == "" || strlen($_POST['tipo_documento']) < 2 || strlen($_POST['tipo_documento']) > 5) {
        $bandera = 0;
    } else if (!isset($_POST['numero_documento']) || $_POST['numero_documento'] == "" || strlen($_POST['numero_documento']) < 6 || strlen($_POST['numero_documento']) > 15) {
        $bandera = 0;
    } else if (!isset($_POST['nombres']) || $_POST['nombres'] == "" || strlen($_POST['nombres']) < 3 || strlen($_POST['nombres']) > 25) {
        $bandera = 0;
    } else if (!isset($_POST['apellidos']) || $_POST['apellidos'] == "" || strlen($_POST['apellidos']) < 3 || strlen($_POST['apellidos']) > 25) {
        $bandera = 0;
    } else if (!isset($_POST['movil']) || $_POST['movil'] == "" || strlen($_POST['movil']) < 10 || strlen($_POST['movil']) > 10) {
        $bandera = 0;
    } else if (!isset($_POST['correo']) || $_POST['correo'] == "" || strlen($_POST['correo']) < 11 || strlen($_POST['correo']) > 40) {
        $bandera = 0;
    } else if (!isset($_POST['ficha']) || $_POST['ficha'] == "" || strlen($_POST['ficha']) < 7 || strlen($_POST['ficha']) > 7) {
        $bandera = 0;
    } else if (!isset($_POST['programa']) || $_POST['programa'] == "" || strlen($_POST['programa']) < 4 || strlen($_POST['programa']) > 50) {
        $bandera = 0;
    }

    $tipo_registro = $_POST['tipo_registro'] ?? '';

    // Verificamos que los datos sean los esperados
    if ($bandera == 0) {
        $vector_respuesta = [
            'titulo' => "REGISTRO FALLIDO",
            'msj' => "Datos incorrectos para la operación indicada.",
            'cod' => 400,
            'ruta' => "../componentes/plantillas/registro_aprendices.html"
        ];
    } else {
        // Sanitizamos y eliminamos variables globales
        $tipo_documento = trim($_POST['tipo_documento']);
        $documento = trim(filter_var($_POST['numero_documento']));
        $nombres = trim(filter_var($_POST['nombres']));
        $apellidos = trim(filter_var($_POST['apellidos']));
        $movil = trim(filter_var($_POST['movil']));
        $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
        $n_ficha = trim(filter_var($_POST['ficha']));
        $programa = strtolower(trim(filter_var($_POST['programa'])));
        $ubicacion = 'AFUERA';

        unset($_POST['tipo_documento'], $_POST['numero_documento'], $_POST['nombres'], $_POST['apellidos'], $_POST['movil'], $_POST['correo'], $_POST['ficha'], $_POST['programa']);

        date_default_timezone_set('America/Bogota');
        $sys_momento = time();
        $momento_registro = date("Y-m-d H:i:s", $sys_momento);

        $sentencia_buscar_visitantes = "SELECT ubicacion FROM visitantes WHERE documento = '$documento';";
        $sentencia_buscar_vigilantes = "SELECT ubicacion FROM vigilantes WHERE documento = '$documento';";
        $sentencia_buscar_funcionarios = "SELECT ubicacion FROM funcionarios WHERE documento = '$documento';";
        $sentencia_buscar_aprendices = "SELECT ubicacion FROM aprendices WHERE documento = '$documento';";
        

        $conexion = require 'conexion.php';
        if (!$conexion){
            $vector_respuesta = [
                'titulo' => "REGISTRO FALLIDO",
                'msj' => "No se pudo conectar con la base de datos por alguna razón.",
                'cod' => 500,
                'ruta' => "../componentes/plantillas/registro_aprendices.html"
            ];

        } else {
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
                    $ubicacion = $buscar_usr->fetch_assoc()['ubicacion'];
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
                }
            }
                
            if ($tabla) {
                if($tabla == 'aprendices'){
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "El usuario ya existe como aprendiz, no es posible registrarlo.",
                        'cod' => 400,
                        'ruta' => "../componentes/plantillas/registro_aprendices.html"
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
                            'ruta' => "../componentes/plantillas/registro_aprendices.html"
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

            $sentencia_buscar_programa = "SELECT nombre FROM programas WHERE nombre = '$programa';";
            $buscar_programa = $conexion01->query($sentencia_buscar_programa);
            if($buscar_programa->num_rows == 0){
                $sentencia_insertar_programa = "INSERT INTO programas(nombre) VALUES('$programa');";
                $registro_programa = $conexion01->query($sentencia_insertar_programa);
                $filas_afectadas = $conexion01->affected_rows;
                if ($filas_afectadas != 1) {
                    $vector_respuesta = [
                        'titulo' => "REGISTRO FALLIDO",
                        'msj' => "El registro ha fallado por alguna razón.",
                        'cod' => 500,
                        'ruta' => "../componentes/plantillas/registro_aprendices.html"
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

            $sentencia_insertar = "INSERT INTO aprendices(documento, tipo_documento, nombres, apellidos, ficha, programa, telefono, email, fecha_registro, ubicacion) VALUES('$documento','$tipo_documento','$nombres','$apellidos','$n_ficha','$programa','$movil','$correo','$momento_registro', '$ubicacion');";
                
            $registro_aprendiz = $conexion01->query($sentencia_insertar);
            $filas_afectadas = $conexion01->affected_rows;
            if ($filas_afectadas != 1) {
                $vector_respuesta = [
                    'titulo' => "REGISTRO FALLIDO",
                    'msj' => "El registro ha fallado por alguna razón.",
                    'cod' => 500,
                    'ruta' => "../componentes/plantillas/registro_aprendices.html"
                ];

                $conexion01->close();

            } else {
                $vector_respuesta = [
                    'titulo' => "REGISTRO EXITOSO!",
                    'msj' => "¡Aprendiz registrado con éxito!",
                    'cod' => 200,
                    'ruta' => "../componentes/plantillas/registro_aprendices.html"
                ];
                    
                $conexion01->close();
                unset($sentencia_buscar_vigilantes, $sentencia_buscar_visitantes, $sentencia_buscar_funcionarios, $sentencia_aprendices, $sentencia_insertar, $conexion, $buscar_usr, $registro_aprendiz, $tipo_documento, $numero_documento, $nombres, $apellidos,$movil, $correo, $cargo, $contrasena_cifrada, $momento_registro, $sys_momento);
            }
        }
    }

    if ($tipo_registro != "autoregistro") {
        echo json_encode($vector_respuesta);
    } else {
        include "../componentes/plantillas/mensajero.php";
    }
}
