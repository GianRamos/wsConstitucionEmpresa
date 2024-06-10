<?php 


if(!class_exists('DB_Connect') ) 
    include "conexion/DB_Connect.php";
include('ClaseLetras.class.php');
include('Vehicle.php');
include('Cliente.php');
include('MedioPago.php');

class Generate  extends DB_Connect {

	private $instrumentNumber;
	private $dateGeneration;
	private $kardex;
	private $response=array();
	private $dataCliente= array();
	private $titulo;
	private $numMinuta;
	private $numWriting;
	private $dateConclusion;
	private $paperIni;
	private $paperEnd;
	private $appearingText="";
	private $minuteText="";
	private $idActType="";
	private $constCertificate="";
	private $dataClienteSigned =array();
	private $dataConstancy =array();
	private $objFecha;

	private $grantorText="";
	private $grantorClients="";
	private $inFavorText="";
	private $inFavorClients="";
	
	private $nameuser;
	private $dateWriting;

	private $folioInitial;
	private $folioEnd;
	private $condition;
	private $dateWritingLetters_descri;
	private $folioInitVuelt;
	private $folioEndVuelt;

	private $papelInitVuelt;
	private $papelEndVuelt;
	private $dateWritingLetters;
	private $listVehiculo=array();
	private $listMedioPago=array();
	

	public function __construct($kardex)
	{
		$this->setKardex($kardex);
		$this->objFecha=new ClaseNumeroLetra();
	}

	public function run()
	{
		$this->runClients();
		$this->runKardex();
		$this->runComparecientesTitulo();
		$this->runVehiculo();
		$this->runMedioPago();
			
	}
	public function runClients()
	{
		$connect=$this->connect();
		$sql="
	SELECT cl2.tipper,
CONVERT(CAST(CONVERT(cl2.apepat  USING LATIN1) AS BINARY) USING UTF8) AS apepat,
CONVERT(CAST(CONVERT(cl2.apemat  USING LATIN1) AS BINARY) USING UTF8) AS apemat,
CONVERT(CAST(CONVERT(cl2.prinom  USING LATIN1) AS BINARY) USING UTF8) AS prinom,
CONVERT(CAST(CONVERT(cl2.segnom  USING LATIN1) AS BINARY) USING UTF8) AS segnom,
CONVERT(CAST(CONVERT(cl2.nombre  USING LATIN1) AS BINARY) USING UTF8) AS nombre,
CONVERT(CAST(CONVERT(cl2.razonsocial  USING LATIN1) AS BINARY) USING UTF8) AS razonsocial,
CONVERT(CAST(CONVERT(cl2.direccion  USING LATIN1) AS BINARY) USING UTF8) AS direccion
,cl2.domfiscal,c.numpartida,sd.dessede,
CONVERT(CAST(CONVERT(cl2.detaprofesion USING LATIN1) AS BINARY) USING UTF8) AS detaprofesion,
		cl2.idtipdoc,t.destipdoc,t.td_abrev as abrev,cl2.numdoc,cl2.sexo,cl2.idestcivil,e.desestcivil,n.descripcion AS nacionalidad,n.idnacionalidad,c.fechafirma,
		ca.uif,ac.parte,ac.parte as parte_generacion,c.firma,u.nomdis,u.nomprov,u.nomdpto,c.idcontratante,ac.condicion
		FROM kardex k INNER JOIN contratantes c ON k.kardex=c.kardex
		INNER JOIN cliente2  cl2 ON c.idcontratante=cl2.idcontratante
		INNER  JOIN `contratantesxacto` ca ON c.idcontratante=ca.idcontratante
		LEFT JOIN `tipodocumento` t ON  cl2.idtipdoc=t.idtipdoc
		LEFT JOIN `tipoestacivil` e ON  cl2.idestcivil=e.idestcivil
		LEFT JOIN `nacionalidades` n ON  cl2.nacionalidad=n.idnacionalidad
		LEFT JOIN sedesregistrales sd ON c.idsedereg=sd.`idsedereg`
		LEFT JOIN actocondicion  ac ON ca.`idcondicion`=ac.idcondicion
		LEFT JOIN ubigeo u ON cl2.idubigeo=u.coddis
		WHERE k.kardex='".$this->getKardex()."'";

		$query=mysqli_query($connect,$sql) or die("=> ".mysqli_error($connect));
		while($row=mysqli_fetch_assoc($query))
		{
			$this->response[]=$row;
		}

	}

