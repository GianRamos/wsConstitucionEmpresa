<?php 

if(!class_exists('DB_Connect') ) 
    include "conexion/DB_Connect.php";


class Models_Usuario extends DB_Connect {
        
    /**
     * Constructor de clase
     */
    function my_status_header($setHeader=null) {
        static $theHeader=null;
        //if we already set it, then return what we set before (can't set it twice anyway)
        if($theHeader) {return $theHeader;}
        $theHeader = $setHeader;
        header('HTTP/1.1 '.$setHeader);
        return $setHeader;
    } 



    public function getLogin()
       {
      $connect=$this->connect();
      $request = json_decode(file_get_contents('php://input'),true);
      $usuario=Config_Utiles::getParametroSaneado($request,"login",array('required'=>true,'type'=>'string'));
      $clave=Config_Utiles::getParametroSaneado($request,"clave",array('required'=>true,'type'=>'string'));
      
      
      
      $sql="select u.id,UPPER(u.nombres) AS nombres,r.accesos from crm_usuario u inner join rol r on u.idrol=r.id where 
            u.usuario=? and u.clave=? ";
        $rsp= array('session' =>0);
        $stmt = $connect->prepare($sql);
		$stmt->bind_param("ss",$usuario,$clave);
		$stmt->execute();
		$stmt->store_result();
		$rows=($stmt->num_rows);
	
		if($rows>0){
			$stmt->execute();

			$stmt->bind_result($id,$nombres,$accesos); // get the mysqli result
			$user = $stmt->fetch(); // fetch data 
			$_SESSION["xid"]=($id);
			$_SESSION["xacceso"]=($accesos);
			
			$rsp= array('xid'=>$_SESSION["xid"],'acceso'=>$_SESSION["xacceso"],'usuario'=>$nombres,'session'=>true);
			return ($rsp);
			
		}else
			return ($rsp);
	$stmt->close();
        return ($rsp);

}
public function cerrarSesion()
{
    unset($_SESSION["PXNOTARIA"]);
    unset($_SESSION["USER_ELECT_NOT"]);
    unset($_SESSION["NUMDOC"]);
    unset($_SESSION["XRUC"]);
    unset($_SESSION["CORREO"]);
    unset($_SESSION[NAME_TOKEN]);
    unset($_COOKIE["CK".NAME_TOKEN]);

    unset($_SESSION["UUID"]);
    return  array('0' =>'session cerrada');
}

public function getTokenSeguridadProcesos()
{

//    Utiles::getTokenValidation();
  require_once 'conexion/Clvs.php';
    $data= array('estado' =>0 ,'msg'=>"","id_t"=>"");
    if(isset($_SESSION["PXNOTARIA"])){
      $data=array('estado'=>1,'msg'=>'correcto','id_t'=>$_SESSION[NAME_TOKEN]);
    }
    return $data;
}
public function getVerificarSesion()
{
$request = $_GET["data"];
$request=json_decode($request,true);

$idnotaria=Config_Utiles::getParametroSaneado($request,"codigo",array('required'=>true,'type'=>'string'));
$idnotaria=base64_decode($idnotaria);

    $headers = apache_request_headers();
    $authorization="";
    if(isset($headers["Authorization"]))      
        $authorization=$headers["Authorization"];
    $idNotariaToken =Config_Utiles::getValuesTokenDescryp($authorization);
    
    if(intval($idnotaria)!=intval($idNotariaToken))
      $data=array('msg'=>'no');
    else
      $data=array('msg'=>'si');
    
     return ($data);

 /*  if(isset($_SESSION["PXNOTARIA"])){
        $data=array('msg'=>'si','id_user'=>$_SESSION["PXNOTARIA"],'nombre'=>$_SESSION["USER_ELECT_NOT"],'numdoc'=>$_SESSION["NUMDOC"],'correo'=>$_SESSION["CORREO"],'rol'=>$_SESSION["ROL"]);
      }
      else{
//        $data=array('msg'=>'si','id_user'=>137,'nombre'=>"Prueba",'numdoc'=>"123",'correo'=>"abc@gmail.com");
        
         $data=array('msg'=>'no');
      }
        return ($data); */
}


}
?>