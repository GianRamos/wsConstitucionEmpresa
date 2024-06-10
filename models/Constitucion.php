<?php 

if(!class_exists('DB_Connect') ) 
    include "conexion/DB_Connect.php";




class Models_Constitucion extends DB_Connect {
        
    private $DIR_BASE="C:/FILES_ELECTNOTARIAL/";
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
    
    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return Array array con los registros obtenidos de la base de datos
     */
    
 public   function getNegrita($texto)
{
  $result="";
  if($texto!="")
  {
    $arrTexo=explode(" ",$texto);
    foreach ($arrTexo as $value) {
    	$value=trim($value);
    	if($value!="")
	        $result.="#".$value."# ";
    }
  }
  return ($result);
}

    private function extraer_decimal($cadena) {
        $patron = '/[\d\.]+/';
        preg_match($patron, $cadena, $matches);
        return isset($matches[0]) ? floatval($matches[0]) : null;
    }
    public function add()
       {
         
            $db=$this->connect();
            $request_body = file_get_contents('php://input');
            $data=json_decode($request_body,true);
            
            $opciones=isset($data["step1"]["company"])?strtoupper($data["step1"]["company"]):"";
            $numsocios=isset($data["step1"]["numsocios"])?strtoupper($data["step1"]["numsocios"]):"";
            
            
            $tipoCapital=isset($data["step2"]["capitalType"])?strtoupper($data["step2"]["capitalType"]):"";
            $montoCapital=isset($data["step2"]["amount"])?strtoupper($data["step2"]["amount"]):"";
            $nombresEmpresas=isset($data["step3"])?($data["step3"]):null;
            $actividades=isset($data["step3"]["activities"])?($data["step3"]["activities"]):"";
            $location=isset($data["step4"]["location"])?($data["step4"]["location"]):"0";
            $direccionFirma=isset($data["step4"]["domicilio"]["address"])?($data["step4"]["domicilio"]["address"]):"";
            
            $numDoc=isset($data["step5"]["documentNumber"])?($data["step5"]["documentNumber"]):"";
            $tipoDoc=isset($data["step5"]["documentType"])?($data["step5"]["documentType"]):"";
            $email=isset($data["step5"]["email"])?($data["step5"]["email"]):"";
            $apellidos=isset($data["step5"]["apellido"])?($data["step5"]["apellido"]):"";
            $celular=isset($data["step5"]["mobile"])?($data["step5"]["mobile"]):"";
            $nombres=isset($data["step5"]["name"])?($data["step5"]["name"]):"";
            $terminos=isset($data["step5"]["terms"])?($data["step5"]["terms"]):"";
            $formaPago=isset($data["step6"]["paymentOption"])?($data["step6"]["paymentOption"]):"";
            $metodoPago=isset($data["step6"]["paymentMethod"])?($data["step6"]["paymentMethod"]):"";
            
            $file=isset($data["fileSelectedPay"])?$data["fileSelectedPay"]:"";
            $fileName=isset($data["fileSelectName"])?$data["fileSelectName"]:"";
            
           
                //ADJUNTAR DOCUMENTO
                $nombreArchivo = $fileName;
                $nombreArchivo=str_replace("_","",$nombreArchivo);
                $info = new SplFileInfo($nombreArchivo);
                $extension=$info->getExtension();
                $filePath="";
                if($extension!=""){
                  $archivo = $file;
                  $archivo = base64_decode($archivo);

                  $filePath="files/".uniqid().".".$extension;
                  file_put_contents($filePath, $archivo);            
                }
            
            $tipoFirma="0";
            if($location=="notaria")
                $tipoFirma=1;
            else if($location=="domicilio")
                $tipoFirma=2;
                
            
            
            $sql="INSERT INTO expediente (opciones,tipocapital,montocapital,detalle_actividades,tipo_firma,direccion,numero_documento,tipo_documento,correo,apellido,celular,nombre,forma_pago,metodo_pago,archivo,num_socios,lugar_firma_escritura) 
                         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssssissssssssssis",$opciones,$tipoCapital,$montoCapital,$actividades,$tipoFirma,$direccionFirma,$numDoc,$tipoDoc,$email,$apellidos,$celular,$nombres,$formaPago,$metodoPago,$filePath,$numsocios,$location);
            $stmt->execute();
            
            $insertedId = $stmt->insert_id;
            foreach ($nombresEmpresas as $objNombre) {
                if (is_array($objNombre) && isset($objNombre["name"])) {
                     $nombre=$objNombre["name"];
                     $abrev=$objNombre["abbreviation"];
                     $sqlNombre="INSERT INTO expediente_nombre_empresa (idexpediente,nombre_empresa,abreviatura) 
                         VALUES (?,?,?) ";
                        $stmt = $db->prepare($sqlNombre);
                        $stmt->bind_param("iss",$insertedId,$nombre,$abrev);
                        $stmt->execute();
                }

            }
            
            $respon=array('msg' =>'Correcto' , 'estado'=>'success');
            return $respon;
       }
       