	public function runVehiculo()
	{
		$connect=$this->connect();
		$sql="SELECT  numplaca,clase,marca,carroceria,anofab,fecinsc AS fechaincripcion,
			color, motor,numserie AS serie,modelo
 			FROM `detallevehicular` WHERE kardex='".$this->getKardex()."'";

		$query=mysqli_query($connect,$sql) or die("=> ".mysqli_error($connect));
		while($row=mysqli_fetch_assoc($query))
		{
			$obj=new Vehicle();  
			$obj->setPlaca($row["numplaca"]);
			$obj->setClase($row["clase"]);
			$obj->setMarca($row["marca"]);
			$obj->setCarroceria($row["carroceria"]);
			$obj->setAnio($row["anofab"]);
			$obj->setColor($row["color"]);
			$obj->setMotor($row["motor"]);
			$obj->setSerie($row["serie"]);
			$obj->setModelo($row["modelo"]);
			$obj->setFechaInscripcion($row["fechaincripcion"]);
			$this->listVehiculo[]=$obj;
		}

	}


	public function runMedioPago()
	{
		$connect=$this->connect();
		$sql="SELECT p.importetrans AS importe,m.desmon AS moneda,mp.desmpagos AS mediopago,mp.sunat AS codigo_pago,
d.documentos,b.desbanco AS banco,foperacion AS fechaoperacion
FROM patrimonial p 
INNER JOIN `detallemediopago` d ON p.itemmp=d.itemmp
LEFT JOIN monedas m ON p.idmon=m.idmon 
LEFT JOIN mediospago mp ON d.codmepag=mp.codmepag
LEFT JOIN bancos b ON d.idbancos=b.idbancos
WHERE p.kardex='".$this->getKardex()."'
";  

		$query=mysqli_query($connect,$sql) or die("=> ".mysqli_error($connect));
		while($row=mysqli_fetch_assoc($query))
		{
			$obj=new MedioPago();  
			$obj->setImporte($row["importe"]);
			if($row["moneda"]=="SOLES")
				$obj->setMoneda("Moneda Nacional");
			else
				$obj->setMoneda($row["moneda"]);

			$obj->setMedioPago($row["mediopago"]);
			$obj->setCodigoPago($row["codigo_pago"]);
			$obj->setDocumento($row["documentos"]);
			$obj->setBanco($row["banco"]);
			$obj->setFechaOperacion($row["fechaoperacion"]);
			$this->listMedioPago[]=$obj;
		}

	}

	public function getListVehiculo()
	{
		return $this->listVehiculo;
	}

	public function getListMedioPago()
	{
		return $this->listMedioPago;
	}

	public function runKardex()
	{
		$connect=$this->connect();
		$sql="SELECT contrato,kardex,numescritura,numminuta,fechaconclusion,papelini,papelfin,codactos,fechaescritura,folioini,foliofin,
		folioinivta,foliofinvta,papelinivta,papelfinvta,
		(
		select importetrans  FROM `patrimonial` WHERE kardex=k.kardex LIMIT 1
		)as importe,
		(
									SELECT 
									(
									CASE prinom
									WHEN '' THEN
									loginusuario
									ELSE prinom END) FROM usuarios WHERE idusuario=k.idusuario LIMIT 1
									) as nombre_usuario
		 FROM kardex k WHERE k.kardex='".$this->getKardex()."'";

		$query=mysqli_query($connect,$sql) or die("=> ".mysqli_error($connect)); 
		while ($row=mysqli_fetch_assoc($query)) {

			if($row["contrato"]!="")
			{
				if(sizeof(explode("/",trim($row["contrato"])))>1)
					$strContrato=str_replace("/"," ",$row["contrato"]);
				else
					$strContrato=str_replace("/","",$row["contrato"]);

				$this->setTitulo($strContrato);
			}
			$this->setNumMinuta($row["numminuta"]);
			$this->setNumWriting($row["numescritura"]);
			$this->setDateWriting($row["fechaescritura"]);
			$this->setImporte($row["importe"]);
		
			$this->setDateWritingLetters($this->objFecha->fun_fech_completo2_anio_02(($row["fechaescritura"])));
			$this->setDateWritingLetters_descrip($this->objFecha->fun_fech_letras(($row["fechaescritura"])));
			
			
			$this->setDateConclusion($this->objFecha->fun_fech_letras(fechan_abd($row["fechaconclusion"])));
			$this->setDateGeneration($this->objFecha->fun_fech_comple2_proto(date("Y-m-d")));
			$this->setPaperIni($row["papelini"]);
			$this->setPaperEnd($row["papelfin"]);
			$this->setIdActType($row["codactos"]);

			$this->setFolioInitial($row["folioini"]);
			$this->setFolioEnd($row["foliofin"]);
			$this->setNameUser($row["nombre_usuario"]);



			if($row["folioinivta"]==1)
				$this->setFolioInitVuelt("VUELTA");

			if($row["foliofinvta"]==1)
				$this->setFolioEndVuelt("VUELTA");
			
			if($row["papelinivta"]==1)
				$this->setPapelInitVuelt("VUELTA");

			if($row["papelfinvta"]==1)
				$this->setPapelEndVuelt("VUELTA");
			

			

		}
		
	}

