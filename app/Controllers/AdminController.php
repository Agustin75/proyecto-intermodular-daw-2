<?php


class AdminController extends Controller
{
    public function gestionarJuegos()
    {
        $mTrivia = new Trivia();
        $mClasificar = new Clasificar();
        $api = new PokeAPI();

        // Obtain all games from the database
        $trivias = $mTrivia->obtenerTodasLasTrivias();
        $clasificar = $mClasificar->listarJuegosClasificar();

        // Add the names of the Pokémon
        foreach ($trivias as $i => $t) {
            $pokemon = $api->getPokemonById($t["id_pokemon"]);
            $trivias[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
        }

        foreach ($clasificar as $i => $t) {
            $pokemon = $api->getPokemonById($t["id_pokemon"]);
            $clasificar[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
        }

        $params = [
            "trivias" => $trivias,
            "clasificar" => $clasificar
        ];

        require __DIR__ . '/../templates/gestionarJuegos.php';
    }

    public function mostrarTools()
    {
        require __DIR__ . '/../templates/DevTools.php';
    }


}
