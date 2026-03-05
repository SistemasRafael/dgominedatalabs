<?php

/**
 * Informe para exportar resultados de laboratorio desde la  Bd  SQL
 * Danira Romero Maldonado * 
 * Enviar resultados al cliente carbones y metalurgia
 * ----------------------------------------
 * XLS Absorci�n
 **/

include "connections/config.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$trn_id_a = $_GET['trn_id_a'];
$metodo_id_a = $_GET['metodo_id_a'];
$pree = $_GET['pree'];

//echo $trn_id_a.' ,'.$metodo_id_a.'ok'.$pree;

$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
$objSheet = $spreadsheet->getActiveSheet(0);

$mysqli->set_charset("utf8");
$datos_at = $mysqli->query("SELECT 
                                od.folio_interno
                               ,(CASE WHEN o.trn_id_rel <> 0 THEN 1 ELSE 0 END ) AS reensaye 
                               ,o.tipo
                            FROM
                                arg_ordenes_detalle od
                                LEFT JOIN arg_ordenes AS o
                                     ON od.trn_id_rel = o.trn_id
                                WHERE od.trn_id = $trn_id_a") or die(mysqli_error($mysqli));
$usuario_atiende = $datos_at->fetch_assoc();
$folio = $usuario_atiende['folio_interno'];
$reensaye = $usuario_atiende['reensaye'];
$tipo_orden = $usuario_atiende['tipo'];

//echo $tope_ree;
$datos_met = $mysqli->query("SELECT nombre AS nombre_metodo FROM `arg_metodos` WHERE metodo_id = $metodo_id_a") or die(mysqli_error($mysqli));
$datos_meto = $datos_met->fetch_assoc();
$nombre_metodo = $datos_meto['nombre_metodo'];
if ($metodo_id_a == 3) {
    $elemento = 'Au_PPM';
} else {
    $elemento = 'Ag_PPM';
}

if ($reensaye == 44){
    //preliminar
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_sobr ($trn_id_a, $metodo_id_a, 1)") or die(mysqli_error($mysqli));   
}
elseif($metodo_id_a == 30){
        mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 1)") or die(mysqli_error($mysqli));
        $i = 1;

        $objSheet->setCellValue('A' . $i, 'FECHAA');
        $objSheet->setCellValue('B' . $i, 'MUESTRA');        
        $objSheet->setCellValue('C' . $i, 'PESO HUMEDO');
        $objSheet->setCellValue('D' . $i, 'PESO SECO');
        $objSheet->setCellValue('E' . $i, 'PORCENTAJE');
        $objSheet->setCellValue('F' . $i, 'METODO FINAL');
        $objSheet->setCellValue('G' . $i, 'FECHA RESULTADO');
        
        $i = 2;
        if ($result = mysqli_store_result($mysqli)) {
            while ($row = mysqli_fetch_assoc($result)) {
        
                $objSheet->setCellValue('A' . $i, $row['fecha']);
                $objSheet->setCellValue('B' . $i, $row['muestra']);
                $objSheet->setCellValue('C' . $i, $row['peso_humedo']);
                $objSheet->setCellValue('D' . $i, $row['peso_seco']);
                $objSheet->setCellValue('E' . $i, $row['porcentaje']);  
                $objSheet->setCellValue('F' . $i, $nombre_metodo);
                $objSheet->setCellValue('G' . $i, $row['fecha_fin']);
                $i = $i + 1;
            }
        }

}
elseif($metodo_id_a == 2){
        mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 1)") or die(mysqli_error($mysqli));
        $i = 1;

        $objSheet->setCellValue('A' . $i, 'Fecha');
        $objSheet->setCellValue('B' . $i, 'Muestra');
        $objSheet->setCellValue('C' . $i, 'Peso g');
        $objSheet->setCellValue('D' . $i, 'Incuarte mg');
        $objSheet->setCellValue('E' . $i, 'Peso Doré mg');        
        $objSheet->setCellValue('F' . $i, 'Peso Oro mg');        
        $objSheet->setCellValue('G' . $i, 'Au total Kgton');        
        $objSheet->setCellValue('H' . $i, 'Ag total Kgton');        
        $objSheet->setCellValue('I' . $i, 'Au_prom_kgton');             
        $objSheet->setCellValue('J' . $i, 'Ag_prom_kgton');
        if($tipo_orden == 8 || $tipo_orden == 9){
            $objSheet->setCellValue('k' . $i, 'Ag (g/tm)');
            $objSheet->setCellValue('L' . $i, 'METODO FINAL');
            $objSheet->setCellValue('M' . $i, 'FECHA RESULTADO');
        }else{
            $objSheet->setCellValue('K' . $i, 'METODO FINAL');
            $objSheet->setCellValue('L' . $i, 'FECHA RESULTADO');
        }
        
        
        $i = 2;
        if ($result = mysqli_store_result($mysqli)) {
            while ($row = mysqli_fetch_assoc($result)) {
        
                $objSheet->setCellValue('A' . $i, $row['fecha']);
                $objSheet->setCellValue('B' . $i, $row['muestra']);
                $objSheet->setCellValue('C' . $i, $row['peso']);
                $objSheet->setCellValue('D' . $i, $row['incuarte']);
                $objSheet->setCellValue('E' . $i, $row['dore']);                
                $objSheet->setCellValue('F' . $i, $row['peso_oro']);  
                
                $objSheet->setCellValue('G' . $i, $row['abs_au']);                
                $objSheet->setCellValue('H' . $i, $row['abs_ag']);  
                
                $objSheet->setCellValue('I' . $i, $row['au_mg_malla200']);                
                $objSheet->setCellValue('J' . $i, $row['ag_mg_malla']); 
                if($tipo_orden == 8 || $tipo_orden == 9){                        
                    $objSheet->setCellValue('K' . $i, $row['ag_g_tm']);                   
                    $objSheet->setCellValue('L' . $i, $nombre_metodo);
                    $objSheet->setCellValue('M' . $i, $row['fecha_fin']);
                }
                else{                    
                    $objSheet->setCellValue('K' . $i, $nombre_metodo);
                    $objSheet->setCellValue('L' . $i, $row['fecha_fin']);
                }
                $i = $i + 1;
            }
        }

         
        if($result = mysqli_store_result($mysqli)){
            mysqli_free_result($result);
        } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
    
        $spreadsheet->createSheet(1);
        $spreadsheet->setActiveSheetIndex(1); 
        $spreadsheet->setActiveSheetIndex(1)
                ->setCellValue('A1', 'Fecha')
                ->setCellValue('B1', 'Muestra')
                ->setCellValue('C1', 'Au_prom_kgton')
                ->setCellValue('D1', 'Ag_prom_kgton')
                ->setCellValue('E1', 'METODO FINAL')
                ->setCellValue('F1', 'FECHA RESULTADO');

        $spreadsheet->getActiveSheet(1)->setTitle($nombre_metodo.'_RESULTADOS');

        mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebrSC ($trn_id_a, $metodo_id_a, 1)") or die(mysqli_error($mysqli));

        $i = 2;
        //echo $i;
        if ($result = mysqli_store_result($mysqli)) {                
               while ($row = mysqli_fetch_assoc($result)) {
               $spreadsheet->setActiveSheetIndex(1) 
               ->setCellValue('A'.$i, $row['fecha'])
               ->setCellValue('B'.$i, $row['muestra'])
              ->setCellValue('C'.$i, $row['au_mg_malla200'])
               ->setCellValue('D'.$i, $row['ag_mg_malla'])
               ->setCellValue('E'.$i, $nombre_metodo)
               ->setCellValue('F'.$i, $row['fecha_fin']);
              
               $i=$i+1;
           }
        }
       

}
 
