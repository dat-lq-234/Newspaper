<?php
spl_autoload_register(function($className){
    $exp = str_replace('_', '/', $className);
    $path = str_replace('app','',dirname(__FILE__));
    // dirname__file__ duong dan den file dang chay
 
    include_once $path.'/'.$exp.'.php';
   
});