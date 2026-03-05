<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$trn_id    = $_GET['trn_id'];
$metodo_id = $_GET['metodo_id'];
$preparacion = $_GET['prep'];

$pdf_style = '';

$pdf_style .= '@page { margin-top: 10px; margin-bottom: 20px; margin-left: 10px; margin-right: 30px;}';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 85%; }';
$pdf_style .= '#header { position: fixed; left: 10px; top: -20px; right: 0px; height: 150px; text-align: center;font-size: 85% }';
$pdf_style .= '#body { margin-left:10px; text-align: center; font-size: 70% }';//'#body { position: absolute; height: 860px; text-align: center; font-size: 70% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';//'#footer { position: fixed; left: 15px; bottom: -10px; right: 0px; height: 100px; }';
//$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
//$pdf_style .= '.column { float: left; width: 53.33%; top: 23.33%;}';
//  $pdf_style .= '.row:after {content: ""; display: flex; clear: both; }';

/*
$pdf_style .= '.row:after {content: ""; display: grid; clear: both; }';
$pdf_style .= '.row {display: grid; text-align: center;}';

$pdf_style .= '@page { margin-top:50px; margin-bottom: 20px; margin-left: 5px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 75%; font-size: 100%;}';
$pdf_style .= '#header { position: fixed; left: 0px; top: 10px; right: 0px; height: 120px; text-align: left;font-size: 100% }';
//$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 15px; }';
//$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '.column { float: left; }';
$pdf_style .= '.row:after {content: ""; display: grid; clear: both; }';
$pdf_style .= '.row {display: grid; text-align: center;}';*/

$datos_orden = $mysqli->query("SELECT
                                    un.nombre AS unidad, od.folio_interno AS folio
                                    ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                    ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                    ,(CASE ord.tipo WHEN 0 THEN 0 WHEN 4 THEN 4 WHEN 3 THEN 3 
                                                    WHEN 7 THEN 7 WHEN 8 THEN 8 ELSE 9 
                                      END) AS tipo                                
                                    ,ord.tipo AS tipo_orden
                                    ,(CASE hora WHEN '07:00' THEN '1T' WHEN '19:00' THEN '2T' ELSE '' END) AS turno
                              FROM 
                                    `arg_ordenes` ord
                                    LEFT JOIN arg_ordenes_detalle od
                                        ON ord.trn_id = od.trn_id_rel
                                    LEFT JOIN arg_empr_unidades AS un
                                        ON un.unidad_id = ord.unidad_id                                          
                                    WHERE od.trn_id =" . $trn_id
) or die(mysqli_error($mysqli));

$orden_encabezado = $datos_orden->fetch_assoc();
$mina        = $orden_encabezado['unidad'];
$folio       = $orden_encabezado['folio'];
$fecha       = $orden_encabezado['fecha'];
$reensaye    = $orden_encabezado['reensaye'];
$tipo_orden  = $orden_encabezado['tipo'];
$turno_orden = $orden_encabezado['turno'];

$datos_met = $mysqli->query("SELECT nombre, nombre_largo FROM arg_metodos WHERE metodo_id =" . $metodo_id) or die(mysqli_error($mysqli));
$datos_meto = $datos_met->fetch_assoc();
$metodo  = $datos_meto['nombre'];
$metodo_desc = $datos_meto['nombre_largo'];

