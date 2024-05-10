<?php
include('conexion.php');


session_start();

if(!isset($_SESSION['id_usuario'])){
    header('location:login.php');
    exit;
}
   
$id_usuario=$_SESSION['id_usuario'];




function AgregarCarrito(){
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        // Redirigir si no se proporciona un ID válido
        header('Location: menu.php');
        exit;
    }
    $cantidad=1;
   global $id_usuario;
   
   $sql="INSERT into carrito (id_usuario,id_producto,cantidad) values
                              (:id_usuario,:id_producto,:cantidad)";

   $conexion = new Conexion();
   $stm=$conexion->conexion->prepare($sql);
   $stm->bindParam(':id_usuario',$id_usuario);
   $stm->bindParam(':id_producto',$id);
   $stm->bindParam(':cantidad',$cantidad);
   $stm->execute();
   $carrito_agregar=$stm->fetchAll(PDO::FETCH_ASSOC); // Utilizar fetchAll() para obtener todos los resultados

   return $carrito_agregar;
   

}


function Productoscarrito(){
    global $id_usuario; // Hacer la variable $id_usuario global para poder usarla dentro de la función

    $sql="SELECT p.nombre AS nombre_producto, SUM(p.precio * c.cantidad) AS total_precio , SUM(c.cantidad) AS cantidad_comprada
    FROM productos p
    INNER JOIN carrito c ON p.id = c.id_producto
    WHERE c.id_usuario = :id_usuario
    GROUP BY p.nombre";

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
    $total=$stm->fetch(PDO::FETCH_ASSOC); // Utilizar fetch() para obtener un solo resultado

    return $total['total_A_pagar']; // Devolver el total a pagar
}

$carrito_agregar=AgregarCarrito();
$productos_carrito = Productoscarrito(); // Obtener los productos del carrito
$total_a_pagar = cantidadPagar(); // Obtener el total a pagar

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Cantidad </th>
            <th>Precio Total</th>
            
        </tr>
        <?php foreach($productos_carrito as $producto): ?>
        <tr>
            <td><?php echo $producto['nombre_producto']; ?></td>
            <td><?php echo $producto['cantidad_comprada'] ?></td>
            <td><?php echo $producto['total_precio']; ?></td>
        
        </tr>
        <?php endforeach; ?>
    </table>
    <h2>Total a Pagar: <?php echo $total_a_pagar; ?></h2>
    <br>
    <a href="menu.php">Volver al menu </a>
</body>
</html>