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
$pdf_style .= '@page { margin-top: 20px; margin-bottom: 20px; margin-left: 20px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -45px; right: 0px; height: 20px; text-align: left;font-size: 20% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';


            $trn_id    = $_GET['trn_id'];
            $query = "CALL arg_rpt_generarPDF (".$trn_id.")";
            $datos_orden = $mysqli->query("SELECT
                                                 un.nombre AS unidad, od.folio_interno AS folio
                                                ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                                ,DATE_FORMAT(odb.fecha_fin,'%d/%m/%Y') AS fecha_fin
                                                ,buscar_etapa('".$trn_id."','0') AS etapa
                                                ,odb.u_id AS id_usuario
                                           FROM `arg_ordenes` ord
                                           LEFT JOIN arg_ordenes_detalle od ON ord.trn_id = od.trn_id_rel
                                           LEFT JOIN arg_empr_unidades AS un ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_ordenes_bitacora_detalle AS odb ON ord.trn_id = odb.trn_id_rel                                          
                                           WHERE od.trn_id =".$trn_id
                                        ) or die(mysqli_error());               
             $orden_encabezado = $datos_orden->fetch_assoc();
             $mina  = $orden_encabezado['unidad'];
             $folio = $orden_encabezado['folio'];
             $fecha = $orden_encabezado['fecha'];
             $fecha_f = $orden_encabezado['fecha_fin'];
             $etapa = $orden_encabezado['etapa'];
             $uid = $orden_encabezado['id_usuario'];


             $pdf_html .= "<h2 style='text-align:center; margin-bottom:5px;'><strong> Certificado de Analisis </strong></h2>";
             $pdf_html .= "<p  style='text-align:center;'> Folio: <strong>".$folio."</strong> </p>";
             if ($fecha_f!=""){
                $pdf_html .= "<p  style='text-align:center;'>fecha final: ".$fecha_f."</p>";
             }
             $pdf_html .= "<p  style='text-align:left; margin-left: 30px;'> Unidad: ".$mina." </p>";
             $pdf_html .= "<p  style='text-align:left; margin-left: 30px;'> Fecha inicial: ".$fecha."</p>";
             $pdf_html .= "<p  style='text-align:left; margin-left: 30px;'> Etapa: ".$etapa."</p>";

             
                if($etapa >= 11){
                    $query2 = $mysqli->query("SELECT u.nombre As nombre, u.email As correo, ud.firma as firma 
                                                From `arg_usuarios_directivas` ud
                                                LEFT JOIN arg_usuarios u ON u.u_id = ud.u_id
                                                WHERE ud.u_id =".$uid )or  die(mysqli_error()); 
                    $datosfimra = $query2->fetch_assoc(); 
                    $nombre = $datosfimra['nombre'];
                    $correo = $datosfimra['correo'];
                    $firma = $datosfimra['firma'];
                    $pdf_html .=  "<table align='right'>";
                    $pdf_html .= "<tr ><td ><p  style='text-align:center; margin-top: 32px;'>  Certificado por:</p></td ></tr>";
                    $pdf_html .= "<tr ><td ><div align='center' style='margin-right: 30px;'><img src = '".$firma."'></img></div></td ></tr>";
                    $pdf_html .= "<tr ><td ><p  style='text-align:center; margin-right: 30px;'>  ".$nombre."</p></td ></tr>";
                    $pdf_html .= "<tr ><td ><p  style='text-align:center; margin-right: 30px;'> ".$correo."</p></td ></tr>";
                    $pdf_html .=  "</table>";

                    
                }
          

             $pdf_html .= '<div style="page-break-after:always; margin-bottom:50px;"></div>';

             $result = $mysqli->query($query);
             $pdf_html .= "<div style='margin-top:50px'></div>
                           <table align='center'; border='1'; cellspacing='0'> 
                                <thead> 
                                    <tr> 
                                        <th align='center' colspan='4'> Resultados </th> 
                                    </tr> 
                                </thead> 
                                <tbody border = '1'>";
                                        while($row = $result->fetch_assoc()){
                                            $field1name = $row["muestra"];
                                            $field2name = $row["resultado1"];
                                            $field3name = $row["resultado2"];
                                            $field4name = $row["resultado3"];
                                            $tabla ="
                                            <tr cellspacing='0' border = '1'>
                                                <td > ".$field1name."</td>
                                                <td >".$field2name."</td>
                                                <td >".$field3name."</td>
                                                <td >".$field4name."</td>
                                            </tr>";
                                        $pdf_html.= $tabla;
                                        };
             $pdf_html .= "     </tbody></table>";


$options = array();          
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter');
$pdf_content = '';

$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<img src="images/minedata_lab.jpg" margin-top: 80px; margin-left: 80px;>';

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
