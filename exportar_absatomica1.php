<?
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * CSV Absorción
 **/

include "connections/config.php";
//include 'mod_html2fpdf/html2fpdf.php';
/*include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';
include '\xampp\htdocs\__pro\argonaut\common\PHPExcel\Classes\Writer\Excel5.php';*/

//require("ldap.php");
/*:(
require("Classes/PHPExcel.php");
require("Classes/IOFactory.php");
require("Classes/Writer/Excel5.php");*/

include "phpExcel/Classes/PHPExcel.php";
include "phpExcel/Classes/IOFactory.php";
include "phpExcel/Classes/Writer/Excel5.php";
//include "phpExcel/PHPExcel/Writer/Excel5.php";
//include "phpExcel/PHPExcel/Writer/Excel5.php";


/*include 'phpExcel\Classes\PHPExcel.php';
include 'phpExcel\Classes\IOFactory.php';
include 'PHPExcel\Classes\Writer\Excel5.php';*/

$trn_id_a = $_GET['trn_id_a'];
$metodo_id_a = $_GET['metodo_id_a'];
$u_id_a = $_GET['u_id_a'];

$objPHPExcel = new PHPExcel;
error_reporting(1);
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

    $datos_at = $mysqli->query("SELECT folio_interno FROM arg_ordenes_detalle WHERE trn_id = $trn_id_a") or die(mysqli_error());
    $usuario_atiende = $datos_at->fetch_assoc();
    $folio = $usuario_atiende['folio_interno'];
    
    $datos_met = $mysqli->query("SELECT nombre AS nombre_metodo, volumen FROM `arg_metodos` WHERE metodo_id = $metodo_id_a") or die(mysqli_error());
    $datos_meto = $datos_met->fetch_assoc();
    $nombre_metodo  = $datos_meto['nombre_metodo'];
    $volumen_metodo = $datos_meto['volumen'];
            
    //mysqli_multi_query ($mysqli, "CALL arg_prc_absorcionAtomicaExp ($trn_id_a,$metodo_id_a,$u_id_a)") OR DIE (mysqli_error($mysqli));
               
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle($nombre_metodo);
     
     //Se inserta el detalle de la consulta al informe
     $i = 1;
     $bloque = 10;
     $total_bloque = 10;
     //echo $i;
  //  if ($result = mysqli_store_result($mysqli)) {                
       //     while ($row = mysqli_fetch_assoc($result)) {
                
            //    if ($bloque%$total_bloque == 0){
                    $objSheet->getCell('A'.$i)->setValue($i);
            /*        $objSheet->getCell('B'.$i)->setValue('PATRON');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                    //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
              /*      $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                    //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                    
                /*    $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO REACTIVO');
                    $objSheet->getCell('C'.$i)->setValue('RBLK');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                    //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
      //              $i=$i+1;
          //      }
     
            //$row = eliminar_acentos($row);
        
        /*    $objSheet->getCell('A'.$i)->setValue($i);
            $objSheet->getCell('B'.$i)->setValue($row['folio_interno']);
            $objSheet->getCell('C'.$i)->setValue($row['nombre']);
            $objSheet->getCell('D'.$i)->setValue($row['peso']);
            $objSheet->getCell('E'.$i)->setValue($volumen_metodo);
            $objSheet->getCell('F'.$i)->setValue('1');
            $objSheet->getCell('G'.$i)->setValue('1');
            $objSheet->getCell('H'.$i)->setValue('1');*/
            
         /*   $colu='F'.$i.':'.'G'.$i;
            $objSheet->protectCells($colu, 'PHP');*/
            //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
          /*  $i=$i+1;
            $bloque=$bloque+1;*/
    /*    }
     }
     
     $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('PATRON');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                  //  $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
              /*      $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO');
                    $objSheet->getCell('C'.$i)->setValue('SAMP');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                    //$objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            /*        $i=$i+1;
                    $objSheet->getCell('A'.$i)->setValue($i);
                    $objSheet->getCell('B'.$i)->setValue('BLANCO REACTIVO');
                    $objSheet->getCell('C'.$i)->setValue('RBLK');
                    $objSheet->getCell('D'.$i)->setValue('1');
                    $objSheet->getCell('E'.$i)->setValue('1');
                    $objSheet->getCell('F'.$i)->setValue('1');
                    $objSheet->getCell('G'.$i)->setValue('1');
                    $objSheet->getCell('H'.$i)->setValue('1');
                    $colu='F'.$i.':'.'G'.$i;
                    $objSheet->protectCells($colu, 'PHP');*/
                  //  $objSheet->getStyle($colu)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
     //               $i=$i+1;
         //$objSheet->getStyle('A2:B2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
         //$objSheet->getProtection()->setPassword('minedata8821');
       
       /*  $objSheet->getProtection()->setPassword('minedata1506');
         $objSheet->getProtection()->setSheet(true);*/
     
         //header('Content-Type: text/csv');         
         //header('Content-Transfer-Encoding: binary; charset=utf-8');
         //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename='.$folio.'_'.$metodo_id_a.'.csv');
         header('Cache-Control: max-age=0');
         
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         //$fp = fopen("php://output", 'w');
         $objWriter->save('php://output');
         exit;
?>