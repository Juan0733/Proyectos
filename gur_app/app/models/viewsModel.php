<?php
    namespace app\models;

    class viewsModel{
        /* ------------ Modelo que permite obtener la vista ------------ */
        protected function obtenerVistasModelo($vista) {

            if (isset($_SESSION['datos_usuario']['num_identificacion_persona'])) {
                # si existe la sesion con los datos que inicion sesión 
                if($_SESSION['datos_usuario']['cargo_persona'] == 'SY') {
                    $listadoVistas=[
                        "registro-empresa",
                        "dashboard-saiyajin"
                    ];
                }elseif ($_SESSION['datos_usuario']['cargo_persona'] == 'MS') {
                    # Si el cargo de la persona es mesero
                    $listadoVistas=[
                        "dashboard",
                        "formulario-producto",
                        "productos",
                        "ingredientes",
                        "formulario-ingrediente",
                        "categorias",
                        "formulario-categoria",
                        "formulario-mesa",
                        "detalle-pedido",
                        "generar-pedido",
                        "home",
                        "mesas"
                    ];
                }
            }else{
                $listadoVistasNoLogin = [
                    "login",
                    "inicio-ppal",
                    "registro-empresa",
                    "formulario-producto",
                    "productos"
                ];

            }

            if (isset($listadoVistas)) {
                if (in_array($vista, $listadoVistas)) {
                    if (is_file("app/views/content/".$vista."-view.php")) {
                        $contenido = "app/views/content/".$vista."-view.php";
                    }else{
                        $contenido = [
                            "code" => 835,
                            "vista" => "inicio-ppal"
                        ];
                    }
                }elseif ($vista == "inicio-ppal" || $vista == "index") {
                    $contenido = [
                        "code" => 835,
                        "vista" => "inicio-ppal"
                    ];
                }else{
                    $contenido = "404";
                }
            }elseif (isset($listadoVistasNoLogin)) {
                if (in_array($vista, $listadoVistasNoLogin)) {
                    if (is_file("app/views/content/".$vista."-view.php")) {
                        echo "existe";
                        $contenido = [
                            "code" => 835,
                            "vista" => $vista
                        ];
                    }else{
                        $contenido = [
                            "code" => 835,
                            "vista" => "inicio-ppal"
                        ];
                    }
                }elseif ($vista == "inicio-ppal" || $vista == "index") {
                    $contenido = [
                        "code" => 835,
                        "vista" => "inicio-ppal"
                    ];
                }else{
                    $contenido = "404";
                }
            }elseif ($vista == "inicio-ppal" || $vista == "index") {
                $contenido = [
                    "code" => 835,
                    "vista" => "inicio-ppal"
                ];
            }else{
                $contenido = "404";
            }
            return $contenido;
        }
    }