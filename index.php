<?php
 include_once 'config/Config.conf';
 include_once  "api/API.php";       
 
 header("Access-Control-Allow-Origin:*");
 //header("Access-Control-Allow-Methods: GET, POST");

    $reports = new API();
    $reports->run();
/*
    $notarioAPI = new NotarioAPI();
    $notarioAPI->API();
*/
    
    
 ?>
