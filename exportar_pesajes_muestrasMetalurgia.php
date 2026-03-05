<?php

/**
 * Informe para exportar desde Bd Minedata-labs
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Listado de �rdenes
 **/

include "connections/config.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$metodo_sel = $_GET['metodo_sel'];
$tipo_mues = $_GET['tipo_mues'];
$orden = $_GET['orden'];

$fecha_ini = $_GET['fecha_i'];
$fecha_fin = $_GET['fecha_f'];

$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

$mysqli->set_charset("utf8");

//mysqli_multi_query($mysqli, "CALL arg_rpt_pesajesMuestras(" . $tipo_mues . ", " . $orden . ", " . $metodo_sel.", '".$fecha_ini."', '".$fecha_fin."')") or die(mysqli_error($mysqli));
mysqli_multi_query($mysqli, "CALL arg_rpt_pesajesMuestrasMetalurgia(" . $orden . ", " . $tipo_mues . ", " . $metodo_sel.", '".$fecha_ini."', '".$fecha_fin."')") or die(mysqli_error($mysqli));
$result = $mysqli->store_result();

//die();

$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Pesajes Muestra');

$objSheet->mergeCells('A1:I1');
$objSheet->setCellValue('A1', 'Pesajes Muestra Metalurgia');
$objSheet->setCellValue('A2', 'No.');
$objSheet->setCellValue('B2', 'Orden');
$objSheet->setCellValue('C2', 'Muestra');
$objSheet->setCellValue('D2', 'Metodo');
$objSheet->setCellValue('E2', 'Fecha');
$objSheet->setCellValue('F2', 'Peso');
$objSheet->setCellValue('G2', 'Peso Payon');
$objSheet->setCellValue('H2', 'Incuarte');
$objSheet->setCellValue('I2', 'Peso Doré');
$objSheet->setCellValue('J2', 'Peso oro');
$objSheet->setCellValue('K2', 'Au gton');
$objSheet->setCellValue('L2', 'Ag gton');
$objSheet->setCellValue('M2', 'Prom Au gton');
$objSheet->setCellValue('N2', 'Prom Ag gton');
$objSheet->setCellValue('O2', 'Peso Húm');
$objSheet->setCellValue('P2', 'Peso Seco');
$objSheet->setCellValue('Q2', '% Hum');

$num = 1;
$i = 3;
while ($row = $result->fetch_row()) {
    $objSheet->setCellValue('A' . $i, $num);
    $objSheet->setCellValue('B' . $i, $row[0]);
    $objSheet->setCellValue('C' . $i, $row[1]);
    $objSheet->setCellValue('D' . $i, $row[2]);    
    $objSheet->setCellValue('E' . $i, $row[3]);
    $objSheet->setCellValue('F' . $i, $row[4]);
    $objSheet->setCellValue('G' . $i, $row[5]);
    $objSheet->setCellValue('H' . $i, $row[6]);
    $objSheet->setCellValue('I' . $i, $row[7]);
    $objSheet->setCellValue('J' . $i, $row[8]);
    $objSheet->setCellValue('K' . $i, $row[9]);
    $objSheet->setCellValue('L' . $i, $row[10]);
    $objSheet->setCellValue('M' . $i, $row[11]);
    $objSheet->setCellValue('N' . $i, $row[12]);
    $objSheet->setCellValue('O' . $i, $row[13]);
    $objSheet->setCellValue('P' . $i, $row[14]);
    $objSheet->setCellValue('Q' . $i, $row[15]);
    //$objSheet->setCellValue('R' . $i, $row[16]);    
    //$objSheet->setCellValue('R' . $i, $row[17]);
    $num = $num + 1;
    $i = $i + 1;
}

for ($col = 'A'; $col != 'P'; $col++) {
  $objSheet->getColumnDimension($col)->setAutoSize(true);
}

for ($col = 'A'; $col != 'P'; $col++) {
  $objSheet->getColumnDimension($col)->setAutoSize(true);
}


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Pesaje Muestra Metalurgia.xlsx"');
$writer->save('php://output');
