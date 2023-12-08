<?php
session_start();
require "connection.php";

$sort = "id";
if(isset($_GET["sort"])){
  $sort = $_GET["sort"];
  setcookie("sort",$sort,time() + 120);
}else if(isset($_COOKIE["sort"])) {
  $sort = $_COOKIE["sort"];
}

if(isset($_GET["name"])){
  if(empty($_SESSION["name"][$_GET["id"]])){
    $_SESSION["name"][$_GET["id"]] = $_GET["name"];
    $_SESSION["amount"][$_GET["id"]] = 1;
  }else{
    if($_SESSION["name"][$_GET["id"]] == $_GET["name"]){
      $_SESSION["amount"][$_GET["id"]]++;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda</title>
  <style>
    table {
      border: 1px solid black;
      text-align: center;
      background-color: Seashell;
    }
    th {
      background-color: LightBlue;
    }
    td {
      background-color: LightGray;
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
  <?php
  if(isset($_SESSION["name"])){
    echo "Cantidad de elementos en el carrito: " . count($_SESSION["name"]) . "<br>";
    foreach($_SESSION["name"] as $productId => $productName){
      if(isset($_SESSION["amount"][$productId])){
        echo $productName . ": " . $_SESSION["amount"][$productId] . "<br>";
      }
    }
  }
  ?>
  <p>
    <a href="shoppingCart.php">Ver carrito de compra</a>
  </p>
  <table>
    <tr>
      <th><a href="index.php?sort=name">Nombre</a></th>
      <th><a href="index.php?sort=price">Precio</a></th>
      <th><a href="index.php?sort=amount">Cantidad</a></th>
      <th></th>
    </tr>
    <?php
      $stmt = $link->stmt_init();
      $stmt->prepare("SELECT id,name,price,amount FROM products ORDER BY $sort");
      $stmt->execute();
      $stmt->bind_result($id,$name,$price,$amount);
      while($stmt->fetch()) {
      ?>
      <tr>
        <td><?=$name ?></td>
        <td><?=$price ?></td>
        <td><?=$amount ?></td>
        <td>
          <form action="">
            <input type="submit" value="Añadir al carrito">
            <input  type="hidden" name="name" value="<?=$name ?>">
            <input  type="hidden" name="id" value="<?=$id ?>">
          </form>
        </td>
      </tr>
    <?php
      }
      $stmt->close();
    ?>
  </table>
  <a href="logout.php">Logout</a>
</body>
</html>
<?php
$link->close();
?>