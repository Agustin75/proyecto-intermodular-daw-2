<?php

class Adivinar
{
    /** @var PDO PDO database connection */
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

    public function crearAdivinanza($idPokemon, $tipo, $pista1, $pista2, $pista3)
    {

        // 1. Verify the Pokémon isn't used in other games
        $sqlCheck = " 
            SELECT id_pokemon FROM j_adivinanza WHERE id_pokemon = :id
            UNION
            SELECT id_pokemon FROM j_trivia_enunciado WHERE id_pokemon = :id
            UNION
            SELECT id_pokemon FROM j_clasificar WHERE id_pokemon = :id
        ";

        $stmt = $this->conexion->prepare($sqlCheck);
        $stmt->bindParam(':id', $idPokemon);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false; // Pokémon already used
        }

        // 2. Insert prompt
        $sqlInsertAdivanza = "
            INSERT INTO j_adivinanza (id_pokemon, id_tipo, pista1, pista2, pista3)
            VALUES (:id_pkmn, :tipo, :uno, :dos, :tres)
        ";

        $stmt = $this->conexion->prepare($sqlInsertAdivanza);
        $stmt->bindParam(':id_pkmn', $idPokemon);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':uno', $pista1);
        $stmt->bindParam(':dos', $pista2);
        $stmt->bindParam(':tres', $pista3);
        $stmt->execute();

        $idAdivinanza = $this->conexion->lastInsertId();

      
        return $idAdivinanza;
    }

     
    public function obtenerAdivinanza($idAdivinanza)
    {
        // Get prompt
        $sql = "SELECT * FROM j_adivinanza WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idAdivinanza);
        $stmt->execute();
        $enunciado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$enunciado) return null;

        // Get hints
        $sqlOpciones = "
            SELECT pista1, pista2, pista3
            FROM j_adivinanza
            WHERE id = :id
        ";

        $stmt = $this->conexion->prepare($sqlOpciones);
        $stmt->bindParam(':id', $$idAdivinanza);
        $stmt->execute();
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "enunciado" => $enunciado,
            "opciones" => $opciones
        ];
    }

     
    public function editarAdivinanza($idPokemon, $idAdivinanza,  $pista1, $pista2, $pista3)
    {
        try {
            $this->conexion->beginTransaction();

            // 1) Verificar que el Pokémon no esté usado en otros juegos o en otra trivia distinta a esta
            $sqlCheck = "
                SELECT id_pokemon FROM j_adivinanza WHERE id_pokemon = :idPokemon
                UNION
                SELECT id_pokemon FROM j_trivia_enunciado WHERE id_pokemon = :idPokemon AND id != :idTrivia
                UNION
                SELECT id_pokemon FROM j_clasificar WHERE id_pokemon = :idPokemon
            ";
            $stmt = $this->conexion->prepare($sqlCheck);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':idTrivia', $idAdivinanza);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->conexion->rollBack();
                return false; // Pokémon ya está en uso
            }

            // 2) Actualizar enunciado
            $sqlUpdate = "
                UPDATE j_trivia_enunciado
                SET id_pokemon = :idPokemon, pista1 = :uno, pista2 = :dos, pista3 = :tres
                WHERE id = :idTrivia
            ";
            $stmt = $this->conexion->prepare($sqlUpdate);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':uno', $pista1);
            $stmt->bindParam(':dos', $pista2);
            $stmt->bindParam(':tres', $pista3);
            $stmt->execute();

          

           
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    
    public function eliminarAdivinanza($idAdivinanza)
    {
        // 1. Delete relations
        $sql = "DELETE FROM j_adivinanza WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idAdivinanza);
        $stmt->execute();



        return true;
    }

 public function obtenerJuegosSinCompletar(int $idUsuario) : array
    {
        $sql = "SELECT * FROM j_adivinanza
                WHERE id_pokemon NOT IN (
                    SELECT id_pokemon FROM pokemon_usuario WHERE id_usuario = :idUsuario
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
       public function obtenerTiposAdivinar() : array
    {
        $sql = "SELECT * FROM j_tipo_adivinanza";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>