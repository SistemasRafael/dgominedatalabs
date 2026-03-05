<?
include "connections/config.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objWriter = new Spreadsheet();

$unidad    = $_GET['unidad'];
$ejercicio = $_GET['ejercicio'];
$mes = $_GET['mes'];
$metodo = $_GET['metodo'];

//$objPHPExcel   = new PHPExcel;
$objWriter->getDefaultStyle()->getFont()->setName('Arial');
$objWriter->getDefaultStyle()->getFont()->setSize(10);
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

switch ($metodo){
    case 3: $nombre_metodo = 'Au';
    break;
    case 6: $nombre_metodo = 'Ag';
    break;
}


$mysqli->set_charset("utf8");
$datos_bancos_detalle = $mysqli->query("CALL arg_rpt_blancos($unidad, $ejercicio, $mes, $metodo)") or die(mysqli_error($mysqli));
$objSheet = $objWriter->getActiveSheet();

    $objSheet->setTitle('Reporte de Blancos ');
    $objSheet->mergeCells('E1:H1');
    $objSheet->setCellValue('E1', 'Análisis Blancos Reactivos - Preparados '.$nombre_metodo);
    
   // $objSheet = getStyle('A1:L1')->getAlignment()->setHorizontal('center');
    // $objSheet = getAlignment('center');
 
    $objSheet->setCellValue('A2', '');
    $objSheet->mergeCells('B2:C2');
    $objSheet->setCellValue('B2', 'Blanco Reactivo');
    $objSheet->setCellValue('C2', '');
    $objSheet->mergeCells('D2:E2');
    $objSheet->setCellValue('D2', 'Muestra Precedente');
    $objSheet->setCellValue('E2', '');
    $objSheet->setCellValue('F2', '');
    $objSheet->mergeCells('G2:H2');
    $objSheet->setCellValue('G2', 'Blanco Preparado');    
    $objSheet->setCellValue('H2', '');
    $objSheet->mergeCells('I2:J2');
    $objSheet->setCellValue('I2', 'Muestra Precedente');    
    $objSheet->setCellValue('J2', '');
    
    $objSheet->setCellValue('A3', 'Fecha Repeción');
    $objSheet->setCellValue('B3', 'ID Muestra');
    $objSheet->setCellValue('C3', 'Ley '.$nombre_metodo.' (ppm)');
    $objSheet->setCellValue('D3', 'ID Muestra');    
    $objSheet->setCellValue('E3', 'Ley '.$nombre_metodo.' (ppm)');
    
    $objSheet->setCellValue('F3', 'Fecha Recepción');
    $objSheet->setCellValue('G3', 'ID Muestra');
    $objSheet->setCellValue('H3', 'Ley '.$nombre_metodo.' (ppm)');
    $objSheet->setCellValue('I3', 'ID Muestra');
    $objSheet->setCellValue('J3', 'Ley '.$nombre_metodo.' (ppm)');
        

$i = 4;

while ($row = $datos_bancos_detalle->fetch_assoc()) {
       $objSheet->setCellValue('A' . $i, $row['fecha_orden']);      
       $objSheet->setCellValue('B' . $i, $row['blanco_reactivo']);       
       $objSheet->setCellValue('C' . $i, $row['br_resultado']);              
       $objSheet->setCellValue('D' . $i, $row['blanco_reactivo_prec']);       
       $objSheet->setCellValue('E' . $i, $row['brpre_resultado']);       
       
       $objSheet->setCellValue('F' . $i, $row['fecha_orden']);       
       $objSheet->setCellValue('G' . $i, $row['blanco_preparado']);       
       $objSheet->setCellValue('H' . $i, $row['blanco_preparado_resultado']);
              
       $objSheet->setCellValue('I' . $i, $row['blanco_preparado_prec']);       
       $objSheet->setCellValue('J' . $i, $row['pre_res_preparado']);
       
        $i = $i + 1;
    } 

//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'O'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}

//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'O'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($objWriter);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte de Blancos "'.$ejercicio.'-'.$mes.'".xlsx"');

$writer->save('php://output');
