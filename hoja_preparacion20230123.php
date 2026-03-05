<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';

use Dompdf\Dompdf;


$pdf_style = '';

$pdf_style .= '@page { margin-top:20px; margin-bottom: 90px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 75%; font-size: 100%;}';
$pdf_style .= '#header { position: fixed; left: 0px; top: 10px; right: 0px; height: 120px; text-align: left;font-size: 100% }';
//$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 15px; }';
//$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '.column { float: left; width: 33.33%; top: 33.33%;}';
$pdf_style .= '.row:after {content: ""; display: flex; clear: both; }';

$trn_id    = $_GET['trn_id'];
$metodo_id = $_GET['metodo_id'];
$preparacion = $_GET['prep'];

$datos_orden = $mysqli->query(
    "SELECT
                                     un.nombre AS unidad, ord.folio AS folio
                                    ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                    FROM `arg_ordenes` ord
                                    LEFT JOIN arg_empr_unidades AS un
                                        ON un.unidad_id = ord.unidad_id                                          
                                    WHERE ord.trn_id = " . $trn_id
) or die(mysqli_error($mysqli));
$orden_encabezado = $datos_orden->fetch_assoc();
$mina  = $orden_encabezado['unidad'];
$folio = $orden_encabezado['folio'];
$fecha = $orden_encabezado['fecha'];
$detalle_etiquetas = $mysqli->query(
    "SELECT 
                                                 o.folio AS orden 
                                                ,(CASE WHEN ot.tipo_id = 0 THEN om.folio ELSE otr.control END) AS muestra_geologia   
                                                ,ot.folio_interno
                                            FROM 
                                             	arg_ordenes_detalle  od
                                            LEFT JOIN arg_ordenes o
                                            	ON od.trn_id_rel = o.trn_id
                                            LEFT JOIN arg_ordenes_transacciones ot
                                            	ON ot.trn_id_batch = od.trn_id    
                                                AND ot.tipo_id IN (0,3,5)
                                            LEFT JOIN arg_ordenes_muestras om
                                            	ON om.trn_id = ot.trn_id_rel
                                                AND om.trn_id_rel = od.trn_id
                                            LEFT JOIN ordenes_transacciones otr
                                            	ON otr.trn_id_batch = om.trn_id_rel
                                                AND otr.trn_id_rel  = om.trn_id  
                                            WHERE
                                                 o.trn_id = $trn_id
                                            ORDER BY
                                            o.folio, ot.folio_interno"
) or die(mysqli_error($mysqli));
$total_muestras = (mysqli_num_rows($detalle_etiquetas));
//echo $total_muestras;

$html_en = "";
$html_en .= "
            <table border='0' width='98%' CELLPADDING=5 CELLSPACING=0>
                             <thead>
                                <tr>
                                    <th width='80%' align='left'>FOLIO DE ORDEN DE TRABAJO: " . $orden_encabezado['folio'] . "</th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>METODO: DRY_250, CRU_60 y PUL_85</th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>ELABORADA: " . $orden_encabezado['fecha'] . " " . $orden_encabezado['hora'] . "</th> 
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>CLIENTE: " . $orden_encabezado['unidad'] . " </th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>DESCRIPCION: Preparacion de muestras secado, quebrado y pulverizado </th>
                                 </tr>";
$html_en .= "</thead></table><br/>";

$html_en .= "<table border = '1' align='center' width='98%' CELLPADDING=5 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th width='80% align='center' style='border:none;'> PESADO:__________</th>
                                    <th width='80% align='center' style='border:none;'> CIANURACION:__________</th>
                                    <th width='80% align='center' style='border:none;'> CENTIFRUGADO:__________</th>
                                    <th width='80% align='center' style='border:none;'> AA:__________</th>
                                </tr>
                </thead></table>
                <table border = '1' align='center' width='98%' CELLPADDING=5 CELLSPACING=0>
                                <tr>
                                    <th width='24% align='center' colspan=4> ARCHIVO:____________________</th> 
                                    <th width='24% align='center' colspan=4> SERIE:____________________</th>  
                                ";

$html_en .= "</thead></table><br/>";
$f = 1;
while ($fila_det = $detalle_etiquetas->fetch_assoc()) {
    $etiqueta_muestra[$f]  = $fila_det['muestra_geologia'];
    $etiqueta_folio[$f]  = $fila_det['folio_interno'];
    $f++;
}

$i = 1;
$page = 1;
$total_paginas = ceil($total_muestras / 28);
$total_filas   = ceil($total_muestras / 28);
$total = $total_muestras;
while ($page <= $total_paginas) {
    $html_det .= "<div class='row' style='margin-left: 2%; margin-top: 52%;'>";
    $columna = 1;
    while ($columna < 3 && $i <= $total_muestras) {
        $f = 0;
        $html_det .= "<div class='column' style='right:100%; padding-left:10%;'>
                                <table border='1' cellspacing='0' style='font-size: 66%;'>
                                            <thead>                                
                                                <tr>      
                                                    <th align='center' colspan='3'>No.</th>
                                                    <th align='center' colspan='3'>ID MUESTRA</th>  
                                                    <th align='center' colspan='3'>FOLIO INTERNO</th> 
                                                </tr>
                                            </thead>
                                        <tbody>";
        while ($total > 0) {
            if ($f == 28) {
                break;
            }
            $html_det .= "<tr>
                                <th colspan='3'>" . $i . "</th> 
                                <th colspan='3'>" . $etiqueta_muestra[$i] . "</th> 
                                <th colspan='3'>" . $etiqueta_folio[$i] . "</th> 
                            </tr>";
            $i = $i + 1;
            $f = $f + 1;
            $total = $total - 1;
        }
        $columna = $columna + 1;
        $html_det .= "</tbody></table></div>";
    }
    if ($i > $total_muestras){
        break;
    }
    if ($i != $total_muestras) {
        $html_det .= '</div><div style="page-break-inside:always;"></div>';
        $page = $page + 1;
    }
}


$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter', 'mm', 'A4');

$pdf_style .= '';

ob_end_clean();

$pdf_header = '';
$img_factor = 0.65; //0.95;

$par_x = 3;
$par_y = 68;

//Encabezado
$ifactor = 220 / 1050;
$iwidth = 1200;
$pdf_header .= "<CXY X='15' Y='15'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.10.112/dgominedatalabs/images/encabezado_prep.jpg">';
$pdf_header .= '<cfs FONTSIZE="15"></cfs>';
$pdf_header .= '<CXY X="' . $par_x + 50 . '" Y="' . ($par_y + 150) . '"></CXY>';
$pdf_header .= $html_en;

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
$pdf_content .= $html_det;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';

$pdf->load_html($pdf_content);

$pdf->render();

$pdf->stream($file_name . ".pdf", array("Attachment" => false));

file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
