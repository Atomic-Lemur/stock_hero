<?php
session_start();
function __autoload($class_name){
    include_once("class.".$class_name.".php");
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

foreach($_SESSION['stocks'] as $one_stock){
  echo $one_stock->name.' ('.$one_stock->symbol.') $'.$one_stock->price.'<br />';
}

 ?>
