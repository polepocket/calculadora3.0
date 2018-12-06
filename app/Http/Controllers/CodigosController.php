<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \App\Codigo;

class CodigosController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  varchar  $cp
     * @return \Illuminate\Http\Response
     */

    public function show($cp)
    {
        $criteria = "(Código postal:".$cp.")";
        $ch = curl_init('https://crm.zoho.com/crm/private/json/CustomModule9/searchRecords');
        curl_setopt($ch, CURLOPT_VERBOSE, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); 
        $authtoken = "0fc99739003c3133fc7b7fab6b99c387";
        $query = "authtoken=" . $authtoken . "&scope=crmapi&criteria=" . $criteria;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); 
        $response = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($response);
        $data = $json->response;
        if(isset($data->result))
        {
            $data = $data->result->CustomModule9->row;
            if(is_array($data))
            {
                for($i=0; $i < count($data); $i++){
                    for ($j=0; $j < count($data[$i]->FL) ; $j++) { 
                        if($data[$i]->FL[$j]->val == 'Zona atendida'){
                            $result['atendido'] = $data[$i]->FL[$j]->content;
                        }
                    }
                }
            }
            else {
                $data = $data->FL;
                for ($i=0; $i < count($data); $i++) {
                    if($data[$i]->val == 'Zona atendida') 
                    $result['atendido'] = $data[$i]->content;
                }   
            }
            return $result;            
        }
        else{
            $result['atendido'] = "false";
            return $result;  
        }
    }
}
