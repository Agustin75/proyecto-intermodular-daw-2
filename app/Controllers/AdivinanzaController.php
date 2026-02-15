<?php
// IMPORTANTE
// FALTA SABER LA RUTA, EL IF DEL ADMIN Y VALIDAR QUE EL POKEMON NO ESTE YA USADO

class AdivinanzaController extends Controller
{
    public function crearAdivinanza()
    {
        $mPokeAPI = new PokeAPI();
        $sAdivinanza = new Adivinar();
        // We create a PokeAPI and an Adivinar as well as set the parameters
        $params = [
            'modo'   => MODE_CREATE,
            'id_pkmn'   => '',
            'id' => '',
            'tipo' => '',
            'pista1'    => '',
            'pista2'   => '',
            'pista3'      => '',
            'pokemon_list' => $mPokeAPI->getAllPokemon(), //we get the list of pokemon and types of adivinanza
            'tiposAdivinar' => $sAdivinanza->obtenerTiposAdivinar()

        ];


        $errores = [];

        try {
            // We check if the form has been submitted
            if (isset($_POST['bCrearAdivinanza'])) {

                // 1. We obtain the form data
                $id_pkmn = intval(recoge('id_pokemon'));
                $tipo = intval(recoge('tipo'));
                $pista1 = recoge('pista1');
                $pista2 = recoge('pista2');
                $pista3 = recoge('pista3');


                // 2. We store the values in $params to preserve the form state
                $params['id_pkmn']   = $id_pkmn;
                $params['tipo'] = $tipo;
                $params['pista1']   = $pista1;
                $params['pista2']   = $pista2;
                $params['pista3']   = $pista3;



                // 3. Basic validation of the received data
                if ($pista1 === '' || $pista2 === '' || $pista3 === '') {
                    $errores[] = "Las pistas no pueden estar vacias.";
                }

                if ($tipo <= 0) {
                    $errores[] = "Debes seleccionar un tipo de Adivinanza.";
                }

                if ($id_pkmn <= 0) {
                    $errores[] = "Debes seleccionar un Pokémon válido.";
                }

                // 4. If there are no validation errors, we call the Adivinanza model
                if (empty($errores)) {
                    $m = new Adivinar();


                    // We attempt to create an entry
                    $idAdivinanza = $m->crearAdivinanza($id_pkmn, $tipo, $pista1, $pista2, $pista3);

                    // If the model returns false, something went wrong
                    if ($idAdivinanza === false) {
                        $params['mensaje'] = "No se ha podido crear la Adivinanza. El Pokémon ya está asignado a otro juego.";
                    } else {
                        // if created successfully → redirect to the games list
                        header("Location: index.php?ctl=gestionarJuegos");
                        exit;
                    }
                }
            }
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $errores[] = "Hubo un error intentando crear la Adivinanza.";
        }

        if(!empty($errores)) {
            // If there were validation errors, we show them in the view
            $params['mensaje'] = implode('<br>', $errores);
        }

        require __DIR__ . '/../templates/crearAdivinanza.php';
    }


