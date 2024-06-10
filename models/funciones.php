<?php

function Conectar(){
$servidor="127.0.0.1";
$usuario = "root";
$password = "12345";
$bd = "notarios";
$dbh=mysql_connect ($servidor, $usuario, $password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($bd); 
return $dbh;
}

function validaFechadmy($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function getValorLlenoCeros($vLength,$valor)
{
	$vvvLengthValor=(int)(strlen($valor));
	if($vLength>$vvvLengthValor)
	{
		
		
		$totalcadena=$vLength-$vvvLengthValor;
		$i=0;
		$ceros="";
				while($totalcadena>$i)
				{
					$ceros=$ceros."0";
					$i++;
				}
				return $ceros.$valor;
	}
	return $valor;


	
}
function correlativo_numero3($numero){

	if($numero<>''){ 

		if ($numero<=9){$printnum="00".$numero;};
	
		if ($numero>=10 and $numero<=99){$printnum="0".$numero;};
	
		if ($numero>=100 and $numero<=999){$printnum=$numero;};

	}

	return $printnum;

}
function RAYAFIRMAS($conteo) {
	$result=0;$concatenador="";
	if($conteo>30){
		$conteo=="30";
	}else{
		$conteo==$conteo;
	}
	
	for($result=1;$result<=$conteo+5;$result++){
		$concatenador.="_";
	}   
    return $concatenador; 
}
function fNegrita($cadena){
	// formato negrita #-
	$formatNegrita="#-";
return $formatNegrita.$cadena.$formatNegrita;
}

function NEGRITA($cadena,$caracter="") {
    $cadena = $caracter.$cadena.$caracter;    
    return $cadena; 
}
function correlativo_numero4($numero){

	if($numero<>''){ 
	
		if ($numero<=9){$printnum="000".$numero;};
	
		if ($numero>=10 and $numero<=99){$printnum="00".$numero;};
	
		if ($numero>=100 and $numero<=999){$printnum="0".$numero;};
	
		if ($numero>=1000 and $numero<=99990){$printnum=$numero;};

	}

	return $printnum;

}

function correlativo_numero($numero){

	if($numero<>''){ 
	
		if ($numero<=9){$printnum="00000".$numero;};
	
		if ($numero>=10 and $numero<=99){$printnum="0000".$numero;};
	
		if ($numero>=100 and $numero<=999){$printnum="000".$numero;};
	
		if ($numero>=1000 and $numero<=9999){$printnum="00".$numero;};
	
		if ($numero>=10000 and $numero<=99999){$printnum="0".$numero;};
	
		if ($numero>=100000 and $numero<=9999990){$printnum=$numero;};
	
		}
	
		return $printnum;
	
	}

function correlativo_numero10($numero){

	if($numero<>''){ 
	
		if ($numero<=9){$printnum="000000000".$numero;};
	
		if ($numero>=10 and $numero<=99){$printnum="00000000".$numero;};
	
		if ($numero>=100 and $numero<=999){$printnum="0000000".$numero;};
	
		if ($numero>=1000 and $numero<=9999){$printnum="000000".$numero;};
	
		if ($numero>=10000 and $numero<=99999){$printnum="00000".$numero;};
	
		if ($numero>=100000 and $numero<=999999){$printnum="0000".$numero;};
	
		if ($numero>=1000000 and $numero<=9999999){$printnum="000".$numero;};
	
		if ($numero>=10000000 and $numero<=99999999){$printnum="00".$numero;};
	
		if ($numero>=100000000 and $numero<=999999999){$printnum="0".$numero;};
	
		if ($numero>=1000000000 and $numero<=99999999990){$printnum=$numero;};

	}

	return $printnum;

}

//error_reporting(0);

function fechabd_an($fechadb){
	
	if($fechadb <> 000-00-00){

	//vamos a suponer que recibmos el formato MySQL básico de YYYY-MM-DD
	
	//lo primero es separar cada elemento en una variable

    list($yy,$mm,$dd)=explode("-",$fechadb);

	//si viniera en otro formato, adaptad el explode y el orden de las variables a lo que necesitéis
	
	//creamos un objeto DateTime (existe desde PHP 5.2)

    //$fecha = new DateTime();

	//definimos la fecha pasándole las variabes antes extraídas

     //$fecha->setDate($yy, $mm, $dd);
	 
	$fecha = $dd.'/'.$mm.'/'.$yy;

	//y ahora el propio objeto nos permite definir el formato de fecha para imprimir que queramos       

    //return $fecha->format('d/m/Y');
	
	return $fecha;
	
	}

}

function fec_tiny($fechadb){
	
	if($fechadb <> 000-00-00){

	//vamos a suponer que recibmos el formato MySQL básico de YYYY-MM-DD
	
	//lo primero es separar cada elemento en una variable

    list($yy,$mm,$dd)=explode("-",$fechadb);

	//si viniera en otro formato, adaptad el explode y el orden de las variables a lo que necesitéis
	
	//creamos un objeto DateTime (existe desde PHP 5.2)

    $fecha = new DateTime();

	//definimos la fecha pasándole las variabes antes extraídas

     $fecha->setDate($yy, $mm, $dd);

	//y ahora el propio objeto nos permite definir el formato de fecha para imprimir que queramos       

    return $fecha->format('d/m/y');
	
	}

}


function fechan_abd($fechan){
	
	if($fechan<>""){

	//vamos a suponer que recibmos el formato MySQL básico de DD-MM-YYYY
	
	//lo primero es separar cada elemento en una variable

    list($dd,$mm,$yy)=explode("/",$fechan);

	/*	echo $dd;
	
		echo $mm;
	
		echo $yy;*/
	
	//si viniera en otro formato, adaptad el explode y el orden de las variables a lo que necesitéis
	
	//creamos un objeto DateTime (existe desde PHP 5.2)

    //$fecha1 = new DateTime();

	//definimos la fecha pasándole las variabes antes extraídas

    //$fecha1->setDate($yy, $mm, $dd);
	
	$fecha = $yy.'-'.$mm.'-'.$dd;

	//y ahora el propio objeto nos permite definir el formato de fecha para imprimir que queramos    
    //return $fecha1->format('Y-m-d');
	
	return $fecha;
	
	}

}

function fecha_aletra($fecha){
	
	$parte = explode("/", $fecha);
	
	$parte[0]; 
	$parte[1]; 
	$parte[2]; 
	
	switch($parte[1]){
		case "01": 
			$mes = "Enero";
			break;
			
		case "02": 
			$mes = "Febrero";
			break;
			
		case "03": 
			$mes = "Marzo";
			break;
			
		case "04": 
			$mes = "Abril";
			break;
			
		case "05": 
			$mes = "Mayo";
			break;
			
		case "06": 
			$mes = "Junio";
			break;
			
		case "07": 
			$mes = "Julio";
			break;
			
		case "08": 
			$mes = "Agosto";
			break;
			
		case "09": 
			$mes = "Setiembre";
			break;
			
		case "10": 
			$mes = "Octubre";
			break;
			
		case "11": 
			$mes = "Noviembre";
			break;	
			
		case "12": 
			$mes = "Diciembre";
			break;
	}
	
	return $fec_new = "Lima, ".$parte[0]." de ".$mes." del ".$parte[2] ;
	
}

function formato_crono_abd($cad){
    $part1 = substr($cad, 0,6);
    $part2 = substr($cad, 7,11);
    $cadena = $part2.$part1; 
    return $cadena;
}

  
function formato_crono_agui($cad){
	$part1 = substr($cad, 0,4);
	$part2 = substr($cad, 4,10);
	$cadena = $part2."-".$part1; 
	return $cadena; 
}



function encrypt($string, $key) {

   $result = '';

   for($i=0; $i<strlen($string); $i++) {

      $char = substr($string, $i, 1);

      $keychar = substr($key, ($i % strlen($key))-1, 1);

      $char = chr(ord($char)+ord($keychar));

      $result.=$char;

   }

   return base64_encode($result);

}



function decrypt($string, $key) {

   $result = '';

   $string = base64_decode($string);

   for($i=0; $i<strlen($string); $i++) {

      $char = substr($string, $i, 1);

      $keychar = substr($key, ($i % strlen($key))-1, 1);

      $char = chr(ord($char)-ord($keychar));

      $result.=$char;

   }

   return $result;

}

function simbolos($dato){
	
	$dato1=str_replace("?","'",$dato);
    $dato2=str_replace("*","&",$dato1);
    $dato3=str_replace("QQ11QQ","#",$dato2);
	$dato4=str_replace("Ã‘","Ñ",$dato3);
    $resultado=str_replace("QQ22KK","°",$dato4); 

    return $resultado;
	
}

function valorEnLetras($x)
{
	if ($x<0) { $signo = "menos ";}
	else      { $signo = "";}
	$x = abs ($x);
	$C1 = $x;
	
	$G6 = floor($x/(1000000));  // 7 y mas
	
	$E7 = floor($x/(100000));
	$G7 = $E7-$G6*10;   // 6
	
	$E8 = floor($x/1000);
	$G8 = $E8-$E7*100;   // 5 y 4
	
	$E9 = floor($x/100);
	$G9 = $E9-$E8*10;  //  3
	
	$E10 = floor($x);
	$G10 = $E10-$E9*100;  // 2 y 1
	
	
	$G11 = round(($x-$E10)*100,0);  // Decimales
	//////////////////////
	
	$H6 = unidades($G6);
	
	if($G7==1 AND $G8==0) { $H7 = "Cien "; }
	else {    $H7 = decenas($G7); }
	
	$H8 = unidades($G8);
	
	if($G9==1 AND $G10==0) { $H9 = "Cien "; }
	else {    $H9 = decenas($G9); }
	
	$H10 = unidades($G10)."con ";
	
	if($G11 < 10) { $H11 = "0".$G11; }
	else { $H11 = $G11; }
	
	/////////////////////////////
		if($G6==0) { $I6=" "; }
	elseif($G6==1) { $I6="Millón "; }
			 else { $I6="Millones "; }
			 
	if ($G8==0 AND $G7==0) { $I8=" "; }
			 else { $I8="Mil "; }
			 
	$I10 = " ";
	$I11 = "/100 Soles ";
	
	//$I11 = " Centimos";
	
	$C3 = $signo.$H6.$I6.$H7.$I7.$H8.$I8.$H9.$I9.$H10.$I10.$H11.$I11;
	
	return $C3; //Retornar el resultado

}
function valorEnLetras2($x,$moneda="SOLES")
{
	if ($x<0) { $signo = "menos ";}
	else      { $signo = "";}
	$x = abs ($x);
	$C1 = $x;
	
	$G6 = floor($x/(1000000));  // 7 y mas
	
	$E7 = floor($x/(100000));
	$G7 = $E7-$G6*10;   // 6
	
	$E8 = floor($x/1000);
	$G8 = $E8-$E7*100;   // 5 y 4
	
	$E9 = floor($x/100);
	$G9 = $E9-$E8*10;  //  3
	
	$E10 = floor($x);
	$G10 = $E10-$E9*100;  // 2 y 1
	
	
	$G11 = round(($x-$E10)*100,0);  // Decimales
	//////////////////////
	
	$H6 = unidades($G6);
	
	if($G7==1 AND $G8==0) { $H7 = "Cien "; }
	else {    $H7 = decenas($G7); }
	
	$H8 = unidades($G8);
	
	if($G9==1 AND $G10==0) { $H9 = "Cien "; }
	else {    $H9 = decenas($G9); }
	
	$H10 = unidades($G10);
	
	if($G11 < 10) { $H11 = "0".$G11; }
	else { $H11 = "y ".$G11; }
	
	/////////////////////////////
		if($G6==0) { $I6=" "; }
	elseif($G6==1) { $I6="Millón "; }
			 else { $I6="Millones "; }
			 
	if ($G8==0 AND $G7==0) { $I8=" "; }
			 else { $I8="Mil "; }
			 
	$I10 = " ";
	$I11 = "/100 ";
	
	//$I11 = " Centimos";
	
	$C3 = trim($signo.$H6.$I6.$H7.$I7.$H8.$I8.$H9.$I9.$H10.$I10)." Y ".$H11.$I11.$moneda.")";
	
	return $C3; //Retornar el resultado

}

function unidades($u)
{
    if ($u==0)  {$ru = " ";}
	elseif ($u==1)  {$ru = "Un ";}
	elseif ($u==2)  {$ru = "Dos ";}
	elseif ($u==3)  {$ru = "Tres ";}
	elseif ($u==4)  {$ru = "Cuatro ";}
	elseif ($u==5)  {$ru = "Cinco ";}
	elseif ($u==6)  {$ru = "Seis ";}
	elseif ($u==7)  {$ru = "Siete ";}
	elseif ($u==8)  {$ru = "Ocho ";}
	elseif ($u==9)  {$ru = "Nueve ";}
	elseif ($u==10) {$ru = "Diez ";}
	
	elseif ($u==11) {$ru = "Once ";}
	elseif ($u==12) {$ru = "Doce ";}
	elseif ($u==13) {$ru = "Trece ";}
	elseif ($u==14) {$ru = "Catorce ";}
	elseif ($u==15) {$ru = "Quince ";}
	elseif ($u==16) {$ru = "Dieciseis ";}
	elseif ($u==17) {$ru = "Decisiete ";}
	elseif ($u==18) {$ru = "Dieciocho ";}
	elseif ($u==19) {$ru = "Diecinueve ";}
	elseif ($u==20) {$ru = "Veinte ";}
	
	elseif ($u==21) {$ru = "Veintiun ";}
	elseif ($u==22) {$ru = "Veintidos ";}
	elseif ($u==23) {$ru = "Veintitres ";}
	elseif ($u==24) {$ru = "Veinticuatro ";}
	elseif ($u==25) {$ru = "Veinticinco ";}
	elseif ($u==26) {$ru = "Veintiseis ";}
	elseif ($u==27) {$ru = "Veintisiente ";}
	elseif ($u==28) {$ru = "Veintiocho ";}
	elseif ($u==29) {$ru = "Veintinueve ";}
	elseif ($u==30) {$ru = "Treinta ";}
	
	elseif ($u==31) {$ru = "Treinta y un ";}
	elseif ($u==32) {$ru = "Treinta y dos ";}
	elseif ($u==33) {$ru = "Treinta y tres ";}
	elseif ($u==34) {$ru = "Treinta y cuatro ";}
	elseif ($u==35) {$ru = "Treinta y cinco ";}
	elseif ($u==36) {$ru = "Treinta y seis ";}
	elseif ($u==37) {$ru = "Treinta y siete ";}
	elseif ($u==38) {$ru = "Treinta y ocho ";}
	elseif ($u==39) {$ru = "Treinta y nueve ";}
	elseif ($u==40) {$ru = "Cuarenta ";}
	
	elseif ($u==41) {$ru = "Cuarenta y un ";}
	elseif ($u==42) {$ru = "Cuarenta y dos ";}
	elseif ($u==43) {$ru = "Cuarenta y tres ";}
	elseif ($u==44) {$ru = "Cuarenta y cuatro ";}
	elseif ($u==45) {$ru = "Cuarenta y cinco ";}
	elseif ($u==46) {$ru = "Cuarenta y seis ";}
	elseif ($u==47) {$ru = "Cuarenta y siete ";}
	elseif ($u==48) {$ru = "Cuarenta y ocho ";}
	elseif ($u==49) {$ru = "Cuarenta y nueve ";}
	elseif ($u==50) {$ru = "Cincuenta ";}
	
	elseif ($u==51) {$ru = "Cincuenta y un ";}
	elseif ($u==52) {$ru = "Cincuenta y dos ";}
	elseif ($u==53) {$ru = "Cincuenta y tres ";}
	elseif ($u==54) {$ru = "Cincuenta y cuatro ";}
	elseif ($u==55) {$ru = "Cincuenta y cinco ";}
	elseif ($u==56) {$ru = "Cincuenta y seis ";}
	elseif ($u==57) {$ru = "Cincuenta y siete ";}
	elseif ($u==58) {$ru = "Cincuenta y ocho ";}
	elseif ($u==59) {$ru = "Cincuenta y nueve ";}
	elseif ($u==60) {$ru = "Sesenta ";}
	
	elseif ($u==61) {$ru = "Sesenta y un ";}
	elseif ($u==62) {$ru = "Sesenta y dos ";}
	elseif ($u==63) {$ru = "Sesenta y tres ";}
	elseif ($u==64) {$ru = "Sesenta y cuatro ";}
	elseif ($u==65) {$ru = "Sesenta y cinco ";}
	elseif ($u==66) {$ru = "Sesenta y seis ";}
	elseif ($u==67) {$ru = "Sesenta y siete ";}
	elseif ($u==68) {$ru = "Sesenta y ocho ";}
	elseif ($u==69) {$ru = "Sesenta y nueve ";}
	elseif ($u==70) {$ru = "Setenta ";}
	
	elseif ($u==71) {$ru = "Setenta y un ";}
	elseif ($u==72) {$ru = "Setenta y dos ";}
	elseif ($u==73) {$ru = "Setenta y tres ";}
	elseif ($u==74) {$ru = "Setenta y cuatro ";}
	elseif ($u==75) {$ru = "Setenta y cinco ";}
	elseif ($u==76) {$ru = "Setenta y seis ";}
	elseif ($u==77) {$ru = "Setenta y siete ";}
	elseif ($u==78) {$ru = "Setenta y ocho ";}
	elseif ($u==79) {$ru = "Setenta y nueve ";}
	elseif ($u==80) {$ru = "Ochenta ";}
	
	elseif ($u==81) {$ru = "Ochenta y un ";}
	elseif ($u==82) {$ru = "Ochenta y dos ";}
	elseif ($u==83) {$ru = "Ochenta y tres ";}
	elseif ($u==84) {$ru = "Ochenta y cuatro ";}
	elseif ($u==85) {$ru = "Ochenta y cinco ";}
	elseif ($u==86) {$ru = "Ochenta y seis ";}
	elseif ($u==87) {$ru = "Ochenta y siete ";}
	elseif ($u==88) {$ru = "Ochenta y ocho ";}
	elseif ($u==89) {$ru = "Ochenta y nueve ";}
	elseif ($u==90) {$ru = "Noventa ";}
	
	elseif ($u==91) {$ru = "Noventa y un ";}
	elseif ($u==92) {$ru = "Noventa y dos ";}
	elseif ($u==93) {$ru = "Noventa y tres ";}
	elseif ($u==94) {$ru = "Noventa y cuatro ";}
	elseif ($u==95) {$ru = "Noventa y cinco ";}
	elseif ($u==96) {$ru = "Noventa y seis ";}
	elseif ($u==97) {$ru = "Noventa y siete ";}
	elseif ($u==98) {$ru = "Noventa y ocho ";}
	else            {$ru = "Noventa y nueve ";}
	return $ru; //Retornar el resultado
}

function decenas($d)
{
    if ($d==0)  {$rd = "";}
	elseif ($d==1)  {$rd = "Ciento ";}
	elseif ($d==2)  {$rd = "Doscientos ";}
	elseif ($d==3)  {$rd = "Trescientos ";}
	elseif ($d==4)  {$rd = "Cuatrocientos ";}
	elseif ($d==5)  {$rd = "Quinientos ";}
	elseif ($d==6)  {$rd = "Seiscientos ";}
	elseif ($d==7)  {$rd = "Setecientos ";}
	elseif ($d==8)  {$rd = "Ochocientos ";}
	else            {$rd = "Novecientos ";}
	return $rd; //Retornar el resultado
} 

function ins_query($cadena){
		
		$parts = explode(",", $cadena);
		
		$total = count($parts);
	
		for($j=0; $j<$total; $j++){${'subparts'.$j} = explode(".",$parts[$j]);}
		
		$tabla = $subparts0[0];
		
		for($j=0; $j<$total; $j++){

            $val[$j] = ${'subparts'.$j}[1];

			if($j==0){
				$campos = $val[$j];
				$vars = "'$".$val[$j]."'";
			}
			if($j>0){
				$campos = $campos.', '.$val[$j];
				$vars = $vars.', '."'$".$val[$j]."'";
			}
		}
		
		$sql_cad = "insert into ".$tabla." (".$campos.") values(".$vars.")";
		
		return $sql_cad;
}

 function holaacentos($rb){ 
        ## Sustituyo caracteres en la cadena final
        $rb = str_replace("Ã¡","A", $rb);
        $rb = str_replace("Ã©","E", $rb);
        $rb = str_replace("Ã­","I", $rb);
        $rb = str_replace("ï¿½","I", $rb);
        $rb = str_replace("Ã³","O", $rb);
        $rb = str_replace("Ãº","U", $rb);
        $rb = str_replace("n~","Ñ", $rb);
        $rb = str_replace("ÃƒÂ¡","A", $rb);
        $rb = str_replace("Ã±","Ñ", $rb);
        $rb = str_replace("Ã'","Ñ", $rb);
        $rb = str_replace("ÃƒÂ±","Ñ", $rb);
        $rb = str_replace("n~","Ñ", $rb);
        $rb = str_replace("Ãš","Ú", $rb);
        $rb = str_replace("Ã?","Ñ", $rb);
		$rb = str_replace("Ã??","Ñ", $rb);
		$rb = str_replace("À?","Ñ", $rb);
		$rb = str_replace("À‘","Ñ", $rb);
		$rb = str_replace("À‘","Ñ", $rb);
		$rb = str_replace("Ã‘","Ñ", $rb);
		
		$rb = str_replace("ã¡","A", $rb);
        $rb = str_replace("ã©","E", $rb);
        $rb = str_replace("ã­","I", $rb);
        $rb = str_replace("ï¿½","I", $rb);
        $rb = str_replace("ã³","O", $rb);
        $rb = str_replace("ãº","U", $rb);
        $rb = str_replace("n~","Ñ", $rb);
        $rb = str_replace("ãƒÂ¡","A", $rb);
        $rb = str_replace("ã±","Ñ", $rb);
        $rb = str_replace("Ã'","Ñ", $rb);
        $rb = str_replace("ãƒÂ±","Ñ", $rb);
        $rb = str_replace("n~","Ñ", $rb);
        $rb = str_replace("ãš","Ú", $rb);
        $rb = str_replace("ã?","Ñ", $rb);
		$rb = str_replace("ã??","Ñ", $rb);
		$rb = str_replace("À?","Ñ", $rb);
		$rb = str_replace("À‘","Ñ", $rb);
		$rb = str_replace("Ã","Ñ", $rb);
		$rb = str_replace("ã‘","Ñ", $rb);
		$rb = str_replace("*","&", $rb);
		$rb = str_replace("Ô","O", $rb);
		$rb = str_replace("я","Ñ", $rb);
		$rb = str_replace("ñ","Ñ", $rb);
		$rb = str_replace("ÃŠ","U", $rb);
		$rb = str_replace("ô","O", $rb);
		$rb = str_replace("?","'", $rb);
		$rb = str_replace("*","&", $rb);
		$rb = str_replace("QQ11QQ", "#", $rb);
		$rb = str_replace("QQ22KK", "°", $rb);
		$rb = str_replace("Á","A", $rb);
		$rb = str_replace("É","E", $rb);
		$rb = str_replace("Í","I", $rb);
		$rb = str_replace("Ó","O", $rb);
		$rb = str_replace("Ú","U", $rb);	
 		$rb = str_replace("Ú","U", $rb);
		$rb = str_replace("Ã","A", $rb);
		$rb = str_replace("I","I", $rb);
		$rb = str_replace("A","A", $rb);
		$rb = str_replace("Á","A", $rb);
		$rb = str_replace("Í","I", $rb);
		$rb = str_replace("Á","A", $rb);
		$rb = str_replace("Í","I", $rb);
		$rb = str_replace("AÂ","A", $rb);
		$rb = str_replace("ÁÂ","A", $rb);
		$rb = str_replace("IÂ","I", $rb);
		$rb = str_replace("Ã‘","Ñ", $rb);
		
		
        return $rb;
    }  




function sanear_string($String)
{
   $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
$String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
$String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
$String = str_replace(array('í','ì','î','ï'),"i",$String);
$String = str_replace(array('é','è','ê','ë'),"e",$String);
$String = str_replace(array('É','È','Ê','Ë'),"E",$String);
$String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
$String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
$String = str_replace(array('ú','ù','û','ü'),"u",$String);
$String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
$String = str_replace("ç","c",$String);
$String = str_replace("Ç","C",$String);
$String = str_replace("ñ","n",$String);
$String = str_replace("Ñ","N",$String);
$String = str_replace("Ý","Y",$String);
$String = str_replace("ý","y",$String);
return $String;
}
?>
