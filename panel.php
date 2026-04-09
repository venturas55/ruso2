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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
</head>

<body>
    <div id="app">
        <?php cabecera() ?>
        <div>
            <h1>PANEL DE CONTROL</h1>
            <p> Usuario: {{idiomas.usuario }}</p>

            <p> Idioma: {{idiomas.idioma2}} </p>


            <button @click="verActualizar=true;">ACTUALIZAR</button>
        </div>




        <!-- Modificar2 item -->
        <div class="contenedor" v-if="verActualizar">
            <div class="modal">
                <div class="header">
                    <button class="close" @click="verActualizar=false">X</button>
                    <h1>MODIFICAR IDIOMAS</h1>
                </div>
                <div class="contenido">
                    <p>Actualizar idiomas del usuario: {{idiomas.usuario }}</p>

                        <p> Idioma: <select name="idioma2" id="idioma2" id="nId2" v-model="seleccionId2">
                    <option value="UK English Male">UK English Male</option>
                            <option value="UK English Female">UK English Female</option>
                            <option value="US English Male">US English Male</option>
                            <option value="US English Female">US English Female</option>
                            <option value="Arabic Male">Arabic Male</option>
                            <option value="Arabic Female">Arabic Female</option>
                            <option value="Brazilian Portuguese Male">Brazilian Portuguese Male</option>
                            <option value="Brazilian Portuguese Female">Brazilian Portuguese Female</option>
                            <option value="Portuguese Male">Portuguese Male</option>
                            <option value="Portuguese Female">Portuguese Female</option>
                            <option value="Chinese Male">Chinese Male</option>
                            <option value="Chinese Female">Chinese Female</option>
                            <option value="Spanish Male">Spanish Male</option>
                            <option value="Spanish Female">Spanish Female</option>
                            <option value="Spanish Latin American Male">Spanish Latin American Male</option>
                            <option value="Spanish Latin American Female">Spanish Latin American Female</option>
                            <option value="Russian Male">Russian Male</option>
                            <option value="Russian Female">Russian Female</option>
                            <option value="Polish Male">Polish Male</option>
                            <option value="Polish Female">Polish Female</option>
                            <option value="Deutsch Male">Deutsch Male</option>
                            <option value="Deutsch Female">Deutsch Female</option>
                            <option value="Catalan Male">Catalan Male</option>
                        </select>

                    </p>
                    <p>Seguro que desea actualizar los idiomas?? (han de estar en ingles)</p>
                    <button @click="verActualizar=false;actualizarIdiomas()">SI</button>
                    <button @click="verActualizar=false">NO</button>
                </div>
            </div>
        </div>



        <?php pie() ?>
    </div>

    <script>
        var app = new Vue({
            el: "#app",
            data: {
                verActualizar: false,
                idiomas: "",
                seleccionId1: "",
                seleccionId2: "",
            },
            mounted: function() {
                this.verIdiomas()
            },
            methods: {

                verIdiomas: function() {
                    axios.get("./api.php?accion=verIdiomas")
                        .then(function(response) {
                            console.log("Respuesta: " + response.data.respuesta)
                            app.idiomas = response.data.respuesta[0]
                        })
                    //window.location.reload(true); //The parameter set to 'true' reloads a fresh copy from the server. Leaving it out will serve the page from cache.
                },

                actualizarIdiomas: function() {
                    let formdata = new FormData();
                    formdata.append("newId2", this.seleccionId2)
                    formdata.append("oldId2", this.idiomas.idioma2)

                    axios.post("./api.php?accion=modificarIdiomas", formdata)
                        .then(function(response) {
                            console.log(response)
                        })


                }

            }
        })
    </script>

</body>

</html>

<!-- 
    


<select name="idioma" id="idioma1">
                <option value="UK English">UK English</option>
                <option value="US English">US English</option>
                <option value="Arabic">Arabic</option>
                <option value="Armenian">Armenian</option>
                <option value="Brazilian">Brazilian</option>
                <option value="Chinese">Chinese</option>
                <option value="Spanish">Spanish</option>
                <option value="Spanish Latin America">Spanish Latin America</option>
                <option value="Russian">Russian</option>
                <option value="Polish">Polish</option>
                <option value="Deutsch">Deutsch</option>
                <option value="Catalan">Catalan</option>
    </select>




UK English Female
Uk English Male
US English Female
US English Male
Arabic Male
Arabic Female
Armenian Male
Brazilian Portuguese Female
Brazilian Portuguese Male
Chinese Female
Chinese Male
Spanish Latin America Male
Spanish Latin America Female
Spanish Male
Spanish Female
Russian Male
Russian Female
Polish Male
Polish Female
Deutsch Male
Deutsch Female
Catalan Male



            </select>
        
        
        -->