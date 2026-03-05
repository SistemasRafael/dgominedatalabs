<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;


//$pdf = new HTML2FPDF();
//$pdf = new HTML2FPDF('L','mm','A4');
$pdf_style = '';

$pdf_style .= '@page { margin-top: 20px; margin-bottom: 390px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 75%; }';
$pdf_style .= '#header { position: fixed; left: 10px; top: 10px; right: 0px; height: 140px; text-align: left;font-size: 80% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
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
    $f++;
}

$i = 1;
$index = 1;
$page = 1;
$total_paginas = ceil($total_muestras / 78);
$total_filas   = ceil($total_muestras / 78);
$total = $total_muestras;
while ($page <= $total_paginas) {
    $html_det = "<div class='row' style='margin-left: 10%;'>";
    $columna = 1;
    while ($columna < 4 && $i <= $total_muestras) {
        $f = 0;
        $html_det .= "<div class='column' style='right:100%;'>
                        <table border='1' cellspacing='0'>
                                            <thead>   
                                            aja                             
                                            </thead>
                                            <tbody> ";
        while ($total > 0) {
            if ($f == 10) {
                break;
            }
            if ($i < $index) {
                $i = $i + 1;
            } else {
                $html_det .= "<tr >      
                            <th align='center' colspan='3'>Unidad " . $mina . "</th> 
                            </tr>
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
                            </tr>
                            <tr>
                                <th colspan='3' bgcolor='gray'>$i</th>
                            </tr>";
                $i = $i + 1;
                $index = $index + 1;
                $f = $f + 1;
                $total = $total - 1;
            }
        }
        $html_det .= "</tbody></table></div>";
        $columna = $columna + 1;
        $pdf_html .= $html_det;
    }
    if ($i != $total_muestras) {
        $pdf_html .= '</div><div style="page-break-inside:always;"></div>';
        $page = $page + 1;
    }
}
$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter', 'mm', 'A4');
$pdf_style .= '';

$par_x = 3;
$par_y = 68;
$pdf_header = '';
$pdf_header .= '<img src="http://192.168.20.22/MineData-Labs/images/Minedata_lab_hs.jpg">';


$pdf_content = '';
$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<style>';
$pdf_content .= $pdf_style;
$pdf_content .= '</style>';
//$pdf_content .= '<div id="header">';
//$pdf_content .= $pdf_header;
//$pdf_content .= '</div>';
$pdf_content .= '<body>';
$pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</head>';
$pdf_content .= '</html>';

$pdf->loadHtml($pdf_content);

$output_options = array();
/*$html_det .= "<tr >      
                            <th align='center' colspan='3'>Unidad " . $mina . "</th> 
                            </tr>
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
                            </tr>
                            <tr>
                                <th colspan='3' bgcolor='gray'>$i</th>
                            </tr>";*/
$html_det .= "<th align='center' colspan='3'>Muestra: " . $etiqueta_muestra[$i] . "</th>
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
                            </tr>";

$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);
file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
