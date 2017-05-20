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
  $stock_count = isset($_GET['stock_count']) ? $_GET['stock_count'] : rand(5, 10);
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
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock Hero</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    :root {
      --nav-background-color: #93caf2;
      --nav-font-color: #fff;
      --body-background-color: #fff;
      --body-font-color: #333;
    }
    body{
      background: var(--body-background-color);
      color: var(--body-font-color);
    }
    .nav_color{
      background: var(--nav-background-color);
      color: var(--nav-font-color);
    }
    .nav-wrapper a{
      color: var(--nav-font-color);
    }
    .stock{
      line-height: 2rem;
      font-size: 1.2rem;
    }
    .dead_stock{
      color: #882b12;
      text-decoration: line-through;
    }
  </style>
</head>
<body>

  <nav>
    <div class="nav-wrapper nav_color">
      <a href="#!" class="brand-logo center"><i class="material-icons">trending_up</i>Stock Hero</a>
    </div>
  </nav>

  <div class="container">
    <h2>The Market</h2>
    <ul id="market_list">
      <?
      foreach($_SESSION['stocks'] as $one_stock){
        echo '<li class="stock'.($one_stock->active ? null : ' dead_stock').'">'.$one_stock->name.' ('.$one_stock->symbol.') $'.$one_stock->price.'</li>';
      }
      ?>
    </ul>
  </div><!--/.container -->

</body>
</html>