elseif($metodo_id_a == 29){
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 2)") or die(mysqli_error($mysqli));
    $i = 1;

    $objSheet->setCellValue('A' . $i, 'fecha');
    $objSheet->setCellValue('B' . $i, 'Muestra');
    $objSheet->setCellValue('C' . $i, 'factor');
    $objSheet->setCellValue('D' . $i, 'peso');
    $objSheet->setCellValue('E' . $i, 'densidad');
    $objSheet->setCellValue('F' . $i, 'METODO FINAL');
    $objSheet->setCellValue('G' . $i, 'FECHA RESULTADO');
    
    $i = 2;
    if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {
    
            $objSheet->setCellValue('A' . $i, $row['fecha']);
            $objSheet->setCellValue('B' . $i, $row['muestra']);
            $objSheet->setCellValue('C' . $i, $row['peso_charola']);
            $objSheet->setCellValue('D' . $i, $row['peso']);
            $objSheet->setCellValue('E' . $i, $row['densidad']);  
            $objSheet->setCellValue('F' . $i, $nombre_metodo);
            $objSheet->setCellValue('G' . $i, $row['fecha_fin']);
            $i = $i + 1;
        }
    }

}

elseif($metodo_id_a == 28){
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 2)") or die(mysqli_error($mysqli));
    $i = 1;
    if ($tipo_orden == 9){
        $etiqueta_elem = '%'; // Barras
    }
    else{
        $etiqueta_elem = 'g/ton';
    }
    $objSheet->setCellValue('A' . $i, 'fecha');
    $objSheet->setCellValue('B' . $i, 'Muestra');
    $objSheet->setCellValue('C' . $i, 'Cu '.$etiqueta_elem);
    $objSheet->setCellValue('D' . $i, 'Fe '.$etiqueta_elem);
    $objSheet->setCellValue('E' . $i, 'Zn '.$etiqueta_elem);    
    $objSheet->setCellValue('F' . $i, 'Pb '.$etiqueta_elem);    
    $objSheet->setCellValue('G' . $i, 'Cd '.$etiqueta_elem);
    $objSheet->setCellValue('H' . $i, 'METODO FINAL');
    $objSheet->setCellValue('I' . $i, 'FECHA RESULTADO');
    
    $i = 2;
    if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {
    
            $objSheet->setCellValue('A' . $i, $row['fecha']);
            $objSheet->setCellValue('B' . $i, $row['muestra']);
            $objSheet->setCellValue('C' . $i, $row['elemento1']);
            $objSheet->setCellValue('D' . $i, $row['elemento2']);
            $objSheet->setCellValue('E' . $i, $row['elemento3']); 
            
            $objSheet->setCellValue('F' . $i, $row['elemento4']); 
            
            $objSheet->setCellValue('G' . $i, $row['elemento5']);  
            $objSheet->setCellValue('H' . $i, $nombre_metodo);
            $objSheet->setCellValue('I' . $i, $row['fecha_fin']);
            $i = $i + 1;
        }
    }
}

