<?php 
session_start();
include './src/funciones.php';

// Debug: Show environment variables
echo "<pre>";
echo "Environment Variables:\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'NOT SET') . "\n";
echo "DB_PASS: " . ($_ENV['DB_PASS'] ?? 'NOT SET') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'NOT SET') . "\n";
echo "</pre>";

$db = conectaDb();
if (!$db) {
    echo "<p style='color: red;'>Database connection failed!</p>";
    exit();
} else {
    echo "<p style='color: green;'>Database connection successful!</p>";
}

//Variables que toman valor de lo enviado desde el form 
$email = strtolower(recoge('email'));
$usuario = strtolower(recoge('usuario'));
$password = recoge('password');


$sql = "select usuario from usuarios where usuario = '" . $email . "'"; //SEleccionamos la lista completa del campo usuarios  
$consulta = $db->prepare($sql);
$consulta->execute();

if ($consulta->rowCount() >= 1) { //Si ya existe un usuario con ese mail 
    echo ("<center> <h1><font color='red'>El usuario<br><fontcolor='blue'> $email<br><font color='red'>y a exis te!< br><a href='../index.php'>Inicio</a>");
} else { //Si el  usuario  no existe 
    $sql = "INSERT INTO usuarios (usuario,contrasena,privilegio,correo) VALUES( '" . $usuario . "', '" . $password . "','user','".$email."')";
    $consulta = $db->prepare($sql);
    $consulta->execute();
    if ($consulta) {
       echo "<script type=\"text/javascript\"> alert(\"Usuario creado con exito\"); </script>";
       echo "0k";
        //header("location: ./index.php"); //Si todo es correcto redireccionamos a la index 
    } else {
       echo "<script type=\"text/javascript\"> alert(\"Usuario no creado\"); </script>";
       echo "ERROR MySql";
    }


    $sql = "INSERT INTO diccionariousuarios (usuario,idioma1,idioma2) VALUES( '" . $usuario . "', 'Spanish Female','UK English Female')";
    $consulta = $db->prepare($sql);
    $consulta->execute();
    if ($consulta) {
       echo "<script type=\"text/javascript\"> alert(\"Usuario creado en la app con exito\"); </script>";
       echo "0k";
        //header("location: ./index.php"); //Si todo es correcto redireccionamos a la index 
    } else {
       echo "<script type=\"text/javascript\"> alert(\"Usuario no creado en la app de ruso\"); </script>";
       echo "ERROR MySql";
    }

    // cerramos la conexión  
    $db = null;
    echo "<script>window.history.go(-2);</script>";
}
