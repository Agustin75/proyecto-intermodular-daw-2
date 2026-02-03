<?php
// IMPORTANTE
// FALTA SABER LA RUTA, EL IF DEL ADMIN Y VALIDAR QUE EL POKEMON NO ESTE YA USADO

class AdivinanzaController extends Controller
{
   /**
 * Function to create a new Trivia entry. It processes the form data, validates it,
 * builds the options structure and, if everything is correct, sends the information
 * to the Trivia model to be inserted into the database.
 */
public function crearAdivinanza()
{

    // Initial state of the form parameters
    $params = [
        'enunciado'   => '',
        'numOpciones' => '',
        'opciones'    => [],   // List of option objects (text + correct flag)
        'idPokemon'   => '',
        'tiempo'      => '',
        'mensaje'     => ''
    ];

    // List that will hold all validation errors
    $errores = [];

    try {
        // We check if the form has been submitted
        if (isset($_POST['bCrearTrivia'])) {

            // 1. We obtain the form data
            $enunciado   = recoge('enunciado');
            $numOpciones = (int) recoge('numOpciones');
            $idPokemon   = (int) recoge('idPokemon');
            $tiempo      = (int) recoge('tiempo');

            // We obtain the options arrays (texts and correct answers)
            $opcionTextos    = recogeArray('opcionTexto');     // List of option texts
            $opcionCorrectas = recogeArray('opcionCorrecta');  // List of indices marked as correct

            // 2. We store the values in $params to preserve the form state
            $params['enunciado']   = $enunciado;
            $params['numOpciones'] = $numOpciones;
            $params['idPokemon']   = $idPokemon;
            $params['tiempo']      = $tiempo;

            // We build the list of option objects
            $opciones = [];
            for ($i = 0; $i < $numOpciones; $i++) {
                $texto = $opcionTextos[$i] ?? '';
                $esCorrecta = in_array($i, $opcionCorrectas ?? []) ? 1 : 0;

                $op = new stdClass();
                $op->texto    = $texto;
                $op->correcta = $esCorrecta;

                $opciones[] = $op;
            }
            $params['opciones'] = $opciones;

            // 3. Basic validation of the received data
            if ($enunciado === '') {
                $errores[] = "El enunciado no puede estar vacío.";
            }

            if ($numOpciones <= 1) {
                $errores[] = "Debe haber al menos 2 opciones.";
            }

            if (count($opciones) !== $numOpciones) {
                $errores[] = "El número de opciones no coincide.";
            }

            // We check that all options have text and at least one is correct
            $hayCorrecta = false;
            foreach ($opciones as $op) {
                if (trim($op->texto) === '') {
                    $errores[] = "Todas las opciones deben tener texto.";
                    break;
                }
                if ($op->correcta) {
                    $hayCorrecta = true;
                }
            }

            if (!$hayCorrecta) {
                $errores[] = "Debe haber al menos una opción correcta.";
            }

            if ($idPokemon <= 0) {
                $errores[] = "Debes seleccionar un Pokémon válido.";
            }

            if ($tiempo <= 0) {
                $errores[] = "El tiempo debe ser mayor que 0.";
            }

            // -------------------------------- IMPORTANT FALTA ----------------------------------
            // Aqui validamos tambien que el pokemon no este ya usado en otros juegos
            // -----------------------------------------------------------------------------------

            // 4. If there are no validation errors, we call the Trivia model
            if (empty($errores)) {
                $m = new Trivia();

                // We adapt the options to the format expected by the model
                $opcionesModelo = [];
                foreach ($opciones as $op) {
                    $opcionesModelo[] = [
                        'texto'    => $op->texto,
                        'correcta' => $op->correcta
                    ];
                }

                // We attempt to create the Trivia entry
                $idTrivia = $m->crearTrivia(
                    $idPokemon,
                    $enunciado,
                    $tiempo,
                    $opcionesModelo
                );

                // If the model returns false, something went wrong
                if ($idTrivia === false) {
                    $params['mensaje'] = "No se ha podido crear la trivia. El Pokémon ya está asignado a otro juego.";
                } else {
                    // Trivia created successfully → redirect to the games list
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

    // We load the Trivia creation form view
    // FALTA SABER LA RUTA
    // require __DIR__ . '/../templates/triviaCrear.php';
}

   /**
 * Function to edit an existing Trivia entry. It loads the current data on the first GET request,
 * validates the updated information on POST, rebuilds the options structure and, if everything is valid,
 * updates the Trivia entry through the model.
 */
public function editarTrivia()
{
 // Tal vez no necesario ya que si no eres admin no puedes llegar a aqui

        // Solo admin puede crear trivias
        // if ($this->session->getUserLevel() < /* nivel admin */ ) {
        //    header("Location: index.php?ctl=inicio");
        //    exit;
        //}

    // We obtain the Trivia ID from the request
    $idTrivia = (int) ($_GET['id'] ?? 0);

    // If the ID is invalid, we redirect back to the games list
    if ($idTrivia <= 0) {
        header("Location: index.php?ctl=juegos");
        exit;
    }

    // Initial state of the form parameters
    $params = [
        'idTrivia'    => $idTrivia,
        'enunciado'   => '',
        'numOpciones' => 0,
        'opciones'    => [],
        'idPokemon'   => '',
        'tiempo'      => '',
        'mensaje'     => ''
    ];

    // List that will hold all validation errors
    $errores = [];

    try {
        $m = new Trivia();

        /* ============================================================
           1. FIRST FORM LOAD (GET)
        ============================================================ */
        if (!isset($_POST['bEditarTrivia'])) {

            // We obtain the current Trivia information
            $trivia = $m->obtenerTrivia($idTrivia);

            // If the Trivia does not exist, we show an error message
            if (!$trivia) {
                $params['mensaje'] = "La trivia no existe.";
// RUTA               require __DIR__ . '/../templates/triviaEditar.php';
                return;
            }

            // We fill the form parameters with the existing data
            $params['enunciado']   = $trivia['enunciado']['pregunta'];
            $params['idPokemon']   = $trivia['enunciado']['id_pokemon'];
            $params['tiempo']      = $trivia['enunciado']['segundos'];
            $params['numOpciones'] = count($trivia['opciones']);

            // We convert the model's options into objects for the view
            foreach ($trivia['opciones'] as $op) {
                $o = new stdClass();
                $o->texto    = $op['opcion'];
                $o->correcta = $op['esCorrecta'];
                $params['opciones'][] = $o;
            }

            // We load the edit view
// RUTA            require __DIR__ . '/../templates/triviaEditar.php';
            return;
        }

        /* ============================================================
           2. PROCESS FORM SUBMISSION (POST)
        ============================================================ */

        // We obtain the updated form data
        $enunciado   = recoge('enunciado');
        $numOpciones = (int) recoge('numOpciones');
        $idPokemon   = (int) recoge('idPokemon');
        $tiempo      = (int) recoge('tiempo');

        $opcionTextos    = recogeArray('opcionTexto');
        $opcionCorrectas = recogeArray('opcionCorrecta');

        // We store the updated values in $params to preserve the form state
        $params['enunciado']   = $enunciado;
        $params['numOpciones'] = $numOpciones;
        $params['idPokemon']   = $idPokemon;
        $params['tiempo']      = $tiempo;

        // We rebuild the list of option objects
        $opciones = [];
        for ($i = 0; $i < $numOpciones; $i++) {
            $o = new stdClass();
            $o->texto    = $opcionTextos[$i] ?? '';
            $o->correcta = in_array($i, $opcionCorrectas ?? []) ? 1 : 0;
            $opciones[] = $o;
        }
        $params['opciones'] = $opciones;

        /* ============================================================
           3. VALIDATION
        ============================================================ */

        // We validate the question text
        if ($enunciado === '') {
            $errores[] = "El enunciado no puede estar vacío.";
        }

        // We ensure there are at least two options
        if ($numOpciones < 2) {
            $errores[] = "Debe haber al menos 2 opciones.";
        }

        // We verify that the number of options matches the expected count
        if (count($opciones) !== $numOpciones) {
            $errores[] = "El número de opciones no coincide.";
        }

        // We check that all options have text and at least one is correct
        $hayCorrecta = false;
        foreach ($opciones as $op) {
            if (trim($op->texto) === '') {
                $errores[] = "Todas las opciones deben tener texto.";
                break;
            }
            if ($op->correcta) {
                $hayCorrecta = true;
            }
        }

        if (!$hayCorrecta) {
            $errores[] = "Debe haber al menos una opción correcta.";
        }

        // We validate the Pokémon ID
        if ($idPokemon <= 0) {
            $errores[] = "Debes seleccionar un Pokémon válido.";
        }

        // We validate the time limit
        if ($tiempo <= 0) {
            $errores[] = "El tiempo debe ser mayor que 0.";
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
// RUTA            require __DIR__ . '/../templates/triviaEditar.php';
            return;
        }

        /* ============================================================
           5. UPDATE TRIVIA IN THE MODEL
        ============================================================ */

        // We convert the options to the format expected by the model
        $opcionesModelo = [];
        foreach ($opciones as $op) {
            $opcionesModelo[] = [
                'texto'    => $op->texto,
                'correcta' => $op->correcta
            ];
        }

        // We attempt to update the Trivia entry
        $ok = $m->editarTrivia(
            $idTrivia,
            $idPokemon,
            $enunciado,
            $tiempo,
            $opcionesModelo
        );

        // If the update failed, we show an error message
        if (!$ok) {
            $params['mensaje'] = "No se ha podido actualizar la trivia.";
// RUTA            require __DIR__ . '/../templates/triviaEditar.php';
            return;
        }

        // Success → redirect to the games list
        header("Location: index.php?ctl=juegos");
        exit;

    } catch (Throwable $e) {
        // We delegate the error handling to the controller's method
        $this->handleError($e);
    }
}


/**
 * Function to delete an existing Trivia entry. It validates the user's permissions,
 * checks the provided Trivia ID and, if valid, requests the model to remove the entry.
 * If the deletion fails, an error view is displayed.
 */
public function eliminarTrivia()
{
     // Tal vez no necesario ya que si no eres admin no puedes llegar a aqui

        // Solo admin puede crear trivias
        // if ($this->session->getUserLevel() < /* nivel admin */ ) {
        //    header("Location: index.php?ctl=inicio");
        //    exit;
        //}

    try {
        // We obtain the Trivia ID from the request
        $idTrivia = (int) ($_GET['id'] ?? 0);

        // If the ID is invalid, we redirect back to the games list
        if ($idTrivia <= 0) {
            header("Location: index.php?ctl=juegos");
            exit;
        }

        // We call the model to attempt the deletion
        $m = new Trivia();
        $ok = $m->eliminarTrivia($idTrivia);

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


}
