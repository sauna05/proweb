<?php
require "conexion.php";

class Usuarios {
    private $nombre;
    private $email;
    private $contrasena;

    // Constructor
    public function __construct($nombre, $email, $contrasena) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }

    // Método para registrar un nuevo usuario en la base de datos
    public function registrarUsuario() {
        try {
            // Conectar a la base de datos
            $conexion = new Conexion();

            // Preparar la consulta SQL
            $consulta = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (:nombre, :email, :contrasena)";
            $stm = $conexion->conexion->prepare($consulta);

            // Vincular los parámetros
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':email', $this->email);
            $stm->bindParam(':contrasena', $this->contrasena);

            // Ejecutar la consulta
            $stm->execute();

            return true; // Indicar éxito al registrar el usuario
        } catch (PDOException $ex) {
            // Manejar errores adecuadamente
            echo "Error al registrar usuario: " . $ex->getMessage();
            return false; // Indicar fallo al registrar el usuario
        }
    }
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Crear una nueva instancia de Usuarios con los valores de POST
    $nuevo_usuario = new Usuarios($nombre, $email, $contrasena);

    // Registrar el nuevo usuario en la base de datos
    if ($nuevo_usuario->registrarUsuario()) {
        // Redirigir a la página de éxito o a donde sea necesario
        header('Location: login.php');
        exit;
    } else {
       
    }
}

