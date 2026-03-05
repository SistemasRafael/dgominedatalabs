<? include "connections/config.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "/xampp/htdocs/registro/dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
include 'dompdf/eds_dompdf_prep.php';
require('\xampp\htdocs\registro\mod_html2fpdf\html2fpdf.php');

if (isset($_GET['trn_id'])){
    
//$pdf = new HTML2FPDF();

$pdf = new HTML2FPDF('L','mm','A4');

$pdf_style = '';

$pdf_style .= '@page { margin-top: 240px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 100%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 140px; text-align: center;font-size: 100% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';

            $trn_id_rel = $_GET['trn_id'];
            $empresa_encab = $mysqli->query("SELECT
                                            	arg_organizaciones.nombre, arg_organizaciones.calle, arg_organizaciones.num_exterior, arg_entradas.comentario, aut.nombre as usuario_firma, dic.firma
                                               ,arg_organizaciones.colonia, arg_ciudades.ciudad, usu.nombre AS usuario, ine.imss AS ine, lic.imss AS licencia, imss.imss AS nss
                                            FROM `arg_entradas` 
                                            LEFT JOIN arg_usuarios usu
                                            	ON usu.u_id = arg_entradas.usuario_id
                                            LEFT JOIN usuarios_doc ine
                                            	ON ine.u_id = usu.u_id
                                                AND ine.tipo_id = 2
                                            LEFT JOIN usuarios_doc lic
                                            	ON lic.u_id = usu.u_id
                                                AND lic.tipo_id = 3
                                            LEFT JOIN usuarios_doc imss
                                            	ON imss.u_id = usu.u_id
                                                AND imss.tipo_id = 1
                                            LEFT JOIN arg_organizaciones
                                                ON usu.org_id = arg_organizaciones.org_id
                                            LEFT JOIN arg_ciudades
                                                ON arg_ciudades.ciudad_id = arg_organizaciones.ciudad_id
                                            LEFT JOIN arg_usuarios_directivas dic
                                                ON dic.valor = arg_entradas.unidad_id
                                                AND dic.directiva_id = 2
                                            LEFT JOIN arg_usuarios aut
                                                ON aut.u_id = dic.u_id
                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $empresa = $empresa_encab ->fetch_array(MYSQLI_ASSOC);
            $datos_v = $mysqli->query("SELECT arg_actividad.nombre 
                                       FROM arg_entradas_actividad 
                                       LEFT JOIN arg_actividad 
                                            ON arg_actividad.act_id = arg_entradas_actividad.act_id
                                       WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error()); 
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
            $datos_at = $mysqli->query("SELECT arg_usuarios.nombre AS atiende, dep.departamento
                                        FROM `arg_entradas`                                         
                                        LEFT JOIN arg_usuarios
                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_atie
                                        LEFT JOIN arg_usuarios_departamentos AS ude
                                        	ON ude.u_id = arg_usuarios.u_id
                                        LEFT JOIN arg_departamentos dep
                                        	ON dep.dep_id = ude.dep_id
                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $datos_ati = $datos_at->fetch_assoc();          
            $datos_res = $mysqli->query("SELECT arg_usuarios.nombre AS responsable
                                        FROM `arg_entradas`                                         
                                        LEFT JOIN arg_usuarios
                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_resp
                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $usuario_resp = $datos_res->fetch_assoc();          
            $visitantes = $mysqli->query("SELECT arg_usuarios.nombre AS visitante, usuarios_doc.imss AS ine, imss.imss AS nss
                                            FROM `arg_entradas_detalle`                                         
                                            LEFT JOIN arg_usuarios
                                            	ON arg_entradas_detalle.usuario_id = arg_usuarios.u_id
                                            LEFT JOIN usuarios_doc
                                            	ON usuarios_doc.u_id = arg_entradas_detalle.usuario_id
                                                AND tipo_id = 2
                                             LEFT JOIN usuarios_doc imss
                                            	ON imss.u_id = arg_entradas_detalle.usuario_id
                                                AND imss.tipo_id = 1
                                            WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());
            $vehiculos = $mysqli->query("SELECT lic.imss as licencia, arg_vehiculos.marca, arg_vehiculos.modelo, arg_vehiculos.color, arg_vehiculos.placas, arg_vehiculos.poliza
                                            FROM `arg_entradas` 
                                            LEFT JOIN arg_vehiculos
                                                ON arg_vehiculos.veh_id = arg_entradas.veh_id
                                            LEFT JOIN usuarios_doc lic
                                            	ON arg_entradas.usuario_id = lic.u_id
                                                AND tipo_id = 3
                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca
                                                FROM `arg_entradas_herramientas` 
                                                LEFT JOIN arg_herramientas
                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                                                WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());           
        

                 $html_en.= "<table>
                                 <tr>   
                                    <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>
                                    <th scope='col' colspan='6'></th>
                                    <th scope='col' colspan='6'></th>
                                    <th scope='col'>Visita Desde: ".$entrada['fecha_inicio']."</th>
                                    <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>
                                  </tr>
                                  <tr> 
                                    <td>  <img src='..images/encabezado_visita.jpg'> </td>
                                  </tr>
                            </table>";
                 //Visitantes  
                 $html_vis = "<table id='veh' width='100%'  style='border:0.5px solid'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col' align='center' bgcolor='gold'>EMPRESA VISITANTE</th>
                                </tr>                                 
                            </thead>";
                  $html_vis.="</table>";
                  
                  $html_vis .= "<table id='veh' width='100%'  style='border:0.5px solid'>
                                <thead>
                                 <tr>            
                                    <th scope='col' align='center' width='40%'  bgcolor='gray'>Nombre de los visitantes</th>
                                    <th scope='col' align='center' bgcolor='gray'>No de Gafete</th>
                                    <th scope='col' align='center' bgcolor='gray'>No. IMSS */ Gastos Medicos Mayores*</th>
                                    <th scope='col' align='center' bgcolor='gray'>No. Identificación*      (No. Credencial IFE)</th>
                                </tr>
                            </thead>";
                  $html_vis.="</table>";
                          
                 $html_vis .= "<table id='visitantes' style='font-size: 14px;'>";
                                $html_vis.="<tr>"; 
                            	$html_vis.="<td width='350'><a>".$empresa['usuario']."</a></td><td width='120'><a>".$empresa['nss']."</a></td><td><a>".$empresa['ine']."</a></td>";
                                $html_vis.="</tr>"; 
                            	while ($fila_v = $visitantes->fetch_assoc()) {   
                            	  // echo $fila_v['visitante'];
                                    $html_vis.="<tr>"; 
                            		$html_vis.="<td width='350'><a>".$fila_v['visitante']."</a></td><td width='120'><a>".$fila_v['nss']."</a></td><td><a>".$fila_v['ine']."</a></td>";
                                    $html_vis.="</tr>";                                  
                            	}
                  $html_vis .= "</table>";
                 
                 //Motivos, datos de la visita
                 $html_m = "<table id='veh' width='100%'  style='border:0.5px solid'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col' align='center' bgcolor='gold'>DATOS DE LA VISITA</th>
                                </tr>                                 
                            </thead>";
                  $html_m .="</table>";
                  $html_m .= "<table id='motivos' style='font-size: 14px;'>";    
                                $html_m.="<tr>"; 
                            	$html_m.="<td width='300px'><a>Responsable de la visita:</a></td>"; 
                                $html_m.="<td width='300px'><a>".$datos_ati['atiende']."</a></td>";  
                                $html_m.="</tr>"; 
                                $html_m.="<tr>";
                            	$html_m.="<td width='300px'><a>Departamento del responsable de la visita:</a></td>";
                                $html_m.="<td width='300px'><a>".$datos_ati['departamento']."</a></td>";  
                                $html_m.="</tr>";
                                $html_m.="<tr>"; 
                            	$html_m.="<td width='300px'><a>Motivos de la visita:</a></td>";  
                                $html_m.="<td width='600px'>";    
                            	while ($fila = $datos_v->fetch_assoc()) {               	                     
                            		$html_m.="<a>".$fila['nombre'].", "."</a>";
                            	}
                                $html_m.="<tr>"; 
                            	$html_m.="<td width='300px'><a>Comentarios:</a></td>"; 
                                $html_m.="<td width='300px'><a>".$empresa['comentario']."</a></td>";  
                                $html_m.="</tr>";
                  $html_m .= "</td></tr></table>";
                 
                $html_veh = "<table id='veh' width='100%'  style='border:0.5px solid'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col' align='center' bgcolor='gold'>INGRESO DE VEHÍCULOS VISITANTES</th>
                                </tr>                                 
                            </thead>";
                  $html_veh.="</table>";
                  
                  $html_veh .= "<table id='veh' width='100%'  style='border:0.5px solid'>
                                <thead>
                                 <tr>            
                                    <th scope='col' align='center' bgcolor='gray'>Licencia</th>
                                    <th scope='col' align='center' bgcolor='gray'>Marca</th>
                                    <th scope='col' align='center' bgcolor='gray'>Modelo</th>
                                    <th scope='col' align='center' bgcolor='gray'>Color</th>
                                    <th scope='col' align='center' bgcolor='gray'>No Póliza</th>
                                    <th scope='col' align='center' bgcolor='gray'>Placas</th>
                                </tr>
                            </thead>";
                  $html_veh.="</table>";
                  
                  $html_veh .= "<table id='veh' width='90%'>
                                <thead>
                                </thead>
                            <tbody>";                    
                            	while ($fila_ve = $vehiculos->fetch_assoc()) {
                            		$html_veh.="<tr>
                                                <td width='160px'>".$fila_ve['licencia']."</td>
                                                <td width='120px'>".$fila_ve['marca']."</td>
                                                <td width='130px'>".$fila_ve['modelo']."</td>
                                                <td width='120px'>".$fila_ve['color']."</td>
                                                <td width='160px'>".$fila_ve['poliza']."</td>
                                                <td width='160px'>".$fila_ve['placas']."</td>
                                             </tr>";
                            	}
                  $html_veh.="</tbody></table>";
                                 
        }

$pdf_header = '';
$img_factor = 1.0;//0.95;

$par_x = 3;
$par_y = 68;

//Encabezado
$ifactor = 220 / 950;
$iwidth = 800;
$pdf_header .= "<CXY X='15' Y='15'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.3:81/registro/images/encabezado_visita.jpg">';
$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY>';
$section_html .= '<strong>Nombre: </strong>'.$empresa['nombre'].'<br />';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html .= '<strong>Dirección: </strong>'.$empresa['calle'].' '.$empresa['num_exterior'].', '.$empresa['colonia'].', '.$empresa['ciudad'].'<br />';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html .= '<strong>Responsable de la Visita: </strong>'.$usuario_resp['responsable'].'<br/>';
$section_html .= '<strong> Atiende: </strong>'.$usuario_atiende['atiende'].'<br/>';
$section_html .= '<strong>'.$entrada['unidad'].'</strong><br/><br/>';

$par_options = array();
$par_options['top'] = '80px;';
$par_options['left'] = '220px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html, $par_options);

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '220px';
$par_options['font-size'] = '13px;';

   $section_html2 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html2 .= '<strong>'.$entrada['fecha_inicio'].'</strong>';
$pdf_header .= par_place($section_html2, $par_options);    

   $section_html3 .= '<CXY X="' . ($par_x+390) . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html3 .= '<strong>'.$entrada['fecha_final'].'</strong>'; 

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '360px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html3, $par_options);

   $section_html4 .= '<CXY X="' . ($par_x+390) . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html4 .= '<strong>'.$entrada['fecha'].'</strong>'; 

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '590px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html4, $par_options); 

$par_options = array();
$par_options['top']  = '200px;';
$par_options['left'] = '20px';
$par_options['font-size'] = '12px;';
$pdf_header2 .='Máximo 3 Días; Los   Gafetes de  Acceso se  entregan y regresan a Seguridad  Patrimonial Diariamente;'.'<br/>';
$pdf_header2 .='Para permisos por  mas de tres días y  para realizar trabajos se deberá asistir al Curso de Inducción'.'<br/>';
$pdf_header2 .='a la Seguridad el cual se imparte solo en días Lunes y Jueves de cada semana de 08:00 a 12:00 del día.'.'<br/>';

$pdf_header .= par_place($pdf_header2, $par_options); 

$img_factor = 1.0;//0.95;
///////Footer
$ifactor = 220 /950;
$iwidth = 1900;
$pdf_footer .= "<CXY X='15' Y='150'></CXY>";
$pdf_footer .= '<img width="800" height="220" src="http://192.168.20.3:81/registro/images/pie_visita1.jpg">';
$par_options = array();
$par_options['top']  = '230px;';
$par_options['left'] = '650px';
$pdf_header3 .= '<img width="50" height="50" src="'.$empresa['firma'].'">';
$pdf_footer .= par_place($pdf_header3, $par_options); 

$par_options = array();
$par_options['top']  = '250px;';
$par_options['left'] = '120px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place($datos_ati['atiende'], $par_options);

$par_options = array();
$par_options['top']  = '250px;';
$par_options['left'] = '550px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place($empresa['usuario_firma'], $par_options); 

$par_options = array();
$par_options['top']  = '252px;';
$par_options['left'] = '40px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('__________________________________________', $par_options);

$par_options = array();
$par_options['top']  = '280px;';
$par_options['left'] = '45px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('Nombre y Firma del Responsable de Atender la Visita', $par_options);

$par_options = array();
$par_options['top']  = '252px;';
$par_options['left'] = '440px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('__________________________________________', $par_options);

$par_options = array();
$par_options['top']  = '280px;';
$par_options['left'] = '440px';
$par_options['font-size'] = '14px;';
//$pdf_headeratie = $html_ati;
$pdf_footer .= par_place('Nombre y Firma Dpto. SSMA de la Unidad de Negocios', $par_options);

///Contenido
$ifactor = 220 /950;
$iwidth = 300;
$section_html = '';
$pdf_html  = "<CXY X='0' Y='150'></CXY>";
//$pdf_html .= '<img width="815" height="50" src="http://192.168.20.3:81/registro/images/detalle_visita.jpg">';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 60) . '"></CXY>';
$section_html20 = $html_vis;

//Visitantes
$par_options = array();
$par_options['top'] = '10px;';
$par_options['left'] = '10px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($section_html20, $par_options);

//Vehículo
$par_options = array();
$par_options['top'] = '280px;';
$par_options['left'] = '10px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($html_veh, $par_options);

//Vehículo
$par_options = array();
$par_options['top'] = '380px;';
$par_options['left'] = '10px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($html_m, $par_options);
    

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

/*                
$pdf->htmlHeader = $pdf_header;
$pdf->tMargin = 55;
 //$pdf_header = ob_get_clean();
// Pie de página

$pdf_footer .= $pdf_html;
$pdf->htmlBeforePageText = '';

$pdf->htmlFooter = $pdf_footer;
//$pdf->WriteHTML($pdf_html); 

$pdf->Output('../registro/argonaut4.pdf');
        
if ($_GET['html'] == 1) {
	print $pdf_header;
	print $pdf_html;
	print $pdf_footer;
} else {
		header('Accept-Ranges: bytes');
        
    	//$pdf->Output();
    $mi_pdf = '/xampp/htdocs/registro/argonaut4.pdf';
    $mi_pdf = '../registro/argonaut4.pdf';
    header('Content-type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
    readfile($mi_pdf);
}
*/          

?>
