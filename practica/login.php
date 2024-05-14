<?php
session_start();
require 'conexion.php';

class InicioSesion {
    public static function iniciarSesion($correo, $contrasena) {
        try {
            $conexion = new Conexion();
            $consulta = "SELECT id FROM Usuarios WHERE email = :correo AND contrasena = :contrasena";
            $stm = $conexion->conexion->prepare($consulta);
            $stm->bindParam(':correo', $correo);
            $stm->bindParam(':contrasena', $contrasena);
            $stm->execute();

            if ($verificar_usuario = $stm->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['id_usuario'] = $verificar_usuario['id'];
                return true;
            }
        } catch (PDOException $ex) {
            // Manejar la excepción, si es necesario
            echo "Error: " . $ex->getMessage();
        }
        return false;
    }
}
$mensaje = '';
//traer los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    if (InicioSesion::iniciarSesion($correo, $contrasena)) {
        header('Location: menu.php');
        exit;
    } else {
        $mensaje = "Correo o contraseña incorrectos. Si no estás registrado, <a href='vistausuario.html'>¡Regístrate aquí!</a>";
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
    <a href="vistausuario.html">Crear cuenta</a>
  
</body>
</html>