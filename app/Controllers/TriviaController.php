<?php
// IMPORTANTE
// FALTA SABER LA RUTA, EL IF DEL ADMIN Y VALIDAR QUE EL POKEMON NO ESTE YA USADO

class TriviaController extends Controller
{
   /**
 * Function to create a new Trivia entry. It processes the form data, validates it,
 * builds the options structure and, if everything is correct, sends the information
 * to the Trivia model to be inserted into the database.
 */
public function crearTrivia()
{
    if ($this->session->getUserLevel() < USER_ADMIN) {
        header("Location: index.php?ctl=inicio");
        exit;
    }

    $params = [
        'modo'        => 'crear',
        'id'          => 0,
        'pregunta'    => '',
        'tiempo'      => '',
        'opciones'    => [],
        'id_pkmn'     => '',
        'mensaje'     => '',
        'pokemon_list' => (new PokeAPI())->getAllPokemon(),
        'type_list'    => (new PokeAPI())->getTypesList(),
        'num_generations' => (new PokeAPI())->getNumGenerations()
    ];

    $errores = [];

    try {
        if (isset($_POST['bCrearTrivia'])) {

            // Recoger datos
            $pregunta   = recoge('enunciado');
            $tiempo     = (int) recoge('tiempo');
            $numOpciones = (int) recoge('numOpciones');
            $pkmnInput  = recoge('pokemonNameInput');

            // Extraer ID del Pokémon (formato "25 - Pikachu")
            $idPokemon = intval(explode(" - ", $pkmnInput)[0]);

            $opcionTextos    = recogeArray('opcionTexto');
            $opcionCorrectas = recogeArray('opcionCorrecta');

            // Guardar estado
            $params['pregunta'] = $pregunta;
            $params['tiempo']   = $tiempo;
            $params['id_pkmn']  = $idPokemon;

            // Construir opciones como objetos
            $opciones = [];
            for ($i = 0; $i < $numOpciones; $i++) {
                $o = new stdClass();
                $o->texto = $opcionTextos[$i] ?? '';
                $o->correcta = in_array($i, $opcionCorrectas ?? []) ? 1 : 0;
                $opciones[] = $o;
            }
            $params['opciones'] = $opciones;

            // Validaciones
            if ($pregunta === '') $errores[] = "El enunciado no puede estar vacío.";
            if ($tiempo <= 0) $errores[] = "El tiempo debe ser mayor que 0.";
            if ($numOpciones < 2) $errores[] = "Debe haber al menos 2 opciones.";
            if (!$idPokemon) $errores[] = "Debes seleccionar un Pokémon válido.";

            $hayCorrecta = false;
            foreach ($opciones as $op) {
                if (trim($op->texto) === '') {
                    $errores[] = "Todas las opciones deben tener texto.";
                    break;
                }
                if ($op->correcta) $hayCorrecta = true;
            }
            if (!$hayCorrecta) $errores[] = "Debe haber al menos una opción correcta.";

            // Si hay errores → volver a la vista
            if (!empty($errores)) {
                $params['mensaje'] = implode("<br>", $errores);
                require __DIR__ . '/../templates/crearTrivia.php';
                return;
            }

            // Llamar al modelo
            $m = new Trivia();

            $opcionesModelo = [];
            foreach ($opciones as $op) {
                $opcionesModelo[] = [
                    'texto'    => $op->texto,
                    'correcta' => $op->correcta
                ];
            }

            $idTrivia = $m->crearTrivia(
                $idPokemon,
                $pregunta,
                $tiempo,
                $opcionesModelo
            );

            if ($idTrivia === false) {
                $params['mensaje'] = "No se ha podido crear la trivia. El Pokémon ya está asignado a otro juego.";
                require __DIR__ . '/../templates/crearTrivia.php';
                return;
            }

            // Éxito
            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        }

    } catch (Throwable $e) {
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
    die();
}


    require __DIR__ . '/../templates/crearTrivia.php';
}

public function editarTrivia()
{
    if ($this->session->getUserLevel() < USER_ADMIN) {
        header("Location: index.php?ctl=inicio");
        exit;
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $m = new Trivia();
    $api = new PokeAPI();

    // Obtener datos de la trivia
    $trivia = $m->obtenerTrivia($id);

    if (!$trivia) {
        echo "La trivia no existe";
        exit;
    }

    // Preparar parámetros iniciales
    $params = [
        'modo' => 'editar',
        'id' => $id,
        'id_pkmn' => $trivia['enunciado']['id_pokemon'],
        'pregunta' => $trivia['enunciado']['pregunta'],
        'tiempo' => $trivia['enunciado']['tiempo'],
        'opciones' => $trivia['opciones'],
        'pokemon_list' => $api->getAllPokemon(),
        'type_list' => $api->getTypesList(),
        'num_generations' => $api->getNumGenerations(),
        'mensaje' => ''
    ];

    $errores = [];

    // ============================
    // PROCESAR ACTUALIZACIÓN
    // ============================
    if (isset($_POST['bEditarTrivia'])) {

        // Recoger datos
        $pregunta = recoge('enunciado');
        $tiempo = (int) recoge('tiempo');
        $numOpciones = (int) recoge('numOpciones');
        $pkmnInput = recoge('pokemonNameInput');
        $idPokemon = intval(explode(" - ", $pkmnInput)[0]);

        $opcionTextos = recogeArray('opcionTexto');
        $opcionCorrectas = recogeArray('opcionCorrecta');

        // Reconstruir opciones como objetos (para devolver a la vista si hay errores)
        $opciones = [];
        for ($i = 0; $i < $numOpciones; $i++) {
            $o = new stdClass();
            $o->texto = $opcionTextos[$i] ?? '';
            $o->correcta = in_array($i, $opcionCorrectas ?? []) ? 1 : 0;
            $opciones[] = $o;
        }

        // Guardar estado en params
        $params['pregunta'] = $pregunta;
        $params['tiempo'] = $tiempo;
        $params['id_pkmn'] = $idPokemon;
        $params['opciones'] = $opciones;

        // ============================
        // VALIDACIONES (IGUAL QUE crearTrivia)
        // ============================

        if ($pregunta === '') $errores[] = "El enunciado no puede estar vacío.";
        if ($tiempo <= 0) $errores[] = "El tiempo debe ser mayor que 0.";
        if ($numOpciones < 2) $errores[] = "Debe haber al menos 2 opciones.";
        if (!$idPokemon) $errores[] = "Debes seleccionar un Pokémon válido.";

        // Validar opciones vacías y al menos una correcta
        $hayCorrecta = false;
        foreach ($opciones as $op) {
            if (trim($op->texto) === '') {
                $errores[] = "Todas las opciones deben tener texto.";
                break;
            }
            if ($op->correcta) $hayCorrecta = true;
        }
        if (!$hayCorrecta) $errores[] = "Debe haber al menos una opción correcta.";

        // Validar duplicados
        $normalized = array_map(fn($t) => mb_strtolower(trim($t)), $opcionTextos ?? []);
        $nonEmpty = array_filter($normalized, fn($v) => $v !== '');
        if (count(array_unique($nonEmpty)) < count($nonEmpty)) {
            $errores[] = "No puede haber opciones repetidas.";
        }

        // ============================
        // SI HAY ERRORES → VOLVER A LA VISTA
        // ============================
        if (!empty($errores)) {
            $params['mensaje'] = implode("<br>", $errores);
            require __DIR__ . '/../templates/crearTrivia.php';
            return;
        }

        // ============================
        // ACTUALIZAR EN BD
        // ============================
        $opcionesModelo = [];
        foreach ($opciones as $op) {
            $opcionesModelo[] = [
                'texto' => $op->texto,
                'correcta' => $op->correcta
            ];
        }

        $ok = $m->actualizarTrivia($id, $idPokemon, $pregunta, $tiempo, $opcionesModelo);

        if ($ok) {
            header("Location: index.php?ctl=gestionarJuegos");
            exit;
        } else {
            $params['mensaje'] = "No se pudo actualizar la trivia.";
        }
    }

    require __DIR__ . '/../templates/crearTrivia.php';
}




/**
 * Function to delete an existing Trivia entry. It validates the user's permissions,
 * checks the provided Trivia ID and, if valid, requests the model to remove the entry.
 * If the deletion fails, an error view is displayed.
 */
public function eliminarTrivia()
{


    try {
        // We obtain the Trivia ID from the request
        $idTrivia = (int) ($_GET['id'] ?? 0);

        // If the ID is invalid, we redirect back to the games list
        if ($idTrivia <= 0) {
            header("Location: index.php?ctl=getstionarJuegos");
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
        header("Location: index.php?ctl=gestionarJuegos");
        exit;

    } catch (Throwable $e) {
        // We delegate the error handling to the controller's method
        $this->handleError($e);
    }
}


}
