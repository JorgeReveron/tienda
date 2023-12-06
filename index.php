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

if(isset($_GET["name"]) && isset($_GET["id"])){
  $count = 1;
  if(empty($_SESSION["id"])){
    $_SESSION["id"] = [$_GET["id"] => $_GET["name"]];
  }else if($_SESSION["id"] != $_GET["id"]){
    $_SESSION["id"] = [$_GET["id"] => $_GET["name"]];
  }
  // $element = ["id" => $_GET["id"],"name" => $_GET["name"]];
  // if ($element["id"] == $_GET["id"]){
  //   $count++;
  // }
  foreach ($_SESSION["id"] as $id => $name) {
    echo "<p>" . $name . "</p>";
  }
  // print_r($_SESSION["id"]);
  // var_dump($_SESSION);
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
    ?>
  </table>
  <a href="index.php?logout=true">Logout</a>
</body>
</html>
<?php
$stmt->close();
$link->close();
if (isset($_GET["logout"])){
  session_destoy();
  header("Location: index.php");
}
?>