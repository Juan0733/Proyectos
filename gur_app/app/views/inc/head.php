
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo $url_variable; ?>app/views/img/" type="image/x-icon">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/login.css">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/header.css">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/index.css">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/all.css">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/productos-view.css">
<link rel="stylesheet" href="<?php echo $url_variable; ?>app/views/css/header-top.css">
<?php
    if ($url[0] != 'login') {
        if (count($url) > 1) {
            /* Stilos para rutas con mas de un parametro en la url ejemplo: /dashboard/11120987/  */
        }else {
            /* Estilos para rutas con un solo paramaero en la url ejempli: /dashboard/   */
        }
    }elseif ($url[0] == 'login') {
        # code...
    }else {
        /* Estilos para el login */
    }
?>


<title><?php echo NOMBRE_APP ?></title>