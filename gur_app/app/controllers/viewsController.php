<?php
    namespace app\controllers;
    use app\models\viewsModel;
    use app\models\mainModel;

    class viewsController extends viewsModel {
        public function obtenerVistasControlador($vista){
            if ($vista != "") {
                $respuesta = $this->obtenerVistasModelo($vista);
            }else{
                $respuesta = "inicio-ppal";
            }
            return $respuesta;
        }
    }