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

$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
//$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
//$fecha_final = date("d-m-Y", strtotime($fecha_final));
$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final   = date("Y-m-d", strtotime($fecha_final));
$tipo_id = $_GET['tipo_id'];
$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
$objSheet = $spreadsheet->getActiveSheet();

$mysqli->set_charset("utf8");

$datos_geo = $mysqli->query("CALL arg_rpt_reporteGeosql('$fecha_inicial','$fecha_final', $tipo_id)") or die(mysqli_error($mysqli));


/*$objSheet->setCellValue('B2', 'Muestra');
$objSheet->setCellValue('C2', 'Au_PPM');
$objSheet->setCellValue('D2', 'Ag_PPM');*/
$nueva_orden = "";
//Se inserta el detalle de la consulta al informe
$num = 1;
$i = 3;
while ($row = $datos_geo->fetch_assoc()) {
    $orden           = $row['orden_trabajo'];
    $fecha_recepcion = $row['fecha_orden'];
    $fecha_lib       = $row['fecha_liberacion_batch'];
    $metodos_inst    = $row['metodos'];
    $fecha_pesta     = $row['fecha_pesta'];
    
    if ($num == 1) {
        
            $i = 10;
            //$objSheet = $spreadsheet->createSheet();
            $objSheet->setTitle('SA '.$fecha_pesta.'-'.$orden);
            $objSheet->setCellValue('A1', $orden);  
            $objSheet->setCellValue('A2', 'PROJECT');
            $objSheet->setCellValue('B2', 'SAN AGUSTIN');
            $objSheet->setCellValue('A3', 'REFERENCE');
            $objSheet->setCellValue('B3', $orden);
            
            
            $objSheet->setCellValue('A4', 'DATE DELIVERED '); 
            $objSheet->setCellValue('B4', $fecha_lib);  
            $objSheet->setCellValue('A5', 'DATE REPORTED ');    
            $objSheet->setCellValue('B5', $fecha_recepcion);        
            $objSheet->setCellValue('A6', 'INSTRUCTIONS ');
            $objSheet->setCellValue('B6', $metodos_inst);
            
            $objSheet->setCellValue('B7', 'Au');
            $objSheet->setCellValue('B8', 'FAAA'); 
            $objSheet->setCellValue('C7', 'Ag');
            $objSheet->setCellValue('C8', 'FAAA');
            
            
            $objSheet->setCellValue('A9', 'SAMPLE');
            $objSheet->setCellValue('B9', 'ppm');
            $objSheet->setCellValue('C9', 'ppm');
        }
    
    if ($num != 1) {
        if ($orden != $nueva_orden) {
            $i = 10;
            
            
            $objSheet = $spreadsheet->createSheet();
            $objSheet->setTitle('SA '.$fecha_pesta.'-'.$orden);
            $objSheet->setCellValue('A1', $orden);    
            $objSheet->setCellValue('A2', 'PROJECT ');
            $objSheet->setCellValue('B2', 'SAN AGUSTIN');
            $objSheet->setCellValue('A3', 'REFERENCE');
            $objSheet->setCellValue('B3', $orden);
            
            
            $objSheet->setCellValue('A4', 'DATE DELIVERED:'); 
            $objSheet->setCellValue('B4', $fecha_lib);  
            $objSheet->setCellValue('A5', 'DATE REPORTED:');    
            $objSheet->setCellValue('B5', $fecha_recepcion);        
            $objSheet->setCellValue('A6', 'INSTRUCTIONS ');
            $objSheet->setCellValue('B6', $metodos_inst);
            
            $objSheet->setCellValue('B7', 'Au');
            $objSheet->setCellValue('B8', 'FAAA'); 
            $objSheet->setCellValue('C7', 'Ag');
            $objSheet->setCellValue('C8', 'FAAA');
            
            
            $objSheet->setCellValue('A9', 'SAMPLE');
            $objSheet->setCellValue('B9', 'ppm');
            $objSheet->setCellValue('C9', 'ppm');
        }
    }else{
        $objSheet->setTitle('SA '.$fecha_pesta.'-'.$orden);
        $objSheet->setCellValue('A1', $orden);
       // $objSheet->setCellValue('B4', max($dates));     
    }

  
    $objSheet->setCellValue('A' . $i, $row['muestra_geologia']);
    $objSheet->setCellValue('B' . $i, $row['au_ppm']);
    $objSheet->setCellValue('C' . $i, $row['ag_ppm']);
 
    
    $num = $num + 1;
    $i = $i + 1;    
    $nueva_orden = $orden;
    
    
   
    //Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'D'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}

//Ajuste de columnas a tama�o del texto contenido
for ($col = 'A'; $col != 'D'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}

        
}

 //$objSheet->setCellValue('B4', max($dates));  


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte GeoSQL.xlsx"');
$writer->save('php://output');