    public function editarAdivinanza()
    {
        $mPokeAPI = new PokeAPI();
        $sAdivinanza = new Adivinar();

        // We obtain the Adivinanza ID from the request
        $idAdivinanza = (int) ($_REQUEST['id'] ?? 0);

        // Initial state of the form parameters
        $params = [
            'modo'  => MODE_EDIT,
            'id'    => $idAdivinanza,
            'tipo' => '',
            'id_pkmn'   => '',
            'pista1'    => '',
            'pista2'   => '',
            'pista3'      => '',
            'pokemon_list' => $mPokeAPI->getAllPokemon(),
            'tiposAdivinar' => $sAdivinanza->obtenerTiposAdivinar()

        ];

        // List that will hold all validation errors
        $errores = [];

        try {
            $m = new Adivinar();

            // If the ID is invalid, we redirect back to the games list
            if ($idAdivinanza <= 0) {
                header("Location: index.php?ctl=juegos");
                exit;
            }

            $ad = $m->obtenerAdivinanza($idAdivinanza);

            if (!$ad) {
                $params['mensaje'] = "La adivinanza no existe.";
                echo "adivinanza no existe";
                return;
            }

            // We store the updated values in $params to preserve the form state
            $params['pista1'] = $ad['pista1'];
            $params['pista2'] = $ad['pista2'];
            $params['pista3'] = $ad['pista3'];
            $params['id_pkmn'] = $ad['id_pokemon'];
            $params['tipo'] = $ad['id_tipo'];
            $params['id'] = $idAdivinanza;

            /* ============================================================
           1. FIRST FORM LOAD (GET)
        ============================================================ */
            //if we clicked the link to edit one, instead of saving the one we are editing..
            if (isset($_POST['bEditarAdivinanza'])) {
                /* ============================================================
           2. PROCESS FORM SUBMISSION (POST)
        ============================================================ */

                // We obtain the updated form data
                $pista1 = recoge('pista1');
                $pista2 = recoge('pista2');
                $pista3 = recoge('pista3');
                $idPokemon = recoge('id_pokemon');
                $idTipo = recoge('tipo');
                $idAdivinanza = recoge('id');


                /* ============================================================
           3. VALIDATION
        ============================================================ */


                if ($pista1 === '' || $pista2 === '' || $pista3 === '') {
                    $errores[] = "Las pistas no pueden estar vacias.";
                }


                // We validate the Pokémon ID
                if ($idPokemon <= 0) {
                    $errores[] = "Debes seleccionar un Pokémon válido.";
                }




        /* ============================================================
           4. IF THERE ARE ERRORS → RETURN TO THE VIEW
        ============================================================ */
                if (!empty($errores)) {
                    $params['mensaje'] = implode("<br>", $errores);

                    require __DIR__ . '/../templates/crearAdivinanza.php';
                    exit;
                } else {

                    /* ============================================================
                    5. UPDATE TRIVIA IN THE MODEL
                    ============================================================ */
                    // We attempt to update the Trivia entry
                    $ok = $m->editarAdivinanza($idPokemon, $idAdivinanza, $idTipo, $pista1, $pista2, $pista3);

                    // If the update failed, we show an error message
                    if (!$ok) {
                        $params['mensaje'] = "No se ha podido actualizar la Adivinanza.";

                        require __DIR__ . '/../templates/crearAdivinanza.php';
                        exit;
                    }
                }

                // Success → redirect to the games list
                header("Location: index.php?ctl=gestionarJuegos");
            }
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/crearAdivinanza.php';
    }


    /**
     * Function to delete an existing Trivia entry. It validates the user's permissions,
     * checks the provided Trivia ID and, if valid, requests the model to remove the entry.
     * If the deletion fails, an error view is displayed.
     */
    public function eliminarAdivinanza()
    {
        try {

            $idAdivinanza = (int) ($_GET['id'] ?? 0);

            if ($idAdivinanza <= 0) {
                header("Location: index.php?ctl=juegos");
                exit;
            }

            // We call the model to attempt the deletion
            $m = new Adivinar();
            $ok = $m->eliminarAdivinanza($idAdivinanza);

            // If the deletion failed, we show an error message
            if (!$ok) {
                $params['mensaje'] = "No se ha podido eliminar la trivia.";
                // RUTA            require __DIR__ . '/../templates/error.php';
                return;
            }

            // Successful deletion → redirect to the games list
            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $this->handleError($e);
        }
    }


    public function jugarAdivinanza()
    {
        $errores = [];

        $params = [];
        $params["gameState"] = GAME_STATE_PLAYING;
        $mAdivinanza = new Adivinar();

        // Obtain the list of games the player hasn't completed (A game where he hasn't obtained the Pokémon yet)
        $gamesList = $mAdivinanza->obtenerJuegosSinCompletar($this->session->getUserId());
        $numGamesLeft = count($gamesList);
        $params["gameFound"] = $numGamesLeft > 0;
        if (!$params["gameFound"]) {
            $params['mensaje'] = "No se han encontrado juegos de Adivinanza";
        }

        if (!isset($_POST["bEnviar"])) {


            if ($numGamesLeft > 0) {
                // Obtain a random game from the list
                $mPokeAPI = new PokeAPI();
                $selectedGame = $gamesList[rand(0, $numGamesLeft - 1)];
                $params['id'] = $selectedGame['id'];
                $params['correctPokemonId'] = $selectedGame["id_pokemon"];
                $pkmn = $mPokeAPI->getPokemonById($params['correctPokemonId']);
                $descripcion = $mPokeAPI->getPokemonDescriptionEs($params['correctPokemonId']);
                $params['tipo'] = $selectedGame['id_tipo'];
                switch ($params['tipo']) {
                    case 1:
                        $params['tipo_object'] = $pkmn["cries"]["latest"];
                        break;

                    case 2:
                        $params['tipo_object'] = $pkmn["sprites"]["front_default"];
                        break;

                    case 3:
                        $params['tipo_object'] = $descripcion;
                        break;
                }

                $params['pista1'] = $selectedGame["pista1"];
                $params['pista2'] = $selectedGame["pista2"];
                $params['pista3'] = $selectedGame["pista3"];
            }
        } else {
            $mPokeAPI = new PokeAPI();
            $id = recoge('id');
            $respuesta = recoge('respuesta');
            $correct = recoge('correctPokemonId');

            cTexto($respuesta, "respuesta", $errores);

            $correct = intval($correct);
            $correct2 = $mPokeAPI->getPokemonName($correct);
            $correctcheck = strtolower($correct2);
            $respuestacheck = strtolower($respuesta);
            if ($correctcheck == $respuestacheck) {
                $params['gameState'] = GAME_STATE_WON;
                $params['imagen_pokemon_recompensa'] = $mPokeAPI->getPokemonNormalSprite($correct);
                $params['nombre_pokemon_recompensa'] = $correct2;

                $mPokemonUsuario = new PokemonUsuario();
                $mPokemonUsuario->insertarRegistro($this->session->getUserId(), $correct);
            } else {
                $params["gameState"] = GAME_STATE_LOST;
            }
        }
        if (!empty($errores)) {
            $params['mensaje'] = implode("<br>", $errores);

            return;
        }
        require __DIR__ . '/../templates/jugarAdivinanza.php';
    }
}
