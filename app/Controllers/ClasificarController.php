<?php
class ClasificarController extends Controller
{
    /**
     * Handles the creation of a Clasificar game
     */
    public function crearClasificar()
    {
        $mClasificar = new Clasificar();
        $mPokeAPI = new PokeAPI();

        // Initial state of the form parameters
        $params = [
            // We set the mode to create so the view knows what fields to display
            'modo' => MODE_CREATE,
            'idTipo' => -1,
            'idPokemon' => '',
            'numOpciones' => '',
            'numPokemon' => '',
            'numRequerido' => '',
            'pokemon_list' => $mPokeAPI->getAllPokemon(),
            'tiposClasificar' => $mClasificar->obtenerTiposClasificar()
        ];

        // Array to store the errors
        $errores = [];

        try {
            // We check if the user is accessing this page through the form
            if (isset($_POST['bCrearClasificar'])) {
                // We obtain all the values
                $idTipo = recoge('idTipo');
                $idPokemon = recoge('idPokemon');
                $numOpciones = recoge('numOpciones');
                $numPokemon = recoge('numPokemon');
                $numRequerido = recoge('numRequerido');

                // We validate all the values
                cNum($idTipo, "idTipo", $errores);
                cNum($idPokemon, "idPokemon", $errores);
                cNum($numOpciones, "numOpciones", $errores);
                cNum($numPokemon, "numPokemon", $errores);
                cNum($numRequerido, "numRequerido", $errores);

                // 2. We store the values in $params to preserve the form state
                $idTipo = intval($idTipo);
                $params['idTipo'] = $idTipo;
                $params['idPokemon'] = $idPokemon;
                $params['numOpciones'] = $numOpciones;
                $params['numPokemon'] = $numPokemon;
                $params['numRequerido'] = $numRequerido;

                // We make sure the values fall under expected ranges.
                if ($numOpciones < 2) {
                    $errores[] = "El número de opciones que verá el usuario no puede ser menor que 2.";
                } else if ($idTipo === CLASIFICAR_GENERACION && $numOpciones > $mPokeAPI->getNumGenerations()) {
                    $errores[] = "El número de opciones que verá el usuario no puede ser mayor que el número de generaciones existente.";
                } else if ($idTipo === CLASIFICAR_TIPO && $numOpciones > count($mPokeAPI->getTypesList())) {
                    $errores[] = "El número de opciones que verá el usuario no puede ser mayor que el número de tipos existente.";
                }

                if ($numPokemon < 1) {
                    $errores[] = "El número de Pokemon a mostrar no puede ser menor que 1.";
                }

                if ($numRequerido < 1) {
                    $errores[] = "El número de Pokemon requeridos no puede ser menor que 1.";
                }

                if ($numRequerido > $numPokemon) {
                    $errores[] = "El número de Pokemon requeridos no puede ser mayor que el número total de Pokémon.";
                }

                $mPokemon = new PokeAPI();
                if ($idPokemon < 0 || $idPokemon >= count($mPokemon->getAllPokemon())) {
                    $errores[] = "Debes seleccionar un Pokémon válido.";
                }

                // If there are no validation errors, we call the Clasificar model
                if (empty($errores)) {
                    $idClasificar = $mClasificar->crearClasificar($idPokemon, $idTipo, $numPokemon, $numOpciones, $numRequerido);

                    // If the model returns false, something went wrong
                    if ($idClasificar === false) {
                        $errores[] = "No se ha podido crear la clasificación. El Pokémon ya está asignado a otro juego.";
                    } else {
                        // Clasificación created successfully → redirect to the games list
                        header("Location: index.php?ctl=gestionarJuegos");
                        exit;
                    }
                }

                if (!empty($errores)) {
                    // If there were validation errors, we show them in the view
                    $params["mensaje"] = implode('<br>', $errores);
                }
            }
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $this->handleError($e);
        }

        // We load the Clasificar creation form view
        require __DIR__ . '/../templates/formClasificar.php';
    }

