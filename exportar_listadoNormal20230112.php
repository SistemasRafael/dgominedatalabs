<?

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

//include "phpExcel/Classes/PHPExcel.php";
//include "phpExcel/Classes/IOFactory.php";
//include "phpExcel/Classes/Writer/Excel5.php";

function eliminar_acentos($cadena)
{

  //Reemplazamos la A y a
  $cadena = str_replace(
    array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
    $cadena
  );

  //Reemplazamos la E y e
  $cadena = str_replace(
    array('�', '�', '�', '�', '�', '�', '�', '�'),
    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
    $cadena
  );

  //Reemplazamos la I y i
  $cadena = str_replace(
    array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'i'),
    $cadena
  );

  //Reemplazamos la O y o
  $cadena = str_replace(
    array('�', '�', '�', '�', '�', '�', '�', '�'),
    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
    $cadena
  );

  //Reemplazamos la U y u
  $cadena = str_replace(
    array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'u'),
    $cadena
  );

  //Reemplazamos la N, n, C y c
  $cadena = str_replace(
    array('�', '�', '�', '�'),
    array('N', 'n', 'C', 'c'),
    $cadena
  );

  return $cadena;
}
$unidad_id = $_GET['unidad_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
$metodo = $_GET['metodo'];
$orden = $_GET['orden'];
$muestra = $_GET['muestra'];
$tipo_muestra = $_GET['tipo_muestra'];

//$objPHPExcel   = new PHPExcel;
$spreadsheet  = new Spreadsheet();
// set syles
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli->set_charset("utf8");
mysqli_multi_query($mysqli, "CALL arg_rpt_listadoNormal(" . $unidad_id . ", '" . $fecha_inicial . "', '" . $fecha_final . "', " .$metodo. ", '".$orden."', '".$muestra."' ,".$tipo_muestra.")") or die(mysqli_error($mysqli));

$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Listado Normal');
//echo utf8_encode($mysqli);

// Se agregan los titulos del reporte
$objSheet->mergeCells('A1:K1');
$objSheet->setCellValue('A1', 'Listado Normal'); //->setValue('Listado de ordenes de trabajo'); 
$objSheet->setCellValue('A2', 'No.'); //->setValue('Folio');
$objSheet->setCellValue('B2', 'Orden Trabajo'); //->setValue('Fecha');
$objSheet->setCellValue('C2', 'Fecha'); //->setValue('Hora');
$objSheet->setCellValue('D2', 'Fecha Liberacion'); //->setValue('Orden de Trabajo');
$objSheet->setCellValue('E2', 'Hora Liberacion'); //->setValue('Muestra Inicial');
$objSheet->setCellValue('F2', 'Metodo'); //->setValue('Muestra Final');
$objSheet->setCellValue('G2', 'Folio Interno'); //->setValue('Cantidad');
$objSheet->setCellValue('H2', 'Muestra Geologia'); //->setValue('Estado');
$objSheet->setCellValue('I2', 'Control'); //->setValue('Id Metodo');
$objSheet->setCellValue('J2', 'Absorcion'); //->setValue('Codigo Metodo');
$objSheet->setCellValue('K2', 'Reensaye'); //->setValue('Nombre Metodo');

//Se inserta el detalle de la consulta al informe
$num = 1;
$i = 3;
if ($result = mysqli_store_result($mysqli)) {
  while ($row = mysqli_fetch_assoc($result)) {

    //$row = eliminar_acentos($row);
    $objSheet->setCellValue('A' . $i, $num);
    $objSheet->setCellValue('B' . $i, $row[3]);
    $objSheet->setCellValue('C' . $i, $row[0]);
    $objSheet->setCellValue('D' . $i, $row[1]);
    $objSheet->setCellValue('E' . $i, $row[2]);
    $objSheet->setCellValue('F' . $i, $row[5]);
    $objSheet->setCellValue('G' . $i, $row[7]);
    $objSheet->setCellValue('H' . $i, $row[8]);
    $objSheet->setCellValue('I' . $i, $row[11]);
    $objSheet->setCellValue('J' . $i, $row[9]);
    $objSheet->setCellValue('K' . $i, $row[12]);
    $num = $num + 1;
    $i = $i + 1;
  }
}
//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'N'; $col++) {
  $objSheet->getColumnDimension($col)->setAutoSize(true);
}

//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'N'; $col++) {
  $objSheet->getColumnDimension($col)->setAutoSize(true);
}


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Listado Normal.xlsx"');
$writer->save('php://output');
