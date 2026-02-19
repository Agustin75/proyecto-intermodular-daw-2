<?php
class WikiController extends Controller
{
    public function verWiki()
    {
        try {
            $mApi = new PokeAPI();

            $params = array(
                "pokemon_list" => $mApi->getAllPokemon(),
                "type_list" => $mApi->getTypesList(),
                "num_generations" => $mApi->getNumGenerations(),
            );
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando obtener la lista de Pokémon.");
        }

        require __DIR__ . '/../templates/verWiki.php';
    }

    public function verPokemon()
    {
        try {
            // Recibir ID desde el script JS
            $id = isset($_GET["pokemonId"]) ? intval($_GET["pokemonId"]) : 0;

            if ($id <= 0) {
                echo "ID inválido";
                exit;
            }

            $mApi = new PokeAPI();

            // Obtener datos del Pokémon
            $pokemon = $mApi->getPokemonById($id);

            if (empty($pokemon)) {
                echo "No se encontró el Pokémon";
                exit;
            }

            // Datos básicos
            $nombre = $mApi->getPokemonName($id);
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
        } catch (Throwable $e) {
            $this->handleError($e, "Hubo un error intentando obtener la información del Pokémon.");
        }
        require __DIR__ . '/../templates/verPokemon.php';
    }
}
