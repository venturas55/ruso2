<?php
include './seguridad.php';
include './src/funciones.php';
$usuario = $_SESSION["miusuario"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Estudiar Ruso</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/script.js"></script>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=h4UJYPUV"></script>
</head>

<body>
    <div id="app">
        <?php cabecera() ?>
        <!--  APP FICHAS -->
        <div>
            Select Kind: <select id="seleccion" v-model="seleccion">
                <option value="" selected="selected">Todo</option>

                <?php
                $db = conectaDb();
                $consulta = "SELECT distinct tipo FROM `diccionarioimagenes` WHERE usuario='" . $usuario . "'";
                $result = $db->prepare($consulta);
                $result->execute();
                $tipos = $result->fetchAll();
                foreach ($tipos as $i)
                    echo '<option value="' . $i['tipo'] . '">' . $i['tipo'] . 's</option>';
                ?>

            </select>

            <button @click="mostrarFicha()">SHOW ANOTHER CARD</button>
            <button @click="verModificarItem=true">MODIFY CARD</button>
            <button @click="verEliminarItem=true">DELETE CARD</button>
            <button @click="leer()">LEER</button>
            <button @click="mostrarListado()">LISTADO</button>

            <div id="flip-container" @click="leer()">
                <div class="ficha" class="card">
                    <div class="front">
                        <img :src="ficha.imagensrc" alt="">
                    </div>
                    <div class="back">
                        {{ficha.palabra}}
                    </div>
                </div>
            </div>
        </div>


        <div>
            <h1>
                <!--  {{listado.[0].palabra}} -->
            </h1>
            <table>
                <tr>
                    <td>Palabra</td>
                    <td> Imagen</td>
                </tr>
                <tr v-for="item in listado">
                    <!--  :key="item.indice" -->
                    <td>
                        <img :src="item.imagensrc" alt="">
                    </td>
                    <td>
                        {{item.palabra}}
                    </td>
                </tr>
            </table>


        </div>

        <!-- nuevo item -->
        <div class="contenedor" v-if="nuevoItem">
            <div class="modal">
                <div class="header">
                    <button class="close" @click="nuevoItem=false">X</button>
                    <h1>Nueva Palabra</h1>
                </div>
                <div class="contenido">
                    <form action="./subirFotoBBDD.php" method="post" enctype="multipart/form-data">
                        <p> <input type="file" name="imagen" id="imagen1"></p>
                        <p> Idioma <input type="text" name="idioma" id="idioma1"></p>
                        <p> Palabra <input type="text" name="palabra" id="palabra1"></p>
                        <p> Tipo <input type="text" name="tipo" id="tipo1"></p>
                        <input type="submit" value="Enviar">
                        <!-- <button @click="nuevoItem=false;insertarFicha()">CREAR</button> -->
                        <!-- Se sustituye por un formulario para poder enviar la imagen sin problemas -->

                    </form>
                </div>
            </div>
        </div>

        <!-- Modificar item -->
        <div class="contenedor" v-if="verModificarItem">
            <div class="modal">
                <div class="header">
                    <button class="close" @click="verModificarItem=false">X</button>
                    <h1>MODIFICAR FICHA</h1>
                </div>
                <div class="contenido">
                    <form action="./subirFotoBBDDactualizar.php" method="post" enctype="multipart/form-data">
                        <p> <input type="file" id="imagen" name="imagen"></p>
                        <p> <input type="hidden" id="mIndice" name="indice" v-bind:value="ficha.indice"></p>
                        <p> Idioma <input type="text" id="mIdioma" name="idioma" v-bind:value="ficha.idioma"></p>
                        <p> Palabra <input type="text" id="mId2" name="palabra" v-bind:value="ficha.palabra"></p>
                        <p> Tipo <input type="text" id="mTipo" name="tipo" v-bind:value="ficha.tipo"></p>

                        <input type="submit" value="Enviar">
                        <!--   <button @click="verModificarItem=false;modificarFicha()">SI</button>
                        <button @click="verModificarItem=false">NO</button> -->
                    </form>
                </div>
            </div>
        </div>

        <!-- eliminar item -->
        <div class="contenedor" v-if="verEliminarItem">
            <div class="modal">
                <div class="header">
                    <button class="close" @click="verEliminarItem=false">X</button>
                    <h1>ELIMINAR FICHA</h1>
                </div>
                <div class="contenido">
                    <p> Está seguro de que desea borrar la ficha </p>
                    <p> {{ficha.palabra}}</p>
                    <button @click="verEliminarItem=false;eliminarFicha()">SI</button>
                    <button @click="verEliminarItem=false">NO</button>
                </div>
            </div>
        </div>
    </div>


    <?php pie() ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>

    <script>
        var app = new Vue({
            el: "#app",
            data: {
                nuevoItem: false,
                verEliminarItem: false,
                verModificarItem: false,
                verBorrar: false,
                idiomas: "",
                seleccion: "",
                ficha: "",
                listado: [],
                imagen: "",
            },
            mounted: function() {
                this.mostrarFicha()
                this.verIdiomas()
            },
            methods: {
                mostrarFicha: function() {
                    let formdata = new FormData()
                    formdata.append("seleccion", this.seleccion)
                    axios.post("./api.php?accion=mostrarFicha", formdata)
                        .then(function(response) {
                            console.log("Mostrar Ficha: ")
                            console.log(response)
                            app.ficha = {
                                'extension': response.data.respuesta.extension,
                                'tipo': response.data.respuesta.tipo,
                                'palabra': response.data.respuesta.palabra,
                                'idioma': response.data.respuesta.idioma,
                                'indice': response.data.respuesta.indice,
                                'imagen': response.data.respuesta.imagen,
                                'imagensrc': 'data:' + response.data.respuesta.extension + ';base64,' + response.data.respuesta.imagen
                            }
                            //Si no genero a mano el JSON se obtiene un objeto vacio. ¿?
                        })

                },
                mostrarListado: function() {
                    app.listado = [];
                    let formdata = new FormData()
                    formdata.append("seleccion", this.seleccion)
                    axios.post("./api.php?accion=mostrarListado", formdata)
                        .then(function(response) {
                            console.log("Mostrar Listado: ")
                            console.log(response)
                            for (var i = 0; i < response.data.respuesta.tamano; i++) {
                                app.listado[i] = {
                                    'extension': response.data.respuesta[i].extension,
                                    'tipo': response.data.respuesta[i].tipo,
                                    'palabra': response.data.respuesta[i].palabra,
                                    'idioma': response.data.respuesta[i].idioma,
                                    'indice': response.data.respuesta[i].indice,
                                    'imagen': response.data.respuesta[i].imagen,
                                    'imagensrc': 'data:' + response.data.respuesta[i].extension + ';base64,' + response.data.respuesta[i].imagen
                                }
                            }
                            //Si no genero a mano el JSON se obtiene un objeto vacio. ¿?
                        })
                    app.mostrarFicha()

                },
                insertarFicha: function() {
                    let formdata = new FormData();
                    $imagencita = document.getElementById("imagen1").files[0]
                    $imagencita = btoa($imagencita)
                    alert($imagencita)
                    formdata.append("idioma", document.getElementById("idioma1").value)
                    formdata.append("imagen", $imagencita)
                    formdata.append("tipo", document.getElementById("tipo1").value)
                    formdata.append("nombre", document.getElementById("palabra1").value)
                    /* axios.post("./api.php?accion=insertarFicha", formdata, {
                            headers: {
                                'content-type': 'multipart/form-data'
                            }
                        })
                        .then(function(response) {
                            console.log(response)
                        }) */
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
                modificarFicha: function() {
                    let formdata = new FormData();
                    formdata.append("indice", document.getElementById("mIndice").value)
                    formdata.append("newPalabra", document.getElementById("mPalabra").value)
                    formdata.append("newIdioma", document.getElementById("mIdioma").value)
                    formdata.append("newTipo", document.getElementById("mTipo").value)

                    formdata.append("oldIdioma", this.ficha.idioma)
                    formdata.append("oldPalabra", this.ficha.palabra)
                    formdata.append("oldTipo", this.ficha.tipo)

                    axios.post("./api.php?accion=modificarFicha", formdata)
                        .then(function(response) {
                            console.log("Modificar ficha: ")
                            console.log(response)
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
                eliminarFicha: function() {
                    let formdata = new FormData();
                    formdata.append("indice", this.ficha.indice)
                    axios.post("./api.php?accion=eliminarFicha", formdata)
                        .then(function(response) {
                            console.log("eliminar ficha: " + response)
                            app.listado = response.data.respuesta
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
                verIdiomas: function() {
                    axios.get("./api.php?accion=verIdiomas")
                        .then(function(response) {
                            console.log("Ver Idiomas")
                            console.log(response)
                            app.idiomas = response.data.respuesta[0]
                        })
                },
                leer: function() {
                    var language2 = app.idiomas.idioma2
                    language2 = language2.charAt(0).toUpperCase() + language2.slice(1)
                    //var leido = document.getElementById("palabrita").innerHTML
                    responsiveVoice.speak(this.ficha.palabra, language2);
                },
            }
        })
    </script>


</body>

</html>