<?php 
//Reanudamos la sesión 
session_start();
//borramos las cookies de sesion primero para asegurar
$_SESSION['miusuario'] = "";
$_SESSION['micontrasena'] = "";
$_SESSION['miprivilegio'] = "";
//y despues literalmente destruimos la sesion 
session_destroy();
//Redireccionamos a index.php (al inicio de sesión)
header("location: ./index.php");
?>
