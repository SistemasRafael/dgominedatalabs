<?

/**
 * Informe para exportar resultados de laboratorio desde la  Bd  SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * XLS Absorci�n
 **/

include "connections/config.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$trn_id_a = $_GET['trn_id_a'];
$metodo_id_a = $_GET['metodo_id_a'];
$pree = $_GET['pree'];

//echo $trn_id_a.' ,'.$metodo_id_a.'ok'.$pree;

$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
$objSheet = $spreadsheet->getActiveSheet(0);

$mysqli->set_charset("utf8");
$datos_at = $mysqli->query("SELECT od.folio_interno, (CASE WHEN o.trn_id_rel <> 0 THEN 1 ELSE 0 END ) AS reensaye 
                               FROM arg_ordenes_detalle od
                               LEFT JOIN arg_ordenes AS o
                                ON od.trn_id_rel = o.trn_id
                                WHERE od.trn_id = $trn_id_a") or die(mysqli_error($mysqli));
$usuario_atiende = $datos_at->fetch_assoc();
$folio = $usuario_atiende['folio_interno'];
$reensaye = $usuario_atiende['reensaye'];

//echo $tope_ree;
$datos_met = $mysqli->query("SELECT nombre AS nombre_metodo FROM `arg_metodos` WHERE metodo_id = $metodo_id_a") or die(mysqli_error($mysqli));
$datos_meto = $datos_met->fetch_assoc();
$nombre_metodo = $datos_meto['nombre_metodo'];
if ($metodo_id_a == 3) {
    $elemento = 'Au_PPM';
} else {
    $elemento = 'Ag_PPM';
}

if ($pree == 1){
    //preliminar
    mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_sobr ($trn_id_a, $metodo_id_a, 1)") or die(mysqli_error($mysqli));   
}
else{
        mysqli_multi_query($mysqli, "CALL arg_consultar_resultados_sobr ($trn_id_a, $metodo_id_a, 0)") or die(mysqli_error($mysqli));
} 
$objSheet->setTitle($nombre_metodo);
//Insertamos encabezado
$i = 1;

$objSheet->setCellValue('A' . $i, 'BANVOL');
$objSheet->setCellValue('B' . $i, 'fecha');
$objSheet->setCellValue('C' . $i, 'Muestra');
$objSheet->setCellValue('D' . $i, 'Au_PPM');
//$objSheet->setCellValue('E' . $i, 'Ag_PPM');
$objSheet->setCellValue('E' . $i, 'METODO FINAL');
$objSheet->setCellValue('F' . $i, 'FECHA RESULTADO');
//$objSheet->setCellValue('H' . $i, 'HORA');
$i = 2;
if ($result = mysqli_store_result($mysqli)) {
    while ($row = mysqli_fetch_assoc($result)) {

        $objSheet->setCellValue('A' . $i, $row['banvol']);
        $objSheet->setCellValue('B' . $i, $row['fecha']);
        $objSheet->setCellValue('C' . $i, $row['muestra']);
        $objSheet->setCellValue('D' . $i, $row['absorcion']);
        //$objSheet->setCellValue('E' . $i, $row['absorcion_ag']);  
        $objSheet->setCellValue('E' . $i, $row['metodo']);
        $objSheet->setCellValue('F' . $i, $row['fecha_fin']);
      //  $objSheet->setCellValue('H' . $i, $row['hora']);

        $i = $i + 1;
    }
}

 for ($col = 'A'; $col != 'H'; $col++) { 
              $objSheet->getColumnDimension($col)->setAutoSize(true);         
 }

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $folio . '_' . $nombre_metodo . '.xls');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
