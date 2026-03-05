<?
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Checador
 **/

 include "connections/config.php";
 require "vendor/autoload.php";
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 

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
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('�', '�', '�', '�'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
//$tipo = $_GET['tipo'];
$unidad_id = $_GET['unidad_id'];
//echo $unidad_mina;

 $unidad = $mysqli->query("SELECT nombre
                           FROM `arg_empr_unidades` 
                           WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
 $unidad_sele = $unidad->fetch_assoc();
 $mina_nom = $unidad_sele['nombre'];
  
 $spreadsheet  = new Spreadsheet();
 // set syles
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

mysqli_multi_query ($mysqli, "CALL visor_export_bancos (".$unidad_id.")") OR DIE (mysqli_error($mysqli));
           
$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Bancos '.$mina_nom);

 // Se agregan los titulos del reporte
 $objSheet->mergeCells('A1:D1');
 $objSheet->setCellValue('A1','Unidad de Mina '.$mina_nom); 
 $objSheet->setCellValue('A2','Mina');
 $objSheet->setCellValue('B2','Banco');
 $objSheet->setCellValue('C2','Descripcion');
 $objSheet->setCellValue('D2','Voladura');
 $objSheet->setCellValue('E2','Folio Actual');
 
 //Se inserta el detalle de la consulta al informe
 $i = 3;
  if ($result = mysqli_store_result($mysqli)) {                
        while ($row = mysqli_fetch_assoc($result)) {
 
    //$row = eliminar_acentos($row);
    $objSheet->setCellValue('A'.$i,$row['unidad_mina']);
    $objSheet->setCellValue('B'.$i,$row['banco']);
    $objSheet->setCellValue('C'.$i,$row['nombre']);
    $objSheet->setCellValue('D'.$i,$row['voladura_id']);
    $objSheet->setCellValue('E'.$i,$row['folio_actual']);
     $i=$i+1; 
    }
 }
 //Ajuste de columnas a tama�o del texto contenido
 for ($col = 'A'; $col != 'E'; $col++) { 
      $objSheet->getColumnDimension($col)->setAutoSize(true);         
    }
             
 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
 header('Content-Disposition: attachment;filename="Bancos y voladuras '.$mina_nom.'.csv"');
 header('Cache-Control: max-age=0');
 $writer = new Xlsx($spreadsheet);
 $writer->save('php://output');
?>