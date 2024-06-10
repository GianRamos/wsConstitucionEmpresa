<?php 
spl_autoload_register('my_autoloader');

function my_autoloader($className) {

	if($className!="" && strpos($className,"_")!==false){
		$allClassName=explode("_",$className);
		$realClassName=strtolower($allClassName[0]).DIRECTORY_SEPARATOR.$allClassName[1];
		$realClassName=$realClassName . '.php';		
	//	   echo "aaaaaaaaa ".$realClassName;
		if (file_exists($realClassName)) 
        	include $realClassName;
    	

	}
   
}




?>