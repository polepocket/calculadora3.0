<?php
namespace App\Http\Controllers;
use App\Recibos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReciboController extends Controller
{
    public function index()
    {   
    }

    public function store(Request $request)
    {   
        $token      = "0fc99739003c3133fc7b7fab6b99c387";
        $lead_id    = $request->get('lead_id');//"1850660000073289942";
        // $name_file  = $request->get('name_file');
        $name_file  = 'recibo_'.$lead_id.'.'.$request->file('file')->extension();
        $name       = $request->file('file')->storeAs(
            'public', $name_file
        );
        $path = '../storage/app/public/'.$name_file;
        $ch=curl_init("https://crm.zoho.com/crm/private/json/Leads/uploadFile?authtoken=$token&scope=crmapi&id=$lead_id");
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_VERBOSE,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_POST,true);

        $queryzoho=array("content"=> curl_file_create($path));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryzoho);   

        $response=curl_exec($ch);
        curl_close($ch);
        Storage::delete($name_file);
        return $response;
    }
}
