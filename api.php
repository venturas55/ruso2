<?php
include './seguridad.php';
require "./src/conexion.php";
require "./src/funciones.php";
$usuario = strtolower(recogecookie('miusuario'));
$conexion = new ApptivaDB();
$accion = recoge('accion');



switch ($accion) {
    case 'mostrarFicha':
        $seleccion = recoge('seleccion');

        $condicion = " usuario LIKE '%" . $usuario . "%' AND tipo LIKE '%" . $seleccion . "%' order by rand() LIMIT 1";
        /*  $condicion = "true order by rand() LIMIT 1"; */
        $u = $conexion->buscar("diccionarioimagenes", $condicion);
        if ($u) :

            foreach ($u as $i) {
                $res['respuesta']['indice'] = $i['indice'];
                $res['respuesta']['palabra'] = $i['palabra'];
                $res['respuesta']['idioma'] = $i['idioma'];
                $res['respuesta']['usuario'] = $i['usuario'];
                $res['respuesta']['tipo'] = $i['tipo'];
                $res['respuesta']['extension'] = $i['extension'];
                $res['respuesta']['imagen'] = base64_encode($i['imagen']); //JSON no envia el BLOB sin codificar en base64
            }
            //Tengo que crear el JSON a mano, de lo contrario se obtiene una respuesta vacia (  ""  ) en VUE


            #$res['respuesta'] = $u;
            $res['consulta'] = $condicion;
            $res['mensaje'] = "exito";
        else :
            $res['mensaje'] = "Sin registros";
            $res['consulta'] = $condicion;
            $res['error'] = true;
        endif;
        break;

    case 'insertarFicha':
        /*         $idioma = recoge('idioma1');
        $imagen = recoge('imagen');
        $tipo = recoge('tipo');
        $nombre = recoge('nombre');
        $data = "(NULL,'" . $idioma . "'," . $imagen . ",'" . $nombre . "','" . $tipo . "')";
        $b = $conexion->insertar("diccionarioimagenes", $data);
        if ($b) :
            $res['data'] = $data;
            $res['mensaje'] = "Insercion exitosa";
        else :
            $res['mensaje'] = "Insercion fallida";
            $res['error'] = true;
        endif; */

        #En la consulta no se puede usar $_REQUEST , no funciona...
        $idioma = recoge('idioma');
        $imagen = recoge('imagen');
        $tipo = recoge('tipo');
        $nombre = recoge('nombre');
        $usuario = strtolower(recogecookie('miusuario'));

        #PREPARAMOS LAS VARIABLES PARA LAS RUTAS...
        $nombre_foto = $_FILES['imagen']['name'];
        $tipo_foto = $_FILES['imagen']['type'];
        $tamano_foto = $_FILES['imagen']['size'];

        $carpeta_destino = $_SERVER['DOCUMENT_ROOT'] . '/ruso/img/';
        if (!file_exists($carpeta_destino))
            mkdir($carpeta_destino, 0777, TRUE);

        #   HACEMOS EL INSERT DE LA FOTO (Parece que si que sobreescribe)

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta_destino . $nombre_foto)) {
            $res['mensaje'] = "El fichero es válido y se subió con exito";
        } else {
            $res['mensaje'] = "¡Posible ataque de subida de ficheros!";
        }
        $archivo_objetivo = fopen($carpeta_destino . $nombre_foto, "r");
        $contenido = fread($archivo_objetivo, $tamano_foto); //Sucesion de bytes que forman la imagen que se ha enviado al servidor por post
        $contenido = addslashes($contenido);

        fclose($archivo_objetivo);

        $conexion = new ApptivaDB();

        $data = "(NULL,'" . $idioma . "','" . $contenido . "','" . $tipo_foto . "','"  . $nombre . "','" . $tipo . "','" . $usuario . "')";

        $b = $conexion->insertar("diccionarioimagenes", $data);

        if ($b) :
            $res['data'] = $data;
            $res['mensaje'] = "Insercion exitosa";
        else :
            $res['mensaje'] = "Insercion fallida";
            $res['error'] = true;
        endif;
        break;

    case 'modificarFicha':  //No se usa, va por PhP
        $newId1 = recoge('newId1');
        $newId2 = recoge('newId2');
        $newTipo = recoge('newTipo');
        $oldId1 = recoge('oldId1');
        $oldId2 = recoge('oldId2');
        $oldTipo = recoge('oldTipo');
        $campos = "idioma1='" . $newId1 .= "' , idioma2='" . $newId2 . "' , tipo='" . $newTipo . "'";
        $condicion = "usuario='" . $usuario . "' and idioma1='" . $oldId1 .= "' and idioma2='" . $oldId2 . "'";

        $e = $conexion->actualizar("diccionarioimagenes", $campos, $condicion);

        if ($b) :
            $res['mensaje'] = "Insercion exitosa";
        else :
            $res['mensaje'] = "Insercion fallida";
            $res['error'] = true;
        endif;
        break;

    case 'eliminarFicha':
        $indice = recoge('indice');
        $t = $conexion->borrar("diccionarioimagenes", "indice='" . $indice . "'");
        if ($t) :
            $res['sql'] = $t;
            $res['mensaje'] = "Borrado de palabra exitoso";
        else :
            $res['mensaje'] = "Borrado de palabra fallido";
            $res['error'] = true;
        endif;
        break;

    case 'mostrarListado':
        $cont=0;
        $seleccion = recoge('seleccion');

        $condicion = " usuario LIKE '%" . $usuario . "%' AND tipo LIKE '%" . $seleccion . "%'";
        /*  $condicion = "true order by rand() LIMIT 1"; */
        $u = $conexion->buscar("diccionarioimagenes", $condicion);
        if ($u) :

            foreach ($u as $i) {
                $res['respuesta'][$cont]['indice'] = $i['indice'];
                $res['respuesta'][$cont]['palabra'] = $i['palabra'];
                $res['respuesta'][$cont]['idioma'] = $i['idioma'];
                $res['respuesta'][$cont]['usuario'] = $i['usuario'];
                $res['respuesta'][$cont]['tipo'] = $i['tipo'];
                $res['respuesta'][$cont]['extension'] = $i['extension'];
                $res['respuesta'][$cont]['imagen'] = base64_encode($i['imagen']); //JSON no envia el BLOB sin codificar en base64
                $cont++;
                $res['respuesta']['tamano']=$cont;
            }
            //Tengo que crear el JSON a mano, de lo contrario se obtiene una respuesta vacia (  ""  ) en VUE


            #$res['respuesta'] = $u;
            $res['consulta'] = $condicion;
            $res['mensaje'] = "exito";
        else :
            $res['mensaje'] = "Sin registros";
            $res['consulta'] = $condicion;
            $res['error'] = true;
        endif;
        break;


    case 'verIdiomas':
        $condicion = " usuario='" . $usuario . "'";
        $u = $conexion->buscar("diccionariousuarios", $condicion);
        if ($u) :
            $res['respuesta'] = $u;
            $res['consulta'] = $condicion;
            $res['mensaje'] = "exito";
        else :
            $res['mensaje'] = "Sin registros";
            $res['consulta'] = $condicion;
            $res['error'] = true;
        endif;
        break;

    case 'modificarIdiomas':
        $newId2 = recoge('newId2');
        $oldId1 = recoge('oldId1');
        $oldId2 = recoge('oldId2');
        $campos = "idioma2='" . $newId2 . "'";
        $condicion = "usuario='" . $usuario . "' and idioma2='" . $oldId2 . "'";

        $a = $conexion->actualizar("diccionariousuarios", $campos, $condicion);

        if ($a) :
            $res['mensaje'] = "Insercion exitosa";
            $res['campos'] = $campos;
            $res['consulta'] = $condicion;

        else :
            $res['mensaje'] = "Insercion fallida";
            $res['error'] = true;
        endif;
        break;


    default:
        $res['mensaje'] = "Case default";
        # <code class=""></code>
        break;
}
//nos retorna json
//$conexion->cerrar();
//echo $res;
echo json_encode($res);
die();
