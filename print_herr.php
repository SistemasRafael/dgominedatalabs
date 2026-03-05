<? include "connections/config.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "/xampp/htdocs/registro/dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
include 'dompdf/eds_dompdf_prep.php';
require('\xampp\htdocs\registro\mod_html2fpdf\html2fpdf.php');

if (isset($_GET['trn_id'])){
    $pdf = new HTML2FPDF('L','mm','A4');
    $pdf_style = '';
    $pdf_style .= '@page { margin-top: 250px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
    $pdf_style .= 'html, body, table { font-family: helvetica; font-size: 100%; }';
    $pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 180px; text-align: center;font-size: 100% }';
    $pdf_style .= '#footer { position: fixed; left: 15px; bottom: -10px; right: 0px; height: 80px; }';
    $pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
    $pdf_style .= '';

            $trn_id_rel = $_GET['trn_id'];
            $empresa_encab = $mysqli->query("SELECT
                                            	arg_organizaciones.nombre
                                            FROM `arg_entradas` 
                                            LEFT JOIN arg_usuarios usu
                                            	ON usu.u_id = arg_entradas.usuario_id                                            
                                            LEFT JOIN arg_organizaciones
                                                ON usu.org_id = arg_organizaciones.org_id
                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $empresa = $empresa_encab ->fetch_array(MYSQLI_ASSOC);

            $datos_e = $mysqli->query("SELECT folio, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, DATE_FORMAT(fecha_inicio,'%d/%m/%Y') AS fecha_inicio
                                            ,DATE_FORMAT(fecha_final,'%d/%m/%Y') AS fecha_final, arg_empr_unidades.nombre AS unidad
                                            ,arg_usuarios.nombre AS usuario
         	                                ,arg_entradas.comentario
                                        FROM `arg_entradas` 
                                        LEFT JOIN arg_entradas_detalle
                                        	ON arg_entradas_detalle.trn_id_rel = arg_entradas.trn_id
                                        LEFT  JOIN arg_empr_unidades
                                            ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id
                                        LEFT JOIN arg_usuarios
                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id
                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $entrada = $datos_e->fetch_assoc();
            
            $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca, arg_herramientas.serie
                                                FROM `arg_entradas_herramientas` 
                                                LEFT JOIN arg_herramientas
                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                                                WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());
                                                
             $html_enc1 = "<table id='encabezad' width='95%'>                                      
                                <h4>
                                 RESGUARDO PROVISIONAL
                            </h4>";
            $html_enc1.="</table>";
            
            $html_enc.= "<table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
                                <thead>
                                 <tr>            
                                    <th scope='col' width='50%'  style='border:0.1px solid' align='left' bgcolor='white'>Nombre de la Empresa: ".$empresa['nombre']."</th>
                                </tr>                                
                            </thead>";
            $html_enc.="</table>";
            
            $html_enc.= "<table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
                                <thead>                                 
                                <tr>     
                                    <th scope='col' width='40%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre de quien elabora el resguardo:</th>
                                    <th scope='col' width='55%' style='border:0.1px solid' align='left' bgcolor='white'></th>
                                </tr>
                                <tr>     
                                    <th scope='col' style='border:0.1px solid' align='left' bgcolor='white'>Fecha de Ingreso: ".$entrada['fecha_inicio']."</th>
                                    <th scope='col' style='border:0.1px solid' align='left' bgcolor='white'>Fecha de Salida: ".$entrada['fecha_final']."</th>
                                </tr>
                            </thead>";
            $html_enc.="</table>";
                  
            $html_veh .= "<table id='encab' width='95%'  style='border:0.1px solid'>                                      
                                <thead>
                                 <tr>            
                                    <th scope='col' align='center' bgcolor='gray'>Descripción de los Materiales</th>
                                </tr>
                            </thead>";
            $html_veh .="</table>";
            $html_veh .= "<table id='veh' width='95%'  style='border:0.1px solid'>                                      
                                <thead>
                                 <tr>            
                                    <th scope='col' width='5%'  align='left' bgcolor='gray'>No</th>
                                    <th scope='col' width='10%' align='left' bgcolor='gray'>Cantidad</th>
                                    <th scope='col' width='20%' align='left' bgcolor='gray'>Marca</th>
                                    <th scope='col' width='30%' align='left' bgcolor='gray'>Serie</th>                                    
                                    <th scope='col' width='40%' align='left' bgcolor='gray'>Descripción</th>
                                </tr>
                            </thead>";
            $html_veh.="</table>";
            
            $i=0;                     
            $html_veh .= "<table id='herrami' width='95%' style='border:1px'>
                              
                            <tbody>";                    
                            	while ($fila_her = $herramientas->fetch_assoc()) {
                            	     $i++;
                            		$html_veh.="<tr>
                                                <td width='5%'  style='border:1px solid; font-size:14px' align='left'>".$i."</td>
                                                <td width='10%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['cantidad']."</td>
                                                <td width='20%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['marca']."</td>
                                                <td width='30%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['serie']."</td>
                                                <td width='40%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['nombre']."</td>
                                             </tr>"; 
                            	}
                  $html_veh.="</tbody></table>";
                  
            $pie_pag = "<table id='pie_p' width='95%' >                                      
                                <thead>
                                 <tr>            
                                    <th scope='col' width='30%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre y firma de quien autoriza el ingreso</th>
                                    <th scope='col' width='70%' style='border:0.1px solid' align='left' bgcolor='white'></th>
                                </tr>
                            </thead>";
            $pie_pag.="</table>";
            
            $pie_pag.= "<table id='pie_p' width='95%'>                                      
                                <thead>
                                 <tr>            
                                    <th scope='col' width='30%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre y firma de quien autoriza la salida</th>
                                    <th scope='col' width='70%' style='border:0.1px solid' align='left' bgcolor='white'></th>
                                </tr>
                            </thead>";
            $pie_pag.="</table>";   
        }

