<?
/**
 * Informe para exportar resultados de laboratorio desde la  Bd  SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * XLS Absorción
 **/

include "connections/config.php";

include "phpExcel/Classes/PHPExcel.php";
include "phpExcel/Classes/IOFactory.php";
include "phpExcel/Classes/Writer/Excel5.php";

$trn_id_a = $_GET['trn_id_a'];
$metodo_id_a = $_GET['metodo_id_a'];
$pree = $_GET['pree'];
//$unidad_id_ree = $_GET['unidad_id_a'];

//mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultados ($trn_id_a, $metodo_id_a, $u_id_a, $unidad_id_ree)") OR DIE (mysqli_error($mysqli));


$objPHPExcel = new PHPExcel;
//error_reporting(0);
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

    $datos_at = $mysqli->query("SELECT folio_interno FROM arg_ordenes_detalle WHERE trn_id = $trn_id_a") or die(mysqli_error());
    $usuario_atiende = $datos_at->fetch_assoc();
    $folio = $usuario_atiende['folio_interno'];
    
    $datos_met = $mysqli->query("SELECT nombre AS nombre_metodo FROM `arg_metodos` WHERE metodo_id = $metodo_id_a") or die(mysqli_error());
    $datos_meto = $datos_met->fetch_assoc();
    $nombre_metodo = $datos_meto['nombre_metodo'];
    if($metodo_id_a == 3){
        $elemento = 'Au_PPM';
    }
    else
        {
         $elemento = 'Ag_PPM';
        }
    
    if($pree == 1){
        mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados ($trn_id_a, $metodo_id_a, 2)") OR DIE (mysqli_error($mysqli));
    }
    else{
       mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados ($trn_id_a, $metodo_id_a, 3)") OR DIE (mysqli_error($mysqli)); 
    }           
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle($nombre_metodo);
     //Insertamos encabezado
     $i = 1;
     $objSheet->getCell('A'.$i)->setValue('Muestra');
     $objSheet->getCell('B'.$i)->setValue('BANVOL');
     $objSheet->getCell('C'.$i)->setValue('fecha');
     $objSheet->getCell('D'.$i)->setValue($elemento);
     $objSheet->getCell('E'.$i)->setValue('METODO FINAL');
     $objSheet->getCell('F'.$i)->setValue('FECHA RESULTADO');
     $objSheet->getCell('G'.$i)->setValue('HORA');
     //Se inserta el detalle de la consulta al informe
     $i = 2;
     //echo $i;
     if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
              
            $objSheet->getCell('A'.$i)->setValue($i);
            $objSheet->getCell('A'.$i)->setValue($row['muestra']);
            $objSheet->getCell('B'.$i)->setValue($row['banvol']);
            $objSheet->getCell('C'.$i)->setValue($row['fecha']);
            $objSheet->getCell('D'.$i)->setValue($row['absorcion']);
            $objSheet->getCell('E'.$i)->setValue($row['metodo']);
            $objSheet->getCell('F'.$i)->setValue($row['fecha_fin']);
            $objSheet->getCell('G'.$i)->setValue($row['hora']);
           
            $i=$i+1;
        }
     }
     
      //Ajuste de columnas a tamaño del texto contenido
         for ($col = 'A'; $col != 'H'; $col++) { 
              $objSheet->getColumnDimension($col)->setAutoSize(true);         
         }
                 
         $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
     
       /*  header('Content-Type: text/csv');         
         header('Content-Transfer-Encoding: binary; charset=utf-8');
         header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');*/
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename='.$folio.'_'.$nombre_metodo.'.xls');
         header('Cache-Control: max-age=0');
         
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         //$fp = fopen("php://output", 'w');
         $objWriter->save('php://output');
         // echo ("<script>actualizar_lib(3)</script>");
          //exit;
?>