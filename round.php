<?php

if(!isset($_SESSION['player'])){
  //start new game
  $_SESSION['player'] = isset($_GET['name']) ? new Player($_GET['name'], $_GET['bank'], $_GET['goal']) : new Player();
  $_SESSION['player']->portfolio = new Portfolio();

  $stock_count = isset($_GET['stock_count']) ? $_GET['stock_count'] : random_int(9, 20);
  for($x=0; $x<$stock_count; ++$x){
    $stock = new Stock();
    $_SESSION['stocks'][$stock->symbol] = $stock;
  }
  $_SESSION['player']->round++;

}else{
  foreach($_SESSION['stocks'] as $one_stock){
    $one_stock->update_stock();
  }
  $_SESSION['player']->round++;
}

?>
