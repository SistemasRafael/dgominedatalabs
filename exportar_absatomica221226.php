<?
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * CSV Absorción
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
     //echo $i;
     mysqli_multi_query ($mysqli, "CALL arg_prc_absorcionAtomicaExp ($trn_id_a,$metodo_id_a,$u_id_a)") OR DIE (mysqli_error($mysqli));
     if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
                
                if ($bloque%$total_bloque == 0){
                    $sheet->setCellValue('A'.$i, $i);
                    $sheet->setCellValue('B'.$i, 'PATRON');//->setValue('PATRON');
                    $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                    
                    //$colu='F'.$i.':'.'H'.$i;
                    //$sheet->protectCells($colu, 'PHP');
                    //$sheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, 'BLANCO');//->setValue('BLANCO');
                    $sheet->setCellValue('C'.$i, 'SAMP');//->setValue('SAMP');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                    //$colu='F'.$i.':'.'H'.$i;
                    //$sheet->protectCells($colu, 'PHP');
                    //$sheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);*/
                    
                    $i=$i+1;
                    $sheet->setCellValue('A'.$i, $i);//->setValue($i);
                    $sheet->setCellValue('B'.$i, 'BLANCO REACTIVO');//->setValue('BLANCO REACTIVO');
                    $sheet->setCellValue('C'.$i, 'RBLK');//->setValue('RBLK');
                    $sheet->setCellValue('D'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('E'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('F'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('G'.$i, '1');//->setValue('1');
                    $sheet->setCellValue('H'.$i, '1');//->setValue('1');
                    //$colu='F'.$i.':'.'G'.$i;
                    //$objSheet->protectCells($colu, 'PHP');
                    //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                }
     
            $sheet->setCellValue('A'.$i, $i);//->setValue($i);
            $sheet->setCellValue('B'.$i, $row['folio_interno']);//->setValue($row['folio_interno']);
            $sheet->setCellValue('C'.$i, $row['nombre']);//->setValue($row['nombre']);
            $sheet->setCellValue('D'.$i, $row['peso']);//->setValue($row['peso']);
            $sheet->setCellValue('E'.$i, '1');//->setValue('1');
            $sheet->setCellValue('F'.$i, $volumen_metodo);//->setValue($volumen_metodo);            
            $sheet->setCellValue('G'.$i, '1');//->setValue('1');
            $sheet->setCellValue('H'.$i, '1');//->setValue('1');
            
            /*$colu='F'.$i.':'.'H'.$i;
            $objSheet->protectCells($colu, 'PHP');
            $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);*/
            $i=$i+1;
            $bloque=$bloque+1;
       }
     }
     
    

    $writer = new Xlsx($spreadsheet);
    //$writer->save('hello world.xlsx');  
   //$spread = new Spreadsheet();
//$spread = 'hi';
/*->getProperties()
->setCreator("Nestor Tapia")
->setLastModifiedBy('BaulPHP')
->setTitle('Excel creado con PhpSpreadSheet')
->setSubject('Excel de prueba')
->setDescription('Excel generado como demostración')
->setKeywords('PHPSpreadsheet')
->setCategory('Categoría Excel');*/
$fileName = $folio.'_'.$metodo_id_a.'.csv';
//$folio."Descarga_excel.csv";
# Crear un "escritor"
//$writer = new Xlsx($spread);
# Le pasamos la ruta de guardado

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');
         
 /*   mysqli_multi_query ($mysqli, "CALL arg_prc_absorcionAtomicaExp ($trn_id_a,$metodo_id_a,$u_id_a)") OR DIE (mysqli_error($mysqli));
               
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle($nombre_metodo);
     
     //Se inserta el detalle de la consulta al informe
     $i = 1;
     $bloque = 10;
     $total_bloque = 10;
     //echo $i;
    if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
                
                if ($bloque%$total_bloque == 0){
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('PATRON');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    
                    $colu='F'.$i.':'.'H'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'H'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    
                    $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO REACTIVO');
                    $objSheet->getCell('C'.$i)->setValue('RBLK');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                }
     
            //$row = eliminar_acentos($row);
        
            $objSheet->getCell('A'.$i)->setValue($i);
            $objSheet->getCell('B'.$i)->setValue($row['folio_interno']);
            $objSheet->getCell('C'.$i)->setValue($row['nombre']);
            $objSheet->getCell('D'.$i)->setValue($row['peso']);
            $objSheet->getCell('E'.$i)->setValue('1');
            $objSheet->getCell('F'.$i)->setValue($volumen_metodo);            
            $objSheet->getCell('G'.$i)->setValue('1');
            $objSheet->getCell('H'.$i)->setValue('1');
            
            $colu='F'.$i.':'.'H'.$i;
            $objSheet->protectCells($colu, 'PHP');
            $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            $i=$i+1;
            $bloque=$bloque+1;
       }
     }
     
     $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('PATRON');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO REACTIVO');
                    $objSheet->getCell('C'.$i)->setValue('RBLK');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'H'.$i;
                    $objSheet->protectCells($colu, 'PHP');
                    $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    $i=$i+1;*/
         //$objSheet->getStyle('A2:B2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
         //$objSheet->getProtection()->setPassword('minedata8821');
       
      //   $objSheet->getProtection()->setPassword('minedata1506');
       //  $objSheet->getProtection()->setSheet(true);
     
         //header('Content-Type: text/csv');         
         //header('Content-Transfer-Encoding: binary; charset=utf-8');
         //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
       /*             
 header('Content-Type: application/vnd.ms-excel');
 header('Content-Disposition: attachment;filename="Reservaciones de Oficinas.xlsx"');
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');*/
 
       /*20221130
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename='.$folio.'_'.$metodo_id_a.'.csv');
         header('Cache-Control: max-age=0');
         
         $objWriter = phpExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         //$fp = fopen("php://output", 'w');
         $objWriter->save('php://output');*/
        // exit;
?>