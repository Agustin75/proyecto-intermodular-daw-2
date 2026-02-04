<?php
class ClasificarController extends Controller
{
    /**
     * Handles de creation of a Clasificar game
     */
    public function crearClasificar()
    {
        // Initial state of the form parameters
        $params = [
            // We set the mode to create so the view knows what fields to display
            'modo' => MODE_CREATE,
            'idTipo' => '',
            'idPokemon' => '',
            'numOpciones' => '',
            'numPokemon' => '',
            'numRequerido' => ''
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

                // We create the model before the if check because we'll need it after the check too
                $mClasificar = new Clasificar();

                // If there are no errors, we check that the Pokémon is not already in use in any game
                if (empty($errores)) {
                    if ($mClasificar->isPokemonUsed($idPokemon)) {
                        $errores[] = "Este Pokémon ya está utilizado por otro juego.";
                    }

                    // The Pokémon was not used in a Clasificar game, we check Trivia
                    if (empty($errores)) {
                        $mTrivia = new Trivia();
                        if ($mTrivia->isPokemonUsed($idPokemon)) {
                            $errores[] = "Este Pokémon ya está utilizado por otro juego.";
                        }

                        // TODO: The Pokémon was not used in a Trivia game, we check Adivinanza
                        // if (empty($errores)) {
                        //     $mAdivinanza = new Adivinanza();
                        //     if ($mAdivinanza->isPokemonUsed($idPokemon)) {
                        //         $errores[] = "Este Pokémon ya está utilizado por otro juego.";
                        //     }
                        // }
                    }
                }
                
                // If there are no validation errors, we call the Clasificar model
                if (empty($errores)) {
                    $idClasificar = $mClasificar->crearClasificar($idPokemon, $idTipo, $numPokemon, $numOpciones, $numRequerido);

                    // If the model returns false, something went wrong
                    if ($idClasificar === false) {
                        $params['mensaje'] = "No se ha podido crear la clasificación. El Pokémon ya está asignado a otro juego.";
                    } else {
                        // Clasificación created successfully → redirect to the games list
                        header("Location: index.php?ctl=juegos");
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
        require __DIR__ . '/../templates/crearClasificar.php';
    }
}
