<?php
class Usuario {
      private PDO $conexion;

    public function __construct() {
        $this->conexion = Database::getConnection();
    }
    public function cambiarImagen($imagen, $id) {
        $sql = "UPDATE usuario
        SET imagen = :imagen,
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':imagen', $imagen);
        return $stmt->execute();
    }
    
    public function verLibroB($idLibro) {
        $consulta = "SELECT * FROM biblioteca.listaLibros WHERE idLibro=:idLibro";
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(':idLibro', $idLibro);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
    
    public function buscarLibrosTitulo($titulo) {
        $consulta = "SELECT * FROM biblioteca.listaLibros WHERE titulo=:titulo";
        
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(':titulo', $titulo);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>