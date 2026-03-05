<?
/**
 * AT01_1.php v0.1
 * ----------------------------------------
 * Reporte PDF para Factura de Venta.
 **/

//Configuración central de sistema.
include '../../common/eds_settings.php';
//$EDS_INCLUDE_QRCODE = 1;
include '\xampp\htcore\eds_core.php';

//Conectarse al servicio de datos.
//include '\xampp\htcore\scripts\\' . $db_srv . '\user_connect.php';

include "connections/config.php";
/*if (!defined('EDS_DONTINCLUDE_MODULES_FPDF'))
	$EDS_DONTINCLUDE_MODULES_FPDF = 0;
if (!$EDS_DONTINCLUDE_MODULES_FPDF) {
	require('\xampp\htdocs\registro\mod_html2fpdf\html2fpdf.php');
}*/
//require('\xampp\htdocs\registro\mod_html2fpdf\html2fpdf.php');

$eds_tema = 'standard';

$trn_id = $_GET['trn_id'];

$empresa_encab = $mysqli->query("SELECT
	 arg_empresas.rfc
	,arg_empresas.nombre
	,arg_empresas.calle
    ,arg_empresas.num_exterior
    ,arg_empresas.num_interior
	,arg_empresas.colonia
	,arg_ciudades.ciudad
	,arg_ciudades.estado
	,arg_ciudades.pais
	,arg_empresas.codigo_postal
FROM
	arg_empresas
	LEFT JOIN arg_ciudades
		ON arg_ciudades.ciudad_id = arg_empresas.ciudad_id") or die(mysqli_error());
$empresa = $empresa_encab ->fetch_array(MYSQLI_ASSOC);

//$documento1 = $mysqli->query("". $trn_id) or die(mysqli_error());
//$documento = $documento1 ->fetch_array(MYSQLI_ASSOC);
/*
$query = "SELECT
	 [direccion] = eu.calle + ' No. ' + eu.num_exterior + ' ' + eu.num_interior
	,eu.colonia
	,cd.ciudad
	,cd.estado
	,cd.pais
	,eu.codigo_postal
	,s.telefono
FROM
	eds_emp_sucursales AS s
	LEFT JOIN eds_emp_ubicaciones AS eu
		ON eu.ubicacion_id = s.ubicacion_id
	LEFT JOIN eds_cd_catalogo AS cd
		ON cd.ciudad_id = eu.ciudad_id
WHERE
	s.sucursal_id = " . $documento[1]['sucursal_id'];

$sucursal = eds_data_query($query, $sys_link, $db_srv);*/


$pdf = new HTML2FPDF();

// Encabezado

$img_factor = 0.95;

$pdf_header = '';

$pdf_header .= '<CXY X="1" Y="0"></CXY>';
$pdf_header .= '<img width="' . (floor(850 * $img_factor)) . '" height="' . (floor(215 * $img_factor)) . '" src="http://127.0.0.1:81/registro/images/encabezado_visita.jpg">';

$par_x = 70;
$par_y = 4;

$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 0) . '"></CXY>';
$pdf_header .= '<FONT COLOR="#666666"><strong>Matriz</strong></FONT> <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 2) . '"></CXY>';
$pdf_header .= $empresa[1]['nombre'] . ' <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 4) . '"></CXY>';
//$pdf_header .= $empresa[1]['direccion'] . ' <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 6) . '"></CXY>';
//$pdf_header .= ucfirst(strtolower($empresa[1]['ciudad'])) . ', ' . ucfirst(strtolower($empresa[1]['estado'])) . ', ';
//$pdf_header .= ucfirst(strtolower($empresa[1]['pais'])) . ' ' . $empresa[1]['codigo_postal'];
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 8) . '"></CXY>';
$pdf_header .= 'Tel.: (662) 214 5115';
//$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 12) . '"></CXY>';
//$pdf_header .= 'info@techosyparedes.com.mx';

$par_x = 110;
$par_y = 4;


$pdf_header .= '<cfs FONTSIZE="7"></cfs>';

$pdf_header .= '<CXY X="10" Y="24"></CXY>';
$pdf_header .= '<table width="490">';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
//$pdf_header .= '[' . $documento[1]['cliente_codigo'] . '] ' . $documento[1]['cliente'] . ' <br />';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';


$pdf_header .= '<cfs FONTSIZE="8"></cfs>';

$pdf_header .= '<CXY X="155" Y="22"></CXY>';
$pdf_header .= '<strong>Fecha</strong>';
$pdf_header .= '<CXY X="181" Y="22"></CXY>';
$pdf_header .= '<strong>Vencimiento</strong>';

$pdf_header .= '<cfs FONTSIZE="9"></cfs>';

$pdf_header .= '<CXY X="152" Y="25.5"></CXY>';
//$pdf_header .= $documento[1]['fecha_l'];
$pdf_header .= '<CXY X="182" Y="25.5"></CXY>';
$pdf_header .= 'fecha_vencimiento';

$pdf_header .= '<cfs FONTSIZE="9"></cfs>';

