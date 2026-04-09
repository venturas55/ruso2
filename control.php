<?php
    include './src/funciones.php';
    $db = conectaDb();

    // usuario y contrasena recibidos del formulario  
    $nombre = strtolower(recoge('usuario'));
    $contrasena = recoge('contrasena');
    echo "aqui estamos";
    //Se valida que exista usuario
    $sql = "select usuario from usuarios where usuario='$nombre'";
    $consulta = $db->prepare($sql);
    $consulta->execute();
    if ($consulta->rowCount() == 1) {
        //Se valida la contrasena
        $sql2 = "SELECT * FROM usuarios WHERE  usuario ='$nombre' and contrasena='$contrasena'";
        $consulta2 = $db->prepare($sql2);
        $consulta2->execute();
        if ($consulta2->rowCount() == 1) {
            $result = $consulta2->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION['miusuario'] = $result['usuario'];
            $_SESSION['micontrasena'] = $result['contrasena'];
            $_SESSION['miprivilegio'] = $result['privilegio'];
            header("location: ./app.php");
        } else {
            echo "<script>alert('La contrase\u00f1a del usuario no es correcta.'); window.location.href=\"index.php\"</script>";
        }
    } else {
        echo "<script>alert('El usuario no existe.'); window.location.href=\"index.php\"</script>";
    }
    $db = null; 
?>
