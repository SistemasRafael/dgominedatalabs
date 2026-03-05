<?
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Checador
 **/

include "connections/config.php";
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';
include '\xampp\htdocs\__pro\argonaut\common\PHPExcel\Classes\Writer\Excel5.php';

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);
 
		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );
 
		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
$tipo = $_GET['tipo'];
$unidad_id = $_GET['unidad_id'];
//echo $unidad_mina;

 $unidad = $mysqli->query("SELECT nombre
                           FROM `arg_empr_unidades` 
                           WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
 $unidad_sele = $unidad->fetch_assoc();
 $mina_nom = $unidad_sele['nombre'];
  
$objPHPExcel = new PHPExcel;
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

mysqli_multi_query ($mysqli, "CALL visor_export_bancos (".$tipo.",".$unidad_id.")") OR DIE (mysqli_error($mysqli));
           
$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->setTitle('Visitas '.$mina_nom);

 // Se agregan los titulos del reporte
 $objSheet->mergeCells('A1:D1');
 $objSheet->getStyle  ('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 $objSheet->getCell('A1')->setValue('Unidad de Mina '.$mina_nom); 
 $objSheet->getCell('A2')->setValue('Mina');
 $objSheet->getCell('B2')->setValue('Id');
 $objSheet->getCell('C2')->setValue('Banco');
 $objSheet->getCell('D2')->setValue('Descripcion');
 
 //Se inserta el detalle de la consulta al informe
 $i = 3;
  if ($result = mysqli_store_result($mysqli)) {                
        while ($row = mysqli_fetch_assoc($result)) {
 
    //$row = eliminar_acentos($row);
    $objSheet->getCell('A'.$i)->setValue($row['unidad_mina']);
    $objSheet->getCell('B'.$i)->setValue($row['banco_id']);
    $objSheet->getCell('C'.$i)->setValue($row['banco']);
    $objSheet->getCell('D'.$i)->setValue($row['nombre']);
     $i=$i+1; 
    }
 }
 //Ajuste de columnas a tamaño del texto contenido
 for ($col = 'A'; $col != 'E'; $col++) { 
      $objSheet->getColumnDimension($col)->setAutoSize(true);         
    }
 
 $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
             
 header('Content-Type: application/vnd.ms-excel');
 //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
 header('Content-Disposition: attachment;filename="Bancos '.$mina_nom.'.xlsx"');
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');
 exit;
?>