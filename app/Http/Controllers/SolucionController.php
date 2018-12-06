<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolucionController extends Controller
{
    public function calculationSolution(Request $request){
        $precios = array (
            array( 'precio' => 2053, 'min' => 4, 'max' => 7 ),
            array( 'precio' => 1955, 'min' => 8, 'max' => 15 ),
            array( 'precio' => 1840, 'min' => 16, 'max' => 24 ),
            array( 'precio' => 1725, 'min' => 25, 'max' => 1000 )
        );

        $preciosNL = array (
            array( 'precio' => 1862, 'min' => 4, 'max' => 7 ),
            array( 'precio' => 1852, 'min' => 8, 'max' => 15 ),
            array( 'precio' => 1677, 'min' => 16, 'max' => 24 ),
            array( 'precio' => 1570, 'min' => 25, 'max' => 1000 )
        );
        $pago_actual= $request->input('consumption');
        $id= $request->input('lead_id');
        $mty = $request->input('mty');
        
        $tipo_cambio = 20.51;
        $iva = 1.16;
        $rendimiento = 1450; //kWh/kWp
        $TDAC = 5; //$ kWh
        $T01 = 1; //$ kWh        
        $proteccion_DAC = 1.2;
        $proteccion_DAC_Mty = 1.3;
        $lim_DAC1 = 3000; //KwH
        $lim_DAC2 = 10200; //KwH 
        $potencia_panel = 0.33; //kWp
        $pago_actual= $request->input('consumption');
    //Consumo bimestral
        //Todos menos Monterrey o Monterrey  consumo_bimestral > $7000
        if(!$mty || ($pago_actual > 6999 && $mty )){
            $consumo_bimestral  = $pago_actual / $TDAC;
        }
        //MTY Tarifa 1C - Consumos entre $2500 y $7000
        else if($mty && $pago_actual > 2499 && $pago_actual < 6999){
            $consumo_bimestral = (($pago_actual - 770) / 2.802)+ 900 ;
        }
        $consumo_anual_total= $consumo_bimestral * 6;
        if(!$mty){// Todos menos Monterrey
            if((($consumo_anual_total - $lim_DAC1) * $proteccion_DAC) > $consumo_anual_total){
                $potencia_necesaria = $consumo_anual_total/$rendimiento;
            }else{
                $potencia_necesaria = (($consumo_anual_total - $lim_DAC1) * $proteccion_DAC)/$rendimiento;
            }
        }
        else if($mty && $pago_actual > 6999 ){ //  Monterrey consumo_bimestral > 9400
            $potencia_necesaria = ($consumo_anual_total + 2400 - $lim_DAC2 ) / $rendimiento;
        }
        else if ($mty && $pago_actual > 2499 && $pago_actual < 6999){
            $potencia_necesaria = ($consumo_anual_total - 4250)/ $rendimiento; 
        }
        $num_paneles        = ceil($potencia_necesaria / $potencia_panel);
        if($num_paneles < 4) { $num_paneles = 4;}
        $potencia_sistema   = $num_paneles * $potencia_panel;
        $generacion_sistema = $potencia_sistema * $rendimiento;
        $consumo_cubierto   = ($generacion_sistema/ $consumo_anual_total)*100;
        if($consumo_cubierto > 100 ) { $consumo_cubierto = 100; }
        $espacio            = $num_paneles * 3;
        $consumo_remanente  = $consumo_bimestral - (($potencia_sistema * $rendimiento)/6);
        if(!$mty)
        {
            $ahorro_bimestral   = $pago_actual - ($consumo_remanente * $T01);
            if( $ahorro_bimestral >= $pago_actual ) { $ahorro_bimestral = $pago_actual - 46; }
        }
        else if($mty && $pago_actual > 6999 ){ //  Monterrey consumo_bimestral > 9400
            $ahorro_bimestral = $pago_actual - 1890;
        }
        else if($mty && $pago_actual > 2499 && $pago_actual < 6999){
            $ahorro_bimestral = $pago_actual - 568;
        }
        $nuevo_pago         = $pago_actual - $ahorro_bimestral;
        $ahorro_anual       = $ahorro_bimestral * 6;
        $ahorro_25          = $ahorro_anual * 25;
        $costo = 0;
        if($mty){
            foreach ($preciosNL as $precio) {
                if($num_paneles >= $precio['min'] && $num_paneles <= $precio['max'] )
                {
                    $costo = $precio['precio'];
                }
            }
        }else{
            foreach ($precios as $precio) {
                if($num_paneles >= $precio['min'] && $num_paneles <= $precio['max'] )
                {
                    $costo = $precio['precio'];
                }
            }
        }
        $roi = ($num_paneles * $potencia_panel * ($costo * $iva * $tipo_cambio)) / $ahorro_anual;
        $result = array(
            'mty'               => $mty,
            'generacion'        => $generacion_sistema,
            'consumo_bimestral'  => $consumo_bimestral,
            'consumo_anual_total'=> $consumo_anual_total,
            'potencia_necesaria' => $potencia_necesaria,
            'num_paneles'        => $num_paneles,
            'potencia_sistema'   => $potencia_sistema,
            'consumo_cubierto'   => $consumo_cubierto,
            'espacio'            => $espacio,
            'consumo_remanente'  => $consumo_remanente,
            'ahorro_bimestral'   => $ahorro_bimestral,
            'nuevo_pago'         => $nuevo_pago,
            'ahorro_anual'       => $ahorro_anual,
            'ahorro_25'          => $ahorro_25,
            'roi'                => ceil($roi)
        );
        return $result;
    }
}
