<?

/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Checador
 **/

include "connections/config.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
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
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
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
$trn_id_batch = $_GET['trn_id_batch'];
$metodo_id = $_GET['metodo_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
mysqli_multi_query($mysqli, "CALL arg_rpt_ultimosResultados ($trn_id_batch,$metodo_id,$fecha_inicial,$fecha_final)") or die(mysqli_error($mysqli));

$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Ultimos Resultados');

// Se agregan los titulos del reporte
$objSheet->setCellValue('A1', 'trn id batch');
$objSheet->setCellValue('B1', 'trn id rel');
$objSheet->setCellValue('C1', 'folio interno');
$objSheet->setCellValue('D1', 'muestra');
$objSheet->setCellValue('E1', 'metodo id');
$objSheet->setCellValue('F1', 'metodo');
$objSheet->setCellValue('G1', 'ultima absorcion');

//Se inserta el detalle de la consulta al informe
$i = 2;
if ($result = mysqli_store_result($mysqli)) {
    while ($row = mysqli_fetch_assoc($result)) {

        //$row = eliminar_acentos($row);
        $objSheet->setCellValue('A' . $i, $row['trn_id_batch']);
        $objSheet->setCellValue('B' . $i, $row['trn_id_rel']);
        $objSheet->setCellValue('C' . $i, $row['folio_interno']);
        $objSheet->setCellValue('D' . $i, $row['muestra']);
        $objSheet->setCellValue('E' . $i, $row['metodo_id']);
        $objSheet->setCellValue('F' . $i, $row['metodo']);
        $objSheet->setCellValue('G' . $i, $row['ultima_absorcion']);
        $i = $i + 1;
    }
}
//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'I'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ultimosResultados.csv"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
