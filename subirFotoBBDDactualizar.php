<?php
include './seguridad.php';
require "./src/conexion.php";
include './src/funciones.php';

#En la consulta no se puede usar $_REQUEST , no funciona...
$indice = recoge('indice');
$idioma = recoge('idioma');
$imagen = recoge('imagen');
$tipo = recoge('tipo');
$nombre = recoge('palabra');
$usuario = strtolower(recogecookie('miusuario'));

#PREPARAMOS LAS VARIABLES PARA LAS RUTAS...
$nombre_foto = $_FILES['imagen']['name'];
$tipo_foto = $_FILES['imagen']['type'];
$tamano_foto = $_FILES['imagen']['size'];

$carpeta_destino = $_SERVER['DOCUMENT_ROOT'] . '/fotos/uploads/';
if (!file_exists($carpeta_destino))
    mkdir($carpeta_destino, 0777, TRUE);

#   HACEMOS EL INSERT DE LA FOTO (Parece que si que sobreescribe)


if ($nombre_foto != "") {
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta_destino . $nombre_foto)) {
        echo "<script type=\"text/javascript\"> alert(\"El fichero es válido y se subió con exito\"); </script>";
    } else {
        echo "<script type=\"text/javascript\"> alert(\"No se subió el archivo al servidor\"); </script>";
    }
    $archivo_objetivo = fopen($carpeta_destino . $nombre_foto, "r");
    $contenido = fread($archivo_objetivo, $tamano_foto); //Sucesion de bytes que forman la imagen que se ha enviado al servidor por post
    $contenido = addslashes($contenido);
    fclose($archivo_objetivo);

    $campos = "idioma='" . $idioma . "', palabra='"  . $nombre . "', tipo='" . $tipo . "', imagen='" . $contenido . "', extension='" . $tipo_foto . "' ";
} else{
    echo "<script type=\"text/javascript\"> alert(\"Actualizacion realizada sin modificar la foto\"); </script>";
    $campos = "idioma='" . $idioma . "', palabra='"  . $nombre . "', tipo='" . $tipo . "'";
}

$condicion = "indice=" . $indice;

$conexion = new ApptivaDB();
$b = $conexion->actualizar("diccionarioimagenes", $campos, $condicion);

if ($b) :
    $res['campos'] = $campos;
    $res['condicion'] = $condicion;
    $res['mensaje'] = "Insercion exitosa";
else :
    $res['mensaje'] = "Insercion fallida";
    $res['error'] = true;
endif;


echo "<script>window.history.go(-1);</script>";
#echo 'Más información de depuración:';
#print_r($_FILES);
