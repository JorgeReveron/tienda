<?php
session_start();
require "connection.php";

if(isset($_GET["increment"])){
  $_SESSION["amount"][$_GET["id"]]++;
}
if(isset($_GET["decrement"])){
  $_SESSION["amount"][$_GET["id"]]--;
  if($_SESSION["amount"][$_GET["id"]] == 0){
    unset($_SESSION["name"][$_GET["id"]]);
    unset($_SESSION["amount"][$_GET["id"]]);
  }
}

if(isset($_GET["delete"])){
  session_unset();
}
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
      background-color: LavenderBlush;
    }
    th {
      background-color: LightBlue;
    }
    td {
      background-color: MistyRose;
    }
    tr,td,th {
      border: 1px solid black;
      padding: 5px;
    }
    a {
      text-decoration: none;
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
      <th></th>
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
        if(isset($_SESSION["amount"][$id])){
          $subtotal = $price*$_SESSION["amount"][$id];
          $total += $subtotal;
    ?>
    <tr>
      <td><?=$product ?></td>
      <td><?=$price ?>€</td>
      <td><?=$_SESSION["amount"][$id] ?></td>
      <td><?=$subtotal ?>€</td>
      <td>
        <a href="shoppingCart.php?id=<?=$id?>&decrement"><button>-</button></a>
        <a href="shoppingCart.php?id=<?=$id?>&increment"><button>+</button></a>
      </td>
    </tr>
    <?php
        }
      }
    }
    if(!empty($stmt)){
      $stmt->close();
    }
    ?>
    <tr>
      <td colspan="5" style="text-align: end">Total: <?=$total ?>€</td>
    </tr>
  </table>
  <p>
    <a href="shoppingCart.php?delete"><button>Vaciar carrito</button></a>
    <br>
  </p>
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