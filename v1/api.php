<?php 

require_once('../model/operacao.php');


function isTheseParametersAvailable($params){
$available =true;
$missingparams ="";

foreach($params as $param){
if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
    $available =false;
    $missingparams =$missingparams.",".$param;
}

}
if(!$available){
 $response = array();
 $response['error'] = true;
 $response['message'] = 'Parameters '.substr($missingparams, 1, strlen($missingparams)).'missing';

 echo jsaon_encode($response);

 die();
}
}

$response = array();

if(isset($_GET['apicall'])){

    switch($_GET['apicall']){

        case 'createFruta':
            isTheseParametersAvailable(Array('campo_2,','campo_3','campo_4',));
            $db = new Operacao();

            $result = $db->createFruta(
$_POST['campo_2'],
$_POST['campo_3'],
$_POST['campo_4'],

            );
            if($result){
                $response['error']=false;
                $response['message']= 'Dados inseridos com sucesso.';
                $response['dadoscreate'] = 
                $db->getFrutas();
            }else{
                $response['error'] = true;
                $response['message']= 'Dados não foram inseridos.';
            }
            break;
            case 'getFrutas':
                $db= new Operacao();
                $response['error'] = false;
                $response['message']= 'Dados listados com sucesso.';
                $response['dadoslista']=$db->getFrutas();
                
                break;
                case 'updateFrutas':
                    isTheseParametersAvailable(Array('campo_1','campo_2,','campo_3','campo_4',));

                    $db = new Operacao();
                    $result = $db->updateFrutas(
                        $_POST['campo_1'],
                    );

                    if($result){
                        $response['error'] = false;
                        $response['message']= 'Dados alterados com sucesso.';
                        $response['dadosalterar']= 
                        $db->getFrutas();
                    }else{
                        $response['error'] = true;
                        $resposne['message'] = "Dados não alterados.";
                    }
                    break;
                    case 'deleteFrutas':
                        if(isset($_GET['uid'])){
                         $db = new Operacao();
                         if($db->deleteFrutas($_GET['uid'])){
                            $response['error'] = false;
                            $response['message']= 'Dado excluido com sucesso.';
                            $response['deleteFrutas']=$db->getFrutas();
                         }else{
                             $response['error'] = true;
                             $response['message'] = "Algo deu errado";
                         }
                       
                        }else{
                            $response['error'] = true;
                            $response['message'] = "Dados não apagados.";
                        }
                        break;
                    
    }
}else{
    $response['error'] = true;
    $response['message'] = "Chamada sw Api com defeito";
}
echo json_encode($response);
