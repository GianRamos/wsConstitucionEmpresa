<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
function getFecha_($strFecha)
{
  if($strFecha!="")
  {
      $arrFecha=explode("-",$strFecha);
      return $arrFecha[2]."/".$arrFecha[1]."/".$arrFecha[0];
  }
  return "";
}*/

function sendCorreo($numcomision,$correo,$correo_comision,$notaria,$anio){

require 'vendor/autoload.php';

if(!class_exists('DB_Connect') ) 
    require "conexion/DB_Connect.php";

$dbconneconect =new DB_Connect();
$cn=$dbconneconect->connect();

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

$mail->Host = "192.168.0.7"; // SMTP a utilizar. Por ej. smtp.elserver.com
$mail->Port = 587; // Puerto a utilizar
$mail->SMTPAuth = true;


/*
$mail->Username = "systemenvios2@gmail.com"; // Correo completo a utilizar
$mail->Password = "Jncarlo***"; // Contraseña
*/


$mail->Username = "alertas@infonotaria.pe"; // Correo completo a utilizar
$mail->Password = "Aocp2022$$"; // Contraseña

$mail->SMTPSecure = "tls";

$mail->setFrom('visitanotarial@notarios.org.pe', 'VISITAS NOTARIALES');

$mail->Subject="VISITA DE INSPECCIÓN ORDINARIA NOTARIAL DEL AÑO ".$anio ;

$xxemail=$correo;

if(strpos($xxemail,";")===false)
 $mail->addCC($xxemail); 
else{
  $aaEmail=explode(";",$xxemail);
  foreach ($aaEmail as  $value) {
    if($value!="")
      $mail->addCC($value);
  }
}

$xxemail_comision=$correo_comision;


if($xxemail_comision!=""){
    if(strpos($xxemail_comision,";")===false)
           $mail->addAddress($xxemail_comision);
    else{
      
              $aaEmail=explode(";",$xxemail_comision);
              foreach ($aaEmail as  $value) {
                if($value!="")
                  $mail->addAddress($value);
              }
        
    }
}

$mail->addBCC("giancarloramosrivas@gmail.com"); // copia oculta
$mail->addBCC("ggarcia@notarios.org.pe"); // copia oculta
$mail->addBCC("1200334@esan.edu.pe"); // copia oculta


$mail->IsHTML(true);
$mail->CharSet = 'UTF-8'; // El correo se envía como HTML
$body="";

$body .="<div style='font-family: Verdana, Geneva, sans-serif;'>";
$body .= "Señor(a)(ita) Notarios de Ancash <br/>";

$body .= "<strong>Miembros de la Comisión de Visita Nª ".$numcomision.". </strong><br/><br/>";

$body .= "Sirvase la presente para informarles que la Notaría <strong>".$notaria."</strong>, ha remitido la información requerida para su visita ";

$body .= "correspondiente a los años 2019, 2020 y 2021 de conformidad con el Reglamento de Visita de Inspección Ordinaria Notarial de los años 2019, 2020 y 2021 la cual se encuentra a disposición  para su revisión.<br><br>";

$body .= "Se podrá ingresar a través la Plataforma Web Visita Notarial Virtual cuyo enlace es el siguiente:"."<br/>";

$body .= " <a href='http://www.notariadigital.org.pe:8081/Visitas_Ancash#/login' style='color:blue;text-decoration: underline;'>http://www.notariadigital.org.pe:8081/Visitas_Ancash#/login</a> <br>";

$body .= "Se debe seleccionar la opción <strong>NOTARIOS A VISITAR.</strong>"."<br/><br/>";

$body .= "Para informes y/o consultas, comunicarse con el Área de Proyectos, al correo electrónico proyectos@notarios.org.pe o al teléfono 461-0016 Anexo 135.";


$body .= "<div style='font-weight:bold;'>";
$xxid="0";
//REGLAMENTO DE VISITA DE INSPECCIÓN ORDINARIA NOTARIAL
//AÑO 2019 Y AÑO 2020

if(isset($data->id) && $data->id!="")
  $xxid=$data->id;


$body .= "</div>";

$body.="<br/>";

$body .= "Atentamente,<br/>";
$body .= "<strong>COLEGIO DE NOTARIOS DE ANCASH</strong><br/>";
$body .="</div>";
//$body .= '<hr style="color: #829AAB;" />';
$mail->Body = $body; // Mensaje a enviar
//$mail->SMTPDebug  = 2;
//$mail->AddAttachment($file);


$exito = $mail->send(); // Envía el correo.


if($exito){
  $arr_resp[0]='1';
  $arr_resp[1]='Se envio correctamente los archivos al(los) correo(s):';
}else{
  $arr_resp[0]='0';
  $arr_resp[1]='No se pudo enviar el correo';
}



return $exito;
}
  

?>