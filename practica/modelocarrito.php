<?php
session_start();
require 'conexion.php';

class Carrito {
    public $cantidad;
    public $id_usuario;
    public $id_producto;

    public function __construct($cantidad, $id_usuario, $id_producto) {
        $this->cantidad = $cantidad;
        $this->id_usuario = $id_usuario;
        $this->id_producto = $id_producto;
    }
    //metodo de insercion de producto al carrioto
    public function agregarCarrito() {
        try {
            $conexion = new Conexion();
            $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, :cantidad)";
            $stm = $conexion->conexion->prepare($sql);
            $stm->bindParam(':id_usuario', $this->id_usuario);
            $stm->bindParam(':id_producto', $this->id_producto);
            $stm->bindParam(':cantidad', $this->cantidad);
            $stm->execute();  
            return true;
        } catch (PDOException $ex) {
            error_log("Error al agregar producto al carrito: " . $ex->getMessage());
            return false;
        }
    }
}
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
}
//verifcar el id que se valido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: menu.php');
}
// Obtener los datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_GET['id'];
$cantidad = 1;
// Crear una instancia de la clase Carrito y agregar el producto
$nuevocarrito = new Carrito($cantidad, $id_usuario, $id_producto);
if ($nuevocarrito->agregarCarrito()) {
    header('Location: menu.php');
}
