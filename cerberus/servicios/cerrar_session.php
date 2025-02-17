<?php
    session_start();
    session_unset(); //Vacia las instacias de   SESSION 
    session_destroy();// Destruye la instancia activa en el servidor

    header('location:../index.html '); // Redirecciona al inicio / Home