<?php
class WikiController extends Controller
{
    public function verWiki()
    {
        $mApi = new PokeAPI();

        $params = array(
            "pokemon_list" => $mApi->getAllPokemon(),
            "type_list" => $mApi->getTypesList(),
            "num_generations" => $mApi->getNumGenerations(),
        );

        require __DIR__ . '/../templates/verWiki.php';
    }
    
    // TODO: A implementar con la vista Pokemon
    // public function verPokemon()
    // {
    //     // $mApi = new PokeAPI();

    //     // $params = array(
    //     //     "pokemon_list" => $mApi->getAllPokemon(),
    //     //     "type_list" => $mApi->getTypesList(),
    //     //     "num_generations" => $mApi->getNumGenerations(),
    //     // );

    //     // require __DIR__ . '/../templates/verPokemon.php';
    // }

    // NOTE: No necesitamos esta función de momento gracias al datalist de html
    // public function filterByName()
    // {
    //     $params = array(
    //         "errors" => [],
    //         "pokemon_list" => [],
    //     );

    //     try {
    //         // We check to see if the user has sent a Pokemon name to search for
    //         // NOTE: DEBE TENER EL MISMO NOMBRE EN EL FORMULARIO
    //         if (isset($_GET['pokemonName'])) {
    //             // We get the Pokemon name from the form
    //             $pokemonName = recoge('pokemonName');
    //             cTexto($pokemonName, "pokemonName", $params["errors"], 20, 1, " -", true);

    //             if (empty($params["errors"])) {
    //                 $mApi = new PokeAPI();
    //                 $params["pokemon_list"] = $mApi->getPokemonByName($pokemonName);
    //             }
    //         } else {
    //             $params['message'] = 'No existe ningún Pokemon que coincida con el nombre indicado.';
    //         }
    //     } catch (Throwable $e) {
    //         $params['message'] = 'No existe ningún Pokemon que coincida con el nombre indicado.';
    //     }

    //     require __DIR__ . '/../templates/verWiki.php';
    // }
}
