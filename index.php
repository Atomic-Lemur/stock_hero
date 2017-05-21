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

if($action=='restart'){
  session_destroy();
  session_start();

}elseif($action=='buy'){
  $message = $_SESSION['my_portfolio']->buy_stock(
    $_POST['buy_stock_symbol'],
    $_POST['buy_stock_amount'],
    $_SESSION['stocks'][$_POST['buy_stock_symbol']]->price
  );

}elseif($action=='sell'){
  $message = $_SESSION['my_portfolio']->sell_stock(
    $_GET['sell_stock_symbol'],
    $_SESSION['my_portfolio']->stocks[$_GET['sell_stock_symbol']],
    $_SESSION['stocks'][$_GET['sell_stock_symbol']]->price
  );
}

if(!isset($_SESSION['stocks'])){
  $_SESSION['my_portfolio'] = new Portfolio();

  $stock_count = isset($_GET['stock_count']) ? $_GET['stock_count'] : rand(5, 10);
  for($x=0; $x<$stock_count; ++$x){
    $stock = new Stock();
    $_SESSION['stocks'][$stock->symbol] = $stock;
  }

}elseif(!$action){
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
      --body-font-color: #666;
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
    .gain{ color: #249f35; }
    .loss{
      color: #7a2424;
      font-weight: bold;
    }
    .stock{
      font-size: 1.2rem;
      line-height: 1.3rem;
      margin: 0;
      padding: 0;
    }
    .dead_stock{
      color: #882b12;
      text-decoration: line-through;
    }
    #buy_stock_form{
      margin-top: 5rem;
      border:1px solid #888;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <?php
    if(isset($message)){
      echo '<script>Materialize.toast("'.$message['message'].'", 3000, "rounded'.($message['result'] ? null : ' red').'");</script>';
    }
  ?>
  <nav class="nav-extended">
    <div class="nav-wrapper nav_color">
      <a href="#!" class="brand-logo center"><i class="material-icons">trending_up</i>Stock Hero</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="?" title="continue"><i class="material-icons">play_arrow</i></a></li>
        <li><a href="?action=restart" title="restart"><i class="material-icons">refresh</i></a></li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row">

      <div class="col s12 m6">
        <h3>Your Portfolio</h3>
        <p>Bank: $<?php echo $_SESSION['my_portfolio']->bank ?></p>

        <table id="portfolio" class="striped">
          <thead>
            <tr>
              <th class="center">Symbol</th>
              <th class="center">Qty</th>
              <th class="center">Value</th>
              <th class="center">Sell Stock</th>
            </tr>
          </thead>
          <?
          foreach($_SESSION['my_portfolio']->stocks as $stock_symbol => $stock_qty){
            echo '
            <tr class="stock">
              <td class="center stock_symbol">'.$stock_symbol.'</td>
              <td class="center">'.$stock_qty.'</td>
              <td class="center">$'.number_format(($_SESSION['stocks'][$stock_symbol]->price * $stock_qty), 2).'</td>
              <td class="center"><a href="?action=sell&sell_stock_symbol='.$stock_symbol.'" title="sell all of this stock"><i class="material-icons">shopping_basket</i></a></td>
            </tr>';
          }
          ?>
        </table>

        <form action="" method="POST" id="buy_stock_form" class="col s12">
          <div class="row">
            <h5 class="col s12">buy stock</h5>
          </div><!--row-->

          <div class="row center">
            <div class="input-field col s5">
              <select name="buy_stock_symbol">
              <?php
                foreach($_SESSION['stocks'] as $one_stock){
                  echo '<option value="'.$one_stock->symbol.'" title="'.$one_stock->name.' ('.$one_stock->symbol.')">'.$one_stock->symbol.'</option>';
                }
              ?>
              </select>
            </div>

            <div class="input-field col s5">
              <input name="buy_stock_amount" class="center" placeholder="amount" />
            </div>

            <div class="input-field col s2">
              <button type="submit" class="btn-floating btn-medium waves-effect waves-light red"><i class="material-icons">add</i></button>
            </div>
            <input type="hidden" name="action" value="buy" />
          </div><!--row-->
        </form>

      </div><!--col-->

      <div class="col s12 m6">
        <h3>The Market</h3>
        <table id="market" class="striped">
          <thead>
            <tr>
              <th>Company</th>
              <th>Symbol</th>
              <th>Price</th>
              <th>Change</th>
            </tr>
          </thead>

          <?
          foreach($_SESSION['stocks'] as $one_stock){
            echo '
            <tr class="stock'.($one_stock->active ? null : ' dead_stock').'">
              <td class="stock_name">'.$one_stock->name.'</td>
              <td class="center stock_symbol">'.$one_stock->symbol.'</td>
              <td class="center">$'.$one_stock->price.'</td>
              <td class="center '.($one_stock->change > 0 ? 'gain' : 'loss').'">'.$one_stock->change.'</td>
            </tr>';
          }
          ?>
        </table>
      </div><!--col-->

    </div><!--row-->
  </div><!--/.container -->

  <script>
    $(document).ready(function() {
      $('select').material_select();
    });
  </script>
</body>
</html>
