<?php
include './seguridad.php';
require "./src/conexion.php";
include './src/funciones.php';

#En la consulta no se puede usar $_REQUEST , no funciona...
$idioma = recoge('idioma');
$imagen = recoge('imagen');
$tipo = recoge('tipo');
$palabra = recoge('palabra');
$usuario = strtolower(recogecookie('miusuario'));

#PREPARAMOS LAS VARIABLES PARA LAS RUTAS...
$nombre_foto = $_FILES['imagen']['name'];
$tipo_foto = $_FILES['imagen']['type'];
$tamano_foto = $_FILES['imagen']['size'];

$carpeta_destino = $_SERVER['DOCUMENT_ROOT'] . '/fotos/uploads/';
if (!file_exists($carpeta_destino))
    mkdir($carpeta_destino, 0777, TRUE);

#   HACEMOS EL INSERT DE LA FOTO (Parece que si que sobreescribe)

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta_destino . $nombre_foto)) {
    echo "<script type=\"text/javascript\"> alert(\"El fichero es válido y se subió con exito\"); </script>";
} else {
    echo "<script type=\"text/javascript\"> alert(\"¡Posible ataque de subida de ficheros!\"); </script>";
}
$archivo_objetivo = fopen($carpeta_destino.$nombre_foto,"r");
$contenido = fread($archivo_objetivo,$tamano_foto); //Sucesion de bytes que forman la imagen que se ha enviado al servidor por post
$contenido = addslashes($contenido);

fclose($archivo_objetivo);

$conexion = new ApptivaDB();

$data = "(NULL,'" . $idioma . "','" . $contenido . "','". $tipo_foto . "','"  . $palabra . "','" . $tipo . "','". $usuario ."')";

$b = $conexion->insertar("diccionarioimagenes", $data);

if ($b) :
    $res['data'] = $data;
    $res['mensaje'] = "Insercion exitosa";
else :
    $res['mensaje'] = "Insercion fallida";
    $res['error'] = true;
endif;


echo "<script>window.history.go(-1);</script>";
echo '</pre>';
#echo 'Más información de depuración:';
#print_r($_FILES);
?>
