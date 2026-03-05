<? include "connections/config.php";
set_include_path("/var/www/html/MineData-Labs/dompdf");
//PRUEBAS set_include_path("C:\\xampp\\htdocs\\minedata_labs\\dompdf");
require_once 'autoload.inc.php';

use Dompdf\Dompdf;
include '/var/www/html/MineData-Labs/dompdf/eds_dompdf_prep.php';
//PRUEBAS   include 'C:\\xampp\\htdocs\\minedata_labs\\dompdf\\eds_dompdf_prep.php';
require('/var/www/html/MineData-Labs/mod_html2fpdf/html2fpdf.php');
//PRUEBAS require('C:\\xampp\\htdocs\\minedata_labs\\mod_html2fpdf\\html2fpdf.php');
    $trn_id    = $_GET['trn_id'];
    $metodo_id = $_GET['metodo_id'];
    $preparacion = $_GET['prep'];
    //echo $metodo_id;
if (isset($_GET['trn_id'])) {

    //$pdf = new HTML2FPDF();
    $pdf = new HTML2FPDF('L', 'mm', 'A4');
    $pdf_style = '';

    $pdf_style .= '@page { margin-top: 20px; margin-bottom: 390px; margin-left: 0px; margin-right: 0px; }';
    $pdf_style .= 'html, body, table { font-family: helvetica; font-size: 65%; }';
    $pdf_style .= '#header { position: fixed; left: 15px; top: -45px; right: 0px; height: 140px; text-align: center;font-size: 80% }';
    $pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
    $pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
    $pdf_style .= '';

    
    
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
                                AND od.trn_id_batch = ".$trn_id."
                                AND od.metodo_id = ".$metodo_id." ORDER BY od.posicion") or die(mysqli_error($mysqli));
    $total_muestras = (mysqli_num_rows($detalle_etiquetas));
    //$total_muestras = 105;

    $html_en = "<table border='0' width='100%' CELLPADDING=3 CELLSPACING=0>
                             <thead>
                                 <tr>
                                    <th width='40%' align='left'><strong>ORDEN: ".$folio."</strong></th>
                                 </tr>
                                 <tr>
                                    <th width='10%' align='left'>METODO: DRY_250, CRU_60 y PUL_85</th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>ELABORADA: " . $orden_encabezado['fecha'] . " " . $orden_encabezado['hora'] . "</th> 
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>CLIENTE:" . $orden_encabezado['unidad'] . " </th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>DESCRIPCION: Preparacion de muestras secado, quebrado y pulverizado </th>
                                 </tr>";
    $html_en .= "</thead></table><br/>";
    if ($preparacion = 1){
        $html_en_met .= "<table border = '1' width='98%' CELLPADDING=5 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th width='80% align='center'> RECEPCION:__________</th>
                                    <th width='80% align='center'> SECADO:__________</th>
                                    <th width='80% align='center'> QUEBRADO:__________</th>
                                    <th width='80% align='center'> PULVERIZADO:__________</th>
                                </tr>
                                <tr>
                                    <th width='98% align='center' colspan=4> ARCHIVO:____________________</th>                                  
                            </thead>"; 
                           $html_en_met .= "</thead></table><br/>";      
    }/*
    else{
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
    }*/
    $f = 1;
    while ($fila_det = $detalle_etiquetas->fetch_assoc()) {
        $etiqueta_muestra[$f]  = $fila_det['muestra_geologia'];
        $etiqueta_posicion[$f] = $fila_det['posicion'];
        $f++;
    }

    $i = 1;
    $page = 1;
    $total_paginas = ceil($total_muestras / 96);
    $total_filas   = ceil($total_muestras / 96);
    //$fila = array_fill(1, $total_muestras, '');
    $total = $total_muestras;
    while ($page <= $total_paginas) {
        //$total_filas = 32;
        //while ($i < $total_filas) {
            //$total = 32;
            $columna = 1;
            while ($columna < 4 && $i <= $total_muestras) {
                $f = 0;
                $html_det .= "<table border='1' cellspacing='0'>
                                            <thead>                                
                                                <tr>      
                                                    <th align='center' colspan='1'>No.</th>
                                                    <th align='center' colspan='1'>ID MUESTRA</th>  
                                                </tr>
                                            </thead>
                                            <tbody> ";
                while ($total > 0) {
                    if ($f == 32){
                        break;
                    }
                    //$html_det.="<th align='center' colspan='3'>Muestra: ".$etiqueta_muestra[$i]."</th>";
                    //$html_det.="<tr>";
                    //$html_det.="<tr>    
                    //                    <th colspan='1'>Consecutivo-Folio:</th> 
                    //                    <th colspan='1'>".$etiqueta_posicion[$i]."</th> 
                    //                    <th colspan='1'>".$folio."</th> 
                    //            </tr>";
                    $html_det .= "<tr>
                                        <th colspan='1'>" . $i . "</th> 
                                        <th colspan='1'>" . $etiqueta_muestra[$i] . "</th> 
                                </tr>";
                    //<tr>
                    //         <th colspan='1'>Fecha Rec. :</th> 
                    //        <th colspan='1'>".$fecha."</th> 
                    //        <th colspan='1'>Lab. Quimico</th> 
                    //</tr>";
                    //$html_det.="</tr>";
                    //$fila[$i] = $etiqueta_muestra[$i];
                    $i++;
                    //$pixeles = array_fill(0, 32, 304);
                    //for ($j = 1; $j < 32; $j++) {
                    //    $mult = 21 * $j;
                    //    $pixeles[$j] = $pixeles[$j] + $mult;
                    //}
                    //$top = strval($pixeles[$f]) . 'px';
                    $top = '220px';
                    $f = $f + 1;
                    $total = $total - 1;
                }
                $html_det .= "</tbody></table>";
                if ($columna == 1) {
                    $left = '32px';
                    /*switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }*/
                }
                /* case 1: $top = '5px';   break; 
                                        case 2: $top = '70px'; break;
                                        case 3: $top = '135px'; break;
                                        case 4: $top = '200px'; break;
                                        case 5: $top = '265px'; break;
                                        case 6: $top = '330px'; break;
                                        case 7: $top = '395px'; break;
                                        case 8: $top = '460px'; break;
                                        case 9: $top = '525px'; break;
                                        case 10: $top = '590px'; break;*/

                if ($columna == 2) {
                    $left = '300px';
                    /*switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }*/
                }

                if ($columna == 3) {
                    $left = '600px';
                    /*switch ($fila){
                                        case 1: $top = '40px';  break; 
                                        case 2: $top = '140px'; break;
                                        case 3: $top = '235px'; break;
                                        case 4: $top = '330px'; break;
                                        case 5: $top = '425px'; break;
                                        case 6: $top = '520px'; break;
                                        case 7: $top = '615px'; break;
                                        case 8: $top = '710px'; break;
                                        case 9: $top = '805px'; break;
                                        case 10: $top = '900px'; break;
                                    }*/
                }
                //    echo 'fila:'.$fila.' '.$top.' columna'.$columna.' px'.$left.'</br>';
                $columna = $columna + 1;
                $par_options = array();
                $par_options['top']  = $top; //'5px;';
                $par_options['left'] = $left; //'10px';
                $par_options['font-size'] = '12px;';
                $pdf_html .= par_place($html_det, $par_options);
                $top  = '';
                $left = '';
                $html_det = '';
            //}
        }
        $pdf_html .= '<div style="page-break-after:always;"></div>';
        //console_log($pdf_html);
        $page = $page + 1;
    }
    $options = array();
    $options["isRemoteEnabled"] = true;

    $pdf = new Dompdf($options);
    $pdf->setPaper('letter');
    $pdf_content = '';

    $pdf_header = '';
    $img_factor = 0.65; //0.95;

    $par_x = 3;
    $par_y = 68;

    //Encabezado
    $ifactor = 220 / 1050;
    $iwidth = 1200;
    $pdf_header .= "<CXY X='15' Y='15'></CXY>";
    $pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.22/MineData-Labs/images/encabezado_prep.jpg">';
    $pdf_header .= '<cfs FONTSIZE="5"></cfs>';
    $pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY>';

    $par_options = array();
    $par_options['top'] = '55px;';
    $par_options['left'] = '220px';
    $par_options['font-size'] = '14px;';
    $pdf_header .= par_place($html_en, $par_options);
    
    $par_options = array();
    $par_options['top'] = '200px;';
    $par_options['left'] = '20px';
    $par_options['font-size'] = '14px;';
    $pdf_header .= par_place($html_en_met, $par_options);

    /*$par_options = array();
    $par_options['top']  = '30px;';
    $par_options['left'] = '20px';
    $par_options['font-size'] = '14px;';
    $pdf_html .= par_place($pdf_html, $par_options);*/


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
}
/*
function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
*/