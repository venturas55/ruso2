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

        Select Kind: <select id="seleccion" v-model="seleccion">
            <option value="" selected="selected">Todo</option>

            <?php
            $db = conectaDb();
            $consulta = "SELECT distinct tipo FROM `diccionario` WHERE usuario='" . $usuario . "'";
            $result = $db->prepare($consulta);
            $result->execute();
            $tipos = $result->fetchAll();
            foreach ($tipos as $i)
                echo '<option value="' . $i['tipo'] . '">' . $i['tipo'] . 's</option>';
            ?>

        </select>
        <button @click="mostrarListado()">FILTER LIST</button>
        <br>
        <br>

        <div>
            <table>
                <tr>
                    <th>Español</th>
                    <th>Ruso</th>
                    <th>Accion</th>
                    <th v-show="false">Categoria</th>
                </tr>
                <tr @click='leer($event)' v-for="item in listado">
                    <td>{{item.idioma1}}</td>
                    <td>{{item.idioma2}}</td>
                    <td><button class="close" @click.prevent="verModificarItem=true;seleccionar($event)">X</button></td>
                    <td v-show="false">{{item.tipo}}</td>
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
                    <p> Español<input type="text" name="idioma1" id="idioma1"></p>
                    <p> Ruso<input type="text" name="idioma2" id="idioma2"></p>
                    <p> Tipo <input type="text" name="tipo" id="tipo"></p>
                    <button @click="nuevoItem=false;insertarFicha()">CREAR</button>
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
                <br>
                <div class="contenido">
                    Idioma1 <input type="text" id="mId1" v-bind:value="ficha.idioma1">
                    Idioma2 <input type="text" id="mId2" v-bind:value="ficha.idioma2">
                    Tipo <input type="text" id="mTipo" v-bind:value="ficha.tipo">
                    <p>Esta seguro de que desea modificar los valores por los mostrados??</p>
                    <button @click="verModificarItem=false;modificarFicha()">SI</button>
                    <button @click="verModificarItem=false">NO</button>
                    <br>
                    <p>O tal vez prefieres borrar la ficha????</p>
                    <button @click="verModificarItem=false;verBorrar=true">SI</button>
                    <button @click="verModificarItem=false">NO</button>
                </div>
            </div>
        </div>

        <div class="contenedor" v-if="verBorrar">
            <div class="modal">
                <div class="header">
                    <button class="close" @click="verBorrar=false">X</button>
                    <h1>BORRAR FICHA</h1>
                </div>
                <br>
                <div class="contenido">
                    <p>Seguro que quieres borrar la ficha???</p>
                    <button @click="verBorrar=false;eliminarFicha()">SI</button>
                    <button @click="verBorrar=false">NO</button>
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
                verEliminaItem: false,
                verModificarItem: false,
                verBorrar: false,
                idioma: false,
                seleccion: "",
                ficha: "",
                listado: [],
            },
            mounted: function() {
                this.mostrarListado()
            },
            methods: {
                mostrarListado: function() {
                    let formdata = new FormData();
                    seleccion = this.seleccion;
                    formdata.append("seleccion", seleccion);
                    axios.post("./api.php?accion=mostrarListado", formdata)
                        .then(function(response) {
                            console.log(response)
                            app.listado = response.data.respuesta
                        })
                },
                modificarFicha: function() {
                    let formdata = new FormData();
                    formdata.append("newId1", document.getElementById("mId1").value)
                    formdata.append("newId2", document.getElementById("mId2").value)

                    formdata.append("newTipo", document.getElementById("mTipo").value)
                    formdata.append("oldId1", this.ficha.idioma1)
                    formdata.append("oldId2", this.ficha.idioma2)
                    formdata.append("oldTipo", this.ficha.tipo)

                    axios.post("./api.php?accion=modificarFicha", formdata)
                        .then(function(response) {
                            console.log(response)
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
                insertarFicha: function() {
                    let formdata = new FormData();
                    formdata.append("idioma1", document.getElementById("idioma1").value)
                    formdata.append("idioma2", document.getElementById("idioma2").value)
                    formdata.append("tipo", document.getElementById("tipo").value)
                    axios.post("./api.php?accion=insertarFicha", formdata)
                        .then(function(response) {
                            console.log(response)
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
                seleccionar: function(e) {
                    padre = e.target.parentNode.parentNode
                    var1 = padre.getElementsByTagName('td')[0].innerHTML
                    var2 = padre.getElementsByTagName('td')[1].innerHTML
                    var3 = padre.getElementsByTagName('td')[3].innerHTML

                    this.ficha = {
                        idioma1: var1,
                        idioma2: var2,
                        tipo: var3
                    }
                },
                leer: function(e){
                    padre = e.target.parentNode
                    var1=padre.getElementsByTagName('td')[0].innerHTML
                    var2=padre.getElementsByTagName('td')[1].innerHTML
                    responsiveVoice.speak(var1,"Spanish Female");
                    responsiveVoice.speak(var2,"Russian Female");
                },
                eliminarFicha: function() {
                    let formdata = new FormData();
                    formdata.append("idioma1", this.ficha.idioma1)
                    formdata.append("idioma2", this.ficha.idioma2)
                    axios.post("./api.php?accion=eliminarFicha", formdata)
                        .then(function(response) {
                            console.log(response)
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },
            }
        })
    </script>
    
</body>

</html>