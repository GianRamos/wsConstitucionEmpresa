<?php 

if(!class_exists('DB_Connect') ) 
    include "conexion/DB_Connect.php";


class Models_Login extends DB_Connect {
        
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
      $usuario=Config_Utiles::getParametroSaneado($request,"user",array('required'=>true,'type'=>'string'));
      $clave=Config_Utiles::getParametroSaneado($request,"password",array('required'=>true,'type'=>'string'));

      $sql="SELECT U.IDNOTARIA AS XIDNOTARIA,U.ROL,N.DESCRIPCION AS NOMBRE,U.CORREO,DNI,N.CODIGO
          FROM ELECTNOTARIAL.USUARIONOT_TEMP U
          INNER JOIN SISGEN.NOTARIA N ON U.IDNOTARIA=N.ID
          WHERE  U.rol<>5 and U.USUARIO=:ussu AND U.CLAVE=:pass"

          ;
        $stid = oci_parse($connect, $sql);
        oci_bind_by_name($stid, ':ussu', $usuario);
        oci_bind_by_name($stid, ':pass', $clave);
        oci_execute($stid);
        $enSession=false;
        $idSesion=0;
        while (($row = oci_fetch_object($stid)) != false) {
            $enSession=true;
            $_SESSION["PXNOTARIA"]=$row->XIDNOTARIA;
            $_SESSION["USER_ELECT_NOT"]=strtoupper($row->NOMBRE);
            $_SESSION["NUMDOC"]=$row->DNI;
            $_SESSION["XRUC"]=$row->CODIGO;
            $_SESSION["CORREO"]=$row->CORREO;
            $_SESSION["ROL"]=$row->ROL;
            $idSesion=$row->XIDNOTARIA;
            
            
        }

        if($enSession){

            $sql="
            INSERT INTO  ELECTNOTARIAL.REGISTRO_SESION_USUARIOS (IDUSUARIO,FECHA,HORA,TXT_USUARIO,TXT_CLAVE)
                  VALUES(".$_SESSION["PXNOTARIA"].",sysdate,CURRENT_TIMESTAMP,'".$usuario."','".$clave."')
            ";
            $stid = oci_parse($connect, $sql);
            oci_execute($stid);


            //CREANDO TOKEN PARA FIRMA
              $tokenFirma="";
              include "ServicioWebFirma.php";
            
              $objFirma=new ServicioWebFirma();
              $tokenFirma=$objFirma->crearTokenServicio();
              
            //GUARDANDO TOKEN
              $sql="INSERT INTO ELECTNOTARIAL.TOKENS_API (TOKEN,FECHA,HORA,IDUSUARIO) 
              VALUES ('".$tokenFirma."',sysdate,CURRENT_TIMESTAMP,".$_SESSION["PXNOTARIA"].")";
             //die($sql);
              $stid = oci_parse($connect, $sql);
              oci_execute($stid);
              $token=Config_Utiles::getTokenSeguridad($idSesion);

              $list=array('id_user'=>base64_encode($_SESSION["PXNOTARIA"]),'nombre'=>$_SESSION["USER_ELECT_NOT"],'msg'=>'correcto','rol'=>1,'token'=>$token);
              

        }else{
            $list=array('msg'=>'Datos no encontrados. !');
        }

        oci_free_statement($stid);
        oci_close($connect);
        return ($list);
    
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