	public function setDateWriting($dateWriting)
	{
		$this->dateWriting=$dateWriting;
	}
	public function getDateWriting()
	{
		return $this->dateWriting;
	}

	public function setDateWritingLetters($dateWritingLetters)
	{
		$this->dateWritingLetters=$dateWritingLetters;
	}
	public function getDateWritingLetters()
	{
		return $this->dateWritingLetters;
	}


	public function setDateWritingLetters_descrip($dateWritingLetters_descri)
	{
		$this->dateWritingLetters_descri=$dateWritingLetters_descri;
	}
	public function getDateWritingLetters_descrip()
	{
		return $this->dateWritingLetters_descri;
	}


	public function setFolioInitVuelt($folioInitVuelt)
	{
		$this->folioInitVuelt=$folioInitVuelt;
	}
	public function getFolioInitVuelt()
	{
		return $this->folioInitVuelt;
	}

	public function setFolioEndVuelt($folioEndVuelt)
	{
		$this->folioEndVuelt=$folioEndVuelt;
	}
	public function getFolioEndVuelt()
	{
		return $this->folioEndVuelt;
	}


	public function setPapelEndVuelt($papelEndVuelt)
	{
		$this->papelEndVuelt=$papelEndVuelt;
	}
	public function getPapelEndVuelt()
	{
		return $this->papelEndVuelt;
	}

	public function setPapelInitVuelt($papelInitVuelt)
	{
		$this->papelInitVuelt=$papelInitVuelt;
	}
	public function getPapelInitVuelt()
	{
		return $this->papelInitVuelt;
	}


	public function getDataClientSignedOne()
	{
		return $this->dataClienteSigned;
	}
	