    /**
     * Handles the displaying of the edit view for Clasificar games
     */
    public function editarClasificar()
    {
        // We check if we received the id of a Clasificar game
        if (!isset($_GET["idClasificar"])) {
            $params["mensaje"] = "Error: No se seleccionó ningún juego de Clasificar";

            // We didn't receive an id, return to the previous page
            header("index.php?ctl=gestionarJuegos");
            exit;
        }

        // Array to store the errors
        $errores = [];

        $idClasificar = $_GET["idClasificar"];
        cNum($idClasificar, "idClasificar", $errores);

        $mClasificar = new Clasificar();

        $game = $mClasificar->obtenerClasificar($idClasificar);

        if (!$game) {
            $params["mensaje"] = "Error: No existe un juego de Clasificar con ese id.";

            // We didn't receive a valid id, return to the previous page
            header("index.php?ctl=gestionarJuegos");
            exit;
        }

        // Initial state of the form parameters
        $params = [
            // We set the mode to edit so the view knows what fields to display
            'modo' => MODE_EDIT,
            'idClasificar' => $idClasificar,
            'idTipo' => $game["id_tipo"],
            'idPokemon' => $game["id_pokemon"],
            'numOpciones' => $game["num_opciones"],
            'numPokemon' => $game["num_pokemon"],
            'numRequerido' => $game["num_requerido"],
            'pokemon_list' => (new PokeAPI())->getAllPokemon(),
            'tiposClasificar' => $mClasificar->obtenerTiposClasificar()
        ];

        if (!empty($errores)) {
            $params['mensaje'] = implode('<br>', $errores);
        }

        // We load the Clasificar creation form view
        require __DIR__ . '/../templates/formClasificar.php';
    }

    /**
     * Handles the updating of Clasificar games in the database
     */
    public function guardarClasificar()
    {
        $mClasificar = new Clasificar();

        // Initial state of the form parameters
        $params = [
            // We set the mode to create so the view knows what fields to display
            'modo' => MODE_EDIT,
            'idTipo' => -1,
            'idPokemon' => '',
            'numOpciones' => '',
            'numPokemon' => '',
            'numRequerido' => '',
            'pokemon_list' => (new PokeAPI())->getAllPokemon(),
            'tiposClasificar' => $mClasificar->obtenerTiposClasificar()
        ];

        try {
            // We check if the user is accessing this page through the form
            if (isset($_POST['bGuardarClasificar'])) {
                // Array to store the errors
                $errores = [];

                // We obtain all the values
                $idClasificar = recoge('idClasificar');
                $idTipo = recoge('idTipo');
                $idPokemon = recoge('idPokemon');
                $numOpciones = recoge('numOpciones');
                $numPokemon = recoge('numPokemon');
                $numRequerido = recoge('numRequerido');

                // We validate all the values
                cNum($idClasificar, "idClasificar", $errores);
                cNum($idTipo, "idTipo", $errores);
                cNum($idPokemon, "idPokemon", $errores);
                cNum($numOpciones, "numOpciones", $errores);
                cNum($numPokemon, "numPokemon", $errores);
                cNum($numRequerido, "numRequerido", $errores);

                // We store the values in $params to preserve the form state
                $params['idClasificar'] = $idClasificar;
                $idTipo = intval($idTipo);
                $params['idTipo'] = $idTipo;
                $params['idPokemon'] = $idPokemon;
                $params['numOpciones'] = $numOpciones;
                $params['numPokemon'] = $numPokemon;
                $params['numRequerido'] = $numRequerido;

                // We make sure the values fall under expected ranges
                if ($idClasificar < -1 || !$mClasificar->obtenerClasificar($idClasificar)) {
                    $errores[] = "El id no corresponde a un juego de Clasificar válido.";
                }

                if ($numOpciones < 2) {
                    $errores[] = "El número de opciones que verá el usuario no puede ser menor que 2.";
                }

                if ($numPokemon < 1) {
                    $errores[] = "El número de Pokemon a mostrar no puede ser menor que 1.";
                }

                if ($numRequerido < 1) {
                    $errores[] = "El número de Pokemon requeridos no puede ser menor que 1.";
                }

                if ($numRequerido > $numPokemon) {
                    $errores[] = "El número de Pokemon requeridos no puede ser mayor que el número total de Pokémon.";
                }

                $mPokemon = new PokeAPI();
                if ($idPokemon < 0 || $idPokemon >= count($mPokemon->getAllPokemon())) {
                    $errores[] = "Debes seleccionar un Pokémon válido.";
                }

                // If there are no validation errors, we call the Clasificar model
                if (empty($errores)) {
                    // If the model returns false, something went wrong
                    if ($mClasificar->editarClasificar($idClasificar, $idPokemon, $idTipo, $numPokemon, $numOpciones, $numRequerido) === false) {
                        $errores[] = "No se ha podido editar el juego de Clasificar. El id del juego no es válido o el Pokémon ya está asignado a otro juego.";
                    } else {
                        // Clasificación created successfully → redirect to the games list
                        header("Location: index.php?ctl=gestionarJuegos");
                        exit;
                    }
                }

                if (!empty($errores)) {
                    // If there were validation errors, we show them in the view
                    $params['mensaje'] = implode('<br>', $errores);
                }
            }
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $this->handleError($e);
        }

        // We load the Clasificar creation form view
        require __DIR__ . '/../templates/formClasificar.php';
    }

