<?php
class ClasificarController extends Controller
{
    /**
     * Handles the creation of a Clasificar game
     */
    public function crearClasificar()
    {
        try {
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
            $this->handleError($e, "Hubo un error intentando insertar el juego de clasificar en la base de datos.");
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

        try {
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
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando editar el juego de clasificar en la base de datos.");
        }

        // We load the Clasificar creation form view
        require __DIR__ . '/../templates/formClasificar.php';
    }

    /**
     * Handles the updating of Clasificar games in the database
     */
    public function guardarClasificar()
    {
        try {
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
            $this->handleError($e, "Hubo un error intentando actualizar el juego de clasificar en la base de datos.");
        }

        // We load the Clasificar creation form view
        require __DIR__ . '/../templates/formClasificar.php';
    }

    /**
     * Handles the displaying of the edit view for Clasificar games
     *
     * Redirects to the games list page upon successful or unsuccessful deletion.
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

        try {
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
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando eliminar el juego de clasificar de la base de datos.");
            exit;
        }

        header("Location: index.php?ctl=gestionarJuegos");
        exit;
    }

    // TODO: Decide if we want to tell the user how many they need to get right to win
    public function jugarClasificar()
    {
        // TODO: The player can resend the information after answering all questions. Doesn't break anything by doing so, but would ideally want to account for it and send them back to the games screen
        $params = [];
        $params["gameState"] = GAME_STATE_PLAYING;

        // Check to see if this is a new game
        if (!isset($_POST["bEnviar"])) {
            try {
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
                        //     "pokemon_id" => 1,
                        //     "pokemon_imagen" => "url imagen",
                        //     "pokemon_nombre" => "nombre",
                        //     "options" => ["Fire", ...] or [1, 3, ...]
                        //     ]
                        // ],
                    ];

                    $numOpciones = $selectedGame["num_opciones"];
                    $numPokemon = $selectedGame["num_pokemon"];
                    $idTipo = $selectedGame["id_tipo"];

                    // Array to save the Pokemon chosen
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
                            // We make sure every entry in the array is unique before continuing
                            $pokemonIds = array_unique($pokemonIds);
                        }
                    }

                    // If we're in a Generacion game
                    if ($idTipo === CLASIFICAR_GENERACION) {
                        // We generate the base array to use for picking generation options
                        $generationsArray = [];
                        for ($i = 1; $i <= $mPokeAPI->getNumGenerations(); $i++) {
                            $generationsArray[] = $i;
                        }

                        // We loop through all the selected Pokemon
                        foreach ($pokemonIds as $index => $pokemonId) {
                            // We save its information
                            $clasificarGame[$index]["pokemon_id"] = $pokemonId;
                            $clasificarGame[$index]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonId);
                            $clasificarGame[$index]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonId);
                            // We grab its generation
                            $pokemonGeneration = $mPokeAPI->getPokemonGeneration($pokemonId);
                            // We set the selected generations array to the array of all generations
                            $generationsSelected = $generationsArray;

                            // We don't need to do anything else if the number of options is equal to the number of Generations
                            if ($numOpciones < $mPokeAPI->getNumGenerations()) {
                                // We shuffle the array of selected generations
                                shuffle($generationsSelected);
                                // We cut the array so there are as many entries as $numOpciones
                                array_splice($generationsSelected, $numOpciones);

                                // If the selected Pokemon's generation isn't in the array
                                if (!in_array($pokemonGeneration, $generationsSelected)) {
                                    // We replace one of the array options with it
                                    $generationsSelected[array_rand($generationsSelected)] = $pokemonGeneration;
                                }
                            }

                            // We sort the array
                            sort($generationsSelected);

                            foreach ($generationsSelected as $generation) {
                                // We add each generation to the options array
                                $clasificarGame[$index]["options"][] = $generation;
                            }
                        }
                    } else if ($idTipo === CLASIFICAR_TIPO) {
                        // We loop through all the selected Pokemon
                        foreach ($pokemonIds as $index => $pokemonId) {
                            // We save its information
                            $clasificarGame[$index]["pokemon_id"] = $pokemonId;
                            $clasificarGame[$index]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonId);
                            $clasificarGame[$index]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonId);

                            // TODO: This works, but it takes too long to load even if there are few options, would be ideal to optimize it if there's time
                            // We obtain the Pokemon's types (array of just the names)
                            $pokemonTypes = $mPokeAPI->getPokemonTypes($pokemonId);
                            // We obtain the list of all Pokemon types (Bidimensional array with id, name, url)
                            $listOfTypes = $mPokeAPI->getTypesList();
                            // We create a new array to save the random types chosen
                            $selectedTypes = [];
                            // We continue to get a new type while there aren't enough options (-1, the last one will be picked from the Pokemon's type so there is exactly 1 correct type)
                            while (count($selectedTypes) < $numOpciones - 1) {
                                // We obtain a random index in the array of types
                                $randId = rand(0, count($listOfTypes) - 1);
                                // We remove the type from the array
                                $type = array_splice($listOfTypes, $randId, 1)[0];
                                // If the type is not one of the Pokemon's types we add its name to the array
                                if (!in_array($type["name"], $pokemonTypes)) {
                                    $selectedTypes[] = $type["name"];
                                }
                            }

                            // We add one of the Pokemon's type to the array of options so we have a correct answer
                            $selectedTypes[] = $pokemonTypes[array_rand($pokemonTypes)];
                            // We sort the array so the types always show up alphabetically
                            sort($selectedTypes);

                            // We add each type to the options array
                            foreach ($selectedTypes as $type) {
                                $clasificarGame[$index]["options"][] = $type;
                            }
                        }
                    }

                    // We add all the information the game will need to the $params variable
                    $params["game"] = $clasificarGame;
                    $params["idTipo"] = $idTipo;
                    $params["gameId"] = $selectedGame["id"];
                    $params["idPokemon"] = $selectedGame["id_pokemon"];
                    $params["numRequerido"] = $selectedGame["num_requerido"];
                }
            } catch (Throwable $e) {
                $this->handleError($e, "Ha ocurrido un error inesperado.");
            }
            // We're going back to an already started game (because the player didn't select all the answers)
        } else {
            $errors = [];
            $params["gameFound"] = false;

            // We obtain the current game's id
            $gameId = recoge("gameId");

            // If it was a number
            if (cNum($gameId, "gameId", $errors)) {
                try {
                    $mClasificar = new Clasificar();
                    // We obtain the game from the database
                    $game = $mClasificar->obtenerClasificar($gameId);
                    // If the game doesn't exist (the id received was wrong)
                    if ($game === false) {
                        $errors[] = "No se encontró un juego con ese id.";
                    } else {
                        $gameType = $game["id_tipo"];
                        $params["idTipo"] = $gameType;
                        $params["gameFound"] = true;

                        $respuestas = [];
                        $allAnswersSelected = true;

                        // We look through all the player answers
                        for ($i = 0; $i < $game["num_pokemon"]; $i++) {
                            // We obtain each one
                            $respuestas[] = recoge("question" . $i);
                            // If the answer was empty, the player didn't answer it
                            if (empty($respuestas[$i])) {
                                // So we update the boolean
                                $allAnswersSelected = false;
                                // We continue looping so we can return all of the player's answers
                            }
                        }

                        // We obtain the pokemon_ids
                        $pokemonIds = recogeArray("pokemon_ids");

                        // If the amount of Pokemon obtained is different than the amount of Pokemon in this game, there was an error
                        if (count($pokemonIds) !== $game["num_pokemon"]) {
                            $errors[] = "Hubo un error obteniendo los resultados.";
                            $params['mensaje'] = implode('<br>', $errors);

                            // We move to a different page
                            header("Location: index.php?ctl=juegos");
                            exit;
                        }

                        $mPokeAPI = new PokeAPI();

                        // The player answered all the questions, check to see if they won
                        if ($allAnswersSelected) {
                            $correctAnswers = 0;

                            // We loop through ever Pokemon in the game
                            foreach ($pokemonIds as $index => $pokemonId) {
                                // We check the type of Clasificar game
                                switch ($gameType) {
                                    case CLASIFICAR_TIPO:
                                        // We obtain the Pokemon's types
                                        $pokemonTypes = $mPokeAPI->getPokemonTypes($pokemonId);

                                        // If the player's answer was one of the Pokemon's type
                                        if (in_array($respuestas[$index], $pokemonTypes)) {
                                            // We increase correct answers
                                            $correctAnswers++;
                                        }
                                        break;
                                    case CLASIFICAR_GENERACION:
                                        // We obtain the Pokemon's generation
                                        $pokemonGeneration = $mPokeAPI->getPokemonGeneration($pokemonId);

                                        // If the player's answer was the Pokemon's generation ($respuestas is in string format, $pokemonGeneration is in int format)
                                        if ($respuestas[$index] == $pokemonGeneration) {
                                            // We increase correct answers
                                            $correctAnswers++;
                                        }
                                        break;
                                }
                            }

                            // If the player answer enough questions correctly
                            if ($correctAnswers >= $game["num_requerido"]) {
                                // The player won, give them the Pokemon
                                $pokemonWonId = $game["id_pokemon"];
                                $mPokemonUsuario = new PokemonUsuario();
                                $mPokemonUsuario->insertarRegistro($this->session->getUserId(), $pokemonWonId);

                                // We set the variables necessary to display the Pokemon won here
                                $params["imagen_pokemon_recompensa"] = $mPokeAPI->getPokemonNormalSprite($pokemonWonId);
                                $params["nombre_pokemon_recompensa"] = $mPokeAPI->getPokemonName($pokemonWonId);
                                $params["gameState"] = GAME_STATE_WON;
                                // We return to the jugarClasificar template from here
                            } else {
                                // The player did not answer enough questions correctly, so they lose
                                $params["gameState"] = GAME_STATE_LOST;
                            }

                            // The user didn't select all the answers, return to the previous state of the game
                        } else {
                            $clasificarGame = [];

                            // We check the options field exists manually (since it's a bidimensional array)
                            if (!isset($_POST["options"])) {
                                $errors[] = "Hubo un error intentando mostrar el juego, intenta seleccionar un nuevo juego.";
                                $params['mensaje'] = implode('<br>', $errors);
                            } else {
                                $opciones = $_POST["options"];

                                // We save the information of every Pokemon (including id, types/generations selected, etc)
                                for ($i = 0; $i < $game["num_pokemon"]; $i++) {
                                    $clasificarGame[$i]["pokemon_id"] = $pokemonIds[$i];
                                    $clasificarGame[$i]["pokemon_image"] = $mPokeAPI->getPokemonNormalSprite($pokemonIds[$i]);
                                    $clasificarGame[$i]["pokemon_name"] = $mPokeAPI->getPokemonName($pokemonIds[$i]);
                                    $clasificarGame[$i]["options"] = $opciones[$i];

                                    // We save the player's chosen answers
                                    if (!empty($respuestas[$i])) {
                                        $params["question" . $i] = $respuestas[$i];
                                    }
                                }

                                $params['mensaje'] = "Quedan Pokemon por clasificar.";
                                $params["game"] = $clasificarGame;
                                $params["gameId"] = $gameId;
                                $params["idPokemon"] = $game["id_pokemon"];
                                $params["numRequerido"] = $game["num_requerido"];
                            }
                        }
                    }
                } catch (Throwable $e) {
                    $this->handleError($e, "Ha ocurrido un error inesperado.");
                }
            }
        }

        require __DIR__ . '/../templates/jugarClasificar.php';
    }
}
