<?php
 
class DB_Connect {
 
    // constructor
    function __construct() 
    {
         
    }
 
    // destructor
    function __destruct() 
    {
        // $this->close();
    }
 
    // Connecting to database
    public function connect() 
    {
//        require_once 'Config.conf';
        try{
                //conexin a base de datos
                $con = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
               // $con -> set_charset("utf8");
                $con->set_charset("utf8");    
                $con->query("SET NAMES 'utf8'");
                    
              }catch (mysqli_sql_exception $e)
                   {
                    my_status_header(500);
                    exit;
        }   

        // return database handler
        return $con;
    }
 

    
     public function getTipoInstrumentoById($id)
    {
        $data_instr=$this->getTipoInstrumento();
        $sdetalle="";
        foreach ($data_instr as $row) {
            if($row["NUM_TIPO"]==$id)
               $sdetalle.="'".$row["TIPO"]."',";
        }
        $sdetalle=substr($sdetalle,0,-1);
        return $sdetalle;
   }







  public function addOrUpdate($info,$queryAddValidation="")
  {


    if(sizeof($info)>0)
    {

          $allCampos=array();
          $allTipo="";
          $xtipoPk="";
          $allValores=array();

          $xtabla=$info["tabla"];
          $xdata="";
          $campoPrincipal="";
          $valorCampoPrincipal="";

          $adicionalWhere="";
          $adicionalWhere2="";
          $parametrosAdicionalWhere="";
          $allWhere=array();

          $data=$info["data"];
     
          foreach ($data as $key => $value) {
            if(isset($value["llave"])!="" && $value["llave"]=="pk")
            {
                $valorCampoPrincipal=($value["valor"]);
                $campoPrincipal=($value["campo"]);
                $xtipoPk=$value["type"];
            }else
            {

               if(isset($value["campo"])){
                  if(isset($value["date"]) && $value["date"]){
                      if($value["valor"]=="")
                        $value["valor"]="0000-00-00";
                      else if($value["valor"]!="" && $value["valor"]!="0000-00-00")
                        $value["valor"]=date("Y-m-d", strtotime($value["valor"]));
                      
                   //   var_dump($value);  
                  } 

                  $allCampos[]=$value["campo"];
                  $allTipo.=$value["type"];
                  $allValores[]=mb_strtoupper($value["valor"],'UTF-8');
                    if(isset($value["compare"]) && $value["compare"]!=""){
                        $adicionalWhere.=" ".$value["compare"]." ".$value["campo"]."=? ";
                        $adicionalWhere2.=" ".$value["compare"]." ".$value["campo"]." = '".$value["valor"]."' ";
                        $allWhere[]=$value["valor"];
                    }

                       // $adicionalWhere=" ".$value["compare"]." ".$value["campo"]."=? ";
               }
               
            }


          } 

    // die($adicionalWhere2);

      $allTipo=trim($allTipo).$xtipoPk;
      
      $db=$this->connect();

      $sql="SELECT ".$campoPrincipal." FROM ".$xtabla." WHERE ".$campoPrincipal."=".$valorCampoPrincipal;
      if($adicionalWhere2!="")
        $sql.=" ".$adicionalWhere2;

 //  die($sql);

      $query=mysqli_query($db,$sql) or die("Error 1".mysqli_error($db));
      $numrows=mysqli_num_rows($query);


     
      if($numrows>0)
      { 
        $sql="UPDATE ".$xtabla." SET ";
        foreach ($allCampos as  $value) {
          $sql=$sql." ".$value." =? ,";
        }
        $sql=substr($sql,0,-1);
        $sql.=" WHERE ".$campoPrincipal." = ?";

        if($adicionalWhere!="")
          $sql.=" ".$adicionalWhere;


      }else
      {
        $sql="INSERT INTO  ".$xtabla." (";
        foreach ($allCampos as  $value) {
          $sql.=" ".$value.",";
        }
          $sql.="".$campoPrincipal;
//        $sql=substr($sql,0,-1);

        $sql.=")";
        $sql.="VALUES (";
        foreach ($allCampos as  $value) {
          $sql.=" ?,";
        }
        $sql.="?";
        //$sql=substr($sql,0,-1);
        
        $sql.=")";

      }
          
          $lengthValores=sizeof($allValores);
          $allValores[$lengthValores]=$valorCampoPrincipal;
                
          if($numrows>0)
          {
            $lengthValores=sizeof($allValores);
             foreach ($allWhere as $j => $value) {
              $allValores[$lengthValores+$j]=$value;
              $allTipo.="s";
            }           
          }
          //die($sql);
          $stmt = $db->prepare($sql);      
          if($stmt){
            $values="";
         
//           $stmt->bind_param($allTipo,...$allValores);
            $params = array_merge(array($allTipo), $allValores);

           call_user_func_array(array($stmt, 'bind_param'), $params);

            $rc=$stmt->execute();
            if ( false===$rc ) {
                die('execute() failed: '.$stmt->error);
            }
            $stmt->close();
          }else
          {
            var_dump($stmt);
            die("Error 2 ::.. ".$stmt->error);
          }
      
       $db->close();
      }
  }

   

 
}
 
?>