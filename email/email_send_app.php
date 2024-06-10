<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function getApi()
{


   $curl = curl_init();
   curl_setopt_array($curl, array(
   CURLOPT_URL => "http://notariacentellas.pe/notariacentellas.pe/sisnot/appServiciosNotariales/escrituras/getLinkReunion.php",
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => '',
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => 'GET'
    ));
 $response = curl_exec($curl);
// var_dump($response);
 curl_close($curl);
 $response=json_decode($response);
 return $response;
}

$rsp=getApi();
$linkEnlace=$rsp;

require 'phpmailer/vendor/autoload.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug  = 0;
$mail->SMTPOptions = array(
                    'ssl' => array(
                      'verify_peer' => false,
                      'verify_peer_name' => false,
                      'allow_self_signed' => true,
                      'cafile' => '[path-to-cert].crt'
                    )
                  );

$mail->Host = "192.168.0.7"; // SMTP a utilizar. Por ej. smtp.elserver.com
//$mail->Host = "smtp.gmail.com";

$mail->Port = 587; // Puerto a utilizar
$mail->SMTPAuth = true;


$mail->Username = "alertas@infonotaria.pe"; // Correo completo a utilizar
$mail->Password = "Aocp2022$$"; // Contraseña

$mail->SMTPSecure = "tls";

$mail->From = "alertas@infonotaria.pe";
$mail->Subject="SALA REUNIONES APP MOBIL ";
$mail->FromName = "NOTARIA CENTELLAS MACHACA";

$mail->AddAddress("o@notariacentellas.pe"); 
$mail->AddAddress("olgercentellas@hotmail.com");
 
//$mail->AddAddress("sistemascong@gmail.com");
$mail->AddBCC("giancarloramosrivas@gmail.com"); // copia oculta
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8'; // El correo se envía como HTML
$html="
	Se ha enviado este correo automático, para indicar que hay un usuario en  la SALA DE REUNIONES en este momento.
	<br>
	Link Reunión: ".$linkEnlace;
$body="<div style='font-size: 14px;'>".$html."</div>";

$body.="<br><br>";
$body .= "Atentamente,<br/>";
$body .= "<strong>ÁREA DE SISTEMAS - NOTARIA CENTELLAS MACHACA</strong><br/>";
//$body .= '<hr style="color: #829AAB;" />';
$mail->Body = $body; // Mensaje a enviar

//$mail->AddStringAttachment($file, 'file.pdf', 'base64', 'application/pdf');


$exito = $mail->Send(); // Envía el correo.

$rsp=array("envio"=>$exito,'link'=>$linkEnlace);
echo json_encode($rsp)

?>