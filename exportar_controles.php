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
$tipo = $_GET['tipo'];
$metodo_id = $_GET['metodo_id'];
$spreadsheet  = new Spreadsheet();
// set syles
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

if ($tipo == 1){
    mysqli_multi_query ($mysqli, "CALL visor_exportar_materiales ($metodo_id)") OR DIE (mysqli_error($mysqli));
               
    $objSheet = $spreadsheet->getActiveSheet();
    $objSheet->setTitle('Controles de Calidad Materiales');
    
     // Se agregan los titulos del reporte
     $objSheet->mergeCells('A1:D1');
     $objSheet->setCellValue('A1','CONTROLES DE CALIDAD'); 
     $objSheet->setCellValue('A2','Metodo de Analisis');
     $objSheet->setCellValue('B2','Tipo');
     $objSheet->setCellValue('C2','Nombre');
     $objSheet->setCellValue('D2','Cantidad Desviacion');
     $objSheet->setCellValue('E2','Desviacion Estandar');
     $objSheet->setCellValue('F2','Ley');
     $objSheet->setCellValue('G2','Maximo');
     $objSheet->setCellValue('H2','Minimo');
     
     //Se inserta el detalle de la consulta al informe
     $i = 3;
      if ($result = mysqli_store_result($mysqli)) {                
            while ($row = mysqli_fetch_assoc($result)) {
     
        //$row = eliminar_acentos($row);
        $objSheet->setCellValue('A'.$i,$row['metodo']);
        $objSheet->setCellValue('B'.$i,$row['control_calidad']);
        $objSheet->setCellValue('C'.$i,$row['nombre']);
        $objSheet->setCellValue('D'.$i,$row['cantidad_desviacion']);
        $objSheet->setCellValue('E'.$i,$row['desv_esta']);
        $objSheet->setCellValue('F'.$i,$row['valor_ley']);
        $objSheet->setCellValue('G'.$i,$row['maximo']);
        $objSheet->setCellValue('H'.$i,$row['minimo']);
         $i=$i+1; 
        }
     }
     //Ajuste de columnas a tama�o del texto contenido
     for ($col = 'A'; $col != 'I'; $col++) { 
          $objSheet->getColumnDimension($col)->setAutoSize(true);         
        }
    }
    
    if ($tipo == 2){
        mysqli_multi_query ($mysqli, "CALL visor_exportar_blancos ($metodo_id)") OR DIE (mysqli_error($mysqli));
                   
        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle('Controles de Calidad Blancos');
        
         // Se agregan los titulos del reporte
         $objSheet->mergeCells('A1:D1');
         $objSheet->setCellValue('A1','CONTROLES DE CALIDAd'); 
         $objSheet->setCellValue('A2','Metodo de Analisis');
         $objSheet->setCellValue('B2','Nombre');
         $objSheet->setCellValue('C2','Ley');
         $objSheet->setCellValue('D2','Maximo');
         $objSheet->setCellValue('E2','Minimo');
         
         //Se inserta el detalle de la consulta al informe
         $i = 3;
          if ($result = mysqli_store_result($mysqli)) {                
                while ($row = mysqli_fetch_assoc($result)) {
         
            //$row = eliminar_acentos($row);
            $objSheet->setCellValue('A'.$i,$row['metodo']);
            $objSheet->setCellValue('B'.$i,$row['nombre']);
            $objSheet->setCellValue('C'.$i,$row['valor_ley']);
            $objSheet->setCellValue('D'.$i,$row['minimo']);
            $objSheet->setCellValue('E'.$i,$row['maximo']);
             $i=$i+1; 
            }
         }
         //Ajuste de columnas a tama�o del texto contenido
         for ($col = 'A'; $col != 'F'; $col++) { 
              $objSheet->getColumnDimension($col)->setAutoSize(true);         
            }
        }
         
         
       //  $objPHPExcel->getActiveSheet()->protectCells('A1:C1', 'php');
         //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                     
         header('Content-Type: application/vnd.ms-excel');
         //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
         header('Content-Disposition: attachment;filename="Metodos de control.csv"');
         header('Cache-Control: max-age=0');
         $writer = new Xlsx($spreadsheet);
         $writer->save('php://output');
?>