$pdf->htmlHeader = $pdf_header;
$pdf->tMargin = 55;

// Pie de página

$pdf_footer = '';

$pdf->footer_margin = -110;
$pdf->bMargin = 0;

$pdf_footer .= '<CXY X="1" Y="' . ($pdf->footer_margin + 22) . '"></CXY>';

$pdf_footer .= '<cfs FONTSIZE="5"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 24) . '"></CXY>';
$pdf_footer.= 'Efectos fiscales al pago | Pago en una sola exhibición | Este comprobante es una representación impresa de un CFD | Persona Moral del Régimen General de Ley';

$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 27) . '"></CXY>';

$pdf_footer .= '<cfs FONTSIZE="5"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 31) . '"></CXY>';
$pdf_footer .= '<strong>Sello Digital</strong>';
$pdf_footer .= '<CXY X="9" Y="' . ($pdf->footer_margin + 33) . '"></CXY>';
$pdf_footer .= '<table>';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td width="530">';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';

$pdf_footer .= '<CXY X="120" Y="' . ($pdf->footer_margin + 44) . '"></CXY>';
$pdf_footer .= '<cfs FONTSIZE="6"></cfs>';
$pdf_footer .= '<table>';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td width="350" align="right">';
$pdf_footer .= 'Este comprobante cumple con lo dispuesto en el Anexo 20 de la Resolución Miscelanea Fiscal para 2012, el cual puede descargarse de la siguiente dirección:';
$pdf_footer .= '<br>';
$pdf_footer .= '<a href="ftp://ftp2.sat.gob.mx/asistencia_servicio_ftp/publicaciones/legislacion12/Anexo20_30122011.doc">ftp://ftp2.sat.gob.mx/asistencia_servicio_ftp/publicaciones/legislacion12/Anexo20_30122011.doc</a>';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';


$pdf_footer .= '<cfs FONTSIZE="10"></cfs>';

$pdf->htmlBeforePageText = '';

//$pdf->htmlFooter = $pdf_footer;


//Contenido
/*$query = "
SELECT *
	 arg_entradas
WHERE trn_id = " . $trn_id . " 
ORDER BY 
	trn_id";

$conceptos = eds_data_query($query, $sys_link, $db_srv);*/

$renglones= 0;
$registros_por_pagina = 30;
$pagina_nueva = 1;
$pagina_abierta = 0;
$registros_pagina_actual = 0;
$linea_para_final = 25;

$detalle_filas = 5;//count($conceptos);

$os_cur_pos = 0;
$os_row_length = 175;
$os_printing = 0;

$detalle_filas += $os_total_rows;
$detalle_filas++;

for ($i = 1; $i <= $detalle_filas; $i++)
{
	$registros_pagina_actual++;
	
	if ($registros_pagina_actual > $registros_por_pagina)
	{
		$pagina_nueva = 1;
		$registros_pagina_actual = 1;
		
	/*	if ($pagina_abierta == 1)
		{
			$pdf_html .= '</table>';
			$pdf_html .= str_replace(
				array(
					'%cur_symbol%'
					,'%doc_importe%'
					,'%doc_impuestos%'
					,'%doc_total%'
				)
				,array(
					'&nbsp;'
					,'&nbsp;'
					,'&nbsp;'
					,'&nbsp;'
				)
				,$pdf_footer
			);
			
			$pdf->WriteHTML($pdf_html);
			$pagina_abierta = 0;
		}
	}*/
	
	if ($pagina_nueva == 1)
	{
		$pdf->AddPage();
		
		$pdf_html = '';
		
		$pdf_html .= '<cfs FONTSIZE="7"></cfs>';
		
		
        
		$pdf_html .= '<CXY X="1" Y="55"></CXY>';
		//$pdf_html .= '<img width="' . (floor(850 * $img_factor)) . '" height="' . (floor(34 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_02_detalle.jpg">';
		
		$pdf_html .= '<CXY X="10" Y="57"></CXY>';
		$pdf_html .= '<FONT COLOR="#ffffff">';
        
        $pdf_html .= '<table>';
        $pdf_html .= '<tr>';
        $pdf_html .= '<td width="25">';
        $pdf_html .= 'No.';
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="100">';
        $pdf_html .= 'Código';
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="220">';
        $pdf_html .= 'Descripción';
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="105">';
        $pdf_html .= 'Presentación';
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="70" align="right">';
        $pdf_html .= 'Cantidad';
        $pdf_html .= '</td>';
		$pdf_html .= '</FONT>';
		
		
		$pagina_nueva = 0;
		$pagina_abierta = 1;
	}
	
}

}
$pdf->Output('../registro/argonaut2.pdf');

//Emitir PDF
if ($_GET['html'] == 1) {
	print $pdf_header;
	print $pdf_html;
	print $pdf_footer;
} else {
	header('Accept-Ranges: bytes');
    	//$pdf->Output();
    $mi_pdf = $pdf;//'/xampp/htdocs/registro/argonaut2.pdf';
    $mi_pdf = '../registro/argonaut2.pdf';
    header('Content-type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
    readfile($mi_pdf);
}
?>