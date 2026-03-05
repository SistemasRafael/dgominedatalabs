<? include "connections/config.php";
//set_include_path("/var/www/html/MineData-Labs/dompdf");
/*require_once 'autoload.inc.php';
use Dompdf\Dompdf;
//include '/var/www/html/MineData-Labs/dompdf/eds_dompdf_prep.php';
//require('/var/www/html/MineData-Labs/mod_html2fpdf/html2fpdf.php');*/


//set_include_path("/var/www/html/MineData-Labs/dompdf");
set_include_path("C:\\xampp\\htdocs\\minedata_labs\\dompdf");
require_once 'autoload.inc.php';

use Dompdf\Dompdf;
//include '/var/www/html/MineData-Labs/dompdf/eds_dompdf_prep.php';
include 'C:\\xampp\\htdocs\\minedata_labs\\dompdf\\eds_dompdf_prep.php';
//require('/var/www/html/MineData-Labs/mod_html2fpdf/html2fpdf.php');
require('C:\\xampp\\htdocs\\minedata_labs\\mod_html2fpdf\\html2fpdf.php');

if (isset($_GET['trn_id'])){
    
//$pdf = new HTML2FPDF();
$pdf = new HTML2FPDF('L','mm','A4');
$pdf_style = '';

$pdf_style .= '@page { margin-top: 20px; margin-bottom: 390px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 65%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -45px; right: 0px; height: 140px; text-align: center;font-size: 80% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';

            $trn_id    = $_GET['trn_id'];
            $metodo_id = $_GET['metodo_id'];
            $datos_orden = $mysqli->query("SELECT
                                                 un.nombre AS unidad, od.folio_interno AS folio
                                                ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                           FROM `arg_ordenes` ord
                                           LEFT JOIN arg_ordenes_detalle od
                                           	   ON ord.trn_id = od.trn_id_rel
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id                                          
                                           WHERE od.trn_id =".$trn_id
                                        ) or die(mysqli_error());               
             $orden_encabezado = $datos_orden->fetch_assoc();
             $mina  = $orden_encabezado['unidad'];
             $folio = $orden_encabezado['folio'];
             $fecha = $orden_encabezado['fecha'];
                                         
             $detalle_etiquetas = $mysqli->query("SELECT
                                                        od.trn_id_batch,
                                                        od.posicion,
                                                        (CASE WHEN od.tipo_id = 0 THEN od.muestra_geologia WHEN od.tipo_id = 3 THEN control ELSE CONCAT(dup.muestra_geologia, ' - D') END) AS muestra_geologia
                                                 FROM
                                                        ordenes_transacciones od
                                                        LEFT JOIN (SELECT muestra_geologia, trn_id_batch, dup.metodo_id, trn_id_rel
                                                                   FROM
                                                                  		ordenes_transacciones dup
                                                                   
                                                                   ) AS dup
                                                        	ON od.trn_id_batch = od.trn_id_batch
                                                            AND od.metodo_id = dup.metodo_id
                                                            AND od.trn_id_dup = dup.trn_id_rel
                                                 WHERE
                                                        od.tipo_id IN(0, 5, 3)
                                                        AND od.trn_id_batch = ".$trn_id."
                                                        AND od.metodo_id = ".$metodo_id."
                                                  ORDER BY od.posicion "
                                                  ) or die(mysqli_error());            
            $total_muestras = (mysqli_num_rows($detalle_etiquetas));
            
            $f = 1;
            while ($fila_det = $detalle_etiquetas->fetch_assoc()) {
                $etiqueta_muestra[$f]  = $fila_det['muestra_geologia'];
                $etiqueta_posicion[$f]= $fila_det['posicion'];
                $f++;
            }    
                             
           $fila = 1;
           $i = 1;   
           $page = 1;
           $total_paginas = ceil($total_muestras/30);
           $total_filas   = ceil($total_muestras/30);
           while ($page <= $total_paginas){            
                $fila = 1;
                   while($fila < 11 and $i < $total_muestras){
                         $columna = 1;
                         while ($columna < 4){
                                if ($i >= $total_muestras){
                                     break;
                                }
                                else{
                                    $html_det.= "<table border='1' cellspacing='0'>
                                            <thead>                                
                                                <tr >      
                                                    <th align='center' colspan='3'>Unidad ".$mina."</th> 
                                                </tr>
                                            </thead>
                                            <tbody> ";
                                                    $html_det.="<th align='center' colspan='3'>Muestra: ".$etiqueta_muestra[$i]."</th>";
                                                    $html_det.="<tr>";
                                                    $html_det.="<tr>      
                                                                        <th colspan='1'>Consecutivo-Folio:</th> 
                                                                        <th colspan='1'>".$etiqueta_posicion[$i]."</th> 
                                                                        <th colspan='1'>".$folio."</th> 
                                                                </tr>
                                                                <tr>
                                                                         <th colspan='1'>Fecha Rec. :</th> 
                                                                        <th colspan='1'>".$fecha."</th> 
                                                                        <th colspan='1'>Lab. Quimico</th> 
                                                                </tr>";
                                                    $html_det.="</tr>";
                                                   
                                                  $html_det.="</tbody></table>";  
                                                  $i++;
                                                  
                                if($columna == 1){ 
                                    $left = '32px'; 
                                    switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }                                            
                                }
                                /* case 1: $top = '5px';   break; 
                                        case 2: $top = '70px'; break;
                                        case 3: $top = '135px'; break;
                                        case 4: $top = '200px'; break;
                                        case 5: $top = '265px'; break;
                                        case 6: $top = '330px'; break;
                                        case 7: $top = '395px'; break;
                                        case 8: $top = '460px'; break;
                                        case 9: $top = '525px'; break;
                                        case 10: $top = '590px'; break;*/
                                                  
                                if ($columna == 2){
                                    $left = '288px'; 
                                    switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }
                                }
                                                       
                                if ($columna == 3){
                                    $left = '552px';
                                    switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }
                                }
                                                                  
                                               //    echo 'fila:'.$fila.' '.$top.' columna'.$columna.' px'.$left.'</br>';
                                if ($columna == 3){
                                    $fila = $fila+1;
                                } 
                                $columna = $columna+1; 
                                                                                               
                                $par_options = array();
                                $par_options['top']  = $top;//'5px;';
                                $par_options['left'] = $left;//'10px';
                                $par_options['font-size'] = '4px;';
                                $pdf_html .= par_place($html_det, $par_options);
                                $top  = '';
                                $left = '';
                                $html_det = '';
                         }////Else del break
                        }                                
                   }  
                    $pdf_html .= '<div style="page-break-after:always;"></div>';
                   $page = $page+1; 
                   
                  
                   
                   
          }
$options = array();          
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter');
$pdf_content = '';

$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<style>';
$pdf_content .= $pdf_style;
$pdf_content .= '</style>';
$pdf_content .= '<body>';
$pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</head>';
$pdf_content .= '</html>';

$pdf->loadHtml($pdf_content);

$output_options = array();


$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);           
          
file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
 }
?>
