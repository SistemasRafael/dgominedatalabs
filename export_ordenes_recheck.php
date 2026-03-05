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



$fecha_inicial = $_GET['fecha_inicial_ex'];
$fecha_final = $_GET['fecha_final_ex'];
$orden_recheck = $_GET['orden'];
$muestra = $_GET['tipo_muestra'];

$objWriter->getDefaultStyle()->getFont()->setName('Arial');
$objWriter->getDefaultStyle()->getFont()->setSize(10);
$mysqli->set_charset("utf8");

$query = "CALL arg_rpt_ordenesRecheck('" . $fecha_inicial . "', '" . $fecha_final . "', " . $orden_recheck . ", '" . $muestra . "')";
mysqli_multi_query($mysqli, $query);
$result = $mysqli->store_result();


$objSheet = $objWriter->getActiveSheet();
$objSheet->setTitle('Ordenes');
$objSheet->setCellValue('A1', 'No.');
$objSheet->setCellValue('B1', 'FECHA DE ENTREGA ORIG');
$objSheet->setCellValue('C1', 'FECHA DE RESULTADO ORIG');
$objSheet->setCellValue('D1', 'MUESTRA');
$objSheet->setCellValue('E1', 'Au ORIG');
$objSheet->setCellValue('F1', 'Ag ORIG');
$objSheet->setCellValue('G1', 'Au RECH');
$objSheet->setCellValue('H1', 'Ag RECH');
$objSheet->setCellValue('I1', 'FECHA DE SOLICITUD RECH');
$objSheet->setCellValue('J1', 'FECHA DE RESULTADO RECH');
$num = 1;
$i = 2;

while ($row = $result->fetch_row()) {
        $objSheet->setCellValue('A' . $i, $num);
        $objSheet->setCellValue('B' . $i, $row[0]);
        $objSheet->setCellValue('C' . $i, $row[1]);
        $objSheet->setCellValue('D' . $i, $row[3]);
        $objSheet->setCellValue('E' . $i, $row[4]);
        $objSheet->setCellValue('F' . $i, $row[5]);
        $objSheet->setCellValue('G' . $i, $row[6]);
        $objSheet->setCellValue('H' . $i, $row[7]);
        $objSheet->setCellValue('I' . $i, $row[8]);
        $objSheet->setCellValue('J' . $i, $row[9]);
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
header('Content-Disposition: attachment;filename="Ordenes Recheck.xlsx"');

$writer->save('php://output');
