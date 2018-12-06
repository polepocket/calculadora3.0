<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromosController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $xmlData = "(Clave interna de la promoción:".$id.")";
        $ch = curl_init('https://crm.zoho.com/crm/private/json/CustomModule23/searchRecords');
        curl_setopt($ch, CURLOPT_VERBOSE, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); 
    
        $authtoken = "0fc99739003c3133fc7b7fab6b99c387";
        $query = "authtoken=" . $authtoken . "&scope=crmapi&criteria=" . $xmlData."&fromIndex=1&toIndex=20";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); 
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response);
        
        if(isset($json->response->nodata))
        {        
            $result['status'] = FALSE ;
            $result['promocion'] = FALSE ;
    
            //SI NO EXISTE LA PROMOCIÓN CON ESTA CLAVE INTERNA, SE BUSCA EN LOS CÓDIGOS DE REFERIDOS
            $xmlData = "(Codigo de referido:".$id.")";
            $ch = curl_init('https://crm.zoho.com/crm/private/json/Contacts/searchRecords');
            curl_setopt($ch, CURLOPT_VERBOSE, 1); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1); 
    
            $authtoken = "0fc99739003c3133fc7b7fab6b99c387";
            $query = "authtoken=" . $authtoken . "&scope=crmapi&criteria=" . $xmlData."&fromIndex=1&toIndex=20";
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query); 
            $response = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($response);
    
            if(isset($json->response->nodata))
            {        
                $result['status'] = FALSE ;
                $result['referido'] = FALSE;
            }
            else{
                $result['status'] = TRUE;
                $result['referido'] = TRUE;
                $json_array=$json->response->result->Contacts->row->FL;
                for ($i=0; $i < count($json_array); $i++) {
                    if($json_array[$i]->val == 'Full Name') 
                    $result['contacto'] = $json_array[$i]->content;
                }
            }
    
        }
        else{
            $result['status'] = TRUE;
            $result['vigente'] = TRUE;
            $result['promocion'] = TRUE;
            $result['codigo'] = $id;
            
            $json_array=$json->response->result->CustomModule23->row->FL;
            for ($i=0; $i < count($json_array); $i++) {
                if($json_array[$i]->val == 'Fin de promoción') 
                {
                    $fecha_fin = $json_array[$i]->content;
                    $fecha_actual = date('Y-m-d');
                    if($fecha_actual > $fecha_fin)
                    {
                        $result['vigente'] = FALSE;
                    }
                }
                if($json_array[$i]->val == 'Nombre') 
                {
                    $result['nombre_promo'] = $json_array[$i]->content;                  
                }
                
            }
        }     
        return $result;
    }
}
