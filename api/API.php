<?php 

class API 
{
   private $allLoadApi=array();
   public static function getIdUsuarioSession()
    {
        return $GLOBALS["XID_USUARIOX"];
    }
    public function __construct()
    {
        $this->allLoadApi=array(
            "constitucion"
        );
    }


     public function run()
     {
     	// header('Content-Type: application/JSON');                
         $method = $_SERVER['REQUEST_METHOD'];
         if(isset($_GET["action"]))
         {

            if(!isset($_SESSION))
                session_start();
            
            
            if($_GET["action"]!="")
            {
                $_action=$_GET["action"];
                if(in_array($_action,$this->allLoadApi))
                {
                    $_action=str_replace("_"," ",$_GET["action"]);
                    $_action=ucwords($_action);
                    $_action=trim($_action);
                    $_action=str_replace(" ","",$_action);
                    $_class= "API_".$_action."API";
                    if(class_exists($_class))
                    {
                        $_obj= new $_class();
                        $_obj->API();
                    } 
                }   


            }   


         }

     }
}

 ?>