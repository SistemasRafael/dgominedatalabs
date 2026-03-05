<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
if (isset($_GET['trn_id'])){

//$pdf = new HTML2FPDF();
$pdf_style = '';

$pdf_style .= '@page { margin-top: 240px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 80%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 140px; text-align: border:1 center;font-size: 60% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';

            $trn_id = $_GET['trn_id'];            
            $datos_orden = $mysqli->query("SELECT
                                                un.nombre AS unidad, ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario
                                           FROM `arg_ordenes` ord                                            
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_usuarios us
                                            	ON us.u_id = ord.usuario_id
                                           WHERE ord.trn_id = ".$trn_id
                                        ) or die(mysqli_error($mysqli));               
             $orden_encabezado = $datos_orden->fetch_assoc();
             
             $datos_orden_encab = $mysqli->query("SELECT
                                                	   od.trn_id, od.trn_id_rel, od.folio_interno, od.folio_inicial, od.folio_final, od.cantidad
                                                  FROM
                                                    arg_ordenes_detalle od
                                                  WHERE od.trn_id_rel = ".$trn_id."
                                                  ORDER BY
                                                	trn_id_rel")or die(mysqli_error($mysqli));
                                                    
            $datos_metodos = $mysqli->query("SELECT 
                                                        DISTINCT me.nombre AS metodo, om.metodo_id                                                 
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id
                                            ) or die(mysqli_error($mysqli)); 
             $total_metodos = (mysqli_num_rows($datos_metodos));  
             
             $total_mues = $mysqli->query("SELECT SUM(cantidad) AS total_muestras FROM arg_ordenes_detalle WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());
             $total_muest = $total_mues->fetch_assoc();
             $total_muestras = $total_muest['total_muestras'];
          
                 $html_en = "<br/>
                 <table border='2' width='98%' CELLPADDING=5 CELLSPACING=0 style='font-size:9px;'>
                             <thead>
                                 <tr border>   
                                    <th width='30%' align='left'>Fecha: </th>
                                    <th width='20%' align='left'>Hora: </th> 
                                    <th width='30%' align='left'>Orden de Trabajo/Hoja: </th>                                  
                                    
                                    
                                  </tr>                                  
                                  <tr>            
                                    <th width='30%' align='left'><strong>".$orden_encabezado['fecha_inicio']."</strong></th>
                                    <th width='20%' align='left'>".$orden_encabezado['hora']."</th>
                                    <th width='30%' align='left'>".$orden_encabezado['folio']."</th>
                                  
                                  </tr>";
                  $html_en.="</thead></table><br/>";
                  //.$orden_encabezado['unidad']..$orden_encabezado['usuario']..$orden_encabezado['fecha_inicio'].
                  
                  $html_en .= "<table border='2' width='98%' CELLPADDING=5 CELLSPACING=0 style='font-size:9px;'>
                             <thead>
                                 <tr border> 
                                    <th colspan='5' align='right'>Usuario:</th>
                                    <th colspan='5' align='left'>".$orden_encabezado['usuario']."</th>
                                  </tr>                                  
                                  <tr>            
                                    <th colspan='5' align='right'>Unidad de Mina:</th>
                                    <th colspan='5' align='left'>".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table><br/>";
                  
                  $html_en .= "<table border='2' width='98%' CELLPADDING=5 CELLSPACING=0 style='font-size: 9px;'>
                             <thead>
                                 <tr > 
                                    <th colspan='5' rowspan='2' align='right'>Tipo de Muestra:</th>
                                    <th colspan='5' align='left'>O Nucleo</th>
                                    <th colspan='5' align='left'>O Detrito</th>
                                    <th colspan='5' align='left'>O Pulpa</th>
                                  </tr>                                  
                                  <tr>            
                                    <th colspan='5' align='left'>O Roca</th>
                                    <th colspan='10' align='left'>O Otro:</th>
                                  </tr>";
                  $html_en.="</thead></table><br/>";
                 
                  $html_det = "<br/><br/><br/><br/><br/><br/><br/><div style='position:relative; margin: 15px; margin-top: 15px; font-size: 12px;'>
                  <table  border='1' width='100%' CELLPADDING=5 CELLSPACING=0>
                                <thead>                                
                                     <tr>    
                                        <th BGCOLOR='gold' colspan='3'></th> 
                                        <th BGCOLOR='gold' colspan='5'>IDENTIFICACION DE MUESTRAS</th>      
                                        <th BGCOLOR='gold' colspan='".$total_metodos."'>ELEMENTOS A ANALIZAR</th>
                                     </tr>
                                    <tr>   
                                        <th colspan='3'>Batch</th>   
                                        <th colspan='2'>De la muestra</th>
                                        <th colspan='2'>A la muestra</th>
                                        <th colspan='1'>No.</th>";                          
                                        while ($fila_met = $datos_metodos->fetch_assoc()) {
                                            $html_det.="<th colspan='1' align='center'>".$fila_met['metodo']."</th>";
                                            $fila[$pos] = $fila_met['metodo'];
                                       }                                                                
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_encab->fetch_assoc()) {
                                   $po = 1;
                                   $trn_id_sig = $fila['trn_id'];
                                   $html_det.="<tr>";
                                      $html_det.="<td style='display:none;'>".$fila['trn_id']."</td>";
                                      $html_det.="<td colspan='3'>".$fila['folio_interno']."</td>";
                                      $html_det.="<td colspan='2'>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td colspan='2'>".$fila['folio_final']."</td>";
                                      $html_det.="<td colspan='1' align='right'>".$fila['cantidad']."</td>";
                                       
                                      $datos_metodos = $mysqli->query("SELECT 
                                                        DISTINCT me.nombre AS metodo, om.metodo_id                                                 
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id
                                            ) or die(mysqli_error($mysqli));            
                                      
                                      while ($fila_metodo = $datos_metodos->fetch_assoc()) {  
                                            $met = $fila_metodo['metodo_id'];
                                            $detalle_metodos = $mysqli->query("SELECT
                                        	                                      COUNT(om.metodo_id) as existe
                                                                               FROM
                                                                            ordenes_metodos_lista om
                                                                           WHERE om.trn_id_orden = ".$trn_id." 
                                                                            AND om.trn_id_rel = ".$fila['trn_id']." 
                                                                            AND metodo_id = ".$met
                                                                        ) or die(mysqli_error($mysqli));
                                                              
                                           $detalle_met = $detalle_metodos->fetch_assoc();
                                           $existe = $detalle_met['existe'];
                                           if($existe>0){
                                                $html_det.="<td colspan='1' align='center'>X</td>";
                                           }
                                           else{
                                                $html_det.="<td colspan='1' align='center'></td>";
                                           }
                                      }                                                                  
                                   $html_det.= "</tr>";
                               }
                               $html_det.="<tr><td border='1' align='right' colspan='7'><strong>NUMERO TOTAL DE MUESTRAS: </strong></td>";
                               $html_det.="<td align='right'><strong>".$total_muestras."</strong></td>";
                               $html_det.="<td colspan='".$total_metodos."'></td></tr>";
                               
                               
                  $html_det.="</tbody></table></div>";
                  
                  $html_foot = "<table  border='1' width='98%' CELLPADDING=5 CELLSPACING=0>
                                <thead>                                
                                     <tr>    
                                        <th colspan='3' align='center'>ENTREGA:</th> 
                                        <th colspan='5' align='center'>RECIBE:</th>      
                                     </tr>
                                     <tr>    
                                        <th colspan='3'><br/></th> 
                                        <th colspan='5'><br/></th>      
                                     </tr>
                               </thead>
                               </table>";
                  
               }
$pdf_header = '';
$img_factor = 0.526;//0.95;

$par_x = 30;
$par_y = 15;

//Encabezado
$ifactor = 220 / 1050;
$iwidth = 1500;
$pdf_header .= "<br/><div style=''><CXY X='15' Y='15'></CXY>";
$pdf_header .= '<img margin="10%" width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.22/MineData-Labs/images/encabezado_visita.jpg">';
$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY></div>';


/*$par_options = array();
$par_options['top'] = '170px;';
$par_options['left'] = '0px';
$par_options['font-size'] = '14px;';
$pdf_header .= par_place($html_en, $par_options);*/
/*
$par_options = array();
$par_options['top'] = '115px;';
$par_options['left'] = '15px';
$par_options['font-size'] = '13px;';

$section_html_personal .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal .= '<strong>'.'Raudel Villa Alvarado'.'</strong>';
$pdf_header .= par_place($section_html_personal, $par_options); 

$par_options = array();
$par_options['top'] = '150px;';
$par_options['left'] = '10px';
$par_options['font-size'] = '13px;';

$section_html_personal2 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal2 .= '<strong>'.'Juan Manuel Gallegos Medrano'.'</strong>';
$pdf_header .= par_place($section_html_personal2, $par_options); 

$par_options = array();
$par_options['top'] = '115px;';
$par_options['left'] = '280px';
$par_options['font-size'] = '13px;';

$section_html_personal3 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal3 .= '<strong>'.'Jose Luis Medrano'.'</strong>';
$pdf_header .= par_place($section_html_personal3, $par_options); 

$par_options = array();
$par_options['top'] = '150px;';
$par_options['left'] = '280px';
$par_options['font-size'] = '13px;';

$section_html_personal4 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html_personal4 .= '<strong>'.'Jesus Soto Perez'.'</strong>';
$pdf_header .= par_place($section_html_personal4, $par_options);

$par_options = array();
$par_options['top'] = '115px;';
$par_options['left'] = '600px';
$par_options['font-size'] = '13px;';

$section_html_personal5 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal5 .= '<strong>'.'Enriquez Reyes Rangel'.'</strong>';
$pdf_header .= par_place($section_html_personal5, $par_options); 

$par_options = array();
$par_options['top'] = '150px;';
$par_options['left'] = '600px';
$par_options['font-size'] = '13px;';

$section_html_personal6 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html_personal6 .= '<strong>'.'Alfredo Villa Gallegos'.'</strong>';
$pdf_header .= par_place($section_html_personal6, $par_options);*/
$pdf_header .= $html_en;
/*$section_html_personal .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal .= '<strong>'.'Raudel Villa Alvarado'.'</strong>';
$pdf_header .= $section_html_personal; 

$section_html_personal2 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal2 .= '<strong>'.'Juan Manuel Gallegos Medrano'.'</strong>';
$pdf_header .= $section_html_personal2; 

$section_html_personal3 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal3 .= '<strong>'.'Jose Luis Medrano'.'</strong>';
$pdf_header .= $section_html_personal3; 

$section_html_personal4 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html_personal4 .= '<strong>'.'Jesus Soto Perez'.'</strong>';
$pdf_header .= $section_html_personal4;

$section_html_personal5 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html_personal5 .= '<strong>'.'Enriquez Reyes Rangel'.'</strong>';
$pdf_header .= $section_html_personal5; 

$section_html_personal6 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html_personal6 .= '<strong>'.'Alfredo Villa Gallegos'.'</strong>';
$pdf_header .= $section_html_personal6;*/
/*


$par_options = array();
$par_options['top']  = '252px;';
$par_options['left'] = '40px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('__________________________________________', $par_options);

$par_options = array();
$par_options['top']  = '280px;';
$par_options['left'] = '150px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('Entrega', $par_options);

$par_options = array();
$par_options['top']  = '252px;';
$par_options['left'] = '440px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('__________________________________________', $par_options);
*/
$pdf_footer .= "__________________________________________";
$pdf_footer .= "Entrega";
$pdf_footer .= "__________________________________________";
/*$par_options = array();
$par_options['top']  = '250px;';
$par_options['left'] = '10px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place($html_foot, $par_options);*/

$pdf_footer .= $html_foot;
///Contenido
$section_html = '';
$section_html20 .= $html_det;

//Visitantes
/*$par_options = array();
$par_options['top']  = '150px;';
$par_options['left'] = '20px';
$par_options['font-size'] = '11px;';
$pdf_html .= par_place($section_html20, $par_options);*/
$pdf_html .= $section_html20;
$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$customPaper = array(0, 0, 180, 320);
$pdf->setPaper('Letter', 'portrait');

$pdf_content = '';

$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<style>';
$pdf_content .= $pdf_style;
$pdf_content .= '</style>';
$pdf_content .= '<div id="header">';
$pdf_content .= $pdf_header;
$pdf_content .= '</div>';
$pdf_content .= '</head>';
$pdf_content .= '<body>';
$pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';


$replace_what = array('á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ');
$replace_with = array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&Ntilde;');

$pdf_content = str_replace($replace_what, $replace_with, $pdf_content);

$pdf->loadHtml($pdf_content);

$output_options = array();

$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);

file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
