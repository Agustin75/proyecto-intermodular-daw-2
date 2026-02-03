<?php
class Usuario
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConnection();
    }
    public function crearUsuario($nombre, $contrasenya, $email, $imagen)
    {
        $sql = "INSERT INTO usuario (nombre, contrasenya, email, imagen)
        VALUES (:nombre, :psswd, :email, :img)"; //we create the sql command
        $stmt = $this->conexion->prepare($sql); //we prepare it
        $stmt->bindParam(':nombre', $nombre); //replace the values with the variables we called into the function
        $stmt->bindParam(':psswd', $contrasenya);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':img', $imagen);
        return $stmt->execute(); //run the request as well as return it

    }

    public function listarUsuarios()
    {
        $sql = "SELECT * FROM usuario ORDER BY nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function buscarUsuario($nombre)
    {
        $sql = "SELECT * FROM usuario WHERE nombre = :nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cambiarImagen($imagen, $id)
    {
        $sql = "UPDATE usuario
        SET imagen = :imagen
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':imagen', $imagen);
        return $stmt->execute();
    }

    public function elegirFavorito($id, $fav, $idpkmn)
    {
        $sql = "UPDATE pokemon_usuario
        SET favorito = :fav,
        WHERE id_usuario = :id AND id = :idpkmn";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':idpkmn', $idpkmn);
        $stmt->bindParam(':fav', $fav);
        return $stmt->execute();
    }


    public function activarUsuario($id, $act)
    {
        $sql = "UPDATE usuario
        SET activo = :act
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':act', $act);
        return $stmt->execute();
    }

     public function cambiarNombre($new, $id) : bool
    {
        $sql = "UPDATE usuario
        SET nombre = :nombre
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $new);
        return $stmt->execute();
    }
}
