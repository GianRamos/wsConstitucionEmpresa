<?php 
class Api_ConstitucionAPI
{
     public function API(){
    // header('Content-Type: application/JSON');                
         $method = $_SERVER['REQUEST_METHOD'];
         switch ($method) {
         case 'GET'://consulta
             $this->get();
             break;     
         case 'POST'://inserta
             $this->post();
             break;                
         case 'PUT'://actualiza
             
             break;      
         case 'DELETE'://elimina
              $this->delete();
             break;
         default://metodo NO soportado
             echo 'METODO NO SOPORTADO';
             break;
        }
    }

       function get()
     {  

     $obj = new Models_Constitucion();
     $actions = [
        'list' => function () use ($obj) {
            $response = [];
            $data = $obj->getList();
            $total = $obj->getListCount();
            
            $response["data"] = $data;
            $response["totals"] = $total;
            
            echo json_encode($response);
        },
        'listSolicitanteById' => function () use ($obj) {
            $response = [];
            $data = $obj->getListSolicitanteById();
            $response["data"] = $data;
            
            echo json_encode($response);
        },
        'informacion_porid'=> function () use ($obj) {
            $response = [];
            $data = $obj->getListInformacionPorId();
            $response["data"] = $data;
            
            echo json_encode($response);
        },
        'participante_porid'=> function () use ($obj) {
            $response = [];
            $data = $obj->getParticipantePorId();
            $response["data"] = $data;
            
            echo json_encode($response);
        },
        'generarDoc' => function () use ($obj) {
            $data = $obj->generarDoc();
            $response["data"] = $data;
            echo json_encode($response);
        },
        'buscardoc' => function () use ($obj) {
            $data = $obj->buscardoc();
          //  $response["data"] = $data;
            echo json_encode($data);
        },
        
    ];

    if (isset($_GET['info']) && array_key_exists($_GET['info'], $actions)) {
        $actions[$_GET['info']]();
    } else {
        $this->response(400);
    }


     }  

       private function post()
     {  

       $obj = new Models_Constitucion();
        $actions = [
        'add' => function () use ($obj) {
            $data = $obj->add();
            echo json_encode($data);
        }
    ];

    if (isset($_GET['info']) && array_key_exists($_GET['info'], $actions)) {
        $actions[$_GET['info']]();
    } else {
        $this->response(400);
    }
     }  
     
     
         private function delete()
     {  

       $obj = new Models_ConstitucionEmpresa();
        $actions = [
        'delete' => function () use ($obj) {
            $data = $obj->delete();
            echo json_encode($data);
        },
        
        'delete_objeto_social' => function () use ($obj) {
            $data = $obj->deleteObjetoSocial();
            echo json_encode($data);
        },
        'delete_participante' => function () use ($obj) {
            $data = $obj->deleteParticipante();
            echo json_encode($data);
        },
        ];
        

        if (isset($_GET['info']) && array_key_exists($_GET['info'], $actions)) {
            $actions[$_GET['info']]();
        } else {
            $this->response(400);
        }
     }
     
 }

