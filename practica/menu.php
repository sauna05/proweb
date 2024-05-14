<?php
session_start();

require 'conexion.php';

// Verificar la sesión
if(!isset($_SESSION['id_usuario'])){
    header('location:login.php');
    
}
$id_usuario = $_SESSION['id_usuario'];
try {
    // Consultar la cantidad de productos en el carrito del usuario
    $sql = "SELECT COUNT(*) AS cantidad FROM carrito WHERE id_usuario=:id_usuario";
    $conexion = new Conexion();
    $stm = $conexion->conexion->prepare($sql);
    $stm->bindParam(':id_usuario',$id_usuario);
    $stm->execute();
    $cantidad_carrito = $stm->fetch(PDO::FETCH_ASSOC)['cantidad'] ?? 0;
} catch (PDOException $ex) {
    $cantidad_carrito = 0; 
}

// Obtener la categoría seleccionada si existe
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

try {
    $conexion = new Conexion();
    $consulta = "SELECT DISTINCT categoria FROM Productos";
    $stm = $conexion->conexion->prepare($consulta);
    $stm->execute();
    $categorias = $stm->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $ex) {
   return false;
    
}

try {
    $consulta_productos = ($categoria == '') ? "SELECT * FROM Productos" : "SELECT * FROM Productos WHERE categoria = :categoria";
    $stm = $conexion->conexion->prepare($consulta_productos);

    if ($categoria != '') {
        $stm->bindParam(':categoria', $categoria);
    }
    $stm->execute();
    $productos = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Agregado para posicionar correctamente los botones */
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        select {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: #fff;
            background-color: blue;
            border-radius: 5px;
            margin-right: 10px;
        }

        a:hover {
            background-color: #45a049;
        }

        /* Estilos para los botones */
        .ver-carrito {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .cantidad {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: green;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: bold;
       }
        .cerrar-sesion {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
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
        <table>
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
                        <td><a href="modelocarrito.php?id=<?php echo $producto['id']; ?>">Comprar</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Botón "Ver Carrito" -->
    <a href="vercarrito.php" class="ver-carrito">Ver Carrito</a>

    <!-- Botón "Cerrar Sesión" -->
    <a href="cerrar.php" class="cerrar-sesion">Cerrar Sesión</a>
</body>
<h4 class="cantidad"><?php echo $cantidad_carrito;?></h4>
</html>