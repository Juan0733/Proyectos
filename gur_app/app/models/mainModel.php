<?php

    namespace app\models;
    use \mysqli;

    if(file_exists(__DIR__."/../../config/app.php")){
		require_once __DIR__."/../../config/app.php";
	}
    class mainModel{
        
		

        protected function conectarBdd(){
            mysqli_report(MYSQLI_REPORT_OFF);
            try {
                //Conexion a la base de datos
                if (isset($_SESSION['datos_usuario']) && $_SESSION['datos_usuario']['cargo_persona'] != '' && $_SESSION['datos_usuario']['cargo_persona'] != 'SY') {
                    $enlace_conexion = new mysqli("localhost", "root", "", $_SESSION['datos_usuario']['bdd_empresa'] );
                }else{
                    $enlace_conexion = new mysqli("localhost", "root", "", 'gurapp_clientes_bdd' );
                }
                if ($enlace_conexion->connect_error) {
                    // throw new \Exception("No se realizo la conexion: ". $enlace_conexion->connect_error);
                    return false;
                }else {
                    return $enlace_conexion;
                }
                
            } catch (\Exception $e) {
                unset($enlace_conexion);
                return false;
            }
        }
        protected function ejecutarConsulta($consulta){
            $conexion = $this->conectarBdd();
            if (!$conexion) {
                return false;
            }else {
                $sql = $conexion->query($consulta);
                $conexion->close();
                return $sql;
            }
        }

        protected function crearBaseDeDatos($nombre_base_de_datos){

            $conexion = $this->conectarBdd();
            if (!$conexion) {
                return false;
            } else {
                $sql = "CREATE DATABASE IF NOT EXISTS $nombre_base_de_datos";
                if ($conexion->query($sql) === TRUE) {
                    return true; 
                } else {
                    return false;
                }
            }
        }
        public function importarTablas($archivoSql, $nombreBaseDeDatos) {
            $consultaSql = file_get_contents($archivoSql);
            

            $conexion = $this->conectarBdd();
            if (!$conexion) {
                return false;
            }
        

            $conexion->select_db($nombreBaseDeDatos);
        

            $consultas = explode(";", $consultaSql);
        
            foreach ($consultas as $consulta) {

                $consulta = trim($consulta);
                if (!empty($consulta)) {
                    if ($conexion->query($consulta) === FALSE) {
                        echo "Error al ejecutar la consulta: " . $conexion->error;
                        return false;
                    }
                }
            }

            return true;
        }
        


        public function limpiarDatos($dato){

			$palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==",";","::"];


			$dato=trim($dato);
			$dato=stripslashes($dato);

			foreach($palabras as $palabra){
				$dato=str_ireplace($palabra, "", $dato);
			}

			$dato=trim($dato);
			$dato=stripslashes($dato);

			return $dato;
		}

		protected function verificarDatos($datos){
            foreach ($datos as $dato) {
                if(!preg_match("/^".$dato['filtro']."$/", $dato['cadena'])){
                    return false;
                };
            };
            return true;
		}
    }