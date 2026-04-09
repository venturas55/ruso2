<?php
include './src/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Estudia Ruso</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/style.css">
    <style type="text/css">
    </style>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=h4UJYPUV"></script>
</head>

<body>
    <div class="rejilla">
        <div class="item">
            Rellene los campos para logarse y pulse aceptar
        </div>

        <div class="item">
            <form method="post" class="signin" action="control.php">
                <fieldset>
                    <div>
                        <label>
                            <span>Usuario</span>
                            <input id="usuario" name="usuario" value="" type="text" autocomplete="on" placeholder="Usuario" required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <span>Contrase&ntilde;a</span>
                            <input id="contrasena" name="contrasena" value="" type="password" placeholder="Contrase&ntilde;a" required>
                        </label>
                    </div>
                    <div>
                        <input class="boton" type="submit" id="go" value="Ingresar">
                        <input class="boton" type="button" id="cancel" value="Cancelar" onClick="window.location.href='./index.php'">
                    </div>
                </fieldset>
            </form>
        </div>

        <p>
            Si todavia no lo has hecho, deberias <a href='reg-form.html'>registrarte</a>
        </p>

    </div>

    <?php pie() ?>
    
</body>
</html>