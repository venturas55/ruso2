<?php 

function recoge($campo){
        if (isset($_REQUEST[$campo])) {
        $valor = htmlspecialchars(trim(strip_tags($_REQUEST[$campo])));
    }else {
        $valor="";
    };
    return $valor;
}

function recogecookie($campo)
{
    if (isset($_SESSION[$campo])) {
        $valor = htmlspecialchars(trim(strip_tags($_SESSION[$campo])));
    } else {
        $valor = "";
    };
    return $valor;
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

function conectaDb()
{
    try {
        $db = new PDO("mysql:host=adriandecradmin.mysql.db;dbname=adriandecradmin", "adriandecradmin", "Administrador1");
        //$db = new PDO("mysql:host=localhost;dbname=ruso", "root", "administrador");
        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
        return ($db);
    } catch (PDOExcepton $e) {
        print "<p>Error: No puede conectarse con la base de datos.</p>\n";
        print "<p>Error: " . $e->getMessage() . "</p>\n";
        exit();
    }
}

function privilegio()  //Devuelve admin, san o none, en funcion del privilegio
{
    if (recogecookie('miprivilegio') == 'admin')
        return 'admin';
    return 'none';
}

function cabecera(){
    echo '
    <div class="cabecera">
        <div class="tabcontent">
            <div><h1>{{idiomas.idioma2}} </h1></div>
        </div>
        <button class="tablink" onclick="javascript:location.href=\'./app.php\'">FICHAS</button>
        <button class="tablink" @click="nuevoItem=true">ADD NEW WORD</button>
        <button class="tablink" onclick="javascript:location.href=\'./panel.php\'">CONTROL PANEL</button>
        <button class="tablink" onclick="javascript:location.href=\'./log-out.php\'">LOG OUT</button>
        <div><p> Bienvenid@: '. $_SESSION["miusuario"].'</p></div>
    </div>';
}

function pie(){
    echo '    <nav class="footer">Adrian de Haro Ortega. <span class="copyleft"> &copy; </span> LearnLanguageAtYourOwnPace LaYoP 2019 · Todos los
    derechos reservados</nav>';
}
