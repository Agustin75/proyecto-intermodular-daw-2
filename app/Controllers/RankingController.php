<?php
class RankingController extends Controller
{
    /**
     * Handles the creation of a Clasificar game
     */
    public function mostrarRanking()
    {
        $errores = [];
        $params = [];

        // We obtain the expected variables
        $page = recoge("page");
        $limit = recoge("limit");

        // We validate them
        cNum($page, "page", $errores, false);
        cNum($limit, "limit", $errores, false);

        // If there were no errors
        if (empty($errores)) {
            // We cast the variable to int if they exist, since they're not required
            $page = empty($page) ? 0 : intval($page);

            if (!empty($limit)) {
                $limit = intval($limit);
            }
            
            // We get the Ranking of users by Pokemon obtained
            $mRanking = new Ranking();
            $mPokemonUsuario = new PokemonUsuario();
            $mPokeAPI = new PokeAPI();
            $users = [];
            if (empty($limit)) {
                $users = $mRanking->obtenerRankingPokemon($page);
            } else {
                $users = $mRanking->obtenerRankingPokemon($page, $limit);
            }

            // We add to $params an array of users that will show the Name, Amount of Pokemon and the player's favorite Pokemon
            $params["users"] = [];
            foreach ($users as $index => $user) {
                // We save the user's information to be displayed in the ranking's page
                $params["users"][$index]["name"] = $user["nombre"];
                $params["users"][$index]["amount"] = $user["num_pokemon"];
                $params["users"][$index]["favorites"] = [];

                // We obtain the user's favorite Pokemon
                $favorites = $mPokemonUsuario->obtenerPokemonUsuario($user["id"], true);
                // We loop through all the favorites and add it to the array
                foreach ($favorites as $favoritePokemon) {
                    $params["users"][$index]["favorites"][] = $mPokeAPI->getPokemonNormalSprite($favoritePokemon["id_pokemon"]);
                }
            }

            // EXPANSION: We would now obtain a different, score-related ranking here
        } else {
            $params["mensaje"] = implode('<br>', $errores);
        }

        // We load the Ranking view
        echo("Página Rankings en proceso de creación.");
        // require __DIR__ . '/../templates/ranking.php';
    }
}
