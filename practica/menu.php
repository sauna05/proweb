<?php
session_start();

include('conexion.php');
if(!isset($_SESSION['id_usuario'])){
    header('location:login.php');
    exit;
}

$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

try {
    $conexion = new Conexion();
    $consulta = "SELECT DISTINCT categoria FROM Productos";
    $stm = $conexion->conexion->prepare($consulta);
    $stm->execute();
    $categorias = $stm->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $ex) {
    echo "Error: " . $ex->getMessage();
}

if ($categoria == '') {
    try {
        $conexion = new Conexion();
        $consulta = "SELECT * FROM Productos";
        $stm = $conexion->conexion->prepare($consulta);
        $stm->execute();
        $productos = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
    }
} else {
    try {
        $conexion = new Conexion();
        $consulta = "SELECT * FROM Productos WHERE categoria = :categoria";
        $stm = $conexion->conexion->prepare($consulta);
        $stm->bindParam(':categoria', $categoria);
        $stm->execute();
        $productos = $stm->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Productos</title>
</head>
<body>
    <div class="container">
        <h2>Seleccione una categoría:</h2>
        <select name="categoria" id="categoria" onchange="window.location.href='?categoria='+this.value">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $cat) { ?>
                <option value="<?php echo $cat; ?>" <?php echo $categoria == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
            <?php } ?>
        </select>

        <h2>Lista de Productos:</h2>
        <table border="0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Enviar al carrito</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['precio']; ?></td>
                        <td>    <a href="carrito.php?id=<?php echo $producto['id']; ?>">Comprar_producto</a></td>
                        
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <a href="cerrar.php">cerrar session </a>
    <br>
    <a href="vercarrito.php">ver carrito</a>


    <br>
 
</body>
</html>