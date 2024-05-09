<?php 
class Conexion{
    public $url='mysql:host=localhost;dbname=db_contactos';
    public $usuario='root';
    public $contrasena='';

    public function __construct(){
        try{
            $conexion = new PDO($this->url,$this->usuario,$this->contrasena);
            

        }catch(PDOException $ex){
            echo($ex->getMessage());
        }

    }

}

$conexion = new Conexion();

