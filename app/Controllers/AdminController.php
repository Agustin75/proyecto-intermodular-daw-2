<?php


class AdminController extends Controller
{
    public function gestionarJuegos()
    {
        $mTrivia = new Trivia();
        $mClasificar = new Clasificar();
        $mAdivinanza = new Adivinar();
        $api = new PokeAPI();

        // Obtain all games from the database
        $trivias = $mTrivia->obtenerTodasLasTrivias();
        $clasificar = $mClasificar->listarJuegosClasificar();
        $adivinar = $mAdivinanza->obtenerTodasAdivinanzas();
        // Add the names of the Pokémon
        foreach ($trivias as $i => $t) {
            $pokemon = $api->getPokemonById($t["id_pokemon"]);
            $trivias[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
        }

        foreach ($clasificar as $i => $t) {
            $pokemon = $api->getPokemonById($t["id_pokemon"]);
            $clasificar[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
        }
        
        foreach ($adivinar as $i => $t){
            $pokemon = $api->getPokemonById($t["id_pokemon"]);
            $adivinar[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
        }

        $params = [
            "trivias" => $trivias,
            "clasificar" => $clasificar,
            "adivinar" => $adivinar
        ];

        require __DIR__ . '/../templates/gestionarJuegos.php';
    }

    public function mostrarTools()
    {
        $params = [];

        try {
            $mUsuario = new Usuario();
            $params["usuarios"] = $mUsuario->listarUsuarios();
        } catch (Exception $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/DevTools.php';
    }


}
