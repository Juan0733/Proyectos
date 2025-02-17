<?php
    if($_SERVER['REQUEST_METHOD']!="POST"){
        header("Location: ../",true);
    }else{
        if($_SESSION['inicio_sesion']['cargo']!="coordinador" && $_SESSION['inicio_sesion']['cargo']!="subdirector"){
            header("Location: ../",true);
        }else{
            function eliminar_excel($ruta_archivo){
                try{
                    if (file_exists($ruta_archivo)) {
                        if (unlink($ruta_archivo)) {
                            $codigo = 200;
                        } else {
                           $codigo = 500;
                        }
                    } else {
                       $codigo = 400;
                    }
                    
                    return $codigo;
                    
                } catch (\Exception $error){
                    return ["error" => "Error al cargar el archivo: " . $error->getMessage()];
                }
            }
        }
    }

?>