$pdf_header = '';
$img_factor = 0.95;

$par_x = 3;
$par_y = 5;

//Encabezado
$ifactor = 220 / 950;
$iwidth = 200;
$pdf_header .= '<CXY X="0" Y="3"></CXY>';
$pdf_header .= '<img width="120" align="left" src="http://192.168.20.3:81/registro/images/logo2.png">'.'<br />'.'<br />'.'<br />';
$pdf_header .= '<CXY X="0" Y="15"></CXY>';
$pdf_header .= '<cfs FONTSIZE="3"></cfs>';
$pdf_header .= 'RESGUARDO PROVISIONAL';

$par_options = array();
$par_options['top'] = '85px;';
$par_options['left'] = '0px';
$par_options['font-size'] = '6px;';
$pdf_header .= par_place($html_enc, $par_options); 

$par_options = array();
$par_options['top'] = '160px;';
$par_options['left'] = '0px';
$par_options['font-size'] = '8px;';
$pdf_header .= par_place($html_veh, $par_options); 

$par_options = array();
$par_options['top'] = '140px;';
$par_options['left'] = '0px';
$par_options['font-size'] = '6px;';
$pdf_footer .= par_place($pie_pag, $par_options); 
/*
$img_factor = 1.0;//0.95;
///////Footer
$ifactor = 220 /950;
$iwidth = 900;
$pdf_footer  = "<CXY X='15' Y='190'></CXY>";
$pdf_footer .= '<img width="750" height="110" src="http://192.168.20.3:81/registro/images/pie_visita_herr.jpg">';
*/

///Contenido
$ifactor = 220 /950;
$iwidth = 300;
$section_html = '';
$pdf_html  = "<CXY X='0' Y='150'></CXY>";
//$pdf_html .= '<img width="815" height="50" src="http://192.168.20.3:81/registro/images/detalle_visita.jpg">';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 60) . '"></CXY>';
$section_html20 = $html_vis;

    

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
