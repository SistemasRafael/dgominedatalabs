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



$mysqli->set_charset("utf8");
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
//$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
//$fecha_final = date("d-m-Y", strtotime($fecha_final));
$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final   = date("Y-m-d", strtotime($fecha_final));
$tipo_id = $_GET['tipo_id'];
$fecha_nombre = str_replace("-","",$fecha_inicial);

$mysqli->set_charset("utf8");

$datos_orden = $mysqli->query("SELECT
       DISTINCT o.folio AS orden_trabajo, DATE_FORMAT(o.fecha, '%Y%m%d') AS fecha_orden
    FROM
        arg_ordenes_detalle od
    INNER JOIN arg_ordenes o ON
        od.trn_id_rel = o.trn_id
    WHERE
        od.estado <> 99 
        AND o.tipo = 1
        AND DATE_FORMAT(o.fecha, '%Y-%m-%d') BETWEEN DATE_FORMAT('$fecha_inicial', '%Y-%m-%d') AND DATE_FORMAT('$fecha_final', '%Y-%m-%d')") or die(mysqli_error($mysqli));

$f = 1;
while ($fila_det = $datos_orden->fetch_assoc()) { 
    $orden_renglon = $fila_det['orden_trabajo'];
    $orden_fecha = $fila_det['fecha_orden'];
    if ($orden_renglon > 0){ 
    $datos_geo_ex = $mysqli->query("CALL arg_rpt_reporteGeosql_export($orden_renglon, 0)") or die(mysqli_error($mysqli));

        $i = 10;
        $spreadsheet  = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $objSheet = $spreadsheet->getActiveSheet();
        
        while ($row = $datos_geo_ex->fetch_assoc()) {
            $orden           = $row['orden_trabajo'];
            $fecha_recepcion = $row['fecha_orden'];
            $fecha_lib       = $row['fecha_liberacion_batch'];
            $metodos_inst    = $row['metodos'];
            $fecha_pesta     = $row['fecha_pesta'];
    
            //Encabezado
             if ($i == 10){
                    $objSheet->setTitle('MSA'.$fecha_pesta.$orden);
                    $objSheet->setCellValue('A1', 'MSA'.$fecha_pesta.$orden);  
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
    
                //Se inserta el detalle de la consulta al informe            
            $objSheet->setCellValue('A' . $i, $row['muestra_geologia']);
            $objSheet->setCellValue('B' . $i, $row['au_ppm']);
            $objSheet->setCellValue('C' . $i, $row['ag_ppm']);
            $i++;
        }
        if($datos_geo_ex = mysqli_store_result($mysqli)){
	   	       mysqli_free_result($datos_geo_ex);
         }while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
         
        $row = '';
        //Ajuste de columnas a tama�o del texto contenido
        for ($col = 'A'; $col != 'D'; $col++) {
            $objSheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        //Ajuste de columnas a tama�o del texto contenido
        for ($col = 'A'; $col != 'D'; $col++) {
            $objSheet->getColumnDimension($col)->setAutoSize(true);
        }        
       
        $writer = new Xlsx($spreadsheet);
        $ruta = 'geosql';
        $nombre_arch = 'MSA'.$orden_fecha.$orden_renglon.'.xlsx';
        $writer->save($ruta."/".$nombre_arch);
   }
}

$datos_orden_ins = $mysqli->query("SELECT
       DISTINCT o.folio AS orden_trabajo, DATE_FORMAT(o.fecha, '%Y%m%d') AS fecha_orden
    FROM
        arg_ordenes_detalle od
    INNER JOIN arg_ordenes o ON
        od.trn_id_rel = o.trn_id
    WHERE
        od.estado <> 99 
        AND o.tipo <> 0
        AND DATE_FORMAT(o.fecha, '%Y-%m-%d') BETWEEN DATE_FORMAT('$fecha_inicial', '%Y-%m-%d') AND DATE_FORMAT('$fecha_final', '%Y-%m-%d')"
    ) or die(mysqli_error($mysqli));


 $filename = 'GeoSQL.zip';  
 $zip = new ZipArchive;
 if ($zip->open($filename,  ZipArchive::CREATE)){
    while ($fila_deta = $datos_orden_ins->fetch_assoc()) {  
        $orden_gua = $fila_deta['orden_trabajo'];  
        $orden_fec = $fila_deta['fecha_orden'];
        if ($orden_gua > 0){
            $orden = 'MSA'.$orden_fec.$orden_gua.'.xlsx';
            $ruta = 'geosql';
            $zip->addFile($ruta."/".$orden,$orden);
        }
    }
 }
$zip->close();

if (file_exists($filename)) {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
readfile("$filename");
} else {
echo "Error, archivo zip no ha sido creado!!";
}
unlink("$filename");

   