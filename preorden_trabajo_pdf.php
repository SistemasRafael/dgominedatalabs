<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
//set_include_path(get_include_path() . PATH_SEPARATOR . "dompdf");
//require_once 'autoload.inc.php';
/*
set_include_path(get_include_path() . PATH_SEPARATOR . "dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
include 'dompdf/eds_dompdf_prep.php';
include 'mod_html2fpdf/html2fpdf.php';*/

//use Dompdf\Dompdf;

//include 'dompdf/eds_dompdf_prep.php';
//include 'mod_html2fpdf/html2fpdf.php';

if (isset($_GET['trn_id'])) {

 $pdf_style = '';

$pdf_style .= '@page { margin-top: 240px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 80%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 140px; text-align: border:1 center;font-size: 100% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';


    $trn_id = $_GET['trn_id'];
    $unidad_id = $_GET['unidad_id'];
    $datos_orden = $mysqli->query("SELECT
                                                un.nombre AS unidad, ord.folio, ord.fecha, ba.banco, ba.voladura_id, us.nombre AS usuario
                                           FROM `arg_preordenes` ord
                                           LEFT JOIN arg_bancos_voladuras AS ba
                                           		ON ba.banco_id = ord.banco_id
                                                AND ba.voladura_id = ord.voladura_id                                           
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_usuarios us
                                            	ON us.u_id = ord.usuario_id
                                           WHERE ord.trn_id = " . $trn_id
    ) or die(mysqli_error($mysqli));
    $preorden = $datos_orden->fetch_assoc();
    
    $html_en = "<table border='0' width='98%' CELLPADDING=5 CELLSPACING=0>
                <thead>
                                 <tr>
                                    <th width='80%' align='left'>ELABORADA: " . $preorden['usuario'] ."</th> 
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>CLIENTE: " . $preorden['unidad'] . " </th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>DESCRIPCION: PRE-ORDEN DE TRABAJO </th>
                                 </tr>";
    $html_en .= "</thead></table>";
        

    $html_det = "<table border='0' width='98%' CELLPADDING=5 CELLSPACING=0>
       <thead>                                
            <tr class='table-info'>      
               <th BGCOLOR='RGB(190,229,235) colspan='1'>Mina</th>
               <th BGCOLOR='RGB(190,229,235) colspan='1'>Fecha/Hora</th>                                        
               <th BGCOLOR='RGB(190,229,235) colspan='1'>Banco+Voladura</th>
            </tr>
           <tr class='table-secondary' justify-content: center;>";
    $html_det .= "<th BGCOLOR='RGB(214,216,219) align='center'>" . $preorden['unidad'] . "</th>";
    $html_det .= "<th BGCOLOR='RGB(214,216,219) align='center'>" . $preorden['fecha'] . "</th>";
    $html_det .= "<th BGCOLOR='RGB(214,216,219) align='center'>" . $preorden['banco'] . $preorden['voladura_id'] . "</th>";

    $html_det .= "</tr>
      </thead>
      <tbody>";


    $html_det .= "</tbody></table>";
}

 $img_factor = 0.65; //0.95;
//Encabezado
    $ifactor = 220 / 1050;
    $iwidth = 1200;
    $pdf_header .= "<CXY X='15' Y='15'></CXY>";
    $pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.10.112/dgominedatalabs/images/encabezado_prep.jpg">';
    $pdf_header .= '<cfs FONTSIZE="15"></cfs>';
    $pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 150) . '"></CXY>';
    $pdf_header .= $html_en;
//$pdf_header .= $html_en;


/*
$par_options = array();
$par_options['top'] = '170px;';
$par_options['left'] = '0px';
$par_options['font-size'] = '14px;';
$pdf_header .= par_place($html_en, $par_options);

$par_options = array();
$par_options['top']  = '0px;';
$par_options['left'] = '15px';
$par_options['font-size'] = '14px;';
$pdf_html .= par_place($html_det, $par_options);*/

$options = array();
$options["isRemoteEnabled"] = true;
/*
$pdf = new Dompdf($options);
$pdf->setPaper('letter');
*/
$pdf = new Dompdf($options);
$pdf->setPaper('letter','mm','A4');
$pdf_style .= '';


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
$pdf_content .= $html_det;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';


$pdf->loadHtml($pdf_content);

$output_options = array();


$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);                   
file_put_contents($file_path . $file_name . '.pdf', $pdf->output());

