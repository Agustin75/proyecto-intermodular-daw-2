<?php
// IMPORTANTE
// FALTA SABER LA RUTA, EL IF DEL ADMIN Y VALIDAR QUE EL POKEMON NO ESTE YA USADO

class AdivinanzaController extends Controller
{
    public function crearAdivinanza()
    {
         $mPokeAPI = new PokeAPI();
        $sAdivinanza = new Adivinar();
        // Initial state of the form parameters
        $params = [
            'modo'   => MODE_CREATE,
            'id_pkmn'   => '',
            'id' => '',
            'tipo' => '',
            'pista1'    => '',
            'pista2'   => '',
            'pista3'      => '',
            'pokemon_list' => $mPokeAPI->getAllPokemon(),
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



                if ($id_pkmn <= 0) {
                    $errores[] = "Debes seleccionar un Pokémon válido.";
                }

                // 4. If there are no validation errors, we call the Trivia model
                if (empty($errores)) {
                    $m = new Adivinar();
               

                // We attempt to create the Trivia entry
                $idAdivinanza = $m->crearAdivinanza($id_pkmn, $tipo, $pista1, $pista2, $pista3);

                // If the model returns false, something went wrong
                if ($idAdivinanza === false) {
                    $params['mensaje'] = "No se ha podido crear la Adivinanza. El Pokémon ya está asignado a otro juego.";
                } else {
                    // Trivia created successfully → redirect to the games list
                    header("Location: index.php?ctl=juegos");
                    exit;
                }}
            } else {
                // If there were validation errors, we show them in the view
                $params['mensaje'] = implode('<br>', $errores);
            }
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
          $this->handleError($e);
          //var_dump($params);
        }



        require __DIR__ . '/../templates/crearAdivinanza.php';
    }


    public function editarAdivinanza()
    {
         $mPokeAPI = new PokeAPI();
        $sAdivinanza = new Adivinar();
        // Tal vez no necesario ya que si no eres admin no puedes llegar a aqui

        // Solo admin puede crear trivias
        // if ($this->session->getUserLevel() < /* nivel admin */ ) {
        //    header("Location: index.php?ctl=inicio");
        //    exit;
        //}

        // We obtain the Trivia ID from the request
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

            /* ============================================================
           1. FIRST FORM LOAD (GET)
        ============================================================ */
            if (!isset($_POST['bEditarAdivinanza'])) {
                

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

                // We fill the form parameters with the existing data

                $params['idPokemon']   = $ad['id_pokemon'];
                $params['pista1']      = $ad['pista1'];
                $params['pista2']      = $ad['pista2'];
                $params['pista3']      = $ad['pista3'];

                //$idPokemon = $ad['enunciado']['id_pokemon'];
                echo $params['pista2'];
                //return;
            }else{

            /* ============================================================
           2. PROCESS FORM SUBMISSION (POST)
        ============================================================ */

            // We obtain the updated form data
            $pista1   = recoge('pista1');
            $pista2 =  recoge('pista2');
            $pista3   =  recoge('pista3');
            $idPokemon = recoge('id_pokemon');
            $idAdivinanza = recoge('id');
          
            // We store the updated values in $params to preserve the form state
            $params['pista1']   = $pista1;
            $params['pista2'] = $pista2;
            $params['pista3']   = $pista3;


            /* ============================================================
           3. VALIDATION
        ============================================================ */


            if ($pista1 === '' || $pista2 === '' || $pista3 === ''){
                $errores[] = "Las pistas no pueden estar vacias.";
            }


            // We validate the Pokémon ID
            if ($idPokemon <= 0) {
                $errores[] = "Debes seleccionar un Pokémon válido.";
            }




            // We check if the Pokémon is available for this Trivia
            /*  FALTA SABER DISPONIBILIDAD      if (!$m->pokemonDisponibleParaTrivia($idPokemon, $idTrivia)) {
 *          $errores[] = "Este Pokémon ya está asignado a otro juego.";
        }

        /* ============================================================
           4. IF THERE ARE ERRORS → RETURN TO THE VIEW
        ============================================================ */
            if (!empty($errores)) {
                $params['mensaje'] = implode("<br>", $errores);
                
                return;
            }

            /* ============================================================
           5. UPDATE TRIVIA IN THE MODEL
        ============================================================ */
            
      
            // We attempt to update the Trivia entry
            $ok = $m->editarAdivinanza($idPokemon, $idAdivinanza, $pista1, $pista2, $pista3);
       
            // If the update failed, we show an error message
            if (!$ok) {
                $params['mensaje'] = "No se ha podido actualizar la Adivinanza.";
  
                return;
            }

            // Success → redirect to the games list
            header("Location: index.php?ctl=juegos");
            
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
        // Tal vez no necesario ya que si no eres admin no puedes llegar a aqui

        // Solo admin puede crear trivias
        // if ($this->session->getUserLevel() < /* nivel admin */ ) {
        //    header("Location: index.php?ctl=inicio");
        //    exit;
        //}

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
            header("Location: index.php?ctl=juegos");
            exit;
        } catch (Throwable $e) {
            // We delegate the error handling to the controller's method
            $this->handleError($e);
        }
    }


    public function jugarAdivinanza()
    {
        $params = [];
        $params["gameState"] = GAME_STATE_PLAYING;


        if (!isset($_POST["bEnviar"])) {

            $mAdivinanza = new Adivinar();

            // Obtain the list of games the player hasn't completed (A game where he hasn't obtained the Pokémon yet)
            $gamesList = $mAdivinanza->obtenerJuegosSinCompletar($this->session->getUserId());
            $numGamesLeft = count($gamesList);
            $params["gameFound"] = $numGamesLeft > 0;

            if ($numGamesLeft > 0) {
                // Obtain a random game from the list
                 $mPokeAPI = new PokeAPI();
                $selectedGame = $gamesList[rand(0, $numGamesLeft - 1)];
                $params['id'] = $selectedGame['id'];
                $params['correctPokemonId'] = $selectedGame["id_pokemon"];
                $pkmn = $mPokeAPI->getPokemonById($params['correctPokemonId']);
                $params['tipo'] = $selectedGame['id_tipo'];
                switch ($params['tipo']){
                        case 1:
                        $params['tipo_object'] = $pkmn['shout']
                }
                
                $params['pista1'] = $selectedGame["pista1"];
                $params['pista2'] = $selectedGame["pista2"];
                $params['pista3'] = $selectedGame["pista3"];
                
              
            } 
        }else {
                $mPokeAPI = new PokeAPI();
                $id = recoge('id');
                $respuesta = recoge('respuesta');
                $correct = recoge('correctPokemonId');
                $correctcheck = strtolower($mPokeAPI->getPokemonName($correct));
                $respuestacheck = strtolower($respuesta);
                if( $correctcheck == $respuestacheck){
                    $params['gameState'] = GAME_STATE_WON;
                }else{
                       $params["gameState"] = GAME_STATE_LOST;
                }

            }
        require __DIR__ . '/../templates/jugarAdivinanza.php';
    }
}