	public function getDataClientAppearing_Test()
	{
		$i=0;
		foreach ($this->getResponse() as $key => $value) {
			if($value["tipper"]=="N" && $value["firma"]==1){
				$objcliente= new Cliente();
				$xnombre="";
				
				$value["prinom"]=trim($value["prinom"]);
				$value["segnom"]=trim($value["segnom"]);
				$value["apepat"]=trim($value["apepat"]);
				$value["apemat"]=trim($value["apemat"]);
				
				if($value["prinom"]!="")
					$xnombre.=$value["prinom"];

				if($value["segnom"]!="")
					$xnombre.=$value["segnom"];
				
				if($value["apepat"]!="")
					$xnombre.=$value["apepat"];
				
				if($value["apemat"]!="")
					$xnombre.=$value["apemat"];
				
				$objcliente->setClientName($xnombre);

				$objcliente->setAddress($value["direccion"]);
				$objcliente->setDocumentAbrev($value["destipdoc"]);
				if($value["numdoc"]!="")
					$objcliente->setNumberDocument("NÚMERO ".$value["numdoc"]);
				$objcliente->setMaritalStatus($value["desestcivil"]); 
				$objcliente->setNationality($value["nacionalidad"]); 
				$objcliente->setSexo($value["sexo"]); 
				$objcliente->setUif($value["uif"]); 
				$objcliente->setTypePerson($value["tipper"]); 
				$objcliente->setProfession($value["detaprofesion"]); 

				$objcliente->setDateSignedClient($this->objFecha->fun_fech_letras(fechan_abd($value["fechafirma"])));
				
				if($value["uif"]=="R"){
						if($value["tipper_repre"]=="N")
						{
							$objcliente->setClientRepresented($value["prinom_repre"]." ".$value["segnom_repre"]." ".$value["apepat_repre"]." ".$value["apemat_repre"]);
							
							$objcliente->setAccordingRepresented("");			
						}else if($value["tipper_repre"]=="J")
						{
							$dessede="";
							if($value["dessede"]!=""){
								$dessede=explode("-",$value["dessede"]);
								$dessede=trim($dessede[1]);
							}
							

							$objcliente->setClientRepresented($value["razonsocial_repre"]);
							$objcliente->setAccordingRepresented("SEGÚN PARTIDA ELECTRÓNICA NÚMERO ".$value["numpartida"]." DEL REGISTRO DE PERSONAS JURÍDICAS DE ".strtoupper($dessede));		
						}
						if($objcliente->getClientRepresented()!="")
							$objcliente->setClientRepresented($objcliente->getClientRepresented().", ");

						
							
				}


				$objcliente->setIdNationality($value["idnacionalidad"]);


				$strUbigeo="";
				if($value["nomdis"]!="")
					$strUbigeo.="DISTRITO DE ".$value["nomdis"].", ";
				if($value["nomprov"]==$value["nomdpto"])
					$strUbigeo.="PROVINCIA Y DEPARTAMENTO DE ".$value["nomdpto"]."";
				else{
					if($value["nomprov"]!="")
						$strUbigeo.="PROVINCIA DE ".$value["nomprov"].", ";
					if($value["nomdis"]!="")
						$strUbigeo.="DISTRITO DE ".$value["nomdis"];
				}
				$objcliente->setUbigeo($strUbigeo); 
				$this->dataCliente[]=$objcliente;		
				
				
				$i++;
			}
			
		
			
		}
		return $this->dataCliente;
	}

