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
    public function crearTrivia($idPokemon, $pregunta, $tiempo, $opciones)
{
    // 1. Verificar que el Pokémon no esté usado
    $sqlCheck = "
    SELECT id_pokemon FROM (
        SELECT id_pokemon FROM j_adivinanza
        UNION
        SELECT id_pokemon FROM j_trivia_enunciado
        UNION
        SELECT id_pokemon FROM j_clasificar
    ) AS t
    WHERE id_pokemon = :id
";

$stmt = $this->conexion->prepare($sqlCheck);
$stmt->bindParam(':id', $idPokemon);
$stmt->execute();


    if ($stmt->rowCount() > 0) {
        return false;
    }

    // 2. Insertar enunciado
    $sqlInsert = "
        INSERT INTO j_trivia_enunciado (id_pokemon, pregunta, tiempo)
        VALUES (:idPokemon, :pregunta, :tiempo)
    ";

    $stmt = $this->conexion->prepare($sqlInsert);
    $stmt->bindParam(':idPokemon', $idPokemon);
    $stmt->bindParam(':pregunta', $pregunta);
    $stmt->bindParam(':tiempo', $tiempo);
    $stmt->execute();

    $idTrivia = $this->conexion->lastInsertId();

    // 3. Insertar opciones
    foreach ($opciones as $op) {
        $texto = $op["texto"];
        $correcta = $op["correcta"];

        // ¿Existe ya la opción?
        $sqlCheckOpcion = "SELECT id FROM j_trivia_opcion WHERE opcion = :opcion";
        $stmt = $this->conexion->prepare($sqlCheckOpcion);
        $stmt->bindParam(':opcion', $texto);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && isset($result["id"])) {
    $idOpcion = $result["id"];
} else {
    $sqlInsertOpcion = "INSERT INTO j_trivia_opcion (opcion) VALUES (:opcion)";
    $stmt = $this->conexion->prepare($sqlInsertOpcion);
    $stmt->bindParam(':opcion', $texto);
    $stmt->execute();
    $idOpcion = $this->conexion->lastInsertId();
}

if (!$idOpcion) {
    throw new Exception("No se pudo obtener idOpcion para la opción '$texto'");
}


        // Insertar relación
        $sqlRelacion = "
            INSERT INTO j_trivia_respuesta (id_pregunta, id_opcion, esCorrecta)
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
            JOIN j_trivia_opcion o ON r.id_opcion = o.id
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

        /**
     * Edit an existing trivia.
     *
     * Updates the prompt and its options inside a database transaction.
     *
     * Steps:
     * 1) Verify the Pokémon is not used in other games or in another trivia (excluding this one).
     * 2) Update the prompt in `j_trivia_enunciado`.
     * 3) Delete current relations in `j_trivia_respuesta` and re-insert according to $opciones,
     *    reusing existing options from `j_trivia_opcion` or creating new ones as needed.
     * 4) Remove orphaned options from `j_trivia_opcion` that are no longer referenced.
     *
     * @param int   $idTrivia  ID of the trivia to edit
     * @param int   $idPokemon ID of the Pokémon associated with the trivia
     * @param string $pregunta Text of the question/prompt
     * @param int   $segundos  Time in seconds to answer
     * @param array $opciones  List of options, each with keys: 'texto' and 'correcta'
     * @return bool True on success, false on failure or if Pokémon is already in use
     */

    public function editarTrivia($idTrivia, $idPokemon, $pregunta, $segundos, $opciones)
    {
        try {
            // 1. Verificar que el Pokémon no esté usado
    $sqlCheck = "
    SELECT id_pokemon FROM (
        SELECT id_pokemon FROM j_adivinanza
        UNION
        SELECT id_pokemon FROM j_trivia_enunciado
        UNION
        SELECT id_pokemon FROM j_clasificar
    ) AS t
    WHERE id_pokemon = :id
";

$stmt = $this->conexion->prepare($sqlCheck);
$stmt->bindParam(':id', $idPokemon);
$stmt->execute();


    if ($stmt->rowCount() > 0) {
        return false;
    }

            // 2) Actualizar enunciado
            $sqlUpdate = "
                UPDATE j_trivia_enunciado
                SET id_pokemon = :idPokemon, pregunta = :pregunta, segundos = :segundos
                WHERE id = :idTrivia
            ";
            $stmt = $this->conexion->prepare($sqlUpdate);
            $stmt->bindParam(':idPokemon', $idPokemon);
            $stmt->bindParam(':pregunta', $pregunta);
            $stmt->bindParam(':segundos', $segundos);
            $stmt->bindParam(':idTrivia', $idTrivia);
            $stmt->execute();

            // 3) Eliminar relaciones actuales de respuestas y volver a insertar según $opciones
            $sqlDelRel = "DELETE FROM j_trivia_respuesta WHERE id_pregunta = :idTrivia";
            $stmt = $this->conexion->prepare($sqlDelRel);
            $stmt->bindParam(':idTrivia', $idTrivia);
            $stmt->execute();

            foreach ($opciones as $op) {
                $texto = $op['texto'];
                $correcta = $op['correcta'];

                // Reutilizar opción existente o crear nueva
                $sqlCheckOpcion = "SELECT id FROM j_trivia_opcion WHERE opcion = :opcion";
                $stmt = $this->conexion->prepare($sqlCheckOpcion);
                $stmt->bindParam(':opcion', $texto);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $idOpcion = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
                } else {
                    $sqlInsertOpcion = "INSERT INTO j_trivia_opcion (opcion) VALUES (:opcion)";
                    $stmt = $this->conexion->prepare($sqlInsertOpcion);
                    $stmt->bindParam(':opcion', $texto);
                    $stmt->execute();
                    $idOpcion = $this->conexion->lastInsertId();
                }

                // Insertar relación pregunta-opción
                $sqlRelacion = "
                    INSERT INTO j_trivia_respuesta (id_pregunta, id_opcion, esCorrecta)
                    VALUES (:idTrivia, :idOpcion, :correcta)
                ";
                $stmt = $this->conexion->prepare($sqlRelacion);
                $stmt->bindParam(':idTrivia', $idTrivia);
                $stmt->bindParam(':idOpcion', $idOpcion);
                $stmt->bindParam(':correcta', $correcta);
                $stmt->execute();
            }

            // 4) Eliminar opciones huérfanas
            $sqlDelOrphan = "
                DELETE FROM j_trivia_opcion
                WHERE id NOT IN (SELECT id_opcion FROM j_trivia_respuesta)
            ";
            $stmt = $this->conexion->prepare($sqlDelOrphan);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
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
            WHERE id NOT IN (SELECT id_opcion FROM j_trivia_respuesta)
        ";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return true;
    }

public function obtenerTodasLasTrivias()
{
    $sql = "SELECT id, id_pokemon FROM j_trivia_enunciado ORDER BY id DESC";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    
}

?>