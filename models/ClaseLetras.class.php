<?php
class ClaseNumeroLetra
{

    public $campo;
    public $tabla;
    public $vari;

    public static function fun_nume_letras($num, $fem = false, $dec = true)
    {
        $matuni[2] = "DOS";
        $matuni[3] = "TRES";
        $matuni[4] = "CUATRO";
        $matuni[5] = "CINCO";
        $matuni[6] = "SEIS";
        $matuni[7] = "SIETE";
        $matuni[8] = "OCHO";
        $matuni[9] = "NUEVE";
        $matuni[10] = "DIEZ";
        $matuni[11] = "ONCE";
        $matuni[12] = "DOCE";
        $matuni[13] = "TRECE";
        $matuni[14] = "CATORCE";
        $matuni[15] = "QUINCE";
        $matuni[16] = "DIECISEIS";
        $matuni[17] = "DIECISIETE";
        $matuni[18] = "DIECIOCHO";
        $matuni[19] = "DIECINUEVE";
        $matuni[20] = "VEINTE";
        $matunisub[2] = "DOS";
        $matunisub[3] = "TRES";
        $matunisub[4] = "CUATRO";
        $matunisub[5] = "QUIN";
        $matunisub[6] = "SEIS";
        $matunisub[7] = "SETE";
        $matunisub[8] = "OCHO";
        $matunisub[9] = "NOVE";

        $matdec[2] = "VEINT";
        $matdec[3] = "TREINTA";
        $matdec[4] = "CUARENTA";
        $matdec[5] = "CINCUENTA";
        $matdec[6] = "SESENTA";
        $matdec[7] = "SETENTA";
        $matdec[8] = "OCHENTA";
        $matdec[9] = "NOVENTA";
        $matsub[3] = 'MILL';
        $matsub[5] = 'BILL';
        $matsub[7] = 'MILL';
        $matsub[9] = 'TRILL';
        $matsub[11] = 'MILL';
        $matsub[13] = 'BILL';
        $matsub[15] = 'MILL';
        $matmil[4] = 'MILLONES';
        $matmil[6] = 'BILLONES';
        $matmil[7] = 'DE BILLONES';
        $matmil[8] = 'MILLONES DE BILLONES';
        $matmil[10] = 'TRILLONES';
        $matmil[11] = 'DE TRILLONES';
        $matmil[12] = 'MILLONES DE TRILLONES';
        $matmil[13] = 'DE TRILLONES';
        $matmil[14] = 'BILLONES DE TRILLONES';
        $matmil[15] = 'DE BILLONES DE TRILLONES';
        $matmil[16] = 'MILLONES DE BILLONES DE TRILLONES';

        $num = trim((string )@$num);
        
        if($num==0)
            return 'CERO '    ;
        if ($num[0] == '-') {
            $neg = 'MENOS ';
            $num = substr($num, 1);
        } else
            $neg = '';
        while ($num[0] == '0')
            $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9)
            $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt)
                    break;
                else {
                    $punt = true;
                    continue;
                }

            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0')
                        $zeros = false;
                    $fra .= $n;
                } else
                    $ent .= $n;
            } else
                break;

        }
        $ent = '     ' . $ent;
        $fin = ' ';
        if ($dec and $fra and !$zeros) {
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' 0 ';
                elseif ($s == '1')
                    $fin .= $fem ? ' 1' : ' 1';
                else
                    $fin .= '' . $s;
            }
            $fin .= '/100';
        } else
            $fin = '';
        if ((int)$ent === 0)
            return 'CERO ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'UNA';
                $subcent = 'AS';
            } else {
                $matuni[1] = $neutro ? 'UN' : 'UNO';
                $subcent = 'OS';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = 'I' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            } else {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = ' Y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' CIENTO' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'IENT' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'CIENT' . $subcent . $t;
            }
            if ($sub == 1) {
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' MIL';
                } elseif ($num > 1) {
                    $t .= ' MIL';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?N';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ONES';
            }
            if ($num == '000')
                $mils++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        return ucfirst($tex);
    }
    
    public static function fun_dia($var)
    {
    	switch ($var){
    		case '1':
                $dia = "DOMINGO";
                break;
            case '2':
                $dia = "LUNES";
                break;
            case '3':
                $dia = "MARTES";
                break;
            case '4':
                $dia = "MIERCOLES";
                break;
            case '5':
                $dia = "JUEVES";
                break;
            case '6':
                $dia = "VIERNES";
                break;
            case '7':
                $dia = "SABADO";
                break;
            default:
            	$dia = "";
    	}
    	return $dia;
    	
    }
    
    public static function fun_mes($var){
    	switch ($var) {
    		case '01':
                $mes = "ENERO";
                break;
            case '02':
                $mes = "FEBRERO";
                break;
            case '03':
                $mes = "MARZO";
                break;
            case '04':
                $mes = "ABRIL";
                break;
            case '05':
                $mes = "MAYO";
                break;
            case '06':
                $mes = "JUNIO";
                break;
            case '07':
                $mes = "JULIO";
                break;
            case '08':
                $mes = "AGOSTO";
                break;
            case '09':
                $mes = "SETIEMBRE";
                break;
            case '10':
                $mes = "OCTUBRE";
                break;
            case '11':
                $mes = "NOVIEMBRE";
                break;
            case '12':
                $mes = "DICIEMBRE";
                break;
            default:
            	$mes = "";
        }
        return $mes;
    }

    public static function fun_fech_comple_suc_intes($fecha)  // ARMA FECHA ESPECIAL PARA SUCESIONES INTESTADAS SEGUN REQ.
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        //$anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		$anio = substr($fecha, 0, 4);
       // return utf8_encode($dia . " DIAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
	    return utf8_encode($dia . " DIAS DEL MES DE " . $mes . " DEL " . $anio);
    }


    public static function fun_fech_comple($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        //$anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		$anio = substr($fecha, 0, 4);
       // return utf8_encode($dia . " DIAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
	    return utf8_encode($dia . " DIAS DE " . $mes . " DEL " . $anio);
    }
	
	 public static function fun_fech_num($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        //$anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		$anio = substr($fecha, 0, 4);
       // return utf8_encode($dia . " DIAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
	    return utf8_encode($dia . " DE " . $mes . " DE " . $anio);
    }
	
	 public static function fun_fech_comple2($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		//$anio = substr($fecha, 0, 4);
        return utf8_encode($dia . " DIAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
	    //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }
	
	public static function fun_fech_completo2($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
		$diasolo = substr($fecha, 8, 2);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		//$anio = substr($fecha, 0, 4);
        return utf8_encode($dia ."(".$diasolo.") DE " . $mes . " DEL AÑO " . $anio);
	    //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }
	
	public static function fun_fech_diayanio($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
		$diasolo = substr($fecha, 8, 2);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
		//$anio = substr($fecha, 0, 4);
		$aniosolo = substr($fecha, 0, 4);
        return utf8_encode($diasolo." DE " . $mes . " DEL " . $aniosolo);
	    //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }
	

    public static function fun_fech_corta_3($fecha)
    {
        $dia = substr($fecha, 8, 2);
        $mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $anio = substr($fecha, 0, 4);
        return ($dia . " DE " . $mes . " DEL AXO " . $anio);
    }
    
	public static function fun_fech_completo2_anio($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
		$diasolo = substr($fecha, 8, 2);
        $anio =substr($fecha, 0, 4);
		//$anio = substr($fecha, 0, 4);
        return utf8_encode($dia ."(".$diasolo.") DÍAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
	    //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }
    
	public static function fun_fech_completo2_dd($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
		$diasolo = substr($fecha, 8, 2);
        $anio =substr($fecha, 0, 4);
		//$anio = substr($fecha, 0, 4);
        return utf8_encode($dia ."(".$diasolo.") DÍAS DEL MES DE " . $mes . " DEL " . $anio);
	    //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }

    public static function fun_fech_completo2_dd2($fecha)
    {
        $mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $diasolo = substr($fecha, 8, 2);
        //$anio =substr($fecha, 0, 4);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
        //$anio = substr($fecha, 0, 4);
        return utf8_encode($dia ." DÍAS DEL MES DE " . $mes . " DEL " . $anio);
        //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }

     public static function fun_fech_completo2_dd22($fecha)
    {
        $mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $diasolo = substr($fecha, 8, 2);
        //$anio =substr($fecha, 0, 4);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
        //$anio = substr($fecha, 0, 4);
        return utf8_encode($dia ." DÍAS DEL MES DE " . $mes . " DEL AÑO " . $anio);
        //return utf8_encode($dia . " DIAS DE " . $mes . " DE " . $anio);
    }
	
    public static function fun_fech_comple_2($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
        return utf8_encode($mes . " " . $dia . ", " . $anio);
    }
    
    public static function fun_fech_comple_3($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
        $sql_se=mysql_query("SELECT DAYOFWEEK('$fecha');");
        $row_se=mysql_fetch_row($sql_se);
		return utf8_encode( ClaseNumeroLetra::fun_dia($row_se[0])." ".$dia . " DEL MES DE " . $mes . " DEL AÑO " . $anio);
    }
    
    public static function fun_fech_letras($fecha)
    {
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $dia = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 8, 2), $fem = false, $dec = true);
        $anio = ClaseNumeroLetra::fun_nume_letras(substr($fecha, 0, 4), $fem = false, $dec = true);
        return utf8_encode($dia . " DE " . $mes . " DEL " . $anio);
    }
    
    public static function fun_fech_corta($fecha)
    {
  		$dia = substr($fecha, 8, 2);
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $anio = substr($fecha, 0, 4);
        return utf8_encode($dia . " DE " . $mes . " DEL " . $anio);
    }
    
    public static function fun_fech_corta_2($fecha)
    {
  		$dia = substr($fecha, 8, 2);
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $anio = substr($fecha, 0, 4);
        return utf8_encode($mes . " " . $dia . ", " . $anio);
    }
    
    public static function fun_hor($hora)
	{
    	return ClaseNumeroLetra::fun_nume_letras(substr($hora,0,2), $fem = false, $dec = false)." Y ".ClaseNumeroLetra::fun_nume_letras(substr($hora,3,5), $fem = false, $dec = false);
    }
    
    public static function fun_fech_solo($fecha)
    {
  		$dia = substr($fecha, 8, 2);
		$mes = ClaseNumeroLetra::fun_mes(substr($fecha, 5, 2));
        $anio = substr($fecha, 0, 4);
        return utf8_encode($mes. " " . $dia . ", " . $anio);
    }
    
    public static function fun_fech_peru($fecha)
    {
  		$dia = substr($fecha, 8, 2);
		$mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);
        return utf8_encode($dia . "/" . $mes . "/" . $anio);
    }
    
    public static function fun_convi_letras($var){
    	return ClaseNumeroLetra::fun_nume_letras($var);
    } 
    
    public function fun_conv_letra(){
    	return ClaseNumeroLetra::fun_convi_letras($this->vari);    
    }

    public function fun_fecha()
    {
        return ClaseNumeroLetra::fun_fech_comple($this->vari);
    }
    
    
    public function fun_fecha_2()
    {
        return ClaseNumeroLetra::fun_fech_comple_2($this->vari);
    }
    
    public function fun_fecha_3()
    {
        return ClaseNumeroLetra::fun_fech_comple_3($this->vari);
    }
    
    public function fun_fecha_corta()
    {
		return ClaseNumeroLetra::fun_fech_corta($this->vari);
    }
    
    public function fun_fecha_corta_2()
    {
		return ClaseNumeroLetra::fun_fech_corta_2($this->vari);
    }
    
    public function fun_fecha_peru()
    {
    	return ClaseNumeroLetra::fun_fech_peru($this->vari);
    }
    
    public function fun_fecha_dia()
    {
		return substr($this->vari,8,2);
    }
    
    public function fun_fecha_mes()
    {
		return ClaseNumeroLetra::fun_mes(substr($this->vari,5,2));
    }
    
    public function fun_fecha_anio()
    {
		return substr($this->vari,0,4);
    }
    
    public function fun_fecha_hoy()
    {
    	$hoy=date("Y-m-d");
		return ClaseNumeroLetra::fun_fech_corta($hoy);
    }
    
    public function fun_hora()
    {
		return ClaseNumeroLetra::fun_hor($this->vari);
    }
    
    public function fun_fecha_hoy_2()
    {
    	$hoy=date("Y-m-d");
		return ClaseNumeroLetra::fun_fech_solo($hoy);
    }
    
    public static function fun_capital_moneda($tipo,$monto)
    {
		if ($tipo=='1'){
			$a = ClaseNumeroLetra::fun_nume_letras($monto, $fem = false, $dec = true)." y 00/100 SOLES";
		}else{
			$a = ClaseNumeroLetra::fun_nume_letras($monto, $fem = false, $dec = true)." DOLARES AMERICANOS";
		}
		return $a;		
    }
    
}
?>