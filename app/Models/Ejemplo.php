<?php

class Ejemplo {
      private PDO $conexion;

    public function __construct() {
        $this->conexion = Database::getConnection();
    }

    
    
}
?>
<h1>hola mundo</h1>