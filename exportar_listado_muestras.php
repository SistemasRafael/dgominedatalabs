<?

/**
 * Informe para exportar desde Bd Minedata-labs
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Listado de �rdenes
 **/

include "connections/config.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objWriter = new Spreadsheet();



$trn_id = $_GET['trn_id'];
$nivel = $_GET['nivel'];

//$objPHPExcel   = new PHPExcel;
$objWriter->getDefaultStyle()->getFont()->setName('Arial');
$objWriter->getDefaultStyle()->getFont()->setSize(10);
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli->set_charset("utf8");
mysqli_multi_query($mysqli, "CALL arg_rpt_ListadoMuestrasResultados ($trn_id, $nivel)") or die(mysqli_error($mysqli));
$result = $mysqli->store_result();
$objSheet = $objWriter->getActiveSheet();
$objSheet->setTitle('Resultados Preparacion');
$objSheet->mergeCells('A1:M1');
$objSheet->setCellValue('A1', 'Listado de Muestras y Resultados de Preparacion');
$objSheet->setCellValue('A2', 'Muestra Geologia');
$objSheet->setCellValue('B2', 'Peso Secado');
$objSheet->setCellValue('C2', 'Fecha secado');
$objSheet->setCellValue('D2', 'Usuario secado');
$objSheet->setCellValue('E2', 'Peso Quebrado');
$objSheet->setCellValue('F2', 'Peso Malla 10');
$objSheet->setCellValue('G2', '% Quebrado');
$objSheet->setCellValue('H2', 'Fecha quebrado');
$objSheet->setCellValue('I2', 'Usuario quebrado');
$objSheet->setCellValue('J2', 'Peso Pulv');
$objSheet->setCellValue('K2', 'Peso Malla');
$objSheet->setCellValue('L2', '% Pulverizado');
$objSheet->setCellValue('M2', 'Fecha pulverizado');
$objSheet->setCellValue('N2', 'Usuario pulverizado');

//Se inserta el detalle de la consulta al informe
$i = 3;

while ($row = $result->fetch_row()) {
  //$row = eliminar_acentos($row);
  $objSheet->setCellValue('A' . $i, $row[1]);
  $objSheet->setCellValue('B' . $i, $row[2]);
  $objSheet->setCellValue('C' . $i, $row[5]);
  $objSheet->setCellValue('D' . $i, $row[6]);
  $objSheet->setCellValue('E' . $i, $row[7]);
  $objSheet->setCellValue('F' . $i, $row[8]);
  $objSheet->setCellValue('G' . $i, $row[9]);
  $objSheet->setCellValue('H' . $i, $row[10]);
  $objSheet->setCellValue('I' . $i, $row[11]);
  $objSheet->setCellValue('J' . $i, $row[12]);
  $objSheet->setCellValue('K' . $i, $row[13]);
  $objSheet->setCellValue('L' . $i, $row[14]);
  $objSheet->setCellValue('M' . $i, $row[15]);
  $objSheet->setCellValue('N' . $i, $row[16]);
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
header('Content-Disposition: attachment;filename="Listado de Muestras.xlsx"');

$writer->save('php://output');
