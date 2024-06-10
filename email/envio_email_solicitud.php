<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function getFecha($strFecha)
{
  if($strFecha!="")
  {
      $arrFecha=explode("-",$strFecha);
      return $arrFecha[2]."/".$arrFecha[1]."/".$arrFecha[0];
  }
  return "";
}

//sendCorreo($correo,$mensaje,$notaria_comision,$notaria
//$correo,$correo_comision,$mensaje,$notaria_comision,$notaria
function sendCorreo($correo_destino,$copia,$docFirma){


require 'phpmailer/vendor/autoload.php';

if(!class_exists('DB_Connect') ) 
    require "conexion/DB_Connect.php";


$mail = new PHPMailer(true);
$mail->isSMTP();   

$mail->SMTPDebug  = 0;


    $mail->SMTPOptions = array(
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'verify_depth' => 3,
        'allow_self_signed' => true
    ],
);

/*
require("class.phpmailer.php");
require("class.smtp.php");
*/
if(!class_exists('DB_Connect') ) 
    require "conexion/DB_Connect.php";




$mail->Host = "192.168.0.7"; // SMTP a utilizar. Por ej. smtp.elserver.com
$mail->Port = 587; // Puerto a utilizar
$mail->SMTPAuth = true;

/*
$mail->Username = "sistemascong@gmail.com"; // Correo completo a utilizar
$mail->Password = "Benjamin123**"; // Contraseña
*/
//Visita de Inspección Ordinaria Notarial
$mail->SMTPSecure = "tls";

$mail->Username = "alertas@infonotaria.pe"; // Correo completo a utilizar
$mail->Password = "Aocp2022$$"; // Contraseña



$mail->From = "alertas@infonotaria.pe";
$mail->Subject="PLATAFORMA ELECTRÓNICA NOTARIAL";
$mail->FromName = "CNL - TESTIMONIOS ELECTRÓNICOS NOTARIALES";
$xxemail=$correo_destino;



//$mail->addStringAttachment($content,"salvoconducto_.pdf");
$mail->addStringAttachment($copia, 'copiaVerificable.pdf');
$mail->addStringAttachment($docFirma, 'documentoFirmado.pdf');


if(strpos($xxemail,";")===false)
 $mail->AddAddress($xxemail); 
else{
  $aaEmail=explode(";",$xxemail);
  foreach ($aaEmail as  $value) {
    $mail->AddAddress($value);
  }
}


 //principal
$mail->AddBCC("systemapp38@gmail.com"); // copia oculta

$body="";

$mail->IsHTML(true);
$mail->CharSet = 'UTF-8'; // El correo se envía como HTML
$body .="<div style='font-family: Verdana, Geneva, sans-serif;'>";
$body .= "Estimado Usuario, se ha enviado la Copia Verificable y el Documento Firmado desde la Plataforma Electrónica Notarial  </strong>. <br/><br/>";

$body .= "<br>";

//$body.="Para informes y/o consultas, comunicarse con la Notaria que ha realizado su  ";
//$body .= "Para informes y/o consultas, comunicarse con el Área de Proyectos, a los correos electrónicos proyectos@notarios.org.pe y/o secretariageneral@notarios.org.pe o al teléfono 461-0016 Anexo 135.";

$body.="<br/>";

$body .= "Atentamente<br/>";
//$body .= "<strong>COLEGIO DE NOTARIOS DE LIMA</strong><br/>";

$body .="</div>";
//$body .= '<hr style="color: #829AAB;" />';
$mail->Body = $body; // Mensaje a enviar
//$mail->SMTPDebug  = 2;
//$mail->AddAttachment($file);

$exito = $mail->Send(); // Envía el correo.


if($exito){
 /*  $sql="INSERT INTO DATAHISTORICA.CORREO_ENVIADO(STATUS,CODIGO_NOTARIO,OFICIO,CORREO,FECHA_REGISTRO) 
  VALUES(1,".$data->ruc.",'".$xxoficio."','".$data->email."',CURRENT_TIMESTAMP)";
*/
  $arr_resp[0]='1';
  $arr_resp[1]='Se envio correctamente la Solicitud:';
}else{
/*  $sql="INSERT INTO DATAHISTORICA.CORREO_ENVIADO(STATUS,CODIGO_NOTARIO,OFICIO,CORREO,FECHA_REGISTRO) 
  VALUES(0,".$data->ruc.",'".$xxoficio."','".$data->email."',CURRENT_TIMESTAMP)";
*/
  $arr_resp[0]='0';
  $arr_resp[1]='No se pudo enviar el correo';
}
/*
$dbconneconect =new DB_Connect();
$cn=$dbconneconect->connect();
$stmt=oci_parse($cn,$sql);
oci_execute($stmt);
oci_free_statement($stmt);
oci_close($cn);*/
return $arr_resp;
}
  

?>