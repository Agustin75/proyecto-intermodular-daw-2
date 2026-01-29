<?php
class WikiController extends Controller
{
    public function displayWiki()
    {
        $mApi = new API();

        $params = array(
            "pokemon_list" => $mApi->getAllPokemon(),
        );

        require __DIR__ . '/../templates/displayWiki.php';
    }

    public function filterByName()
    {
        $params = array(
            "errors" => [],
            "pokemon_list" => [],
        );

        try {
            // We check to see if the user has sent a Pokemon name to search for
            // NOTE: DEBE TENER EL MISMO NOMBRE EN EL FORMULARIO
            if (isset($_GET['pokemonName'])) {
                // We get the Pokemon name from the form
                $pokemonName = recoge('pokemonName');
                cTexto($pokemonName, "pokemonName", $params["errors"], 20, 1, " -", true);

                if (empty($params["errors"])) {
                    $mApi = new API();
                    $params["pokemon_list"] = $mApi->getPokemonByName($pokemonName);
                }
            } else {
                $params['message'] = 'No existe ningún Pokemon que coincida con el nombre indicado.';
            }
        } catch (Throwable $e) { 
                $params['message'] = 'No existe ningún Pokemon que coincida con el nombre indicado.';
            // $this->handleError($e); 
        }

        require __DIR__ . '/../templates/displayWiki.php';
    }

    public function filterByType()
    {
        $params = array(
            "errors" => [],
            "pokemon_list" => [],
        );

        try {
            // We check to see if the user has sent a Pokemon type to search for
            // NOTE: DEBE TENER EL MISMO NOMBRE EN EL FORMULARIO
            if (isset($_GET['type'])) {
                // We get the Pokemon type from the form
                $type = recoge('type');
                cTexto($type, "type", $params["errors"], 20, 1, " -", true);

                if (empty($params["errors"])) {
                    $mApi = new API();
                    $params["pokemon_list"] = $mApi->getPokemonByType($type);
                }
            } else {
                $params['message'] = 'No existe ningún Pokemon que coincida con el tipo indicado.';
            }
        } catch (Throwable $e) { 
                $params['message'] = 'No existe ningún Pokemon que coincida con el tipo indicado.';
            // $this->handleError($e); 
        }

        require __DIR__ . '/../templates/displayWiki.php';
    }

    public function filterByGeneration()
    {
        $params = array(
            "errors" => [],
            "pokemon_list" => [],
        );

        try {
            // We check to see if the user has sent a Pokemon name to search for
            // NOTE: DEBE TENER EL MISMO NOMBRE EN EL FORMULARIO
            if (isset($_GET['generation'])) {
                // We get the Pokemon name from the form
                $generation = recoge('generation');
                cNum($generation, "generation", $params["errors"], true);

                if (empty($params["errors"])) {
                    $mApi = new API();
                    $params["pokemon_list"] = $mApi->getPokemonByGeneration($generation);
                }
            } else {
                $params['message'] = 'No existe ningún Pokemon que coincida con la generación indicada.';
            }
        } catch (Throwable $e) { 
                $params['message'] = 'No existe ningún Pokemon que coincida con la generación indicada.';
            // $this->handleError($e); 
        }

        require __DIR__ . '/../templates/displayWiki.php';
    }
}
