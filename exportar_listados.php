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



$unidad_id = $_GET['unidad_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];

$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
$fecha_final = date("d-m-Y", strtotime($fecha_final));

//$objPHPExcel   = new PHPExcel;
$objWriter->getDefaultStyle()->getFont()->setName('Arial');
$objWriter->getDefaultStyle()->getFont()->setSize(10);
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli->set_charset("utf8");
mysqli_multi_query($mysqli, "CALL arg_rpt_listadoDuplicados(" . $unidad_id . ", '" . $fecha_inicial . "', '" . $fecha_final . "', 0, 0)") or die(mysqli_error($mysqli));
$result = $mysqli->store_result();
$objSheet = $objWriter->getActiveSheet();
$objSheet->setTitle('Resultados Duplicados');
$objSheet->mergeCells('A1:I1');
$objSheet->setCellValue('A1', 'Listado de Muestras y Resultados Duplicados');
$objSheet->setCellValue('A2', 'Folio');
$objSheet->setCellValue('B2', 'Orden Trabajo');
$objSheet->setCellValue('C2', 'Fecha');
$objSheet->setCellValue('D2', 'Metodo');
$objSheet->setCellValue('E2', 'Muestra Original');
$objSheet->setCellValue('F2', 'Absorcion Original');
$objSheet->setCellValue('G2', 'Muestra Duplicada');
$objSheet->setCellValue('H2', 'Control');
$objSheet->setCellValue('I2', 'Absorcion Duplicado');

//Se inserta el detalle de la consulta al informe
$num = 1;
$i = 3;

while ($row = $result->fetch_row()) {
    //$row = eliminar_acentos($row);
    $objSheet->setCellValue('A' . $i, $num);
    $objSheet->setCellValue('B' . $i, $row[1]);
    $objSheet->setCellValue('C' . $i, $row[0]);
    $objSheet->setCellValue('D' . $i, $row[3]);
    $objSheet->setCellValue('E' . $i, $row[5]);
    $objSheet->setCellValue('F' . $i, $row[6]);
    $objSheet->setCellValue('G' . $i, $row[8]);
    $objSheet->setCellValue('H' . $i, $row[9]);
    $objSheet->setCellValue('I' . $i, $row[10]);
    $i = $i + 1;
    $num = $num + 1;
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
header('Content-Disposition: attachment;filename="Listados.xlsx"');

$writer->save('php://output');
