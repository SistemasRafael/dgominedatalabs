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
$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
$fecha_final = date("d-m-Y", strtotime($fecha_final));
$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli->set_charset("utf8");
mysqli_multi_query($mysqli, "CALL arg_rpt_ResultadosMuestrasGeologia('', '" . $fecha_inicial . "', '" . $fecha_final . "')") or die(mysqli_error($mysqli));
$result = $mysqli->store_result();
$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Reporte Geología');
//echo utf8_encode($mysqli);

// Se agregan los titulos del reporte
$objSheet->mergeCells('A1:J1');
$objSheet->setCellValue('A1', 'Reporte Geología para' .$fecha_inicial. 'hasta '.$fecha_final); //->setValue('Listado de ordenes de trabajo'); 
$objSheet->setCellValue('A2', 'No.'); //->setValue('Folio');
$objSheet->setCellValue('B2', 'Folio'); //->setValue('Fecha');
$objSheet->setCellValue('C2', 'Banvol'); //->setValue('Hora');
$objSheet->setCellValue('D2', 'Fecha'); //->setValue('Orden de Trabajo');
$objSheet->setCellValue('E2', 'Oro'); //->setValue('Muestra Inicial');
$objSheet->setCellValue('F2', 'Plata'); //->setValue('Muestra Final');
$objSheet->setCellValue('G2', 'Fecha Res Oro'); //->setValue('Cantidad');
$objSheet->setCellValue('H2', 'Hora Res Oro'); //->setValue('Estado');
$objSheet->setCellValue('I2', 'Fecha Res Plata'); //->setValue('Id Metodo');
$objSheet->setCellValue('J2', 'Hora Res Plata'); //->setValue('Codigo Metodo');

//Se inserta el detalle de la consulta al informe
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
    $num = $num + 1;
    $i = $i + 1;

    //$row = eliminar_acentos($row);

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
header('Content-Disposition: attachment;filename="Reporte Geología.xlsx"');
$writer->save('php://output');
