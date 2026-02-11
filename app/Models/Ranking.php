<?php

class Ranking
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

    /**
     * Returns the player ranking based on Pokemon
     * 
     * @param int $page The page to show (0-based index)
     * @param string $limit The amount of users to show per page
     * @return array|false array of users sorted by highest Pokemon count first, or false if a wrong combination of page/limit was received
     */
    public function obtenerRankingPokemon(int $page, int $limit = 100)
    {
        // First of all, we check to make sure we received a valid page based on the limit
        $sqlCheck = "SELECT * FROM pokemon_usuario GROUP BY id_usuario";

        $stmt = $this->conexion->prepare($sqlCheck);
        $stmt->execute();
        $numUsers = $stmt->rowCount();

        $offset = $page * $limit;

        // If we're trying to check a page with no users (ex. $offset = 2, $limit = 100, $numUsers = 40. In this case, we're trying to show users 200 to 300, but there's only 40 users that have obtained Pokémon)
        if ($offset * $limit > $numUsers) {
            // We return false
            return false;
        }

        // We obtain a list of users (excluding any admin users) with their amount of Pokemon owned
        $sql = "SELECT usuario.id, usuario.nombre, imagen, COUNT(id_pokemon) AS num_pokemon
                 FROM pokemon_usuario
                 INNER JOIN usuario ON pokemon_usuario.id_usuario=usuario.id AND nivel!=:adminLevel
                 GROUP BY id_usuario
                 ORDER BY num_pokemon DESC
                 LIMIT :limit
                 OFFSET :offset";

        $adminLevel = USER_ADMIN;
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':adminLevel', $adminLevel);
        $stmt->bindParam(':limit', $limit);
        $stmt->bindParam(':offset', $offset);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns the player ranking based on Score
     * 
     * @param int $page The page to show (0-based index)
     * @param string $limit The amount of users to show per page
     * @return array|false array of users sorted by highest Score first, or false if a wrong combination of page/limit was received
     */
    public function obtenerRankingPuntuacion(int $page, int $limit = 100)
    {
        // First of all, we check to make sure we received a valid page based on the limit
        $sqlCheck = "SELECT COUNT(*) AS num_usuarios FROM usuario";

        $stmt = $this->conexion->prepare($sqlCheck);
        $stmt->execute();
        $numUsers = $stmt->fetch(PDO::FETCH_ASSOC)["num_usuarios"];

        $offset = $page * $limit;

        // If we're trying to check a page with no users (ex. $offset = 2, $limit = 100, $numUsers = 40. In this case, we're trying to show users 200 to 300, but there's only 40 users that have obtained Pokémon)
        if ($offset * $limit > $numUsers) {
            // We return false
            return false;
        }

        // We obtain the list of users (excluding any admin users) ordered by their amount of score
        $sql = "SELECT id, nombre, puntuacion
                 FROM usuario
                 WHERE nivel!=:adminLevel
                 ORDER BY puntuacion DESC
                 LIMIT :limit
                 OFFSET :offset";

        $adminLevel = USER_ADMIN;
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':adminLevel', $adminLevel);
        $stmt->bindParam(':limit', $limit);
        $stmt->bindParam(':offset', $offset);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
