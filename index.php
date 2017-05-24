<?php
$message = null;
require('helpers.php');
function __autoload($class_name){
    include_once("class.".$class_name.".php");
}
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require('controller.php');
require('round.php');
$message = $_SESSION['player']->is_winner() ? array('result'=>true, 'message'=>'You won!') : $message;
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="stock_hero.css">
</head>
<body>
  <?php
    if(isset($message)){
      echo '<script>Materialize.toast("'.$message['message'].'", 3000, "rounded'.($message['result'] ? null : ' red').'");</script>';
    }
  ?>
  <nav class="nav-extended">
    <div class="nav-wrapper nav_color">
      &nbsp;&nbsp; <a href="#!" class="brand-logo left"><i class="material-icons">trending_up</i>Stock Hero</a>
      <ul class="right">
        <li>Updates in: <span id="timer"><?php echo $_SESSION['player']->round_time/1000; ?></span></li>
        <li><a href="?" title="skip turn"><i class="material-icons">skip_next</i></a></li>
        <li><a href="?action=restart" title="restart"><i class="material-icons">refresh</i></a></li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row">

      <div class="col s12 m6 l5">
        <h3>Your Portfolio</h3>
        <ul>
          <li><b>Round</b>: <?php echo $_SESSION['player']->round ?></li>
          <li><b>Bank</b>: $<?php echo number_format($_SESSION['player']->bank, 2) ?></li>
          <li><b>Goal</b>: $<?php echo number_format($_SESSION['player']->goal, 2) ?></li>
        </ul>

        <table id="portfolio" class="striped">
          <thead>
            <tr>
              <th class="center">Symbol</th>
              <th class="center">Qty</th>
              <th class="center">Value</th>
              <th class="center">Gain / Loss</th>
              <th class="center">Sell Stock</th>
            </tr>
          </thead>
          <?
          $portfolio_value = 0;
          foreach($_SESSION['player']->portfolio->stock_qtys as $stock_symbol => $stock_qty){
            $value = number_format(($_SESSION['stocks'][$stock_symbol]->price * $stock_qty), 2);
            $portfolio_value = $portfolio_value + ($_SESSION['stocks'][$stock_symbol]->price * $stock_qty);
            echo '
            <tr class="stock">
              <td class="center stock_symbol">'.$stock_symbol.'</td>
              <td class="center">'.$stock_qty.'</td>
              <td class="center">$'.$value.'</td>
              <td class="center '.($_SESSION['player']->portfolio->stock_purchase_prices[$stock_symbol] <= $_SESSION['stocks'][$stock_symbol]->price ? 'gain' : 'loss').'">$'.number_format($_SESSION['stocks'][$stock_symbol]->price - $_SESSION['player']->portfolio->stock_purchase_prices[$stock_symbol], 2).'</td>
              <td class="center"><a href="?action=sell&sell_stock_symbol='.$stock_symbol.'" title="sell all of this stock"><i class="fa fa-money"></i></a></td>
            </tr>';
          }

          ?>
          <tr class="stock grey white-text">
            <td colspan="2" class="center">TOTAL</td>
            <td colspan="3" class="center">$<?php echo number_format($portfolio_value, 2); ?></td>
          </tr>
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
              <input type="number" min="0" name="buy_stock_amount" class="center" placeholder="amount" />
            </div>

            <div class="input-field col s2">
              <button type="submit" class="btn-floating btn-medium waves-effect waves-light red"><i class="material-icons">add</i></button>
            </div>
            <input type="hidden" name="action" value="buy" />
          </div><!--row-->
        </form>

      </div><!--col-->

      <div class="col s12 m6 l7">
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
              <td class="center'.($one_stock->news ? ' tooltipped' : null).' '.($one_stock->change >= 0 ? 'gain' : 'loss').'"
                '.($one_stock->news ?
                'data-position="left"
                data-delay="50"
                data-tooltip="'.$one_stock->news.'"
                onclick="Materialize.toast(`'.$one_stock->news.'`, 3000, `'.($one_stock->change > 0 ? 'green' : 'red').'`);"'
                :
                null
                ).'
              >
                '.$one_stock->change.'&percnt;
                '.($one_stock->news ? ' <i class="tiny material-icons">info_outline</i>' : null).'
              </td>
            </tr>';
          }
          ?>
        </table>
      </div><!--col-->

    </div><!--row-->
  </div><!--/.container -->

  <footer class="page-footer nav_color">
    <div class="row">
      <div class="col s12">
        &copy;2017 created by Jon Link ( <a href="https://www.linkedin.com/in/jon-link/">LinkedIn</a> | <a href="https://github.com/jonnylink">Github</a> )
        Licensed under the <a href="https://tldrlegal.com/license/gnu-general-public-license-v3-%28gpl-3%29">GNU General Public License v3</a>
      </div>
    </div>
  </footer>

  <script>
    $(document).ready(function() {
      if(!<?php echo $_SESSION['player']->is_winner() ? 'true' : 'false'; ?>){
        setInterval( () => { window.location.assign(window.location.pathname); }, <?php echo $_SESSION['player']->round_time; ?>);
        setInterval( () => { document.getElementById('timer').innerHTML = document.getElementById('timer').innerHTML - 1; }, 1000);
      }
      $('select').material_select();
    });
  </script>
</body>
</html>
