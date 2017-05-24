<?php
if(isset($_POST['action'])){
  $action = $_POST['action'];
}elseif(isset($_GET['action'])){
  $action = $_GET['action'];
}else{
  $action = false;
}

if($action=='restart'){
  session_destroy();
  session_start();

}elseif($action=='buy'){
  $message = $_SESSION['player']->portfolio->buy_stock(
    $_SESSION['player'],
    $_POST['buy_stock_symbol'],
    $_POST['buy_stock_amount'],
    $_SESSION['stocks'][$_POST['buy_stock_symbol']]->price
  );

}elseif($action=='sell'){
  $message = $_SESSION['player']->portfolio->sell_stock(
    $_SESSION['player'],
    $_GET['sell_stock_symbol'],
    $_SESSION['player']->portfolio->stock_qtys[$_GET['sell_stock_symbol']],
    $_SESSION['stocks'][$_GET['sell_stock_symbol']]->price
  );
}
?>
