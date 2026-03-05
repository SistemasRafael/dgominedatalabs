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

//$fecha_ini = $_GET['fecha_inicial'];
//$fecha_fin = $_GET['fecha_final'];
$u_id = $_GET['u_id'];
$hoy = $_GET['hoy'];
$fechafinal = $_GET['fecha_final'];
$empleado = $_GET['empleado'];
$unidad_mina_sel = $_GET['unidad'];
//echo $unidad_mina;

 $unidad = $mysqli->query("SELECT nombre
                           FROM `arg_empr_unidades` 
                           WHERE unidad_id = ".$unidad_mina_sel) or die(mysqli_error());
 $unidad_sele = $unidad->fetch_assoc();
 $mina_nom = $unidad_sele['nombre'];
    
if ($empleado == ''){
    $empleado = '';
}

$objPHPExcel = new PHPExcel;
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

mysqli_multi_query ($mysqli, "CALL visor_visitas (".$u_id.",'".$hoy."','".$fechafinal."',".$empleado.", ".$unidad_mina_sel.", 0".")") OR DIE (mysqli_error($mysqli));
           
$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->setTitle('Visitas '.$mina_nom);

 // Se agregan los titulos del reporte
 $objSheet->mergeCells('A1:K1');
 $objSheet->getStyle  ('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 $objSheet->getCell('A1')->setValue('Unidad de Mina '.$mina_nom); 
 $objSheet->getCell('A2')->setValue('Empresa');
 $objSheet->getCell('B2')->setValue('Proveedor');
 $objSheet->getCell('C2')->setValue('Mina');
 $objSheet->getCell('D2')->setValue('Folio');
 $objSheet->getCell('E2')->setValue('Atiende');
 $objSheet->getCell('F2')->setValue('Fecha Inicio');
 $objSheet->getCell('G2')->setValue('Fecha Final');
 $objSheet->getCell('H2')->setValue('Estado');
 $objSheet->getCell('I2')->setValue('Iniciado');
 $objSheet->getCell('J2')->setValue('Finalizado');
 $objSheet->getCell('K2')->setValue('Duracion');
 
 //Se inserta el detalle de la consulta al informe
 $i = 3;
  if ($result = mysqli_store_result($mysqli)) {                
        while ($row = mysqli_fetch_assoc($result)) {
 
    //$row = eliminar_acentos($row);
    $objSheet->getCell('A'.$i)->setValue($row['empresa']);
    $objSheet->getCell('B'.$i)->setValue($row['nombre']);
    $objSheet->getCell('C'.$i)->setValue($row['mina']);
    $objSheet->getCell('D'.$i)->setValue($row['folio']);
    $objSheet->getCell('E'.$i)->setValue($row['atiende']);
    $objSheet->getCell('F'.$i)->setValue($row['fecha_inicio']);
    $objSheet->getCell('G'.$i)->setValue($row['fecha_final']);
    $objSheet->getCell('H'.$i)->setValue($row['estado']);
    $objSheet->getCell('I'.$i)->setValue($row['iniciado']);
    $objSheet->getCell('J'.$i)->setValue($row['finalizado']);
    $objSheet->getCell('K'.$i)->setValue($row['duracion_visita']);
     $i=$i+1; 
    }
 }
 //Ajuste de columnas a tamaño del texto contenido
 for ($col = 'A'; $col != 'L'; $col++) { 
      $objSheet->getColumnDimension($col)->setAutoSize(true);         
    }
 
 $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
             
 header('Content-Type: application/vnd.ms-excel');
 //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
 header('Content-Disposition: attachment;filename="Visitas de proveedores '.$mina_nom.'.xlsx"');
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');
 exit;
?>