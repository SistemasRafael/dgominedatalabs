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

$unidad_id = $_GET['unidad_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
//$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
//$fecha_final = date("Y-m-d", strtotime($fecha_final));
/*
echo $fecha_inicial;
echo $fecha_final;*/
$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
//$mysqli->set_charset("utf8");
///mysqli_multi_query($mysqli, "CALL arg_rpt_ResultadosMuestrasGeologia('', '" . $fecha_inicial . "', '" . $fecha_final . "')") or die(mysqli_error($mysqli));
//$result = $mysqli->store_result();
$datos_geo = $mysqli->query("SELECT 
                                  om.folio
                                , banco_voladura(om.trn_id) AS banvol
                                , DATE_FORMAT(ot.fecha, '%d-%m-%Y') AS fecha, IFNULL(au.resultado, -2) AS Au
                                , IFNULL(ag.resultado, -2) AS Ag
                                , IFNULL(DATE_FORMAT(au.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Au
                                , IFNULL(DATE_FORMAT(au.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Au
                                , IFNULL(DATE_FORMAT(ag.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Ag
                                , IFNULL(DATE_FORMAT(ag.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Ag
                                 FROM `arg_ordenes_muestras` om 
                                    LEFT JOIN arg_ordenes_detalle AS od 
                                        ON od.trn_id = om.trn_id_rel
                                    LEFT JOIN arg_ordenes AS ot 
                                        ON ot.trn_id = od.trn_id_rel 
                                        AND ot.trn_id_rel = 0 
                                    LEFT JOIN arg_muestras_liberadas AS au 
                                        ON om.trn_id = au.trn_id_rel 
                                        AND au.metodo_id = 3 
                                    LEFT JOIN arg_muestras_liberadas AS ag 
                                        ON om.trn_id = ag.trn_id_rel 
                                        AND ag.metodo_id = 6 
                                 WHERE 
                                    om.tipo_id = 0 
                                    AND od.estado <> 99 
                                    AND od.folio_interno NOT LIKE '%-RE%' 
                                    AND ot.unidad_id = ".$unidad_id."
                                    AND DATE_FORMAT(au.fecha, '%Y-%m-%d') BETWEEN '$fecha_inicial' AND '$fecha_final'
                                    AND om.trn_id NOT IN (SELECT trn_id_muestra FROM ordenes_sobrelimites_sinrecheck)
                                UNION ALL
                                SELECT 
                                      om.folio
                                    , banco_voladura(om.trn_id) AS banvol
                                    , DATE_FORMAT(ot.fecha, '%d-%m-%Y') AS fecha
                                    , IFNULL(au.resultado, -2) AS Au
                                    , IFNULL(ag.resultado, -2) AS Ag
                                    , IFNULL(DATE_FORMAT(au.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Au
                                    , IFNULL(DATE_FORMAT(au.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Au                                    
                                    , IFNULL(DATE_FORMAT(ag.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Ag
                                    , IFNULL(DATE_FORMAT(ag.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Ag
                                FROM 
                                    `arg_ordenes_muestras` om
                                    LEFT JOIN arg_ordenes_detalle AS od
                                        ON od.trn_id = om.trn_id_rel
                                    LEFT JOIN arg_ordenes AS ot
                                        ON ot.trn_id = od.trn_id_rel 
                                        AND ot.trn_id_rel = 0
                                   LEFT JOIN arg_metodos AS met
                                   	  ON met.metodo_id = 1         
                                   LEFT JOIN arg_muestras_liberadas AS au
                                      ON om.trn_id = au.trn_id_rel 
                                      AND au.metodo_id = 1
                                   LEFT JOIN arg_muestras_liberadas AS ag 
                                        ON om.trn_id = ag.trn_id_rel 
                                        AND ag.metodo_id = 6 
                                WHERE 
                                    om.tipo_id = 0 
                                    AND od.estado <> 99 
                                    AND od.folio_interno NOT LIKE '%-RE%'
                                    AND ot.unidad_id = ".$unidad_id."
                                    AND DATE_FORMAT(au.fecha, '%Y-%m-%d') BETWEEN '$fecha_inicial' AND '$fecha_final'
                                    AND om.trn_id IN (SELECT trn_id_muestra FROM ordenes_sobrelimites_sinrecheck)
                                    ORDER BY folio") or die(mysqli_error());
$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Reporte Geología');
//echo utf8_encode($mysqli);

//sleep(5000);
// Se agregan los titulos del reporte
//$objSheet->mergeCells('A1:J1');
//$objSheet->setCellValue('A1', 'Reporte Geología para' .$fecha_inicial. 'hasta '.$fecha_final); //->setValue('Listado de ordenes de trabajo'); 
$objSheet->setCellValue('A1', 'Muestra'); //->setValue('Fecha');
$objSheet->setCellValue('B1', 'BAN+VOL'); //->setValue('Hora');
$objSheet->setCellValue('C1', 'FECHA RECEPCION'); //->setValue('Orden de Trabajo');
$objSheet->setCellValue('D1', 'Au_PPM'); //->setValue('Muestra Inicial');
$objSheet->setCellValue('E1', 'Ag_PPM'); //->setValue('Muestra Final');
$objSheet->setCellValue('F1', 'FECHA RESULTADO Au'); //->setValue('Cantidad');
$objSheet->setCellValue('G1', 'Hora Au'); //->setValue('Estado');
$objSheet->setCellValue('H1', 'FECHA RESULTADO Ag'); //->setValue('Id Metodo');
$objSheet->setCellValue('I1', 'Hora Ag'); //->setValue('Codigo Metodo');

//Se inserta el detalle de la consulta al informe
$num = 1;
$i = 2;
while  ($row = $datos_geo->fetch_assoc()) {
    $objSheet->setCellValue('A' . $i, $row['folio']);
    $objSheet->setCellValue('B' . $i, $row['banvol']);
    $objSheet->setCellValue('C' . $i, $row['fecha']);
    $objSheet->setCellValue('D' . $i, $row['Au']);
    $objSheet->setCellValue('E' . $i, $row['Ag']);
    $objSheet->setCellValue('F' . $i, $row['fecha_res_Au']);
    $objSheet->setCellValue('G' . $i, $row['hora_res_Au']);
    $objSheet->setCellValue('H' . $i, $row['fecha_res_Ag']);
    $objSheet->setCellValue('I' . $i, $row['hora_res_Ag']);
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

/*
$fileName = 'Reporte_geologia.csv';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');*/


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Geología.xlsx"');
$writer->save('php://output');
