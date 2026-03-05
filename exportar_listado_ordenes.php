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

function eliminar_acentos($cadena){
		
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
		$cadena );
 
		//Reemplazamos la I y i
		$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('�', '�', '�', '�'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
$unidad_id     = $_GET['unidad_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final   = $_GET['fecha_final'];
//$objPHPExcel   = new PHPExcel;
$spreadsheet  = new Spreadsheet();
 // set syles
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

//http://192.168.20.3:81/minedata_labs/exportar_listado_ordenes.php?unidad_id=3&fecha_inicial=10-06-2022&fecha_final=12-06-2022
$mysqli -> set_charset("utf8");
    mysqli_multi_query ($mysqli, "CALL arg_rpt_TrazabilidadOrden ($unidad_id, '$fecha_inicial', '$fecha_final')") OR DIE (mysqli_error($mysqli));
               
    $objSheet = $spreadsheet->getActiveSheet();
    $objSheet->setTitle('Listado de ordenes');
    //echo utf8_encode($mysqli);
    
     // Se agregan los titulos del reporte
     $objSheet->mergeCells('A1:M1');
     $objSheet->setCellValue('A1','Listado de ordenes de trabajo');//->setValue('Listado de ordenes de trabajo'); 
     $objSheet->setCellValue('A2','Folio');//->setValue('Folio');
     $objSheet->setCellValue('B2','Fecha');//->setValue('Fecha');
     $objSheet->setCellValue('C2','Hora');//->setValue('Hora');
     $objSheet->setCellValue('D2','Orden de Trabajo');//->setValue('Orden de Trabajo');
     $objSheet->setCellValue('E2','Muestra Inicial');//->setValue('Muestra Inicial');
     $objSheet->setCellValue('F2','Muestra Final');//->setValue('Muestra Final');
     $objSheet->setCellValue('G2','Cantidad');//->setValue('Cantidad');
     $objSheet->setCellValue('H2','Estado');//->setValue('Estado');
     $objSheet->setCellValue('I2','Id Metodo');//->setValue('Id Metodo');
     $objSheet->setCellValue('J2','Codigo Metodo');//->setValue('Codigo Metodo');
     $objSheet->setCellValue('K2','Nombre Metodo');//->setValue('Nombre Metodo');
     $objSheet->setCellValue('L2','Fase');//->setValue('Fase');
     $objSheet->setCellValue('M2','Etapa');//->setValue('Etapa');
     
     //Se inserta el detalle de la consulta al informe
     $i = 3;
     if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
     
        //$row = eliminar_acentos($row);
        $objSheet->setCellValue('A'.$i,$row['folio']);//->setValue($row['folio']);
        $objSheet->setCellValue('B'.$i,$row['fecha_creacion']);//->setValue($row['fecha_creacion']);
        $objSheet->setCellValue('C'.$i,$row['hora']);//->setValue($row['hora']);
        $objSheet->setCellValue('D'.$i,$row['folio_interno']);//->setValue($row['folio_interno']);       
        $objSheet->setCellValue('E'.$i,$row['folio_inicial']);//->setValue($row['folio_inicial']);
        $objSheet->setCellValue('F'.$i,$row['folio_final']);//->setValue($row['folio_final']);
        $objSheet->setCellValue('G'.$i,$row['cantidad']);//->setValue($row['cantidad']);
        $objSheet->setCellValue('H'.$i,$row['estado']);//->setValue($row['estado']);
        $objSheet->setCellValue('I'.$i,$row['metodo_id']);//->setValue($row['metodo_id']);
        $objSheet->setCellValue('J'.$i,$row['nombre']);//->setValue($row['nombre']);
        $objSheet->setCellValue('K'.$i,$row['nombre_metodo']);//->setValue($row['nombre_metodo']);
        $objSheet->setCellValue('L'.$i,$row['fase']);//->setValue($row['fase']);
        $objSheet->setCellValue('M'.$i,$row['etapa']);//->setValue($row['etapa']);
        
         $i=$i+1; 
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
         
         //$spreadsheet->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
         
       //  $objPHPExcel->getActiveSheet()->protectCells('A1:C1', 'php');
         //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
         //$objSheet->protectCells('A1:B1', 'PHP');
         //$objSheet->getStyle('A2:N2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
         //$objSheet->getProtection()->setSheet(true);
         $writer = new Xlsx($spreadsheet);            
         //header('Content-Type: application/vnd.ms-excel');
         //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
         //header('Content-Disposition: attachment;filename="Listado de Ordenes.csv"');
         //header('Cache-Control: max-age=0');
         //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         //$objWriter->save('php://output');
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename="Listado de Ordenes.xlsx"');
         $writer->save('php://output');
