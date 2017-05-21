<?php
//supports older versions of PHP
if(!function_exists('random_int')){
    function random_int($min, $max){ return rand($min, $max); }
}
?>
