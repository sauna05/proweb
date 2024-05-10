<?php
session_start(); // Iniciar la sesión para poder usar $_SESSION

include('conexion.php');

$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    try {
        $conexion = new Conexion();
        $consulta = "SELECT id FROM Usuarios WHERE email = :correo AND contrasena = :contrasena";
        $stm = $conexion->conexion->prepare($consulta);
        $stm->bindParam(':correo', $correo);
        $stm->bindParam(':contrasena', $contrasena);
        $stm->execute();

        $verificar_usuario = $stm->fetch(PDO::FETCH_ASSOC);
        if ($verificar_usuario) {
            $_SESSION['id_usuario'] = $verificar_usuario['id'];
            header('Location: menu.php'); // Redirigir al usuario a la página de menú
            exit;
        } else {
            $mensaje = "Credenciales incorrectas";
        }
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <label for="correo">Correo</label><br>
            <input type="email" name="correo" placeholder="Ingrese su correo" required><br>
            <label for="contrasena">Contraseña</label><br>
            <input type="password" name="contrasena" placeholder="Ingrese su contraseña" required><br>
            <input type="submit" value="Iniciar sesión">
        </form>
        <?php echo $mensaje; ?>
    </div>
</body>
</html>