       public function getListInformacionPorId(){
            $id = $_GET["id"];
            $db=$this->connect();
            $sql="SELECT id,idtipo_empresa,denominacion,capital_social, fecha_actual,'' as objeto_social,idplantilla
            FROM crm_constitucion_empresa where id=$id";
            $query=mysqli_query($db,$sql) or die("Error Select ".mysqli_error($db));
            $objConstitucion=mysqli_fetch_assoc($query);
            
            $sqlCont="select id,idtipodoc as tipodoc,numdoc,cliente,ocupacion,idestado_civil as idestadocivil,domicilio,distrito,provincia,departamento,
                    cantidad_acciones,monto_aportado
                    from  crm_constitucion_cliente where activo=1 and idconstitucion_empresa=$id";
            $queryCont=mysqli_query($db,$sqlCont) or die("Error Select ".mysqli_error($db));
            $allConstitucion=[];
            while($row=mysqli_fetch_assoc($queryCont)){
                $allConstitucion[]=$row;
            }
            
            $sqlObjetoSoc="select id,descripcion
                    from  cms_objeto_social where activo=1 and idconstitucion_empresa=$id";
            $queryObjeto=mysqli_query($db,$sqlObjetoSoc) or die("Error Select ".mysqli_error($db));
            $allObjetoSoc=[];
            while($rowObjetoS=mysqli_fetch_assoc($queryObjeto)){
                $allObjetoSoc[]=$rowObjetoS;
            }
            
            
            return array('constitucion'=>$objConstitucion,'contratantes'=>$allConstitucion,'objeto'=>$allObjetoSoc);
       }
       
