<?php
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\IOFactory;

    if($_SERVER['REQUEST_METHOD']!="POST"){
        header("Location: ../",true);
    }else{
        if($_SESSION['inicio_sesion']['cargo']!="coordinador" && $_SESSION['inicio_sesion']['cargo']!="subdirector"){
            header("Location: ../",true);
        }else{
            /* require '../vendor/autoload.php';
            use PhpOffice\PhpSpreadsheet\IOFactory; */

            function leerArchivoExel($ruta_archivo){
                try{
                    //Cargar el archivo Excel
                    $documento = IOFactory::load($ruta_archivo);
                    
                    //Obtener la Hoja activa.
                    $hoja_actual = $documento->getActiveSheet();
                    
                    //Array para almacenar Datos
                    $datos = [];
                    $encabezados = [];
                    $primeraFila = true;
                    
                    //Itererar sobre las filas
                    foreach($hoja_actual->getRowIterator() as $fila){
                        $valores = [];
                        
                        //Iterar sobre las celdas de cada fila
                        foreach($fila->getCellIterator() as $celda){
                            if($primeraFila) {
                                // Guardar los encabezados
                                $encabezados[] = $celda->getValue();
                            } else {
                                // Guardar los valores
                                $valores[] = $celda->getValue();
                            }
                        }
                        
                        if(!$primeraFila) {
                            // Crear array asociativo con encabezados como claves
                            $fila_datos = array();
                            foreach($encabezados as $index => $encabezado) {
                                if(isset($valores[$index]) && $valores[$index] !== null) {
                                    $fila_datos[$encabezado] = $valores[$index];
                                }
                            }
                            if(!empty($fila_datos) && count($fila_datos) == 6) {
                                $datos[] = $fila_datos;
                            }
                        }else{
                            $primeraFila = false;
                        }
                        
                    }
                    
                    return $datos;
                    
                } catch (\Exception $error){
                    return ["error" => "Error al cargar el archivo: " . $error->getMessage()];
                }
            }
        }
    }
    