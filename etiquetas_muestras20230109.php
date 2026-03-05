<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$pdf_style .= '@page { margin-top: 20px; margin-bottom: 390px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 75%; font-size: 100%;}';
$pdf_style .= '#header { position: fixed; left: 10px; top: 10px; right: 0px; height: 140px; text-align: left;font-size: 100% }';
//$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 15px; }';
//$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '.column { float: left; }';
$pdf_style .= '.row:after {content: ""; display: grid; clear: both; }';
$pdf_style .= '.row {display: grid; text-align: center;}';

$trn_id    = $_GET['trn_id'];
$metodo_id = $_GET['metodo_id'];
$datos_orden = $mysqli->query(
    "SELECT
                                                 un.nombre AS unidad, od.folio_interno AS folio
                                                ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                           FROM `arg_ordenes` ord
                                           LEFT JOIN arg_ordenes_detalle od
                                           	   ON ord.trn_id = od.trn_id_rel
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id                                          
                                           WHERE od.trn_id =" . $trn_id
) or die(mysqli_error($mysqli));
$orden_encabezado = $datos_orden->fetch_assoc();
$mina  = $orden_encabezado['unidad'];
$folio = $orden_encabezado['folio'];
$fecha = $orden_encabezado['fecha'];

$detalle_etiquetas = $mysqli->query(
    "SELECT
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
                                                        AND od.trn_id_batch = " . $trn_id . "
                                                        AND od.metodo_id = " . $metodo_id . "
                                                  ORDER BY od.posicion "
) or die(mysqli_error($mysqli));
$total_muestras = (mysqli_num_rows($detalle_etiquetas));

$f = 1;
while ($fila_det = $detalle_etiquetas->fetch_assoc()) {
    $etiqueta_muestra[$f]  = $fila_det['muestra_geologia'];
    $etiqueta_posicion[$f] = $fila_det['posicion'];
    //echo $etiqueta_muestra[$f];
    $f++;
}

$i = 1;
$page = 1;
$total_paginas = ceil($total_muestras / 30);
$total_filas = 10;
$total = $total_muestras;
while ($page <= $total_paginas) {
    $fila = 0;
    while ($fila < $total_filas) {
        $fila++;
        $html_det .= "<div class='row' style='margin-left: 12%; height: 10%'>";
        $columna = 1;
        while ($columna < 4 && $i <= $total_muestras) {
            if ($columna == 1) {
                $html_det .= "<div class='column' style='right:100%; padding-left:9px;'>
                    <table border='1' cellspacing='0'  style='font-size: 58%;'>
                          <tbody> 
                                <tr>  
                                    <th align='center' colspan='3'>Unidad " . $mina . "</th> 
                                </tr>
                                <tr>
                                    <th align='center' colspan='3'>Muestra: " . $etiqueta_muestra[$i] . " </th>  
                                 </tr>     
                                <tr>                             
                                    <th colspan='1'>Consecutivo-Folio:</th> 
                                    <th colspan='1'>" . $etiqueta_posicion[$i] . "</th> 
                                    <th colspan='1'>" . $folio . "</th> 
                                </tr>
                                <tr>
                                    <th colspan='1'>Fecha Rec. :</th> 
                                    <th colspan='1'>" . $fecha . "</th> 
                                    <th colspan='1'>Lab. Quimico</th> 
                                </tr>
                          </tbody></table></div>";
                //$html_det .= "<div class='col-sm-2'></div>";
                $i = $i + 1;
                $columna = $columna + 1;
                $total = $total - 1;
            }
            if ($total < 1) {
                break;
            }
            if ($columna == 2) {
                $html_det .= "<div class='column' style='right:100%; padding-left:9px;'>
                          <table border='1' cellspacing='0'  style='font-size: 58%;'>
                          
                          <tbody>   
                                <tr>
                                    <th align='center' colspan='3'>Unidad " . $mina . "</th> 
                                </tr>
                                <tr>
                                    <th align='center' colspan='3'>Muestra: " . $etiqueta_muestra[$i] . "</th>
                                <tr>
                                    <tr>      
                                    <th colspan='1'>Consecutivo-Folio:</th> 
                                    <th colspan='1'>" . $etiqueta_posicion[$i] . "</th> 
                                    <th colspan='1'>" . $folio . "</th> 
                                </tr>
                                <tr>
                                    <th colspan='1'>Fecha Rec. :</th> 
                                    <th colspan='1'>" . $fecha . "</th> 
                                    <th colspan='1'>Lab. Quimico</th> 
                                </tr>
                          </tbody></table></div>";
                $i = $i + 1;
                $columna++;
                $total = $total - 1;
            }
            if ($total < 1) {
                break;
            }
            if ($columna == 3 && $i <= $total_muestras) {
                $html_det .= "<div class='column' style='right:100%; padding-left:9px;'>
                          <table border='1' cellspacing='0'  style='font-size: 58%;'>                          
                          <tbody>
                                <tr>   
                                    <th align='center' colspan='3'>Unidad " . $mina . "</th> 
                                </tr>
                                <tr>
                                     <th align='center' colspan='3'>Muestra: " . $etiqueta_muestra[$i] . "</th>
                                </tr> 
                                <tr> 
                                    <th colspan='1'>Consecutivo-Folio:</th> 
                                    <th colspan='1'>" . $etiqueta_posicion[$i] . "</th> 
                                    <th colspan='1'>" . $folio . "</th> 
                                </tr>
                                <tr>
                                    <th colspan='1'>Fecha Rec. :</th> 
                                    <th colspan='1'>" . $fecha . "</th> 
                                    <th colspan='1'>Lab. Quimico</th> 
                                </tr>
                          </tbody></table></div>";
                //$html_det .= "</br></br></br>";
                //$html_det .= "<div class='row'></div></br>";
                $i = $i + 1;
                $columna++;
                $total = $total - 1;
            }
        }
        $html_det .= "</div>";
    }
    if ($i != $total_muestras) {
        $page = $page + 1;
    }
}
$pdf_html .= $html_det;


$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter', 'mm', 'A4');

$pdf_style .= '';

ob_end_clean();


$pdf_content = '';
$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<style>';
$pdf_content .= $pdf_style;
$pdf_content .= '</style>';
$pdf_content .= '</head>';
/*$pdf_content .= '<div id="header">';
$pdf_content .= $pdf_header;
$pdf_content .= '</div>';*/
$pdf_content .= '<body>';
//$pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
//$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';


$pdf->load_html($pdf_content);

$output_options = array();


$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;


$pdf->render();


$pdf->stream($file_name . ".pdf", array("Attachment" => false));

file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
