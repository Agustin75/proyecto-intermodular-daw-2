<?php

class Ejemplo {
    private PDO $conexion;

    public function __construct() {
        $this->conexion = Database::getConnection();
    }
}

?>
