<?php
class APIWikiController extends Controller
{
    public function filterByType()
    {
        $errors = [];
        try {
            // We check to see if the user has sent a Pokemon type to search for
            if (isset($_GET['type'])) {
                // We get the Pokemon type from the select
                $type = recoge('type');
                cNum($type, "type", $errors);

                if (empty($errors)) {
                    $mApi = new PokeAPI();

                    ob_clean();
                    print_r(json_encode($mApi->getPokemonByType($type)));
                }
            }
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando obtener la lista de Pokémon.");
        }
    }

    public function filterByGeneration()
    {
        $errors = [];
        try {
            // We check to see if the user has sent a Pokemon generation to search for
            if (isset($_GET['generation'])) {
                // We get the Pokemon generation from the select
                $generation = recoge('generation');
                cNum($generation, "generation", $errors);

                if (empty($errors)) {
                    $mApi = new PokeAPI();

                    ob_clean();
                    print_r(json_encode($mApi->getPokemonByGeneration($generation)));
                }
            }
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando obtener la lista de Pokémon.");
        }
    }
}