    /**
     * Handles the displaying of the edit view for Clasificar games
     *
     * Redirects to the games list page upon successful or unsuccessful deletion.
     * TODO: Make it so it stays in the edit page if Eliminar is called from there?
     */
    public function eliminarClasificar()
    {
        // We check if we received the id of a Clasificar game
        if (!isset($_GET["idClasificar"])) {
            $params["mensaje"] = "Error: No se seleccionó ningún juego de Clasificar";

            // We didn't receive an id, return to the previous page
            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        }

        // Array to store the errors
        $errores = [];

        $idClasificar = $_GET["idClasificar"];
        cNum($idClasificar, "idClasificar", $errores);

        $mClasificar = new Clasificar();

        $game = $mClasificar->obtenerClasificar($idClasificar);

        if (!$game) {
            $errores[] = "Error: No existe un juego de Clasificar con ese id.";

            // We didn't receive a valid id, return to the previous page
            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        }

        if (!$mClasificar->eliminarClasificar($idClasificar)) {
            $errores[] = "Error: Hubo un error intentando eliminar el juego de Clasificar.";

            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        }

        if (!empty($errores)) {
            // TODO: This may be irrelevant if we're redirecting, need to find a way to display the error message
            $params['mensaje'] = implode('<br>', $errores);
        }

        header("Location: index.php?ctl=gestionarJuegos");
        exit;
    }







