<?php

class Validacion
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }

    /**
     * Inserts a new Token for account activation
     * 
     * @param int $idUsuario The id of the User assigned to the token
     * @param string $token The token to be checked
     * @param int $fechaLimite How long the token will remain active for
     * @return bool returns true if it was successful, false otherwise
     */
    public function guardarToken(int $idUsuario, string $token, int $fechaLimite) : bool
    {
        // We insert the new Token
        $sql = "INSERT INTO token_validacion (id_user, token, valido_hasta)
                VALUES (:idUsuario, :token, :fechaLimite)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':fechaLimite', $fechaLimite);
        return $stmt->execute();
    }

    /**
     * Obtaines the information of a specific Token
     * 
     * @param string $token
     * @return array|false Object PDO with all the informacion from the selected Token, or false if no token was found
     */
    public function confirmarToken(string $token)
    {
        $sql = "SELECT * FROM token_validacion WHERE token = :token";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes the specified Token
     *
     * @param string $token
     * @return bool Returns whether the deletion was successful
     */
    public function eliminarToken(string $token): bool
    {
        $sql = "DELETE FROM token_validacion WHERE token = :token";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }
}