       public function getParticipantePorId(){
            $id = $_GET["id"];
            $db=$this->connect();

            $sql="select c.id,idtipodoc,numdoc,cliente,ocupacion,idestado_civil,domicilio,distrito,provincia,departamento,
                        cantidad_acciones,monto_aportado,em.idtipo_empresa,td.descripcion as tipo_documento,
                        ec.descripcion as estado_civil
                    from  crm_constitucion_cliente c 
                    inner join crm_constitucion_empresa em on c.idconstitucion_empresa=em.id 
                    left join cms_tipodocumento td on c.idtipodoc=td.id
                    left join cms_estado_civil ec on c.idestado_civil=ec.id
                    where c.activo=1 and c.id=$id";
            $query=mysqli_query($db,$sql) or die("Error Select ".mysqli_error($db));
            $all=[];
            $row=mysqli_fetch_assoc($query);
            return $row;
       }
       public function getList(){
             $db=$this->connect();
             $data = $_GET["data"];
             $data=json_decode($data,true);
             
            $pageSize=Config_Utiles::getParametroSaneado($data,"pageSize",array('required'=>true,'type'=>'number'));
            $pageIndex=Config_Utiles::getParametroSaneado($data,"pageIndex",array('required'=>true,'type'=>'number'));
            $pageIndex=$pageIndex+1;
            
            $sql="
                    SELECT const.id,const.idtipo_empresa,const.denominacion,const.capital_social,
                    DATE_FORMAT(const.fecha_actual, '%d/%m/%Y') as fecha_actual,const.objeto_social,
                    DATE_FORMAT(const.fecha_registro, '%d/%m/%Y') as fecha_registro,
                    ( select  GROUP_CONCAT(CONCAT(cliente, '')  SEPARATOR '\n')
                    from crm_constitucion_cliente where activo=1 and idconstitucion_empresa=const.id ) as solicitante
                    FROM crm_constitucion_empresa const
                    WHERE activo=1
                    order by const.id desc
                    
            ";
            
            //die($sql);
            
            $pageInit=0;
            if ($pageIndex > 0)
                $pageInit = ($pageIndex - 1) * $pageSize;
            if ($pageSize > 0)
                $sql .= " LIMIT ".($pageInit>0?$pageInit.",":"")." ".$pageSize;
            else 
                $sql.=" LIMIT 50 ";
             
            $stmt = $db->prepare($sql);
           // $stmt->bind_param("i",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            //$row = $result->fetch_assoc();
            $rows = array(); // Inicializa un array para almacenar todas las filas
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row; // Agrega cada fila al array
            }
            $stmt->close();
            $db->close();
            
//            var_dump($rows);
            return $rows;
       }
       
       
       public function getListCount(){
             $db=$this->connect();
             $data = $_GET["data"];
             $data=json_decode($data,true);
            $sql="
                    SELECT count(1) as total FROM crm_constitucion_empresa  cons
                     WHERE activo=1
            ";
            $stmt = $db->prepare($sql);
           // $stmt->bind_param("i",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            //$row = $result->fetch_assoc();
            $rows = array(); // Inicializa un array para almacenar todas las filas
            $row = $result->fetch_assoc();
            $total=$row["total"];
            $stmt->close();
            $db->close();
           
            return $total;
       }
       
    public function delete()       
    {
           $db=$this->connect();
           $id=$_REQUEST["id"];
             $sql="
                UPDATE crm_constitucion_empresa set activo=0 where id=$id";     
    
            //die($sql);
           mysqli_query($db,$sql) or die("Error 1".mysqli_error($db));
            
        return array();
    }
    public function deleteObjetoSocial(){
         $db=$this->connect();
           $id=$_REQUEST["id"];
             $sql="
                UPDATE cms_objeto_social set activo=0 where id=$id";     
    
            //die($sql);
           mysqli_query($db,$sql) or die("Error 1".mysqli_error($db));
        $respon=array('msg' =>'Correcto' , 'estado'=>100);
        return $respon;
    }
    
    
    public function deleteParticipante(){
         $db=$this->connect();
           $id=$_REQUEST["id"];
             $sql="
                UPDATE crm_constitucion_cliente set activo=0 where id=$id";     
    
            //die($sql);
           mysqli_query($db,$sql) or die("Error 1".mysqli_error($db));
        $respon=array('msg' =>'Correcto' , 'estado'=>100);
        return $respon;
    }
    

       public function buscardoc()
       {
           $data = $_GET["data"];
           
           $url = 'http://www.notariadigital.org.pe:4000/consultadocumento/query.php?numdoc=47386983';

              $curl = curl_init();
             curl_setopt_array($curl, array(
             CURLOPT_URL => $url,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_SSL_VERIFYHOST=>0,
             CURLOPT_SSL_VERIFYPEER=>0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'GET'
          ));
           $response = curl_exec($curl);
           if(curl_errno($curl))
           {
            echo curl_error($curl);
            return;
           }
           curl_close($curl);
           $response=json_decode($response);
           return $response;
            
         

       }
        public function getListSolicitanteById()
        {
             $db=$this->connect();
             $data = $_GET["data"];
             $data=json_decode($data,true);

             $id=Config_Utiles::getParametroSaneado($data,"id",array('required'=>true,'type'=>'number'));
             
              $stmt = $db->prepare(" SELECT id,idtipodoc||'' as  tipodoc,numdoc,nombre FROM `crm_legalizacion_solicitante` where idlegalizacion=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            //$row = $result->fetch_assoc();
            $rows = array(); // Inicializa un array para almacenar todas las filas
            while ($row = $result->fetch_assoc()) {
                $row["tipodoc"]= "".$row["tipodoc"];
                $rows[] = $row; // Agrega cada fila al array
            }
            $stmt->close();
            $db->close();
            return $rows;
        }


}
?>