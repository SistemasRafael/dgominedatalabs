<? include "connections/config.php";
//set_include_path(get_include_path() . PATH_SEPARATOR . "/xampp/htdocs/minedata_labs/dompdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
include 'dompdf/eds_dompdf_prep.php';
include 'mod_html2fpdf/html2fpdf.php';
//require('\xampp\htdocs\minedata_labs\mod_html2fpdf\html2fpdf.php');
//require('\mod_html2fpdf\html2fpdf.php');

if (isset($_GET['trn_id'])){
    
//$pdf = new HTML2FPDF();
$pdf = new HTML2FPDF('L','mm','A4');
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
                                        ) or die(mysqli_error());               
             $orden_encabezado = $datos_orden->fetch_assoc();
             
             $datos_orden_encab = $mysqli->query("SELECT
                                                	   od.trn_id, od.trn_id_rel, od.folio_interno, od.folio_inicial, od.folio_final, od.cantidad
                                                  FROM
                                                    arg_ordenes_detalle od
                                                  WHERE od.trn_id_rel = ".$trn_id."
                                                  ORDER BY
                                                	trn_id_rel")or die(mysqli_error());
                                                    
            $datos_metodos = $mysqli->query("SELECT 
                                                        DISTINCT me.nombre AS metodo, om.metodo_id                                                 
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id
                                            ) or die(mysqli_error()); 
             $total_metodos = (mysqli_num_rows($datos_metodos));  
             
             $total_mues = $mysqli->query("SELECT SUM(cantidad) AS total_muestras FROM arg_ordenes_detalle WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());
             $total_muest = $total_mues->fetch_assoc();
             $total_muestras = $total_muest['total_muestras'];
          
                 $html_en = "<table border='1' BGCOLOR='grey' CELLPADDING=4 CELLSPACING=2>
                             <thead>
                                 <tr border>   
                                    <th colspan='11'>Unidad de Mina: ".$orden_encabezado['unidad']."</th>
                                    <th colspan='5'>Usuario: ".$orden_encabezado['usuario']."</th> 
                                    <th colspan='4'>Fecha: ".$orden_encabezado['fecha_inicio']."</th>                                  
                                    
                                    
                                  </tr>                                  
                                  <tr>            
                                    <th colspan='11'>ORDEN: ".$orden_encabezado['folio']."</th>  
                                                               
                                    <th colspan='5'>Departamento: Geología</th>
                                    <th colspan='4'>Hr: ".$orden_encabezado['hora']."</th>
                                  
                                  </tr>";
                  $html_en.="</thead></table>";
                 
                  $html_det = "<table   border='1' CELLPADDING=5 CELLSPACING=0>
                                <thead>                                
                                     <tr>    
                                        <th BGCOLOR='gold' colspan='3'></th> 
                                        <th BGCOLOR='gold' colspan='5'>FOLIOS</th>      
                                        <th BGCOLOR='gold' colspan='".$total_metodos."'>ELEMENTOS A ANALIZAR</th>
                                     </tr>
                                    <tr>   
                                        <th colspan='3'>Batch</th>   
                                        <th colspan='2'>De la muestra</th>
                                        <th colspan='2'>A la muestra</th>
                                        <th colspan='1'>Muestras</th>";                          
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
                                            ) or die(mysqli_error());            
                                      
                                      while ($fila_metodo = $datos_metodos->fetch_assoc()) {  
                                            $met = $fila_metodo['metodo_id'];
                                            $detalle_metodos = $mysqli->query("SELECT
                                        	                                      COUNT(om.metodo_id) as existe
                                                                               FROM
                                                                            ordenes_metodos_lista om
                                                                           WHERE om.trn_id_orden = ".$trn_id." 
                                                                            AND om.trn_id_rel = ".$fila['trn_id']." 
                                                                            AND metodo_id = ".$met
                                                                        ) or die(mysqli_error());
                                                              
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
                               $html_det.="<td border='1' align='right' colspan='7'><strong>TOTAL MUESTRAS: </strong></td>";
                               $html_det.="<td align='right'><strong>".$total_muestras."</strong></td>";
                               
                               
                  $html_det.="</tbody></table>";
                  
                /// echo ("$html_en");
                // echo ("$html_det");    
            /* $html_det = "<table border='1' cellspacing='0'>
                                <thead>                                
                                     <tr class='table'>      
                                        <th colspan='3'>Folios</th>      
                                        <th colspan='".$total_metodos."'>Elementos a Analizar</th>
                                     </tr>
                                    <tr class='table-secondary' justify-content: center;>            
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>
                                        <th scope='col1'>Total muestras</th>";                                  
                                       while ($fila_met = $datos_metodos->fetch_assoc()) {
                                            $html_det.="<th align='center'>".$fila_met['nombre']."</th>";
                                       }                                
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               
              while ($fila = $datos_orden_encab->fetch_assoc()) {
                                   $po = 1;
                                   $trn_id_sig = $fila['trn_id'];
                                   $html_det.="<tr>";
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";
                                      $html_det.="<td align='right'>".$fila['cantidad']."</td>";
                                      
                                      $num = 1;             
                                      $datos_metodos_o = $mysqli->query("SELECT
                                    	                                   om.metodo_id, met.nombre
                                                                        FROM
                                                                            arg_ordenes_detalle od
                                                                            LEFT JOIN `arg_ordenes_metodos` om
                                                                                ON od.trn_id = om.trn_id_rel
                                                                            LEFT JOIN arg_metodos met
                                                                                ON om.metodo_id = met.metodo_id
                                                                            WHERE od.trn_id_rel = ".$trn_id
                                                                        ) or die(mysqli_error());
                                      while ($fila_met = $datos_metodos_o->fetch_assoc()) { 
                                        $valor_pos[$num] = $fila_met['metodo_id'];                                        
                                        $num++;                                        
                                      }  
                                    
                                      $po = 1;                                                                             
                                      while ($po <= $total_metodos){   
                                            $val = $valor_pos[$po];
                                            $datos_metodos_d = $mysqli->query("SELECT
                                                                        	om.metodo_id, met.nombre
                                                                        FROM
                                                                            `arg_ordenes_metodos` om
                                                                            LEFT JOIN arg_metodos met
                                                                            	ON om.metodo_id = met.metodo_id
                                                                        WHERE om.trn_id_rel = ".$trn_id_sig." and om.metodo_id = ".$val
                                                                        ) or die(mysqli_error()); 
                                                                        
                                            if ($datos_metodos_d->num_rows > 0) {                                       
                                                while ($fila_meto = $datos_metodos_d->fetch_assoc()) {
                                                    $met_id = $fila_meto['metodo_id'];
                                                    $val = $valor_pos[$po];
                                                      if ($met_id == $val){
                                                            $html_det.="<td align='center'>".'X'."</td>";
                                                      }
                                                      else{
                                                            $html_det.="<td align='center'></td>";
                                                            }
                                                }
                                            }
                                            else{
                                                    $html_det.="<td align='center'></td>";
                                            }                                                        
                                            $po++; 
                                      }
                                   $html_det.= "</tr>";
                            	}
                  $html_det.="</tbody></table>";    */   
        }
        //
       /// echo $html_det;

$pdf_header = '';
$img_factor = 1.0;//0.95;

$par_x = 3;
$par_y = 68;

//Encabezado
$ifactor = 220 / 950;
$iwidth = 800;
$pdf_header .= "<CXY X='45' Y='15'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.22/MineData-Labs/images/encabezado_visita.jpg">';
$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY>';


$par_options = array();
$par_options['top'] = '190px;';
$par_options['left'] = '110px';
$par_options['font-size'] = '8px;';
$pdf_header .= par_place($html_en, $par_options);

$par_options = array();
$par_options['top'] = '115px;';
$par_options['left'] = '20px';
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
$pdf_header .= par_place($section_html_personal6, $par_options);

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

$par_options = array();
$par_options['top']  = '280px;';
$par_options['left'] = '540px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('Recibe', $par_options);

///Contenido
$section_html = '';
$section_html20 .= $html_det;

//Visitantes
$par_options = array();
$par_options['top']  = '15px;';
$par_options['left'] = '105px';
$par_options['font-size'] = '11px;';
$pdf_html .= par_place($section_html20, $par_options);

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
$pdf_content .= '<div id="header">';
$pdf_content .= $pdf_header;
$pdf_content .= '</div>';
$pdf_content .= '<div id="footer">';
$pdf_content .= $pdf_footer;
$pdf_content .= '</div>';
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

?>
