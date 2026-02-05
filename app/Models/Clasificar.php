<?php

class Clasificar
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

    /**
     * Inserts a new Clasificar game
     * 
     * @param int $idPokemon ID of the Pokémon associated with the Clasificar game
     * @param string $idTipoClasificacion ID of the type of Clasificar game (type or generation)
     * @param int $numPokemon Amount of Pokemon the game will show
     * @param int $numOpciones Amount of options that will be shown
     * @param int $numRequerido Amount of classificactions the player needs to answer correctly to obtain the Pokemon
     * @return int|false ID of the created Clasificar game, or false if the Pokémon is already in use
     */
    public function crearClasificar(int $idPokemon, int $idTipoClasificacion, int $numPokemon, int $numOpciones, int $numRequerido): int|false
    {
        $sql = "INSERT INTO j_clasificar (id_pokemon, id_tipo, num_pokemon, num_opciones, num_requerido)
                VALUES (:idPokemon, :idTipo, :numPokemon, :numOpciones, :numRequerido)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idPokemon', $idPokemon);
        $stmt->bindParam(':idTipo', $idTipoClasificacion);
        $stmt->bindParam(':numPokemon', $numPokemon);
        $stmt->bindParam(':numOpciones', $numOpciones);
        $stmt->bindParam(':numRequerido', $numRequerido);
        $stmt->execute();

        $idClasificar = $this->conexion->lastInsertId();

        return $idClasificar;
    }

    /**
     * Obtaines a specific Clasificar game
     * 
     * @param int $idClasificar
     * @return array|false Object PDO with all the informacion from the selected Clasificar game, or false if no game was found
     */
    public function obtenerClasificar($idClasificar): array | false
    {
        $sql = "SELECT * FROM j_clasificar WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idClasificar);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns the list of all Clasificar games
     * 
     * @return array The list of all the Clasificar games
     */
    public function listarJuegosClasificar(): array
    {
        $sql = "SELECT * FROM j_clasificar";
        $stmt = $this->conexion->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Edits the specified Clasificar game
     * 
     * @param int   $idClasificar  ID of the classification to edit
     * @param int   $idPokemon ID of the Pokémon associated with the classification
     * @param int   $idTipoClasificacion ID of the type of classification (type or generation)
     * @param int   $numPokemon Amount of Pokemon the game will show
     * @param int   $numOpciones Amount of options that will be shown
     * @param int   $numRequerido Amount of classifications the player needs to answer correctly to obtain the Pokemon
     * @return bool True on success, false on failure or if Pokémon is already in use
     */

    public function editarClasificar(int $idClasificar, int $idPokemon, int $idTipoClasificacion, int $numPokemon, int $numOpciones, int $numRequerido): bool
    {
        try {
            // We update the classification
            $sql = "UPDATE j_clasificar
                    SET id_pokemon = :idPokemon, id_tipo = :idTipo, num_pokemon = :numPokemon, num_opciones = :numOpciones, num_requerido = :numRequerido
                    WHERE id = :idClasificar";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':idTipo', $idTipoClasificacion);
            $stmt->bindParam(':numPokemon', $numPokemon);
            $stmt->bindParam(':numOpciones', $numOpciones);
            $stmt->bindParam(':numRequerido', $numRequerido);
            $stmt->bindParam(':idClasificar', $idClasificar);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Deletes the specified Clasificar game
     *
     * @param int $idClasificar
     * @return bool Returns whether the deletion was successful
     */
    public function eliminarClasificar($idClasificar): bool
    {
        $sql = "DELETE FROM j_clasificar WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idClasificar);
        return $stmt->execute();
    }

    /**
     * Returns whether the Pokemon is already used for a Clasificar game
     * 
     * @param int $idPokemon id of the Pokemon to check
     * @param int $idClasificar id of the Clasificar game to exclude for the check (If we're editing a game, and the Pokmeon hasn't changed, it shouldn't count as a repeated Pokemon)
     * @return array|false the results of the query, or false if no matches were found
     */
    public function isPokemonUsed(int $idPokemon, int $idClasificar = -1): array | false
    {
        $sql = "SELECT * FROM j_clasificar WHERE id_pokemon = :idPokemon AND id != :idClasificar";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idPokemon', $idPokemon);
        $stmt->bindParam(':idClasificar', $idClasificar);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