	private function getRepresentants($idcontratanterp)
	{
		$connect=$this->connect();
		$list=array();
		if($idcontratanterp!=""){
			$sql="
				SELECT  cl2repre.tipper,cl2repre.apepat,cl2repre.apemat,cl2repre.prinom,cl2repre.segnom,cl2repre.nombre,cl2repre.razonsocial,cl2repre.direccion,cl2repre.sexo,
				trepre.destipdoc AS tipodocumento,cl2repre.numdoc AS numerodocumento,
					n.descripcion AS nacionalidad,e.desestcivil as estadocivil,p.desprofesion as profesion,
					u.nomdis,u.nomprov,u.nomdpto
				 FROM contratantes conrepre
					INNER JOIN cliente2 cl2repre  ON conrepre.`idcontratante`=cl2repre.`idcontratante`
					LEFT JOIN tipodocumento trepre ON cl2repre.idtipdoc=trepre.idtipdoc
					LEFT JOIN `nacionalidades` n ON  cl2repre.nacionalidad=n.idnacionalidad
					LEFT JOIN `tipoestacivil` e ON  cl2repre.idestcivil=e.idestcivil
					LEFT JOIN profesiones p on cl2repre.idprofesion=p.idprofesion
					LEFT JOIN ubigeo u ON cl2repre.idubigeo=u.coddis
					WHERE conrepre.idcontratanterp='".$idcontratanterp."'
			";  
			$query=mysqli_query($connect,$sql) or die("=> ".mysqli_error($connect));
			while($row=mysqli_fetch_assoc($query))
			{
				if($row["direccion"]!="")
					$direccion=trim($row["direccion"])."; ";
				if($row["nomdis"]!="" && $row["nomprov"]!="" && $row["nomdpto"]!="" )
				{
					if($row["nomdis"]==$row["nomprov"])
						$direccion.="DEL DISTRITO Y PROVINCIA DE ".$row["nomdis"].", DEPARTAMENTO DE  ".$row["nomdpto"];
					else
						$direccion.="DEL DISTRITO DE ".$row["nomdis"].", PROVINCIA DE ".$row["nomprov"].", DEPARTAMENTO DE  ".$row["nomdpto"];
				}
				$row["direccion"]=$direccion;
				$row["representante"]=(trim($row["prinom"])." ".trim($row["segnom"])." ".trim($row["apepat"])." ".trim($row["apemat"]));
				$list[]=$row;
			}
			return $list;
		}
		return $list;
	}
    public function getDataClientAppearing()
	{
		$i=0;
		foreach ($this->getResponse() as $key => $value) {
			$objcliente= new Cliente();
			$direccion="";



			if($value["tipper"]=="J")
			{
				$objcliente->setClientName($value["razonsocial"]);

				if($value["domfiscal"]!="")
					$direccion=trim($value["domfiscal"]).";";
				if($value["nomdis"]!="" && $value["nomprov"]!="" && $value["nomdpto"]!="" )
				{
					if($value["nomdis"]==$value["nomprov"])
						$direccion.=$value["domfiscal"]." DEL DISTRITO Y PROVINCIA DE ".$value["nomdis"].", DEPARTAMENTO DE  ".$value["nomdpto"];
					else
						$direccion.=$value["domfiscal"]." DEL DISTRITO DE ".$value["nomdis"].", PROVINCIA DE ".$value["nomprov"].", DEPARTAMENTO DE  ".$value["nomdpto"];
				}
				$objcliente->setAddress($direccion);

				$objcliente->setProvince($value["nomprov"]);
				$objcliente->setDepartament($value["nomdpto"]);
				$objcliente->setDistrict($value["nomdis"]);

				$represents=$this->getRepresentants($value["idcontratante"]);
				if(sizeof($represents)>0)
					$objcliente->setRepresented($represents);

			}else{
				
				$objcliente->setClientName(trim($value["prinom"])." ".trim($value["segnom"])." ".trim($value["apepat"])." ".trim($value["apemat"]));
				
				if($value["direccion"]!="")
					$direccion=trim($value["direccion"]).";";
				/*if($value["nomdis"]!="" && $value["nomprov"]!="" && $value["nomdpto"]!="" )
				{
					if($value["nomdis"]==$value["nomprov"])
						$direccion.=$value["direccion"]." DEL DISTRITO Y PROVINCIA DE ".$value["nomdis"].", DEPARTAMENTO DE  ".$value["nomdpto"];
					else
						$direccion.=$value["direccion"]." DEL DISTRITO DE ".$value["nomdis"].", PROVINCIA DE ".$value["nomprov"].", DEPARTAMENTO DE  ".$value["nomdpto"];
				}*/
				$objcliente->setAddress($direccion);
				$objcliente->setDocumentAbrev($value["destipdoc"]);
				if($value["numdoc"]!="")
					$objcliente->setNumberDocument($value["numdoc"]);
				$objcliente->setMaritalStatus($value["desestcivil"]); 
				$objcliente->setNationality($value["nacionalidad"]); 
				$objcliente->setSexo($value["sexo"]); 
				$objcliente->setTypePerson($value["tipper"]); 
				$objcliente->setProfession($value["detaprofesion"]);
				$objcliente->setCondition($value["condicion"]);
				$objcliente->setProvince($value["nomprov"]);
				$objcliente->setDepartament($value["nomdpto"]);
				$objcliente->setDistrict($value["nomdis"]);
				 

				$objcliente->setDateSignedClient($this->objFecha->fun_fech_letras(fechan_abd($value["fechafirma"])));
				

				$objcliente->setIdNationality($value["idnacionalidad"]);


			}
			$objcliente->setUif($value["uif"]); 
			$this->dataCliente[]=$objcliente;		

			//data para qlos que firman
			if($value["firma"]==1)
				$this->dataClienteSigned[]=$objcliente;

			$i++;
		
			
		}
		return $this->dataCliente;
	}

