<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CERBERUS | <?php echo $vector_respuesta['titulo']; ?> </title>
    <link rel="stylesheet" href="../componentes/estilos_css/final_qr.css">
    <link rel="icon" href="../img/logo_c_negro.png" type="image/png">
</head>
<body>
<div class="mensaje">
    <h2 class="<?php echo ($vector_respuesta['cod'] == 200 ? 'registro-exitoso' : 'registro-fallido'); ?>">
        <?php echo $vector_respuesta['titulo']; ?>
    </h2>
</div>

    <img src="../componentes/img/logo.png" alt="Logo sena" id="logo">
    <p> <?php
        if($vector_respuesta['cod'] == 200){
            echo $vector_respuesta['msj'] . " " . "Bienvenido al Centro Agropecuario de Buga (CAB)";
        }else{
            echo $vector_respuesta['msj'];
        }
      
     ?></p>

    <div class="boton-intentar">
        <a href="<?php echo $vector_respuesta['ruta']; ?> " class="boton-intentar-enlace <?php echo ($vector_respuesta['cod'] == 200 ? 'btn-verde' : 'btn-rojo'); ?>"><?php echo ($vector_respuesta['cod'] == 200 ? 'OK' : 'INTENTAR DE NUEVO')?></a>
    </div>

    <img src="../componentes/img/logo_c_gris.png" alt="Logo cerberus" id="logo-cerberus">

</body>
</html>
