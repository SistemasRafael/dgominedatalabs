<?php
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * CSV Absorci�n
 **/

include "connections/config.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$trn_id_a = $_GET['trn_id_a'];
$metodo_id_a = $_GET['metodo_id_a'];
$u_id_a = $_GET['u_id_a'];

    $datos_at = $mysqli->query("SELECT folio_interno FROM arg_ordenes_detalle WHERE trn_id = $trn_id_a") or die(mysqli_error());
    $usuario_atiende = $datos_at->fetch_assoc();
    $folio = $usuario_atiende['folio_interno'];
    
    $datos_met = $mysqli->query("SELECT nombre AS nombre_metodo, volumen FROM `arg_metodos` WHERE metodo_id = $metodo_id_a") or die(mysqli_error());
    $datos_meto = $datos_met->fetch_assoc();
    $nombre_metodo  = $datos_meto['nombre_metodo'];
    $volumen_metodo = $datos_meto['volumen'];
    
    $spreadsheet = new Spreadsheet(); 
    $sheet = $spreadsheet->getActiveSheet();
    
     $i = 1;
     $bloque = 10;
     $total_bloque = 10;
     
     $bloque5 = 5;
     $cont = 1;
     $cont_sol = 0;
     $a = array(7, 17, 38, 48, 57);     
     $b = array(1, 10, 19, 32, 41, 50, 59);

     //echo $i;
     mysqli_multi_query ($mysqli, "CALL arg_prc_absorcionAtomicaExpCian ($trn_id_a,$metodo_id_a,$u_id_a)") OR DIE (mysqli_error($mysqli));
     if ($result = mysqli_store_result($mysqli)) {  
            if ($metodo_id_a == 33 || $metodo_id_a == 27 || $metodo_id_a == 9 || $metodo_id_a == 35) {
                    $sheet->setCellValue('A'.$i, $i);                    
                    $sheet->setCellValue('B'.$i, 'PATRONNNN');//->setValue($i);
                    $sheet->setCellValue('C'.$i, '1');//->setValue($row['folio_interno']);
                    $sheet->setCellValue('D'.$i, '1');//->setValue($row['nombre']);
                    $sheet->setCellValue('E'.$i, '1');//->setValue($row['peso']);
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue($volumen_metodo);            
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');;   
                    
                    $i=$i+1;
            }
            while ($row = mysqli_fetch_assoc($result)) {                
              
                if ($metodo_id_a == 33 || $metodo_id_a == 27 || $metodo_id_a == 9 || $metodo_id_a == 35) {
                    $sheet->setCellValue('A'.$i, $i);                    
                    $sheet->setCellValue('B'.$i, $row['folio_interno']);//->setValue($i);
                    $sheet->setCellValue('C'.$i, 'SAM');//->setValue($row['folio_interno']);
                    $sheet->setCellValue('D'.$i, $row['peso']);//->setValue($row['nombre']);
                    $sheet->setCellValue('E'.$i, '1');//->setValue($row['peso']);
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue($volumen_metodo);            
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');;   
                    
                    $i=$i+1;
                }
                elseif($metodo_id_a == 28){
                     $sheet->setCellValue('A'.$i, $i);                    
                    $sheet->setCellValue('B'.$i, $row['folio_interno']);//->setValue($i);
                    $sheet->setCellValue('C'.$i, 'SAM');//->setValue($row['folio_interno']);
                    $sheet->setCellValue('D'.$i, $row['peso']);//->setValue($row['nombre']);
                    $sheet->setCellValue('E'.$i, '1');//->setValue($row['peso']);
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue($volumen_metodo);            
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');;   
                    
                    $i=$i+1;
                }
         }
     }    

    $writer = new Xlsx($spreadsheet);
    $fileName = $folio.'_'.$metodo_id_a.'.csv';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');
?>