<?php
/**
 * Class Trivia
 *
 * Manages basic CRUD operations related to trivia:
 * - Create a trivia (prompt + options)
 * - Retrieve a complete trivia by id
 * - Delete a trivia and clean up orphaned options
 *
 * Uses the PDO connection provided by `Database::getConnection()`.
 */
class Trivia
{
    /** @var PDO PDO database connection */
    private PDO $conexion;

    /**
     * Trivia constructor.
     * Initializes the PDO connection from the `Database` class.
     */
    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

     /* ============================================================
         1. INSERT NEW TRIVIA
         ============================================================ */
    /**
    * Create a new trivia.
    *
    * Flow:
    * 1) Verify the Pokémon is not used in other games (prevents duplicates by id_pokemon).
    * 2) Insert the prompt into `j_trivia_enunciado`.
    * 3) For each option: reuse the option if it already exists in `j_trivia_opcion`,
    *    or create it and finally insert the relation into `j_trivia_respuesta`.
    *
    * @param int $idPokemon ID of the Pokémon associated with the trivia
    * @param string $pregunta Text of the question/prompt
    * @param int $segundos Time in seconds to answer
    * @param array $opciones List of options, each with keys: 'texto' and 'correcta'
    * @return int|false ID of the created trivia, or false if the Pokémon is already in use
     */
    public function crearTrivia($idPokemon, $pregunta, $segundos, $opciones)
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
        $sqlInsertTrivia = "
            INSERT INTO j_trivia_enunciado (id_pokemon, pregunta, segundos)
            VALUES (:idPokemon, :pregunta, :segundos)
        ";

        $stmt = $this->conexion->prepare($sqlInsertTrivia);
        $stmt->bindParam(':idPokemon', $idPokemon);
        $stmt->bindParam(':pregunta', $pregunta);
        $stmt->bindParam(':segundos', $segundos);
        $stmt->execute();

        $idTrivia = $this->conexion->lastInsertId();

        // 3. Insert options (reuse existing option or create a new one)
        foreach ($opciones as $op) {
            $texto = $op["texto"];
            $correcta = $op["correcta"];

            // Does the option already exist?
            $sqlCheckOpcion = "SELECT id FROM j_trivia_opcion WHERE opcion = :opcion";
            $stmt = $this->conexion->prepare($sqlCheckOpcion);
            $stmt->bindParam(':opcion', $texto);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $idOpcion = $stmt->fetch(PDO::FETCH_ASSOC)["id"];
            } else {
                // Insert new option
                $sqlInsertOpcion = "INSERT INTO j_trivia_opcion (opcion) VALUES (:opcion)";
                $stmt = $this->conexion->prepare($sqlInsertOpcion);
                $stmt->bindParam(':opcion', $texto);
                $stmt->execute();
                $idOpcion = $this->conexion->lastInsertId();
            }

            // Insert relation in j_trivia_respuesta
            $sqlRelacion = "
                INSERT INTO j_trivia_respuesta (id_pregunta, id_opción, esCorrecta)
                VALUES (:idTrivia, :idOpcion, :correcta)
            ";

            $stmt = $this->conexion->prepare($sqlRelacion);
            $stmt->bindParam(':idTrivia', $idTrivia);
            $stmt->bindParam(':idOpcion', $idOpcion);
            $stmt->bindParam(':correcta', $correcta);
            $stmt->execute();
        }

        return $idTrivia;
    }

     /* ============================================================
         2. GET COMPLETE TRIVIA BY ID
         ============================================================ */
     /**
      * Get the complete trivia by its ID.
      *
      * Retrieves the prompt from `j_trivia_enunciado` and the associated options
      * (id, text and whether they are correct) from `j_trivia_respuesta` + `j_trivia_opcion`.
      *
      * @param int $idTrivia
      * @return array|null Array with keys 'enunciado' and 'opciones', or null if not found
      */
    public function obtenerTrivia($idTrivia)
    {
        // Get prompt
        $sql = "SELECT * FROM j_trivia_enunciado WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idTrivia);
        $stmt->execute();
        $enunciado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$enunciado) return null;

        // Get options related to the prompt
        $sqlOpciones = "
            SELECT o.id, o.opcion, r.esCorrecta
            FROM j_trivia_respuesta r
            JOIN j_trivia_opcion o ON r.id_opción = o.id
            WHERE r.id_pregunta = :id
        ";

        $stmt = $this->conexion->prepare($sqlOpciones);
        $stmt->bindParam(':id', $idTrivia);
        $stmt->execute();
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "enunciado" => $enunciado,
            "opciones" => $opciones
        ];
    }

     /* ============================================================
         3. DELETE TRIVIA
         ============================================================ */
    /**
    * Delete a trivia and clean up related data.
    *
    * Steps:
    * 1) Delete relations in `j_trivia_respuesta` for the question.
    * 2) Delete the prompt in `j_trivia_enunciado`.
    * 3) Delete orphaned options in `j_trivia_opcion` that are no longer referenced.
    *
    * @param int $idTrivia
    * @return bool Returns true (no exception thrown or error propagated here)
     */
    public function eliminarTrivia($idTrivia)
    {
        // 1. Delete relations
        $sql = "DELETE FROM j_trivia_respuesta WHERE id_pregunta = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idTrivia);
        $stmt->execute();

        // 2. Delete prompt
        $sql = "DELETE FROM j_trivia_enunciado WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idTrivia);
        $stmt->execute();

        // 3. Delete orphaned options
        $sql = "
            DELETE FROM j_trivia_opcion
            WHERE id NOT IN (SELECT id_opción FROM j_trivia_respuesta)
        ";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return true;
    }
}

?>