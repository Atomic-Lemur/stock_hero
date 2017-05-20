<?php
session_start();
function __autoload($class_name){
    include_once("class.".$class_name.".php");
}

if(isset($_POST['action'])){
  $action = $_POST['action'];
}elseif(isset($_GET['action'])){
  $action = $_GET['action'];
}else{
  $action = false;
}

if($action=='reset'){
  session_destroy();
  session_start();
}

if(!isset($_SESSION['stocks'])){
  $stock_count = $_GET['stock_count'];
  for($x=0; $x<$stock_count; ++$x){
    $_SESSION['stocks'][] = new Stock();
  }
}else{
  foreach($_SESSION['stocks'] as $one_stock){
    $one_stock->update_price(rand(0, 1));
  }
}
 ?>

<!DOCTYPE html>
<html>
<head>
  <title>Stock Hero</title>
</head>
<body>

  <h2>The Market</h2>
  <ul id="market_list">
  <?
  foreach($_SESSION['stocks'] as $one_stock){
    echo '<li>'.$one_stock->name.' ('.$one_stock->symbol.') $'.$one_stock->price.'</li>';
  }
  ?>
  </ul>
</body>
</html>
