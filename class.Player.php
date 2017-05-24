<?php

class Player{
  function __construct($name='Anonymous Player', $bank=10000, $goal=30000, $round_time=30000){
    $this->name = $name;
    $this->bank = $bank;
    $this->goal = $goal;
    $this->round = 0;
    $this->round_time = $round_time;
    $this->portfolio = null;
  }

  function update_bank($amount){
    $this->bank = $this->bank + $amount;
  }

  function is_winner(){
    return $this->bank >= $this->goal;
  }
}

?>
