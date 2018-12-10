<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadsController extends Controller
{
    // public function sendNotes(Request $request, $id){
    //     $token      = "0fc99739003c3133fc7b7fab6b99c387";
    //     $consumption      = $request->input('consumption');
    //     $consumption_tmp  = $request->input('consumption_tmp');
       
    //     // Set XML Data
    //     $xmlData = '
    //     <Notes>
    //         <row no="1">
    //         <FL val="entityId">'.$id.'</FL>
    //         <FL val="Note Title">Cambio de Consumo</FL>
    //         <FL val="Note Content">Cambió el consumo de '.$consumption_tmp.' a '.$consumption.'</FL>
    //         </row>
    //     </Notes>
    //     '; 
    //     header("Content-type: application/xml");
    //     $url = "https://crm.zoho.com/crm/private/xml/Notes/insertRecords?newFormat=1&authtoken=$token&scope=crmapi";
        
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryzoho);
    //     $result = curl_exec($ch);
    //     curl_close($ch);
        
    //     echo json_encode($result);
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $token      = "0fc99739003c3133fc7b7fab6b99c387";
        $name       = $request->input('name');
        $last_name  = '';
        $email      = $request->input('email');
        $phone      = $request->input('phone');
        $category   = $request->input('category');
        $consumption= $request->input('consumption');
        $cp         = $request->input('cp');
        $cp_perfila = $request->input('cp_perfila');
        $lat        = $request->input('lat');
        $lng        = $request->input('lng');
        $promo_convenio = $request->input('promo_convenio');
        $contacto_refiere = $request->input('contacto_refiere');
        $nombre_completo = explode(" ", $name);
        $name = $nombre_completo[0];
        if(count($nombre_completo) > 1){
            $last_name = $nombre_completo[1];
        }
        else{
            $last_name = $nombre_completo[0];
        }
        if($request->input('source'))
        {
            if($request->input('source') == 'EH')
            {
                $source = 'Referencia desde Enlight Hub';
            }
            else{
                $source = "Digital - Calculadora New";
            }            
        }
        else {
            $source = "Digital - Calculadora New";
        }
        // Optional data
        if ($request->input('landing'))
        { $landing = $request->input('landing'); } else { $landing = '';}
        if ($request->input('external_key'))
        { $key = $request->input('external_key'); } else { $key = '';}
    
        // Set XML Data
        $xmlData = "
            <Leads>
              <row no='1'>
                <FL val='First Name'>".$name."</FL>
                <FL val='Last Name'>".$last_name."</FL>
                <FL val='Email'>".$email."</FL>
                <FL val='Phone'>".$phone."</FL>
                <FL val='Lead Source'>".$source."</FL>
                <FL val='Anuncio MKT'>".$key."</FL>
                <FL val='Landing page'>".$landing."</FL>
                <FL val='Promoción/Convenio'>".$promo_convenio."</FL>
                <FL val='Contacto que refiere'>".$contacto_refiere."</FL>
                <FL val='Zip Code'>".$cp."</FL>
                <FL val='Street'></FL>
                <FL val='City'></FL>
                <FL val='Estado de la República'></FL>
                <FL val='Colonia'></FL>
                <FL val='Categoría'>".$category."</FL>
                <FL val='Consumo bimestral'>".$consumption."</FL>
                <FL val='Description'>https://www.google.com/maps/place/".$lat.','.$lng."</FL>
                <FL val='Lead Owner'>rodolfo.escalante@enlight.mx</FL>";
        if($consumption < 2500){
            $xmlData.= "
                <FL val='Lead Status'>Perdido por tarifa</FL>
                <FL val='Motivo de rechazo'>Tarifa</FL>
            ";
        }
        else if(!$cp_perfila){
            $xmlData.= "
                <FL val='Lead Status'>Perdido</FL>
                <FL val='Motivo de rechazo'>Ubicación</FL>
            ";
        }
        else if($consumption > 2500 && $cp_perfila){
            $xmlData.= "<FL val='Lead Status'>Por Contactar</FL>";
        }
        $xmlData.= "
            </row>
        </Leads>
        "; 
        header("Content-type: application/xml");
        $url = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
        // $param= "authtoken=".$token."&scope=crmapi&xmlData={$xmlData}";
        $queryzoho = "wfTrigger=true&newFormat=2&authtoken=".$token."&scope=crmapi&duplicateCheck=2&xmlData={$xmlData}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryzoho);
        $result = curl_exec($ch);
        curl_close($ch);
        $return['result'] = $result;
        $xml = simplexml_load_string($result);
        if(isset($xml->result->recorddetail->FL[0][0]))
        {
            $return['idlead'] = $xml->result->recorddetail->FL[0][0];
        }
        else{
            $return['idlead'] = false;
        }
        
        // //Return the data back to form
        // echo json_encode($data);
        return $return;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $xmlData = "(Email:".$id.")";
        $ch = curl_init('https://crm.zoho.com/crm/private/json/Leads/searchRecords');
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
            $result['status'] = TRUE ;
        }
        else{
            $result['status'] = FALSE;
        }     
        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token      = "0fc99739003c3133fc7b7fab6b99c387";
        $xmlData = "<Leads>
                        <row no='1'>";
        if($request->input('lat') && $request->input('lng')){
            $lat        = $request->input('lat');
            $lng        = $request->input('lng');
            $xmlData .= "<FL val='Description'>https://www.google.com/maps/place/".$lat.','.$lng."</FL>";
        }
        if($request->input('consumption'))
        {
            $consumption= $request->input('consumption');
            $cp_perfila = $request->input('cp_perfila');
            $xmlData .= "<FL val='Consumo bimestral'>".$consumption."</FL>";
            if($consumption < 2500){
                $xmlData.= "
                    <FL val='Lead Status'>Perdido por tarifa</FL>
                    <FL val='Motivo de rechazo'>Tarifa</FL>
                ";
            }
            else if($consumption > 2500 && $cp_perfila){
                $xmlData.= "<FL val='Lead Status'>Por Contactar</FL>";
            }
        }
        $xmlData.= "
            </row>
        </Leads>
        "; 
        header("Content-type: application/xml");
        $url = "https://crm.zoho.com/crm/private/xml/Leads/updateRecords";
        $param= "authtoken=".$token."&scope=crmapi&xmlData={$xmlData}";
        $queryzoho = "wfTrigger=true&newFormat=2&authtoken=".$token."&scope=crmapi&duplicateCheck=2&id=".$id."&xmlData={$xmlData}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryzoho);
        $result = curl_exec($ch);
        curl_close($ch);
        $return['result'] = $result;
        $xml = simplexml_load_string($result);
        if(isset($xml->result->recorddetail->FL[0][0]))
        {
            $return['idlead'] = $xml->result->recorddetail->FL[0][0];
        }
        else{
            $return['idlead'] = false;
        }
        
        // //Return the data back to form
        // echo json_encode($data);
        return $return;
    }
}