	public function runComparecientesTitulo()
	{	
		$listOtorgante=array();
		$listBeneficiario=array();
		
		foreach ($this->getResponse() as $value) {
			if($value["uif"]=="O" || $value["uif"]=="0" )
				$listOtorgante[]=$value;
			if($value["uif"]=="B")
				$listBeneficiario[]=$value;
		}
		$contadotOtorgante=0;
		$cantidadOtor=sizeof($listOtorgante);			
		foreach ($listOtorgante as  $value) {
			$contadotOtorgante++;

					$name="";
				if($value["tipper"]=="N")
					$name=$value["prinom"]." ".$value["segnom"]." ".$value["apepat"]." ".$value["apemat"];
				if($value["tipper"]=="J")
					$name=$value["razonsocial"];
				
				if($contadotOtorgante==1)
					$this->grantorClients.=$name;	
				else if($cantidadOtor>1) {
					if($contadotOtorgante==$cantidadOtor)
						$this->grantorClients.=" y ".$name;	
					else
						$this->grantorClients.="; ".$name;	
					
				}

		}


		$contadotBeneficiario=0;
		$cantidadBene=sizeof($listBeneficiario);
		foreach ($listBeneficiario as  $value) {
				$contadotBeneficiario++;
				$name="";
				if($value["tipper"]=="N")
					$name=$value["prinom"]." ".$value["segnom"]." ".$value["apepat"]." ".$value["apemat"];
				if($value["tipper"]=="J")
					$name=$value["razonsocial"];


				if($cantidadBene==1)
					$this->inFavorClients.=$name;	
				else if($cantidadBene>1) {
					if($contadotBeneficiario==$cantidadBene)
						$this->inFavorClients.=" y ".$name;	
					else
						$this->inFavorClients.=", ".$name;	
					
				}
		}


		if($this->grantorClients!="")
			$this->grantorText.="QUE OTORGA";

		if($this->inFavorClients!="")
			$this->inFavorText.="A FAVOR DE";
		
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function getKardex()
	{
		return $this->kardex;
	}
	public function setKardex($kardex)
	{
		$this->kardex=$kardex;
	}

	public function getTitulo()
	{
		return $this->titulo;
	}
	public function setTitulo($titulo)
	{
		$this->titulo=$titulo;
	}

	public function getNumWriting()
	{
		return $this->numWriting;
	}
	public function setNumWriting($numWriting)
	{
		$this->numWriting=$numWriting;
	}

	public function getNumMinuta()
	{
		return $this->numMinuta;
	}

	public function setNumMinuta($numminuta)
	{
		$this->numMinuta=$numminuta;
	}

	public function getDateConclusion()
	{
		return $this->dateConclusion;
	}
	public function setDateConclusion($dateConclusion)
	{
		$this->dateConclusion=$dateConclusion;
	}

	public function setPaperIni($paperIni)
	{
		$this->paperIni=$paperIni;
	}

	public function getPaperIni()
	{
		return $this->paperIni;
	}
	public function setPaperEnd($paperEnd)
	{
		$this->paperEnd=$paperEnd;
	}
	public function getPaperEnd()
	{
		return $this->paperEnd;
	}

	public function getDateGeneration()
	{
		return $this->dateGeneration;
	}
	public function setDateGeneration($dateGeneration)
	{
		$this->dateGeneration=$dateGeneration;
	}


	public function getGrantorText()
	{
		return $this->grantorText;
	}
	public function getGrantorClients()
	{
		return $this->grantorClients;
	}
	public function getInFavorText()
	{
		return $this->inFavorText;
	}
	public function getInFavorClients()
	{
		return $this->inFavorClients;
	}

	public function setFolioInitial($folioInitial)
	{
		$this->folioInitial=$folioInitial;
	}
	public function getFolioInitial()
	{
		return $this->folioInitial;
	}


	public function setFolioEnd($folioEnd)
	{
		$this->folioEnd=$folioEnd;
	}
	public function getFolioEnd()
	{
		return $this->folioEnd;
	}


	public function setNameUser($nameuser)
	{
		$this->nameuser=$nameuser;
	}
	public function getNameUser()
	{
		return $this->nameuser;
	}




	public function setImporte($importe)
	{
	  $this->importe=$importe;
	}

	public function getImporte()
	{
		return $this->importe;
	}

	public function getAppearingText()
	{	

		$cont=0;
			$sexo="";
			foreach ($this->getResponse() as $value) {
					if($value["uif"]=="B" OR $value["uif"]=="O" OR $value["uif"]=="0" ){
						$cont++;
						$sexo=$value["sexo"];
					}
					
			}

		if($this->getNumMinuta()!="" && $this->getNumMinuta()!="S/M"){
			if($cont>1)
				$this->appearingText="LOS COMPARECIENTES: A QUIENES IDENTIFICO, SON MAYORES DE EDAD E INTELIGENTES EN EL IDIOMA CASTELLANO, QUIENES PROCEDEN CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HAN SIDO ADVERTIDAS SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y ME ENTREGAN UNA MINUTA FIRMADA PARA QUE SU CONTENIDO SEA ELEVADO A ESCRITURA PÚBLICA, LA MISMA QUE ARCHIVO EN MI LEGAJO RESPECTIVO BAJO EL NÚMERO DE ORDEN CORRESPONDIENTE Y CUYO TENOR LITERAL ES EL SIGUIENTE.";
			else{
				if($sexo=="F")
				$this->appearingText="EL COMPARECIENTE: A QUIEN IDENTIFICO, ES MAYOR DE EDAD E INTELIGENTE EN EL IDIOMA CASTELLANO, QUIEN PROCEDE CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HA SIDO ADVERTIDO SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y ME ENTREGA UNA MINUTA FIRMADA PARA QUE SU CONTENIDO SEA ELEVADO A ESCRITURA PÚBLICA, LA MISMA QUE ARCHIVO EN MI LEGAJO RESPECTIVO BAJO EL NÚMERO DE ORDEN CORRESPONDIENTE Y CUYO TENOR LITERAL ES EL SIGUIENTE. ";
				else 
				$this->appearingText="LA COMPARECIENTE: A QUIEN IDENTIFICO, ES MAYOR DE EDAD E INTELIGENTE EN EL IDIOMA CASTELLANO, QUIEN PROCEDE CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HA SIDO ADVERTIDA SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y ME ENTREGA UNA MINUTA FIRMADA PARA QUE SU CONTENIDO SEA ELEVADO A ESCRITURA PÚBLICA, LA MISMA QUE ARCHIVO EN MI LEGAJO RESPECTIVO BAJO EL NÚMERO DE ORDEN CORRESPONDIENTE Y CUYO TENOR LITERAL ES EL SIGUIENTE.";
			}
		}else if($this->getNumMinuta()=="S/M" || $this->getNumMinuta()=="")
		{
			if($cont>1)
			{
				$this->appearingText="LOS COMPARECIENTES: A QUIENES IDENTIFICO SON MAYORES DE EDAD E INTELIGENTES EN EL IDIOMA CASTELLANO, QUIENES PROCEDEN CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HAN SIDO ADVERTIDAS SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y OTORGAN LA PRESENTE ESCRITURA PUBLICA CONFORME".$this->getAdditionalNotMinute()." ARTICULO 58 DEL DECRETO LEGISLATIVO 1049.";
			}else{
				if($sexo=="F")
					$this->appearingText="LA COMPARECIENTE: A QUIEN IDENTIFICO ES MAYOR DE EDAD E INTELIGENTE EN EL IDIOMA CASTELLANO, QUIEN PROCEDE CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HA SIDO ADVERTIDA SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y OTORGAN LA PRESENTE ESCRITURA PUBLICA CONFORME".$this->getAdditionalNotMinute()." ARTICULO 58 DEL DECRETO LEGISLATIVO 1049.";
				else 
					$this->appearingText="EL COMPARECIENTE: A QUIEN IDENTIFICO ES MAYOR DE EDAD E INTELIGENTE EN EL IDIOMA CASTELLANO, QUIEN PROCEDE CON CAPACIDAD, LIBERTAD Y CONOCIMIENTO BASTANTE PARA CONTRATAR HA SIDO ADVERTIDO SOBRE LOS EFECTOS Y OBLIGACIONES LEGALES DE ESTE INSTRUMENTO PÚBLICO Y OTORGAN LA PRESENTE ESCRITURA PUBLICA CONFORME".$this->getAdditionalNotMinute()." ARTICULO 58 DEL DECRETO LEGISLATIVO 1049.";
			}
		}
		return $this->appearingText;
	}

	public function getMinuteText()
	{

		if($this->numMinuta!="S/M")
			$this->minuteText="MINUTA : SEÑOR NOTARIO";
		return $this->minuteText;
	}

	public function getConstCertificate()
	{

		if($this->getIdActType()=="033" or  $this->getIdActType()=="036")
			$this->constCertificate="CERTIFICO: QUE LOS OTORGANTES EXHIBIERON EL FORMATO DE LA PERSONA NATURAL QUE CALIFICA COMO BENEFICIARIO FINAL DE CONFORMIDAD CON EL ARTÍCULO 10 DEL DECRETO SUPREMO N°003-2019-EF";
		return $this->constCertificate;
	}
	public function getDataConstancy()
	{
			$i=0;
			$j=0;
			foreach ($this->getResponse() as $value) {
				if($value["tipper"]=="N"){
						 if($value["idtipdoc"]==2){
						 	$info["subtitulo"]="CONSTANCIA.- D.LEG N° 1232.-";
						 	$info["constancia"]="EL NOTARIO QUE AUTORIZA DEJA EXPRESA CONSTANCIA QUE ACCEDIO A LA BASE DE DATOS DEL REGISTRO DE CARNÉS DE EXTRANJERIA DE LA SUPERINTENDENCIA NACIONAL DE MIGRACIONES, CONFORME AL INCISO C) DEL ARTICULO 55 DEL DECRETO LEGISLATIVO N° 1049.";
						 }else if($value["idtipdoc"]==5){
						  	$info["subtitulo"]="CONSTANCIA.- D.LEG N° 1232.-";
						  	$info["constancia"]="EL NOTARIO QUE AUTORIZA DEJA EXPRESA CONSTANCIA QUE ACCEDIO A LA BASE DE DATOS DEL REGISTRO DE PASAPORTES DE LA SUPERINTENDENCIA NACIONAL DE MIGRACIONES, CONFORME AL INCISO C) DEL ARTICULO 55 DEL DECRETO LEGISLATIVO N° 1049.";
						 }else{
						 	$i++;
						  	$info["subtitulo"]="CONSTANCIA.- D.LEG N° 1232.-";
						  	$info["constancia"]="EL NOTARIO QUE AUTORIZA DEJA EXPRESA CONSTANCIA QUE SE HA VERIFICADO LA IDENTIDAD DE LOS INTERVINIENTES UTILIZANDO LA COMPARACIÓN BIOMÉTRICA DE LAS HUELLAS DACTILARES A TRAVÉS DEL SERVICIO QUE BRINDA EL RENIEC, CONFORME AL INCISO A) DEL ARTÍCULO 55 DEL DECRETO LEGISLATIVO N° 1049.";
						  }
						  $this->dataConstancy[]=$info;
						 $j++;
				}
				  
			}
			if($i==$j)
			{
				$this->dataConstancy=array();
				$info["subtitulo"]="CONSTANCIA.- D.LEG N° 1232.-";
				$info["constancia"]="EL NOTARIO QUE AUTORIZA DEJA EXPRESA CONSTANCIA QUE SE HA VERIFICADO LA IDENTIDAD DE LOS INTERVINIENTES UTILIZANDO LA COMPARACIÓN BIOMÉTRICA DE LAS HUELLAS DACTILARES A TRAVÉS DEL SERVICIO QUE BRINDA EL RENIEC, CONFORME AL INCISO A) DEL ARTÍCULO 55 DEL DECRETO LEGISLATIVO N° 1049.";

				$this->dataConstancy[]=$info;
			}
		return $this->dataConstancy;
	}


	public function getIdActType()
	{
		return $this->idActType;
	}
	public function setIdActType($idActType)
	{
		$this->idActType=$idActType;
	}
	public function getAdditionalNotMinute()
	{
		if($this->getIdActType()=="888")
			return " CONFORME AL INCISO A) DEL";
		return " AL";
	}

}



function fechan_abd($fechan){
	if($fechan<>""){
	//vamos a suponer que recibmos el formato MySQL básico de DD-MM-YYYY
    list($dd,$mm,$yy)=explode("/",$fechan);
	$fecha = $yy.'-'.$mm.'-'.$dd;
	return $fecha;
	}
}


 ?>