elseif($metodo_id_a == 5){
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 3)") or die(mysqli_error($mysqli));
    $i = 1;

    $objSheet->setCellValue('A' . $i, 'fecha');
    $objSheet->setCellValue('B' . $i, 'Muestra');
    $objSheet->setCellValue('C' . $i, '% Malla 1/2');
    $objSheet->setCellValue('D' . $i, '% Malla 3/8');    
    $objSheet->setCellValue('E' . $i, '% Malla 1/4');    
    $objSheet->setCellValue('F' . $i, '% Malla+100');    
    $objSheet->setCellValue('G' . $i, '% Malla+50');    
    $objSheet->setCellValue('H' . $i, '% Malla+100');    
    $objSheet->setCellValue('I' . $i, '% Malla-100');    
    $objSheet->setCellValue('J' . $i, 'METODO FINAL');
    $objSheet->setCellValue('K' . $i, 'FECHA RESULTADO');
    
    $i = 2;
    if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {
    
            $objSheet->setCellValue('A' . $i, $row['fecha']);
            $objSheet->setCellValue('B' . $i, $row['muestra']);
            $objSheet->setCellValue('C' . $i, $row['p_malla12']);
            $objSheet->setCellValue('D' . $i, $row['p_malla38']);            
            $objSheet->setCellValue('E' . $i, $row['p_malla14']);            
            $objSheet->setCellValue('F' . $i, $row['p_malla10']);            
            $objSheet->setCellValue('G' . $i, $row['p_malla50']);            
            $objSheet->setCellValue('H' . $i, $row['p_malla100']);            
            $objSheet->setCellValue('I' . $i, $row['p_mallamenos100']);   
            $objSheet->setCellValue('J' . $i, $nombre_metodo);
            $objSheet->setCellValue('K' . $i, $row['fecha_fin']);
            $i = $i + 1;
        }
    }

}
elseif($metodo_id_a == 9 || $metodo_id_a == 35){
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 2)") or die(mysqli_error($mysqli));
    $i = 1;
    
    $objSheet->setCellValue('A' . $i, 'fecha');
    $objSheet->setCellValue('B' . $i, 'Muestra');
    $objSheet->setCellValue('C' . $i, 'Absorcion Au ');
    $objSheet->setCellValue('D' . $i, 'METODO FINAL');
    $objSheet->setCellValue('E' . $i, 'FECHA RESULTADO');
    
    $i = 2;
    if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {
    
            $objSheet->setCellValue('A' . $i, $row['fecha']);
            $objSheet->setCellValue('B' . $i, $row['muestra']);
            $objSheet->setCellValue('C' . $i, $row['au']);
            $objSheet->setCellValue('D' . $i, $nombre_metodo);
            $objSheet->setCellValue('E' . $i, $row['fecha_fin']);
            $i = $i + 1;
        }
    }

}
elseif($metodo_id_a == 33){
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_quebr ($trn_id_a, $metodo_id_a, 3)") or die(mysqli_error($mysqli));
    $i = 1;

    $objSheet->setCellValue('A' . $i, 'fecha');
    $objSheet->setCellValue('B' . $i, 'Muestra');
    $objSheet->setCellValue('C' . $i, 'Au');
    $objSheet->setCellValue('D' . $i, 'Ag');
    $objSheet->setCellValue('E' . $i, 'Cu');
    $objSheet->setCellValue('F' . $i, 'METODO FINAL');
    $objSheet->setCellValue('G' . $i, 'FECHA RESULTADO');
    
    $i = 2;
    if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {    
            $objSheet->setCellValue('A' . $i, $row['fecha']);
            $objSheet->setCellValue('B' . $i, $row['muestra']);
            $objSheet->setCellValue('C' . $i, $row['elemento1']);
            $objSheet->setCellValue('D' . $i, $row['elemento2']);
            $objSheet->setCellValue('E' . $i, $row['elemento3']);
            $objSheet->setCellValue('F' . $i, $nombre_metodo);
            $objSheet->setCellValue('G' . $i, $row['fecha_fin']);
            $i = $i + 1;
        }
    }
}

$objSheet->setTitle($nombre_metodo);
//Insertamos encabezado

 for ($col = 'A'; $col != 'M'; $col++) { 
              $objSheet->getColumnDimension($col)->setAutoSize(true);         
 }

 $objSheet = $spreadsheet->getActiveSheet(1);


 for ($col = 'A'; $col != 'M'; $col++) { 
    $objSheet->getColumnDimension($col)->setAutoSize(true);         
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $folio . '_' . $nombre_metodo . '.xls');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
