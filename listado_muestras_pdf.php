<? include "connections/config.php";
//set_include_path("/var/www/html/MineData-Labs/dompdf");
//set_include_path("C:\\xampp\\htdocs\\minedata_labs\\dompdf");
//require_once 'autoload.inc.php';
////use Dompdf\Dompdf;
//include '/var/www/html/MineData-Labs/dompdf/eds_dompdf_prep.php';
//include 'C:\\xampp\\htdocs\\minedata_labs\\dompdf\\eds_dompdf_prep.php';
//require('/var/www/html/MineData-Labs/mod_html2fpdf/html2fpdf.php');
//require('C:\\xampp\\htdocs\\minedata_labs\\mod_html2fpdf\\html2fpdf.php');

require_once 'vendors/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if (isset($_GET['trn_id'])) {

$pdf_style = '';

$pdf_style .= '@page { margin-top: 10px; margin-bottom: 20px; margin-left: 10px; margin-right: 0px;}';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 85%; }';
$pdf_style .= '#header { position: fixed; left: 5px; top: 15px; right: 0px;  text-align: center;font-size: 100% }';
$pdf_style .= '#body { margin-left:25px; text-align: center; font-size: 80% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
//$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
//$pdf_style .= '.column { float: left; width: 33.33%; top: 90.33%;}';
//$pdf_style .= '.row:after {content: ""; display: flex; clear: both; }';

    $trn_id    = $_GET['trn_id'];
    $metodo_id = $_GET['metodo_id'];
    $preparacion = $_GET['prep'];
    //echo $trn_id;
    $datos_orden = $mysqli->query("SELECT
                                    un.nombre AS unidad, od.folio_interno AS folio
                                    ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                    FROM `arg_ordenes` ord
                                    LEFT JOIN arg_ordenes_detalle od
                                        ON ord.trn_id = od.trn_id_rel
                                    LEFT JOIN arg_empr_unidades AS un
                                        ON un.unidad_id = ord.unidad_id                                          
                                    WHERE od.trn_id = " . $trn_id
    ) or die(mysqli_error($mysqli));
    $orden_encabezado = $datos_orden->fetch_assoc();
    $mina  = $orden_encabezado['unidad'];
    $folio = $orden_encabezado['folio'];
    $fecha = $orden_encabezado['fecha'];
    /*$detalle_etiquetas = $mysqli->query("SELECT
                                                        od.trn_id_batch,
                                                        od.posicion,
                                                        (CASE WHEN od.tipo_id = 0 THEN od.muestra_geologia WHEN od.tipo_id = 3 THEN control END) AS muestra_geologia
                                                 FROM
                                                        ordenes_transacciones od
                                                 WHERE
                                                        od.tipo_id IN(0, 3)
                                                        AND od.trn_id_batch = ".$trn_id."
                                                        AND od.metodo_id = ".$metodo_id."
                                                  ORDER BY od.posicion "
                                                  ) or die(mysqli_error($mysqli));*/
    $detalle_etiquetas = $mysqli->query("SELECT
                                            od.trn_id_batch,
                                            od.posicion,
                                            (CASE WHEN od.tipo_id = 0 THEN od.muestra_geologia 
                                                  WHEN od.tipo_id = 3 THEN control 
                                            ELSE CONCAT(dup.muestra_geologia, ' - D') END) AS muestra_geologia,
                                            od.folio_interno
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
                                            AND od.trn_id_batch = ".$trn_id."
                                            AND od.metodo_id = ".$metodo_id."
                                        ORDER BY
                                            od.posicion"    
    ) or die(mysqli_error($mysqli));
    $total_muestras = (mysqli_num_rows($detalle_etiquetas));
    //echo $total_muestras;
    
    $html_en = "";
    $html_en .= "
            <table border='0' width='98%' CELLPADDING=5 CELLSPACING=0>
                             <thead>
                                <tr>
                                    <th width='80%' align='left'>BATCH DE TRABAJO: ".$orden_encabezado['folio']."</th>
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
        $etiqueta_posicion[$f] = $fila_det['posicion'];
        $f++;
    }

    $i = 1;
    $page = 1;
    if ($total_muestras < 96){
        $total_paginas = 1;
    }
    else{     
        $total_paginas = ceil($total_muestras / 96);   
    }
    $total_filas   = ceil($total_muestras / 96);
    $total = $total_muestras;
    while ($page <= $total_paginas) {
        while ($i < $total_muestras) {
            $columna = 1;
            while ($columna < 4 && $total > 0) {
                $f = 0;
                
                $html_det .= "<div class='row' style='margin-left: 2%; margin-top: 50%; padding-top:0px;  height: 90%'>
                                <table border='1' cellspacing='0'>
                                            <thead>                                
                                                <tr>      
                                                    <th align='center' colspan='3'>No.</th>
                                                    <th align='center' colspan='3'>ID MUESTRA</th>  
                                                    <th align='center' colspan='3'>FOLIO INTERNO</th> 
                                                </tr>
                                            </thead>
                                            <tbody>";
                while ($total > 0) {
                    if ($f == 32){
                        break;
                    }
                    
                    $html_det .= "<tr>
                                        <th colspan='3'>" . $i . "</th> 
                                        <th colspan='3'>".$etiqueta_muestra[$i]."</th> 
                                        <th colspan='3'>".$etiqueta_folio[$i]."</th> 
                                </tr>";
                               
                    $i++;
                    $top = '804px';
                    $f = $f + 1;
                    $total = $total - 1;
            }
                $html_det .= "</tbody></table></div>";
                if ($columna == 1) {
                    $left = '94px';
                }
                if ($columna == 2) {
                    $left = '350px';
                }
                if ($columna == 3) {
                    $left = '606px';
                }
              
                $columna = $columna + 1;
               // $options = array();
               // $options['top']  = $top; //'5px;';
               // $options['left'] = $left; //'10px';
               // $par_options['font-size'] = '4px;';
                //$pdf_html .= par_place($html_det, $options);
                $top  = '';
                $left = '';
                //$html_det = '';
            }
            //$pdf_html .= '<div style="page-break-after:always;"></div>';
            $page = $page + 1;
    }
        
    }
   //$options = array();
    //$options["isRemoteEnabled"] = true;

$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter', 'mm', 'A4');
$pdf_style .= '';

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
    $pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 150) . '"></CXY>';
    $pdf_header .= $html_en;
  
    $pdf_content .= '<html>';
    $pdf_content .= '<head>';
    $pdf_content .= '<style>';
    $pdf_content .= $pdf_style;
    $pdf_content .= '</style>';
    $pdf_content .= '<body>';
   
    $pdf_content .= '<div id="header">';
    $pdf_content .= $pdf_header;
    $pdf_content .= '</div>';
    
    $pdf_content .= '<div id="content">';
    $pdf_content .= $html_det;
    $pdf_content .= '</div>';
    
    $pdf_content .= '</body>';
    $pdf_content .= '</head>';
    $pdf_content .= '</html>';
    /*
    $pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';*/


    $pdf->loadHtml($pdf_content);

$output_options = array();


$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);
//file_put_contents($file_path . $file_name . '.pdf', $pdf->output());
}
