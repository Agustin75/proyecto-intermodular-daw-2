<?php

class PokemonUsuario
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

    /**
     * Inserts a new Pokemon-User pair (By default, favorito will be false)
     * 
     * @param int $idUsuario ID of the User that just obtained a new Pokémon (Typically the registered user)
     * @param int $idPokemon ID of the Pokémon the User has just obtained
     * @return bool whether the pair was added successfully
     */
    public function insertarRegistro(int $idUsuario, int $idPokemon): bool
    {
        try {
            $sql = "INSERT INTO pokemon_usuario (id_pokemon, id_usuario)
                VALUES (:idPokemon, :idUsuario)";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Favorites or unfavorites a Pokemon
     * 
     * @param int $idUsuario - ID of the User to check for
     * @param int $idPokemon - ID of the Pokemon to update
     * @param bool $favorito - Whether we set the Pokemon as favorite or unfavorite it
     * @return bool Whether the assignment was successful
     */
    public function asignarFavorito(int $idUsuario, int $idPokemon, bool $favorito): bool
    {
        try {
            $sql = "UPDATE pokemon_usuario SET favorito = :favorito WHERE id_pokemon = :idPokemon AND id_usuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':favorito', $favorito, PDO::PARAM_BOOL);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Returns the list of Pokemon the User has obtained
     * 
     * @param int $idUsuario - ID of the user to obtain the Pokemon from
     * @param bool $favoritos - Whether we return the list of favorites or all the pokemon
     * @return array The list of all the Pokemon owned or favorited by the user
     */
    public function obtenerPokemonUsuario(int $idUsuario, bool $favoritos): array
    {
        $sql = "SELECT * FROM pokemon_usuario where id_usuario = :idUsuario";
        if ($favoritos) {
            $sql .= " AND favorito = TRUE";
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
