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
    
public function verPokemon()
{
    $mApi = new PokeAPI();

    // Recibir ID desde el script JS
    $id = isset($_GET["pokemonId"]) ? intval($_GET["pokemonId"]) : 0;

    if ($id <= 0) {
        echo "ID inválido";
        exit;
    }

    // Obtener datos del Pokémon
    $pokemon = $mApi->getPokemonById($id);

    if (empty($pokemon)) {
        echo "No se encontró el Pokémon";
        exit;
    }

    // Datos básicos
    $nombre = ucfirst($pokemon["name"]);
    $tipos = array_map(fn($t) => $t["type"]["name"], $pokemon["types"]);
    $imagenNormal = $pokemon["sprites"]["front_default"] ?? "";
    $imagenShiny = $pokemon["sprites"]["front_shiny"] ?? "";
    $grito = $pokemon["cries"]["latest"] ?? "";
    $generacion = $pokemon["generation"] ?? 0;

    // Descripción en español
    $descripcion = $mApi->getPokemonDescriptionEs($id);

    // Parámetros para la vista
    $params = [
        "id" => $id,
        "nombre" => $nombre,
        "tipos" => $tipos,
        "imagenes" => [
            "normal" => $imagenNormal,
            "shiny" => $imagenShiny
        ],
        "grito" => $grito,
        "descripcion" => $descripcion,
        "generacion" => $generacion
    ];

    require __DIR__ . '/../templates/verPokemon.php';
}


}
