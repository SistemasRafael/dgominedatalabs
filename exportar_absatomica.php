<?
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

    $datos_unidad = $mysqli->query("SELECT o.unidad_id 
                                FROM 
                                    arg_ordenes_detalle od
                                    LEFT JOIN arg_ordenes o
                                        ON od.trn_id_rel = o.trn_id
                                WHERE od.trn_id = $trn_id_a") or die(mysqli_error());
    $datos_unidad_mina = $datos_unidad->fetch_assoc();
    $unidadMina = $datos_unidad_mina['unidad_id'];
    
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
       
     $sa = array(1, 7, 13, 19, 25, 31, 37, 43, 49);

     //echo $i;
     mysqli_multi_query ($mysqli, "CALL arg_prc_absorcionAtomicaExp ($trn_id_a,$metodo_id_a,$u_id_a)") OR DIE (mysqli_error($mysqli));
     if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
            
        
              
            if (($metodo_id_a == 14) || ($metodo_id_a == 15) || ($metodo_id_a == 16)) {
               // if ($i == 1 or $i == 10 or $i == 19 or $i == 32){
                if($unidadMina == 2){
                    if (in_array ($i, $sa, true)){
                        $sheet->setCellValue('A'.$i, $i);
                        $sheet->setCellValue('B'.$i, 'PATRON');//->setValue('PATRON');
                        $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1'); 
                    
                        $i=$i+1;
                        $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                        $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
                        $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                                        
                        $i=$i+1;      
                        $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                        $sheet->setCellValue('B'.$i, 'BLANCO REACTIVO');//->setValue('BLANCO REACTIVO');
                        $sheet->setCellValue('C'.$i, 'RBLK');//->setValue('RBLK');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                        $i=$i+1;
                        
                    }
                    
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, $row['nombre']);//->setValue($row['folio_interno']);
                    $sheet->setCellValue('C'.$i, '1');//->setValue($row['nombre']);
                    $sheet->setCellValue('D'.$i, '1');//->setValue($row['peso']);
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue($volumen_metodo);            
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue( '1');   
                    
                    $i=$i+1;     
                }
                else{
                    if (in_array ($i, $b, true)){
                        $sheet->setCellValue('A'.$i, $i);
                        $sheet->setCellValue('B'.$i, 'PATRON');//->setValue('PATRON');
                        $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1'); 
                    
                        $i=$i+1;
                        $cont_sol++;
                        $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                        $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
                        $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                                        
                        $i=$i+1;                    
                        $cont_sol++;
                        $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                        $sheet->setCellValue('B'.$i, 'BLANCO REACTIVO');//->setValue('BLANCO REACTIVO');
                        $sheet->setCellValue('C'.$i, 'RBLK');//->setValue('RBLK');
                        $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                        $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                        $i=$i+1;
                        $cont_sol++;
                    }
                    
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, $row['nombre']);//->setValue($row['folio_interno']);
                    $sheet->setCellValue('C'.$i, '1');//->setValue($row['nombre']);
                    $sheet->setCellValue('D'.$i, '1');//->setValue($row['peso']);
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue($volumen_metodo);            
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue( '1');   
                    
                    $i=$i+1;
                
                    if (in_array ($i, $a, true)){
                            $sheet->setCellValue('A'.$i, $i);
                            $sheet->setCellValue('B'.$i, 'PATRON');
                            $sheet->setCellValue('C'.$i, 'SAMP');
                            $sheet->setCellValue('D'.$i, '1');
                            $sheet->setCellValue('E'.$i, '1');
                            $sheet->setCellValue('F'.$i, '1');
                            $sheet->setCellValue('G'.$i, '1');
                            $sheet->setCellValue('H'.$i, '1');
                            
                            $i=$i+1;
                    }                                  
                    $cont_sol++;
                }
            }//Fin  de  métodos de soluciones
            else{
                  if ($bloque%$total_bloque == 0){
                    $sheet->setCellValue('A'.$i, $i);
                    $sheet->setCellValue('B'.$i, 'PATRON');//->setValue('PATRON');
                    $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1'); 
                   
                    $i=$i+1;
                    $cont_sol++;
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
                    $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                                       
                    $i=$i+1;                    
                    $cont_sol++;
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, 'BLANCO REACTIVO');//->setValue('BLANCO REACTIVO');
                    $sheet->setCellValue('C'.$i, 'RBLK');//->setValue('RBLK');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                    $i=$i+1;
                    $cont_sol++;
                }
                
                $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                $sheet->setCellValue('B'.$i, $row['folio_interno']);//->setValue($row['folio_interno']);
                $sheet->setCellValue('C'.$i, $row['nombre']);//->setValue($row['nombre']);
                $sheet->setCellValue('D'.$i, $row['peso']);//->setValue($row['peso']);
                $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                $sheet->setCellValue('F'.$i, $volumen_metodo);//->setValue($volumen_metodo);            
                $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                $sheet->setCellValue('H'.$i, '1');//->setValue('1');            
            
            $i=$i+1;
            $bloque=$bloque+1;
            
            if ($cont%$bloque5 == 0 && $bloque%$total_bloque <> 0){
                    $sheet->setCellValue('A'.$i, $i);
                    $sheet->setCellValue('B'.$i, 'PATRON');
                    $sheet->setCellValue('C'.$i, 'SAMP');
                    $sheet->setCellValue('D'.$i, '1');
                    $sheet->setCellValue('E'.$i, '1');
                    $sheet->setCellValue('F'.$i, '1');
                    $sheet->setCellValue('G'.$i, '1');
                    $sheet->setCellValue('H'.$i, '1');
                    
                    $i=$i+1;
             }
            $cont = $cont+1;
        }
       }// Fin del while
       
       //ULTIMAS LINEAS
       $sheet->setCellValue('A'.$i, $i);
       $sheet->setCellValue('B'.$i, 'PATRON');//->setValue('PATRON');
       $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
       $sheet->setCellValue('D'.$i, '1');//->setValue('1');
       $sheet->setCellValue('E'.$i, '1');//->setValue('1');
       $sheet->setCellValue('F'.$i, '1');//->setValue('1');
       $sheet->setCellValue('G'.$i, '1');//->setValue('1');
       $sheet->setCellValue('H'.$i, '1');//->setValue('1');
       $i=$i+1;
       
       $sheet->setCellValue('A'.$i, $i);//->setValue($i);
       $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
       $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
       $sheet->setCellValue('D'.$i, '1');//->setValue('1');
       $sheet->setCellValue('E'.$i, '1');//->setValue('1');
       $sheet->setCellValue('F'.$i, '1');//->setValue('1');
       $sheet->setCellValue('G'.$i, '1');//->setValue('1');
       $sheet->setCellValue('H'.$i, '1');//->setValue('1');                    
       $i=$i+1;
       
       $sheet->setCellValue('A'.$i, $i);//->setValue($i);
       $sheet->setCellValue('B'.$i, 'BLANCO REACTIVO');//->setValue('BLANCO REACTIVO');
       $sheet->setCellValue('C'.$i, 'RBLK');//->setValue('RBLK');
       $sheet->setCellValue('D'.$i, '1');//->setValue('1');
       $sheet->setCellValue('E'.$i, '1');//->setValue('1');
       $sheet->setCellValue('F'.$i, '1');//->setValue('1');
       $sheet->setCellValue('G'.$i, '1');//->setValue('1');
       $sheet->setCellValue('H'.$i, '1');//->setValue('1');
       $i=$i+1;

       if($unidadMina == 2){
            if (($metodo_id_a == 14) || ($metodo_id_a == 15) || ($metodo_id_a == 16)) {
                $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
                $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                $sheet->setCellValue('H'.$i, '1');//->setValue('1');                    
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