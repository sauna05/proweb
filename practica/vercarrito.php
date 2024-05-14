<?php
require 'conexion.php';
session_start();
if(!isset($_SESSION['id_usuario'])){
    header('location:login.php');
    exit;
}
   
$id_usuario=$_SESSION['id_usuario'];

function  Productoscarrito(){
    global $id_usuario; // Hacer la variable $id_usuario global para poder usarla dentro de la función

    $sql="SELECT p.nombre AS nombre_producto, 
    SUM(p.precio * c.cantidad) AS total_precio, 
    SUM(c.cantidad) AS cantidad_comprada,
    c.id_producto
FROM productos p
INNER JOIN carrito c ON p.id = c.id_producto
WHERE c.id_usuario = :id_usuario
GROUP BY p.nombre, c.id_producto";

    $conexion = new Conexion();
    $stm=$conexion->conexion->prepare($sql);
    $stm->bindParam(':id_usuario',$id_usuario);
  
    $stm->execute();
    $carrito=$stm->fetchAll(PDO::FETCH_ASSOC); // Utilizar fetchAll() para obtener todos los resultados

    return $carrito; // Devolver los resultados de la consulta
}
function cantidadPagar(){
    global $id_usuario; // Hacer la variable $id_usuario global para poder usarla dentro de la función

    $sql="
    SELECT SUM(p.precio * c.cantidad) AS total_A_pagar 
    FROM productos p
    INNER JOIN carrito c ON p.id = c.id_producto
    WHERE c.id_usuario = :id_usuario";

    $conexion = new Conexion();
    $stm=$conexion->conexion->prepare($sql);
    $stm->bindParam(':id_usuario',$id_usuario);
    $stm->execute();
    $total=$stm->fetch(PDO::FETCH_ASSOC); 

    return $total['total_A_pagar']?? 0; 
}

$productos_carrito = Productoscarrito(); // Obtener los productos del carrito
$total_a_pagar=cantidadPagar();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #45a049;
}
        
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Total</th>
            <th>Deselecionar</th>
        </tr>
        <?php foreach($productos_carrito as $producto): ?>
        <tr>
            <td><?php echo $producto['nombre_producto']  ?></td>
            <td><?php echo $producto['cantidad_comprada'] ?></td>
            <td><?php echo $producto['total_precio']  ?></td>
            <td><a href="eliminar.php?id_producto=<?php echo $producto['id_producto']; ?>"> ❌ </a></td> 
        </tr>
        <?php endforeach; ?>
    </table>
    <h2>Total a Pagar: <?php echo $total_a_pagar;
                                
    ?>
    
    <a href="menu.php" class="button">⬅️ seguir comprando </a>
</body>
</html>
