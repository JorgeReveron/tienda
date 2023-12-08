<?php
session_start();
require "connection.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito de compra</title>
  <style>
    table {
      border: 1px solid black;
      text-align: center;
    }
    tr,td,th {
      border: 1px solid black;
      padding: 5px;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <th>Producto</th>
      <th>Precio</th>
      <th>Cantidad</th>
      <th>Subtotal</th>
    </tr>
    <?php
    if(isset($_SESSION["name"])){
    $total = 0;
    foreach($_SESSION["name"] as $id => $product){
      $stmt = $link->stmt_init();
      $stmt->prepare("SELECT price,amount FROM products WHERE id=?");
      $stmt->bind_param("i",$id);
      $stmt->execute();
      $stmt->bind_result($price,$stock);
      if($stmt->fetch()){
        $subtotal = $price*$_SESSION["amount"][$id];
        $total += $subtotal;
        $_SESSION[$id][$price] = $stock;
    ?>
    <tr>
      <td><?=$product ?></td>
      <td><?=$price ?>€</td>
      <td><?=$_SESSION["amount"][$id] ?></td>
      <td><?=$subtotal ?>€</td>
    </tr>
    <?php
      }
    }
    $stmt->close();
    ?>
    <tr>
      <td colspan="4" style="text-align: end">Total: <?=$total ?>€</td>
    </tr>
  </table>
  <p>
    <a href="index.php">Volver a lista de productos</a>
  </p>
    <?php
    }else{ 
    ?>
  <p>No hay ningun producto en el carrito</p>
  <p>
    <a href="index.php">Volver a lista de productos</a>
  </p>
</body>
</html>

<?php
    }
$link->close();
?>