<?
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
$pdf_style = '';


$options = array();          
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter','mm','A4');
$pdf_style .= '';

$pdf_html = "<h1>Contenido del pdf generado</h1>";


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

?>