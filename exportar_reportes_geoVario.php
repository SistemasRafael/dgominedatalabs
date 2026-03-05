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

$spreadsheet  = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
$objSheet = $spreadsheet->getActiveSheet();

$mysqli->set_charset("utf8");
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
//$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
//$fecha_final = date("d-m-Y", strtotime($fecha_final));
$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final   = date("Y-m-d", strtotime($fecha_final));
$tipo_id = $_GET['tipo_id'];

$mysqli->set_charset("utf8");

$datos_geo = $mysqli->query("CALL arg_rpt_reporteGeosql('$fecha_inicial','$fecha_final', $tipo_id)") or die(mysqli_error($mysqli));

$nueva_orden = "";
//Se inserta el detalle de la consulta al informe
$num = 1;
$i = 3;
  
  
  
    
$f = 1;
while ($fila_det = $datos_geo->fetch_assoc()) {
    $orden_renglon = $fila_det['orden_trabajo'];
    if ($orden_renglon != $nueva_orden){
        $etiqueta_orden[$f]  = $orden_renglon;
         $f++;
    }
   // echo $etiqueta_orden[$f];
    $nueva_orden = $orden_renglon;
   
}

    if($datos_geo = mysqli_store_result($mysqli)){
	   	       mysqli_free_result($datos_geo);
         }while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
  
        
  $row_ord = 1;
       
while ($row_ord <= $f) {
    $orden_enc           = $etiqueta_orden[$row_ord];//$row_ord['orden_trabajo'];
    
    if ($orden_enc > 0){ 
    $datos_geo_ex = $mysqli->query("CALL arg_rpt_reporteGeosql_export($orden_enc, 0)") or die(mysqli_error($mysqli));

        $i = 10;
        echo $orden_enc;
       /* while ($row = $datos_geo_ex->fetch_assoc()) {
            $orden           = $row['orden_trabajo'];
            $fecha_recepcion = $row['fecha_orden'];
            $fecha_lib       = $row['fecha_liberacion_batch'];
            $metodos_inst    = $row['metodos'];
            $fecha_pesta     = $row['fecha_pesta'];
    
            //Encabezado
             if ($i == 10){
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
    
                //Se inserta el detalle de la consulta al informe
                $i++;    
                $objSheet->setCellValue('A' . $i, $row['muestra_geologia']);
                $objSheet->setCellValue('B' . $i, $row['au_ppm']);
                $objSheet->setCellValue('C' . $i, $row['ag_ppm']);    
 
           // echo $uestra.'</br>';
        }*/
        if($datos_geo_ex = mysqli_store_result($mysqli)){
	   	       mysqli_free_result($datos_geo_ex);
         }while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
         
      /*  $row_ord = $row_ord+1;
        $writer = new Xlsx($spreadsheet);
        $writer->save($orden_enc.'.xlsx');
        
     */
    }    
}  
    /*
   
 $filename = 'prueba.zip';  
 $zip = new ZipArchive; 
 if ($zip->open($filename,  ZipArchive::CREATE)){
        $j = 1;
        while ($j <= 4) {
          //$zip->addFile(getcwd().'/'.$orden.','.$orden);
            //print getcwd();
            $orden_enc = $etiqueta_orden[$j];
      
            $zip->addFile($orden_enc.'.xlsx');
            //$zip->addFile('316.xlsx');
            $j = $j+1;
        }
   }
  $zip->close(); 
*/
/*            
while ($row = $datos_geo_ex->fetch_assoc()) {
    
    //Ajuste de columnas a tama�o del texto contenido
    for ($col = 'A'; $col != 'D'; $col++) {
        $objSheet->getColumnDimension($col)->setAutoSize(true);
    }

    //Ajuste de columnas a tama�o del texto contenido
    for ($col = 'A'; $col != 'D'; $col++) {
        $objSheet->getColumnDimension($col)->setAutoSize(true);
    }        
}


        /*echo "<script> exportar_l ($orden,$tipo_id); </script>";
        
    if ($zip->open($filename,  ZipArchive::CREATE)){
        while($row  =   $fileQry->fetch_assoc()){
            $zip->addFile(getcwd().'SA-'.$orden);
        }
        
    }else{
           echo 'Failed!';
        }*/
 
 /* */
 
       
       //echo "window.location.href=exportar_reportes_geoInd.php?orden=".$orden."&tipo_id=0";
    /*   $url = 'exportar_reportes_geoInd.php?orden='.$orden.'&tipo_id=0';
header("Location:".$url); */
      //  unlink($filename);

/*
//if (file_exists($rutaFinal. "/" . $archivoZip)) {
if (file_exists($filename)) {
//Definimos el primer header como un archivo binario generico
header("Content-type: application/octet-stream");

//Este header indica un archivo adjunto el cual tendra un nombre
header("Content-Disposition: attachment; filename=\"".$filename."\"");

//leemos el archivo de la ruta para enviarlo al navegador
readfile("$filename");
} else {
echo "Error, archivo zip no ha sido creado!!";
}
  */
          /*    
header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=$filename");
        
        header("Content-length: " . filesize($filename));
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("$filename");
        ob_clean();
       flush();*/