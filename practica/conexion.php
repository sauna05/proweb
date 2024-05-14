<?php
class Conexion {
    public $url = 'mysql:host=localhost;dbname=tienda_online';
    public $usuario = 'root';
    public $contrasena = '';
    public $conexion; // Declarar la propiedad $conexion

    public function __construct() {
        try {
            $this->conexion = new PDO($this->url, $this->usuario, $this->contrasena);
            //$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer el modo de error para lanzar excepciones
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
}
