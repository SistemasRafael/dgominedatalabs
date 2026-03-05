<? include "connections/config.php";
require_once 'vendors/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$trn_id    = $_GET['trn_id'];
$metodo_id = $_GET['metodo_id'];
$preparacion = $_GET['prep'];

$pdf_style = '';

$pdf_style .= '@page { margin-top: 20px; margin-bottom: 20px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 65%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -45px; right: 0px; height: 140px; text-align: center;font-size: 80% }';
$pdf_style .= '#body { position: absolute; height: 140px; text-align: center; font-size: 80% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '.column { float: left; width: 33.33%; top: 33.33%;}';
$pdf_style .= '.row:after {content: ""; display: flex; clear: both; }';

$datos_orden = $mysqli->query("SELECT
                                 un.nombre AS unidad, od.folio_interno AS folio
                                ,DATE_FORMAT(ord.fecha,'%d/%m/%Y') AS fecha
                                ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                ,(CASE ord.tipo WHEN 0 THEN 0 WHEN 1 THEN 1 WHEN 2 THEN 2 
                                                WHEN 5 THEN 5 WHEN 6 THEN 6 ELSE 0 
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

$datos_muestras_tipo = $mysqli->query("SELECT
                                                	   (CASE WHEN ms.area_id = 1 THEN 'Planta' ELSE 'Metalurgia' END) AS area
                                                  FROM
                                                    arg_ordenes_detalle od
                                                    LEFT JOIN arg_ordenes_soluciones AS os
                                                    	ON od.trn_id = os.trn_id_batch
                                                    LEFT JOIN arg_ordenes_muestrasSoluciones AS ms
                                                    	ON ms.trn_id = os.trn_id_rel
                                                  WHERE od.trn_id = ".$trn_id."
                                                  LIMIT 1")or die(mysqli_error($mysqli));
$datos_muestras_t = $datos_muestras_tipo->fetch_assoc();
$datos_muestras_area = $datos_muestras_t['area'];

if ($tipo_orden == 1 or $tipo_orden == 6) {
    $detalle_etiquetas = $mysqli->query("SELECT
                                                     om.trn_id_batch                                                  
                                                    ,muestra_geologia
                                                    ,om.folio_interno as folio_interno
                                                    ,om.posicion                                                    
                                                    FROM
                                                        ordenes_transacciones om
                                                    WHERE 
                                                    	om.trn_id_batch = ".$trn_id."
                                                        AND tipo_id = 0
                                                    UNION ALL
                                                    SELECT
                                                    	 cn.trn_id_batch
                                                   		,cn.control  AS muestra_geologia
                                                    	,cn.folio_interno as folio_interno
                                                        ,cn.posicion
                                                    FROM
                                                        ordenes_transacciones cn
                                                    WHERE 
                                                    	cn.trn_id_batch = ".$trn_id."
                                                        AND cn.trn_id_dup = 0
                                                        AND tipo_id <> 0
                                                        AND cn.metodo_id = ".$metodo_id."
                                                    UNION ALL
                                                    SELECT
                                                    	 ot.trn_id_batch
                                                        ,CONCAT(dup.muestra_geologia, ' - D') AS muestra_geologia
                                                    	,ot.folio_interno as folio_interno
                                                        ,ot.posicion
                                                    FROM
                                                        ordenes_transacciones ot
                                                        LEFT JOIN (SELECT muestra_geologia, trn_id_batch, dup.metodo_id, trn_id_rel
                                                                    FROM
                                                                        ordenes_transacciones dup
                                                                   ) AS dup
                                                            ON  ot.trn_id_batch = dup.trn_id_batch
                                                            AND ot.trn_id_dup = dup.trn_id_rel
                                                            AND ot.metodo_id = dup.metodo_id
                                                    WHERE 
                                                    	ot.trn_id_batch = ".$trn_id."
                                                        AND ot.trn_id_dup <> 0
                                                        AND ot.metodo_id = ".$metodo_id." ORDER BY posicion") or die(mysqli_error($mysqli));
} else if ($tipo_orden == 5){
        $detalle_etiquetas = $mysqli->query("SELECT                                                
                                                  muestra_geologia
                                                ,(CASE WHEN od.tipo_id = 1 THEN mr.nombre ELSE od.folio_interno END) AS folio_interno                                            
                                            FROM
                                                ordenes_sobrelimites od
                                                LEFT JOIN arg_controles_materiales mr
                                                    ON od.material_id = mr.material_id
                                                    AND od.tipo_id = 1
                                            WHERE
                                                od.trn_id_rel = " . $trn_id . "
                                                AND od.metodo_id = " . $metodo_id . " 
                                            ORDER BY od.folio_interno") or die(mysqli_error($mysqli));
    } else if ($tipo_orden == 2){
        $detalle_etiquetas = $mysqli->query("SELECT                                                
                                                 od.folio AS muestra_geologia
                                                ,od.folio_interno
                                                ,1 AS posicion                                            
                                            FROM
                                                ordenes_soluciones AS od 
                                                WHERE od.trn_id_batch = " . $trn_id . "
                                                AND od.metodo_id = " . $metodo_id . " 
                                            ORDER BY od.folio_interno") or die(mysqli_error($mysqli));
    }
    else{
        $origen_reen = $mysqli->query("SELECT 
                                            COUNT(*) AS existe_rech
                                       FROM 
                                            arg_muestras_reensaye mre
                                       WHERE 
                                            mre.trn_id_rel = " . $trn_id . " 
                                            AND mre.metodo_id = (CASE WHEN " . $metodo_id . " = 0 THEN mre.metodo_id 
                                                                      ELSE " . $metodo_id . " END) 
                                            AND mre.trn_id_muestra IN (SELECT trn_id_rel 
                                                                       FROM muestras_recheck)"
                                    ) or die(mysqli_error($mysqli));
        $existe_reche = $origen_reen->fetch_assoc();
        $existe_rec = $existe_reche['existe_rech'];
                                    
        if ($existe_rec == 0){
            $detalle_etiquetas = $mysqli->query("SELECT
                                                    ot.muestra_geologia,
                                                    ot.folio_interno,                                                                
                                                    ot.tipo_id,
                                                    ot.control
                                                FROM
                                                    ordenes_reensayes ot
                                                WHERE
                                                    ot.trn_id_rel = " . $trn_id . " AND ot.metodo_id = " . $metodo_id . "
                                                ORDER BY
                                                    ot.folio_interno"
                                                ) or die(mysqli_error($mysqli));
        }
        else{
             $detalle_etiquetas = $mysqli->query("SELECT
                                                        ot.muestra_geologia,
                                                        ot.folio_interno,                                                                
                                                        ot.tipo_id,
                                                        ot.control
                                                  FROM
                                                        ordenes_reensayes_recheck ot
                                                  WHERE
                                                        ot.trn_id_rel = " . $trn_id . " AND ot.metodo_id = " . $metodo_id . "
                                                  ORDER BY
                                                        ot.folio_interno"
                                                ) or die(mysqli_error($mysqli));
             }
    }
$total_muestras = (mysqli_num_rows($detalle_etiquetas));

$html_en = "";
$html_en .= "<table border='0' width='100%' CELLPADDING=3 CELLSPACING=0>
                             <thead>
                                 <tr>
                                    <th width='40%' align='left'><strong>ORDEN: " . $folio . "</strong></th>
                                 </tr>
                                 <tr>
                                    <th width='10%' align='left'>METODO: " . $metodo . " </th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>ELABORADA: " . $orden_encabezado['fecha'] . " " . $orden_encabezado['hora'] . "</th> 
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>CLIENTE: " . $orden_encabezado['unidad'] . " </th>
                                 </tr>
                                 <tr>
                                    <th width='80%' align='left'>DESCRIPCION: " . $metodo_desc . " </th>
                                 </tr>";
$html_en .= "</thead></table><br/>";
$html_en_met = "";

if ($metodo_id == 3 ) {
    $html_en_met .= "<table border = '1' width=70 HEIGHT=250 CELLPADDING=3 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th align='center'> PESADO:__________</th>
                                    <th align='center'> FUNDIDO:__________</th>
                                    <th align='center'> COPELADO:__________</th>
                                    <th align='center'> DIGESTION:__________</th>
                                    <th align='center'> AA:__________</th>
                                </tr>
                                <tr>
                                    <th align='center' colspan=5> ARCHIVO:____________________</th>                                  
                            </thead>";
    $html_en_met .= "</thead></table><br/>";
}
if ($metodo_id == 1) {
    $html_en_met .= "<table border = '1' width=70 HEIGHT=250 CELLPADDING=3 CELLSPACING=0>
                            <thead>
                                <tr>
                                    <th align='center'> PESADO:__________</th>
                                    <th align='center'> FUNDIDO:__________</th>
                                    <th align='center'> COPELADO:__________</th>
                                    <th align='center'> ATAQUE:__________</th>
                                    <th align='center'> PESAJE Au:_________</th>
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
$total_paginas = ceil($total_muestras / 78);
$total_filas   = ceil($total_muestras / 78);
$total = $total_muestras;
while ($page <= $total_paginas) {
    $html_det = "<div class='row' style='margin-top: 55%; margin-left: 10%;>'";
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
            if ($f == 26) {
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

$pdf = new Dompdf($options);
$customPaper = array(0, 0, 180, 320);
$pdf->setPaper($customPaper, 'portrait');
$pdf_content = '';

$pdf_header = '';
$img_factor = 0.30; //0.95;

$par_x = 0;
$par_y = 15;

$ifactor = 220 / 1050;
$iwidth = 750;
$pdf_header .= "<br/><br/><br/><br/><CXY X='0' Y='80'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.22/MineData-Labs/images/encabezado_prep.jpg" margin-top="20">';
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
