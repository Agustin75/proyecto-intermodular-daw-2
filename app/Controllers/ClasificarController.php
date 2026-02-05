<?php
class ClasificarController extends Controller
{
    /**
     * Handles the creation of a Clasificar game
     */
    public function crearClasificar()
    {
        $mClasificar = new Clasificar();

        // Initial state of the form parameters
        $params = [
            // We set the mode to create so the view knows what fields to display
            'modo' => MODE_CREATE,
            'idTipo' => -1,
            'idPokemon' => '',
            'numOpciones' => '',
            'numPokemon' => '',
            'numRequerido' => '',
            'pokemon_list' => (new PokeAPI())->getAllPokemon(),
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
                } else {
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
                } else {
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
}
