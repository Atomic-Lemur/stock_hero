<?php
class Portfolio{
  function __construct(){
    $this->stocks = array();
    $this->bank = 1000;
  }

  function update_bank($amount){
    $this->bank = $this->bank + $amount;
  }

  function add_stock($symbol, $qty){
    if(!$symbol || !$qty) return false;
    $this->stocks[$symbol] = $qty + $this->stocks[$symbol];
  }

  function buy_stock($symbol, $qty, $price){
    if(!$symbol || !$qty || !$price) return array("result"=> false, "message" => "Info missing, unable to make the purchase.");

    $cost = $qty * $price * -1;
    if(($this->bank + $cost) > 0){
      $this->add_stock($symbol, $qty);
      $this->update_bank($cost);
      return array("result"=> true, "message" => "You just bought ".$qty." shares of ".$symbol.".");
    }else{
      return array("result"=> false, "message" => "You don't have enough money.");
    }
  }
}

?>
