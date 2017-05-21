<?php
class Portfolio{
  function __construct(){
    $this->stocks = array();
    $this->stock_purchase_prices = array();
    $this->bank = 10000;
    $this->goal = 30000;
    $this->round = 0;
  }

  function update_bank($amount){
    $this->bank = $this->bank + $amount;
  }

  function update_stock($symbol, $qty){
    if(!$symbol || !$qty) return false;

    $new_amount = isset($this->stocks[$symbol]) ? $this->stocks[$symbol] + $qty : $qty;
    if($new_amount == 0){
      unset($this->stocks[$symbol]);
    }elseif($new_amount < 0){
      return false;
    }else{
      $this->stocks[$symbol] = $new_amount;
    }
    return true;
  }

  function update_purchase_price($symbol, $qty, $price){
    if(!$symbol || !$qty || !$price) return false;

    $current_purchase_price = isset($this->stock_purchase_prices[$symbol]) ? $this->stock_purchase_prices[$symbol] : 0;
    $current_stock_qty = isset($this->stocks[$symbol]) ? $this->stocks[$symbol] : 0;
    $new_value = ($current_stock_qty * $current_purchase_price) + ($qty * $price);
    $this->stock_purchase_prices[$symbol] = sprintf("%.2f", ($new_value / ($current_stock_qty + $qty)) );
  }

  function buy_stock($symbol, $qty, $price){
    if(!$symbol || !$qty || !$price) return array("result"=> false, "message" => "Info missing, unable to make the purchase.");

    $cost = $qty * $price * -1;
    if(($this->bank + $cost) > 0){
      $this->update_purchase_price($symbol, $qty, $price);
      $this->update_stock($symbol, $qty);
      $this->update_bank($cost);
      return array("result"=> true, "message" => "You just bought ".$qty." shares of ".$symbol.".");
    }else{
      return array("result"=> false, "message" => "You don't have enough money.");
    }
  }

  function sell_stock($symbol, $qty, $price){
    $missing = null;
    if(!$symbol) $missing .= 'stock information, ';
    if(!$qty) $missing .= 'number of shares to sell, ';
    if(!$price) $missing .= 'stock price, ';
    if($missing) return array("result"=> false, "message" => "Info missing, unable to make the sale. We need ".trim($missing, ', ').".");

    $income = $qty * $price;
    if( $this->update_stock($symbol, ($qty * -1)) ){
      $this->update_bank($income);
      return array("result"=> true, "message" => "You just sold ".$qty." shares of ".$symbol." for $".$income.".");
    }else{
      return array("result"=> false, "message" => "Unable to complete the sale.");
    }
  }

  function did_win(){
    return $this->bank >= $this->goal;
  }

}

?>