$datos_muestras_tipo = $mysqli->query(" SELECT
                                            (CASE WHEN ms.area_id = 1 THEN 'Planta' ELSE 'Metalurgia' END) AS area
                                        FROM
                                            arg_ordenes_detalle od
                                            LEFT JOIN arg_ordenes_soluciones AS os
                                                    	ON od.trn_id = os.trn_id_batch
                                            LEFT JOIN arg_ordenes_muestrasSoluciones AS ms
                                                    	ON ms.trn_id = os.trn_id_rel
                                        WHERE 
                                            od.trn_id = ".$trn_id."
                                            LIMIT 1")or die(mysqli_error($mysqli));
$datos_muestras_t = $datos_muestras_tipo->fetch_assoc();
$datos_muestras_area = $datos_muestras_t['area'];

//if ($tipo_orden == 4) {
    $detalle_etiquetas = $mysqli->query("SELECT
                                             om.trn_id_batch                                                  
                                            ,(CASE WHEN om.tipo_id = 0 THEN om.folio_interno 
                                                   WHEN om.tipo_id = 99 THEN om.folio_interno
                                              	   WHEN om.tipo_id = 200 THEN om.folio_interno
                                                   ELSE om.control END) as folio_interno
                                            ,om.posicion                                                    
                                         FROM
                                            ordenes_metalurgia om
                                         WHERE 
                                            om.trn_id_batch = ".$trn_id."
                                            AND om.metodo_id = " . $metodo_id ."
                                         ORDER BY om.posicion") 
                                        or die(mysqli_error($mysqli));

$total_muestras = (mysqli_num_rows($detalle_etiquetas));

$html_en = "";
$html_en .= "<table border='0' width='100%' CELLPADDING=3 CELLSPACING=0>
                             <thead>
                                 <tr>
                                    <th width='40%' align='left'><strong>ORDEN: " . $folio . "</strong></th>
                                 </tr>
                                 <tr>
                                    <th width='10%' align='left'>METODO: " . $metodo . " </th>
                                     <th width='80%' align='left'>ELABORADA: " . $orden_encabezado['fecha'] . " " . $orden_encabezado['hora'] . "</th> 
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>CLIENTE: " . $orden_encabezado['unidad'] . " </th>                              
                                    <th width='80%' align='left'>DESCRIPCION: " . $metodo_desc . " </th>
                                 </tr>";
$html_en .= "</thead></table><br/>";
$html_en_met = "";

if ($metodo_id == 2) {
    $html_en_met .= "<table border = '1' width=100% HEIGHT=250 CELLPADDING=3 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th align='center'> PESADO:__________</th>
                                    <th align='center'> FUNDIDO:__________</th>
                                    <th align='center'> COPELADO:__________</th>
                                    <th align='center'> INCUARTE:__________</th>
                                    <th align='center'> ATAQUE QUÍM:__________</th>
                                    <th align='center'> PESO Au:__________</th>
                                </tr>
                                                         
                            </thead>";
    $html_en_met .= "</thead></table><br/>";
}
if ($metodo_id == 33) {
    $html_en_met .= "<table border = '1' width=100% HEIGHT=250 CELLPADDING=3 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th align='center'> PESADO:__________</th>
                                    <th align='center'> CIANURADO:__________</th>
                                    <th align='center'> AGITADO:__________</th>
                                    <th align='center'> CENTRIFUGADO:__________</th>
                                    <th align='center'> AA:__________</th>
                                </tr>
                                <tr>
                                    <th align='center' colspan=5> ARCHIVO:____________________</th>                                  
                            </thead>";
    $html_en_met .= "</thead></table><br/>";
}
if ($metodo_id == 6 || $metodo_id == 7) {
    $html_en_met .= "<table border = '1' width='98%' CELLPADDING=5 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th width='80% align='center'> PESADO:__________</th>
                                    <th width='80% align='center'> DIGESTION:__________</th>
                                    <th width='80% align='center'> AFORO:__________</th>
                                    <th width='80% align='center'> AA:__________</th>
                                </tr>
                                <tr>
                                    <th width='98% align='center' colspan=4> ARCHIVO:____________________</th>                                  
                            </thead>";
    $html_en_met .= "</thead></table><br/>";
}

if ($metodo_id == 11 || $metodo_id == 12 || $metodo_id == 13) {
    $html_en_met .= "<table border = '1' width='98%' CELLPADDING=5 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th width='80% align='center'> PESADO:__________</th>
                                    <th width='80% align='center'> CIANURACION:__________</th>
                                    <th width='80% align='center'> CENTIFRUGADO:__________</th>
                                    <th width='80% align='center'> AA:__________</th>
                                </tr>
                                <tr>
                                    <th width='98% align='center' colspan=4> ARCHIVO:____________________</th>                                  
                            </thead>";
    $html_en_met .= "</thead></table><br/>";
}

$f = 1;
while ($fila_det = $detalle_etiquetas->fetch_assoc()) {
    $etiqueta_muestra[$f]  = $fila_det['folio_interno'];
    $etiqueta_posicion[$f] = $fila_det['posicion'];
    $f++;
}
$i = 1;
$page = 1;
$total_paginas = 1;//ceil($total_muestras / 78);
$total_filas   = 50;// ceil($total_muestras / 78);
$total = $total_muestras;
while ($page <= $total_paginas) {
    $html_det = "<div class='row' width='98%' style='margin-top: 35%; margin-left: 10%;>'";
    $columna = 1;
    while ($columna < 4 && $i <= $total_muestras) {
        $f = 0;
        $html_det .= "<div class= 'column' style='right:100%;'>
                        <table border='1' cellspacing='0'>
                        <thead>                                
                            <tr>      
                                <th align='center' colspan='1'>No.</th>
                                <th align='center' colspan='1'>ID MUESTRA</th>  
                            </tr>
                        </thead>
                        <tbody> ";
        while ($total > 0) {
            if ($f == 32) {
                break;
            }
            $html_det .= "<tr>
                            <th colspan='1'>" . $i . "</th> 
                            <th colspan='1'>" . $etiqueta_muestra[$i] . "</th> 
                          </tr>";
            $i = $i + 1;
            $f = $f + 1;
            $total = $total - 1;
        }
        $html_det .= "</tbody></table></div>";
        $columna = $columna + 1;
        $pdf_html .= $html_det;
        $top  = '';
        $left = '';
        $html_det = '';
    }
    if ($i != $total_muestras) {
        $pdf_html .= '</div><div style="page-break-inside:always;"></div>';
        $page = $page + 1;
    }
}

$options = array();
$options["isRemoteEnabled"] = true;
/*
$pdf = new Dompdf($options);
$customPaper = array(0, 0, 180, 320);
$pdf->setPaper($customPaper, 'portrait');
$pdf_content = '';
*/


$pdf = new Dompdf($options);
$pdf->setPaper('letter', 'mm', 'A4');
$pdf_style .= '';



$pdf_header = '';
$img_factor = 0.60; //0.95;

$par_x = 0;
$par_y = 15;

$ifactor = 220 / 1050;
$iwidth = 1200;
$pdf_header .= "<br/><br/><br/><br/><CXY X='0' Y='80'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.10.112/dgominedatalabs/images/encabezado_prep.jpg" margin-top="20">';
$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY>';

$pdf_header .= $html_en;
$pdf_header .= $html_en_met;

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

$pdf->loadHtml($pdf_content);
$output_options = array();

$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();
$pdf->stream($file_name . ".pdf", $output_options);
file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
