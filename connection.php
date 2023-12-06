<?php
$link = new mysqli("localhost","root","","tienda");
$error = $link->connect_errno;
if($error != null) {
  echo "<p>Error $error conectando a la base de datos: $link->connect_error</p>";
  die();
}