    public function jugarClasificar()
    {
        $params = [];
        $params["gameState"] = GAME_STATE_PLAYING;

        // Check to see if this is a new game
        if (!isset($_POST["bEnviar"])) {
            // We generate the first question
            $mClasificar = new Clasificar();

            // Obtain the list of games the player hasn't completed (A game where he hasn't obtained the Pokémon yet)
            $gamesList = $mClasificar->obtenerJuegosSinCompletar($this->session->getUserId());
            $numGamesLeft = count($gamesList);
            $params["gameFound"] = $numGamesLeft > 0;

            if ($numGamesLeft > 0) {
                // Obtain a random game from the list
                $selectedGame = $gamesList[rand(0, $numGamesLeft - 1)];

                // Variable to fill up and send to $params
                $clasificarGame = [
                    // [
                    //     "pokemon_imagen" => "imagen",
                    //     "pokemon_nombre" => "nombre",
                    //     "options" => ["Fire", ...]
                    //     ]
                    // ],
                ];

                $numOpciones = $selectedGame["num_opciones"];
                $numPokemon = $selectedGame["num_pokemon"];
                $idTipo = $selectedGame["id_tipo"];

                $pokemonIds = [];

                $mPokeAPI = new PokeAPI();
                // We get the total amount of existing Pokémon
                $totalNumOfPokemon = $mPokeAPI->getNumPokemon();

                // We obtain $numPokemon different Pokemon
                while (count($pokemonIds) < $numPokemon) {
                    // We save a random Pokemon
                    $pokemonIds[] = rand(1, $totalNumOfPokemon);

                    // If we have enough Pokemon saved
                    if (count($pokemonIds) == $numPokemon) {
                        // We make sure every entry in the array is unique before leaving the while so the check works as intended
                        $pokemonIds = array_unique($pokemonIds);
                    }
                }

                if ($idTipo === CLASIFICAR_GENERACION) {
                    // We generate the base array to use for picking generation options
                    $generationsArray = [];
                    for ($i = 1; $i <= $mPokeAPI->getNumGenerations(); $i++) {
                        $generationsArray[] = $i;
                    }

                    // We loop through all the selected Pokemon
                    foreach ($pokemonIds as $index => $pokemonId) {
                        // We save its image
                        $clasificarGame[$index]["pokemon_id"] = $pokemonId;
                        $clasificarGame[$index]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonId);
                        $clasificarGame[$index]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonId);
                        // We grab its generation
                        $pokemonGeneration = $mPokeAPI->getPokemonGeneration($pokemonId);
                        // We set the generations array to the array of all generations
                        $generationsSelected = $generationsArray;

                        // We don't need to do anything else if the number of options is equal to the number of Generations
                        if ($numOpciones < $mPokeAPI->getNumGenerations()) {
                            // We shuffle the array of generations
                            shuffle($generationsSelected);
                            // We cut the array so there are as many entries as $numOpciones
                            array_splice($generationsSelected, $numOpciones);

                            // If the selected Pokemon's generation isn't in the array
                            if (!in_array($pokemonGeneration, $generationsSelected)) {
                                // We replace one of the array options with it
                                $generationsSelected[array_rand($generationsSelected)] = $pokemonGeneration;
                            }
                        }

                        foreach ($generationsSelected as $generation) {
                            // We add each generation to the options array, indicating whether the generation is the correct answer
                            $clasificarGame[$index]["options"][] = $generation;
                            // $clasificarGame[$index]["options"][] = ["value" => $generation, "correct" => $generation === $pokemonGeneration];
                        }
                    }
                } else if ($idTipo === CLASIFICAR_TIPO) {
                    // We loop through all the selected Pokemon
                    foreach ($pokemonIds as $index => $pokemonId) {
                        // We save its image
                        $clasificarGame[$index]["pokemon_id"] = $pokemonId;
                        $clasificarGame[$index]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonId);
                        $clasificarGame[$index]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonId);

                        // Check what type of game it is
                        // Choose $numOpciones random types/generations to show the player, with only 1 of them being correct
                        // TODO: This works, but it takes too long to load even if there are few options, would be ideal to optimize it if there's time
                        // We obtain the Pokemon's types (array of just the names)
                        $pokemonTypes = $mPokeAPI->getPokemonTypes($pokemonId);
                        // We obtain the list of all Pokemon types (Bidimensional array with id, name, url)
                        $listOfTypes = $mPokeAPI->getTypesList();
                        // We crear a new array where to save the random types chosen
                        $selectedTypes = [];
                        // We continue to generate a new type while there aren't enough options (-1, the last one will be picked from the Pokemon's type so it's correct)
                        while (count($selectedTypes) < $numOpciones - 1) {
                            // We obtain a random index in the array of types
                            $randId = rand(0, count($listOfTypes) - 1);
                            // We remove the type from the array
                            $type = array_splice($listOfTypes, $randId, 1)[0];
                            // If the type is not one of the Pokemon's types we add its name to the array (A correct type is added at the end)
                            if (!in_array($type["name"], $pokemonTypes)) {
                                $selectedTypes[] = $type["name"];
                            }
                        }

                        // We add one of the Pokemon's type to the array of options so we have a correct answer
                        $selectedTypes[] = $pokemonTypes[array_rand($pokemonTypes)];
                        // We randomize the array
                        shuffle($selectedTypes);

                        foreach ($selectedTypes as $type) {
                            // We add each type to the options array, indicating whether the type is the correct answer
                            $clasificarGame[$index]["options"][] = $type;
                            // $clasificarGame[$index]["options"][] = ["value" => $type, "correct" => in_array($type, $pokemonTypes)];
                        }
                    }
                }

                $params["game"] = $clasificarGame;
                $params["idTipo"] = $idTipo;
                $params["gameId"] = $selectedGame["id"];
                $params["idPokemon"] = $selectedGame["id_pokemon"];
                $params["numRequerido"] = $selectedGame["num_requerido"];
            }
        } else {
            $errors = [];
            $params["gameFound"] = false;

            $gameId = recoge("gameId");

            if(cNum($gameId, "gameId", $errors)) {
                $mClasificar = new Clasificar();

                $game = $mClasificar->obtenerClasificar($gameId);
                if ($game === false) {
                    $errors[] = "No se encontró un juego con ese id.";
                } else {
                    $gameType = $game["id_tipo"];
                    $params["idTipo"] = $gameType;
                    $params["gameFound"] = true;

                    $opciones = $_POST["options"];
                    $numOpciones = count($opciones);
                    $respuestas = [];

                    $allAnswersSelected = $numOpciones > 0;

                    for ($i = 0; $i < $game["num_pokemon"]; $i++) {
                        $respuestas[] = recoge("question" . $i);
                        if (empty($respuestas[$i])) {
                            $allAnswersSelected = false;
                            break;
                        }
                    }

                    // The player answered all the questions, check to see if they won
                    $pokemonIds = recogeArray("pokemon_ids");

                    if (count($pokemonIds) === 0) {
                        $errors[] = "Hubo un error obteniendo los resultados.";

                        header("Location: index.php?ctl=juegos");
                        exit;
                    }

                    $mPokeAPI = new PokeAPI();

                    if ($allAnswersSelected) {
                        $correctAnswers = 0;

                        foreach ($pokemonIds as $index => $pokemonId) {
                            switch ($gameType) {
                                case CLASIFICAR_TIPO:
                                    $pokemonTypes = $mPokeAPI->getPokemonTypes($pokemonId);

                                    // if ($opciones[$index]) {
                                        if (in_array($respuestas[$index], $pokemonTypes)) {
                                            $correctAnswers++;
                                        }
                                    // }

                                    if ($correctAnswers >= $game["num_requerido"]) {
                                        // The player won, give them the Pokemon and take them to the win screen (Could be the same screen)
                                        $pokemonWonId = $game["id_pokemon"];
                                        $mPokemonUsuario = new PokemonUsuario();
                                        $mPokemonUsuario->insertarRegistro($this->session->getUserId(), $pokemonWonId);

                                        $params["imagen_pokemon_recompensa"] = $mPokeAPI->getPokemonNormalSprite($pokemonWonId);
                                        $params["nombre_pokemon_recompensa"] = $mPokeAPI->getPokemonName($pokemonWonId);
                                        $params["gameState"] = GAME_STATE_WON;
                                        // We return to the jugarClasificar template from here
                                    } else {
                                        $params["gameState"] = GAME_STATE_LOST;
                                    }
                                    break;
                                case CLASIFICAR_GENERACION:
                                    // TODO: FILL UP GENERATION GAMES
                                    break;
                            }
                        }

                    // The user didn't select all the answers, return to the previous state of the game
                    } else {
                        $clasificarGame = [];

                        for ($i = 0; $i < $game["num_pokemon"]; $i++) {
                            $clasificarGame[$i]["pokemon_id"] = $pokemonIds[$i];
                            $clasificarGame[$i]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonIds[$i]);
                            $clasificarGame[$i]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonIds[$i]);
                            $clasificarGame[$i]["options"] = $opciones[$i];
                            if (!empty($respuestas[$i])) {
                                $params["question" . $i] = $respuestas[$i];
                            }
                        }

                        $params["game"] = $clasificarGame;
                        $params["gameId"] = $gameId;
                        $params["idPokemon"] = $game["id_pokemon"];
                        $params["numRequerido"] = $game["num_requerido"];
                    }
                }
            }
        }

        require __DIR__ . '/../templates/jugarClasificar.php';
    }
}
