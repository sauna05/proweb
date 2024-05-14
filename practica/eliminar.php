<?php
require "conexion.php";
session_start();
$id_usuario = $_SESSION['id_usuario'] ?? null;
$id_producto = $_GET['id_producto'] ?? null;

if (!$id_usuario || !$id_producto) {
    header('Location: menu.php');
    exit;
}

try {
    $conexion = new Conexion();
    $sql = "DELETE FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stm = $conexion->conexion->prepare($sql);
    $stm->bindParam(':id_usuario', $id_usuario);
    $stm->bindParam(':id_producto', $id_producto);
    $stm->execute();  
    header('Location: vercarrito.php');
} catch (PDOException $ex) {
    // Manejar la excepción, si es necesario
    return false;
}
