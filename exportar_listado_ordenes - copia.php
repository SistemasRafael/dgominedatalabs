<?
/**
 * Informe para exportar desde Bd Minedata-labs
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Listado de órdenes
 **/

include "connections/config.php";

include "phpExcel/Classes/PHPExcel.php";
include "phpExcel/Classes/IOFactory.php";
include "phpExcel/Classes/Writer/Excel5.php";


$trn_id     = $_GET['trn_id'];
$nivel = $_GET['nivel'];
$objPHPExcel   = new PHPExcel;
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli -> set_charset("utf8");
    mysqli_multi_query ($mysqli, "CALL arg_rpt_ListadoMuestrasResultados ($trn_id, $nivel)") OR DIE (mysqli_error($mysqli));
               
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle('Resultados Preparacion');
    //echo utf8_encode($mysqli);
    
     // Se agregan los titulos del reporte
     $objSheet->mergeCells('A1:M1');
     $objSheet->getStyle  ('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $objSheet->getCell('A1')->setValue('Listado de Muestras y Resultados de Preparacion'); 
     $objSheet->getCell('A2')->setValue('Muestra Geología');
     $objSheet->getCell('B2')->setValue('Peso Secado');
     $objSheet->getCell('C2')->setValue('Fecha secado');
     $objSheet->getCell('D2')->setValue('Usuario secado');
     $objSheet->getCell('E2')->setValue('Peso Quebrado');
     $objSheet->getCell('F2')->setValue('Peso Malla 10');
     $objSheet->getCell('G2')->setValue('% Quebrado');
     $objSheet->getCell('H2')->setValue('Fecha quebrado');
     $objSheet->getCell('I2')->setValue('Usuario quebrado');
     $objSheet->getCell('J2')->setValue('Peso Pulv');
     $objSheet->getCell('K2')->setValue('Peso Malla');
     $objSheet->getCell('L2')->setValue('% Pulverizado');
     $objSheet->getCell('M2')->setValue('Fecha pulverizado');
     $objSheet->getCell('N2')->setValue('Usuario pulverizado');
     
     //Se inserta el detalle de la consulta al informe
     $i = 3;
     if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
     
        //$row = eliminar_acentos($row);
        $objSheet->getCell('A'.$i)->setValue($row['muestra_geologia']);
        $objSheet->getCell('B'.$i)->setValue($row['peso_sec']);
        $objSheet->getCell('C'.$i)->setValue($row['fecha_sec']);
        $objSheet->getCell('D'.$i)->setValue($row['usuario_sec']);       
        $objSheet->getCell('E'.$i)->setValue($row['peso_que']);
        $objSheet->getCell('F'.$i)->setValue($row['peso_malla_que']);
        $objSheet->getCell('G'.$i)->setValue($row['porcentaje_que']);
        $objSheet->getCell('H'.$i)->setValue($row['fecha_que']);
        $objSheet->getCell('I'.$i)->setValue($row['usuario_que']);
        $objSheet->getCell('J'.$i)->setValue($row['peso_pul']);
        $objSheet->getCell('K'.$i)->setValue($row['peso_malla_pul']);
        $objSheet->getCell('L'.$i)->setValue($row['porcentaje_pul']);
        $objSheet->getCell('M'.$i)->setValue($row['fecha_pul']);
        $objSheet->getCell('N'.$i)->setValue($row['usuario_pul']);
         $i=$i+1; 
      }
     }
     //Ajuste de columnas a tamaño del texto contenido
     for ($col = 'A'; $col != 'O'; $col++) { 
          $objSheet->getColumnDimension($col)->setAutoSize(true);         
        }
   
         //Ajuste de columnas a tamaño del texto contenido
         for ($col = 'A'; $col != 'O'; $col++) { 
              $objSheet->getColumnDimension($col)->setAutoSize(true);         
            } 
         
         $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
         
       //  $objPHPExcel->getActiveSheet()->protectCells('A1:C1', 'php');
         //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                     
         header('Content-Type: application/vnd.ms-excel');
         //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
         header('Content-Disposition: attachment;filename="Listado de Muestras.csv"');
         header('Cache-Control: max-age=0');
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         $objWriter->save('php://output');
         exit;
?>