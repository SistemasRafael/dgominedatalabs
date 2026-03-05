<?
/**
 * XT01.php v0.1
 * ----------------------------------------
 * Reporte PDF para Factura Electronica.
 **/
 
set_include_path(get_include_path() . PATH_SEPARATOR . "/xampp/htcore/modules/dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;

//Configuración central de sistema.
include '../../common/eds_settings.php';
$EDS_INCLUDE_QRCODE = 1;
include '\xampp\htcore\eds_core.php';

//Conectarse al servicio de datos.
include '\xampp\htcore\scripts\\' . $db_srv . '\user_connect.php';

include '../../common/eds_dompdf_prep.php';

//$eds_tema = 'standard';

if ($_GET['trn_id']) {
	$trn_id = $_GET['trn_id'];
} else {
	$trn_id = $_GET['key_field'];
}

$query = "SELECT
	 e.rfc
	,e.nombre
	,[direccion] = e.calle + ' No. ' + e.num_exterior + ' ' + e.num_interior
	,e.colonia
	,cd.ciudad
	,cd.estado
	,cd.pais
	,e.codigo_postal
FROM
	eds_emp AS e
	LEFT JOIN eds_cd_catalogo AS cd
		ON cd.ciudad_id = e.ciudad_id";

$empresa = eds_data_query($query, $sys_link, $db_srv);

$query = "SELECT
	 [serie] = LTRIM(RTRIM(vt.serie))
	,[folio] = vt.folio
	,vt.codigo
	,vt.sucursal_id
	,[almacen] = ISNULL((SELECT TOP 1 LTRIM(RTRIM(REPLACE(alm.nombre, 'Almacen', ''))) FROM eds_emp_almacenes AS alm WHERE alm.sucursal_id = vt.sucursal_id), '')
	,[fecha] = (
		 dbo._sys_fnc_fillString(YEAR(vt.fecha), '0', 4)
		+dbo._sys_fnc_fillString(MONTH(vt.fecha), '0', 2)
		+dbo._sys_fnc_fillString(DAY(vt.fecha), '0', 2)
		+dbo._sys_fnc_fillString(DATEPART(HOUR, vt.fecha_hora), '0', 2)
		+dbo._sys_fnc_fillString(DATEPART(MINUTE, vt.fecha_hora), '0', 2)
		+dbo._sys_fnc_fillString(DATEPART(SECOND, vt.fecha_hora), '0', 2)
	)
	,[fecha_l] = CONVERT(VARCHAR(20), vt.fecha, 6)
	,[cliente] = c.nombre
	,[cliente_codigo] = c.codigo
	,[cliente_rfc] = c.rfc
 	,[cliente_calle] = cfa.calle + ''
 	,[cliente_numext] = cfa.no_exterior
 	,[cliente_colonia] = cfa.colonia
 	,[cliente_localidad] = cfa.localidad
 	,[cliente_ciudad] = ccd.ciudad
 	,[cliente_estado] = ccd.estado
 	,[cliente_pais] = ccd.pais
 	,[cliente_telefono1] = c.telefono1
 	,[cliente_cp] = cfa.cp
 	,[condicion] = (CASE WHEN ctr.credito = 1 THEN 'CREDITO' ELSE 'CONTADO' END)
 	,[plazo] = ctr.credito_plazo
 	,[cantidad_letras] = dbo._sys_fnc_cantidadEnLetras(vt.total, 'PESOS', 'MN')
 	,[porc_iva] = CONVERT(DECIMAL(18,2), (
 		CASE 
 			WHEN (vt.impuestos / vt.importe) BETWEEN 0.1530 AND 0.1670 THEN 16.00 
 			WHEN (vt.impuestos / vt.importe) BETWEEN 0.1030 AND 0.1170 THEN 11.00 
 			ELSE (vt.impuestos / vt.importe)
 		END
 	))
 	,vt.importe
 	,[impuestos] = vt.impuestos
 	,vt.total
    ,vt.retenciones
    ,[total_cantidad] = (SELECT SUM(cantidad_facturada) FROM edS_ven_transacciones_detalle WHERE trn_id = vt.trn_id)
 	
 	,vt.comentarios
 	,[movimiento] = objf.name
 	,[rep_codigo] = ISNULL(rep.codigo, '')
 	,[usuario]    = u.nombre
FROM
	eds_ven_transacciones AS vt
	LEFT JOIN eds_ident_catalogo AS c
		ON c.ident_id = vt.ident_id
	LEFT JOIN eds_cxc_terminos AS ctr
		ON ctr.ident_id = vt.ident_id
	LEFT JOIN eds_ident_ubicaciones AS cfa
		ON cfa.ubic_id = vt.cargo_ubic_id
	LEFT JOIN eds_cd_catalogo AS ccd
		ON ccd.ciudad_id = cfa.ciudad_id
	LEFT JOIN eds_obj_forms AS objf
		ON objf.code = vt.codigo
	LEFT JOIN eds_ven_representantes AS rep
		ON rep.rep_id = vt.rep_id
	LEFT JOIN eds_sys_usuarios AS u
		ON u.u_id = vt.u_id
WHERE
	vt.trn_id = " . $trn_id;

$documento = eds_data_query($query, $sys_link, $db_srv);

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

$sucursal = eds_data_query($query, $sys_link, $db_srv);

// $pdf = new FPDF('L','mm','A4');
$pdf = new HTML2FPDF('L','mm','A4');
//$pdf=new PDF('P', 'mm', '200, 300');  

// Encabezado

$img_factor = 1.0;//0.95;

$pdf_style = '';

$pdf_style .= '@page { margin-top: 240px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 100%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 140px; text-align: center;font-size: 100% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';

$pdf_header = '';

$ifactor = 215 / 1050;
$iwidth = 1030;
$pdf_header .= '<CXY X="1" Y="0"></CXY>';
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://ss4.zairus.com/__pro/' . $EDS_CUENTA . '/store/images/notav3_01_encabezado.jpg">';

//$ifactor = 415 / 650;
//$iwidth = 650;
//$section_html .= '<table style="font-size: 12px;">';
$section_html .= '<strong>Matriz</strong> <br />';
$section_html .= $empresa[1]['nombre'] . ' <br />';
$section_html .= 'DOMICILIO: '. $empresa[1]['direccion'] . ' '.ucfirst(strtolower($empresa[1]['ciudad'])) . ', ' . ucfirst(strtolower($empresa[1]['estado'])) . ', '.ucfirst(strtolower($empresa[1]['pais'])) . ' ' . $empresa[1]['codigo_postal']. ' <br />' ;
$section_html .= 'Tel.: ' . $sucursal[1]['telefono'].' Correo: Transportedecargabacame@hotmail.com' ;
//$section_html .= '</table>';

$par_options = array();

$par_options['top'] = '12px;';
$par_options['left'] = '300px';
$par_options['font-size'] = '12px;';

$pdf_header .= par_place($section_html, $par_options);

$img_factor = 1; 

$pdf_header .= '<table style="position: absolute; top: 85px; left: 285px; width: 290px; font-size: 16px;">';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['cliente'] . ' <br />';
$pdf_header .= 'RFC: ' . $documento[1]['cliente_rfc'] . ' <br />';
$pdf_header .= $documento[1]['cliente_calle'] . ' ' . $documento[1]['cliente_numext'] . ' ' . $documento[1]['cliente_numint']. ' ' . $documento[1]['cliente_colonia'];
if ($documento[1]['cliente_colonia'] != '' && $documento[1]['cliente_colonia'] =! ' ') {
	$pdf_header .= ', Col. ';
	$pdf_header .= $documento[1]['cliente_colonia'];
}
$pdf_header .= ' <br />';
if ( $documento[1]['cliente_localidad'] != $documento[1]['cliente_ciudad']) {
	$pdf_header .= $documento[1]['cliente_localidad'] . ', ';
}
$pdf_header .= $documento[1]['cliente_ciudad'] . ', ' . $documento[1]['cliente_estado'] . ', ' . $documento[1]['cliente_pais'] . ' CP: ' . $documento[1]['cliente_cp'] . ' <br />';
$pdf_header .= (($documento[1]['cliente_telefono1'] == '' || $documento[1]['cliente_telefono1'] == ' ')? '':('Tel.: ' . $documento[1]['cliente_telefono1'] . ' ')) . '<br />';
$pdf_header .= ' <br />';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<table style="position: absolute; top: 10px; left: 650px; width: 490px; font-size: 15px; color: white;">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= '<b>';
$pdf_header .= 'RELACION DE VIAJES';
$pdf_header .= '</b>';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';
$pdf_header .= '<table style="position: absolute; top: 40px; left: 645px; width: 490px; font-size: 19px; color: red;">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= $documento[1]['serie'] . ' ' . $documento[1]['folio'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<table style="position: absolute; top:110px; left: 560px; width: 540px; font-size: 19px;">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= $documento[1]['almacen'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<table style="position: absolute; top:80px; left: 610px; width: 540px; font-size: 16px;">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= $documento[1]['fecha_l'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<img width="' . (floor(1070 * $img_factor)) . '" height="' . (floor(32 * $img_factor)) . '" src="http://ss4.zairus.com/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_02_detalle.jpg">';
	
	/*$pdf_html .= '<CXY X="210" Y="20"></CXY>';
	$pdf_html .= '<img width="' . (floor(20 * $img_factor)) . '" height="' . (floor(600 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/docv3_03_leyenda.jpg">';*/
	
    $pdf_header .= '<table style="position: absolute; top:215px; left: 10; width: 450px; font-size: 15px; color: white;">';
    //$pdf_header .= '<table style="position: absolute; top:5px; left: 45px; width: 540px; font-size: 17px; color: white;">';
	$pdf_header .= '<tr>';
	$pdf_header .= '<td width="25" align="left">';
	$pdf_header .= 'No.';
	$pdf_header .= '</td>';
	$pdf_header .= '<td width="40">';
	$pdf_header .= 'Código';
	$pdf_header .= '</td>';
	$pdf_header .= '<td width="90">';
	$pdf_header .= 'Descripción';
	$pdf_header .= '</td>';
	$pdf_header .= '<td width="45">';
	$pdf_header .= 'Unidad';
	$pdf_header .= '</td>';
    $pdf_header .= '<td width="95"  align="left">';
	$pdf_header .= 'Boleta / Destino';
	$pdf_header .= '</td>';
    $pdf_header .= '<td width="160">';
	$pdf_header .= 'Conductor';
	$pdf_header .= '</td>';
    $pdf_header .= '<td width="40">';
	$pdf_header .= 'Transporte';
	$pdf_header .= '</td>';
    $pdf_header .= '<td width="50">';
	$pdf_header .= 'Placas';
	$pdf_header .= '</td>';
	$pdf_header .= '<td width="40">';
	$pdf_header .= 'Cantidad';
	$pdf_header .= '</td>';
    $pdf_header .= '<td width="50">';
	$pdf_header .= 'Precio';
	$pdf_header .= '</td>';
    
	$pdf_header .= '<td width="50" align="right">';
	$pdf_header .= 'Importe';
	$pdf_header .= '</td>';
	$pdf_header .= '</tr>';
	$pdf_header .= '</table>';
	//$pdf_html .= '</FONT>';
	

$pdf->htmlHeader = $pdf_header;

// Pie de página

$pdf_footer = '';

$pdf->footer_margin = -70;

$ifactor = 346 / 850;
$iwidth = 1030;

$pdf_footer .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * 0.50)) . '" src="http://ss4.zairus.com/__pro/' . $EDS_CUENTA . '/store/images/docv3_05_resumen.jpg" style="position: absolute; top: 124px; left: 0px;">';

$section_html = '<strong>Son: ' . $documento[1]['cantidad_letras'] . '</strong>';

$par_options = array();

$par_options['top'] = '160px;';
$par_options['left'] = '40px';
$par_options['font-size'] = '12px;';

$pdf_footer .= par_place($section_html, $par_options);

$pdf_footer .= '<table style="position: absolute; top: 160px; left: 740px; width: 170px; font-size: 17px; font-weight: bold; color: white;">';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= 'Total Ton.';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= 'Importe';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= 'Impuestos';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= 'Retencion 4%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= 'Total';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer .= '<table style="position: absolute; top: 160px; left: 850px; width: 110px; font-size: 16px;">';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td style="width: 100px; text-align: right;">';
$pdf_footer .= '%doc_totalton%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= '%doc_importe%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= '%doc_impuestos%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= '%doc_retencion%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td style="width: 2px; text-align: right;"">';
$pdf_footer .= '%cur_symbol%';
$pdf_footer .= '%doc_total%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer = str_replace('%cur_symbol%', '$', $pdf_footer);
$pdf_footer = str_replace('%doc_totalton%', out_number($documento[1]['total_cantidad']), $pdf_footer);
$pdf_footer = str_replace('%doc_importe%', out_number($documento[1]['importe']), $pdf_footer);
$pdf_footer = str_replace('%doc_impuestos%', out_number($documento[1]['impuestos']), $pdf_footer);
$pdf_footer = str_replace('%doc_retencion%', out_number($documento[1]['retenciones']), $pdf_footer);
$pdf_footer = str_replace('%doc_total%', out_number($documento[1]['total']), $pdf_footer);
$pdf_footer = str_replace('', '', $pdf_footer);

$par_x = 22;
$par_y = 240;

$pdf_footer .= '<table style="position: absolute; top: 200px; left: 35px; width: 810px; font-size: 20px;">';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= '<strong>Comentarios: <strong></FONT> <br />';
$pdf_footer .= $documento[1]['comentarios'] . '<br />'. '<br />';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td align ="center">';
$pdf_footer .= 'Elaboró: '.$documento[1]['usuario'] . ' <br />';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf->htmlFooter = $pdf_footer;

//Contenido
$query = "SELECT [registros] = COUNT(*) FROM eds_ven_transacciones_detalle AS vtm WHERE vtm.trn_id = " . $trn_id;
$num_conceptos = eds_data_query($query, $sys_link, $db_srv);

//$conceptos = eds_data_query("SET TEXTSIZE 2147483647", $sys_link, $db_srv);

$query = "SELECT 
     vtd.ps_id
	,pc.codigo
	,pc.nombre
	,vtd.comentarios
    ,socio_nombre = cond.nombre
	,trsoc.unidad
	,trsoc.placas
	,vtd.um_id
    ,[presentacion] = u.codigo
	,[cantidad_ordenada] = vtd.cantidad_facturada
	,vtd.cantidad_facturada
	,[precio_venta] = vtd.precio_unitario
	,[iva_tasa] = vt.impuesto_tasa
	,vtd.importe
	,vtd.impuestos
	,vtd.total
FROM
	eds_ven_transacciones_detalle AS vtd
	LEFT JOIN eds_ps_catalogo AS pc
		ON pc.ps_id = vtd.ps_id
	LEFT JOIN eds_ven_transacciones AS vt
		ON vt.trn_id = vtd.trn_id
    LEFT JOIN eds_ps_unidades AS	U
		ON u.um_id  = pc.um_id
    LEFT JOIN eds_ven_documentos_transportes AS tr
		ON tr.trn_id_rel = vt.trn_id
		AND tr.trn_id = vtd.trn_id_rel
	LEFT JOIN eds_ident_transportes AS trsoc
		ON trsoc.transporte_id = tr.transporte_id
	LEFT JOIN edS_ident_catalogo AS cond
		ON cond.ident_id = tr.conductor_id
WHERE
	vtd.trn_id = " . $trn_id;
	
	$conceptos = eds_data_query($query, $sys_link, $db_srv);
 
$renglones = 0;
$registros_por_pagina = 0;
$pagina_nueva = 1;
$pagina_abierta = 0;
$registros_pagina_actual = 0;
$linea_para_final = 60;

$detalle_filas .= count($conceptos);

$os_cur_pos = 0;
$os_row_length = 0;
$os_printing = 0;

for ($i = 1; $i <= $detalle_filas; $i++)
{
    
    $pdf_html .= '<table style="width: ' . (25+40+90+45+90+185+40+50+40+40+60) . '; font-size: 12px; margin-left: 20px;">';
    	
		$pdf_html .= '<tr>';        
		$pdf_html .= '<td width="25" valign="top">';
		$pdf_html .= $i;
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="40" valign="top">';
		$pdf_html .= $conceptos[$i]['codigo'];
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="90" align="left" valign="top">';
		$pdf_html .= $conceptos[$i]['nombre'];
        $pdf_html .= '</td>';
		$pdf_html .= '<td width="45" align="left" valign="top">';
		$pdf_html .= strtoupper($conceptos[$i]['presentacion']);
		$pdf_html .= '</td>';     
        $pdf_html .= '<td width="90"  align="left" valign="top">';
		$pdf_html .= $conceptos[$i]['comentarios'];
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="185" align="left" valign="top">';
		$pdf_html .= $conceptos[$i]['socio_nombre'];
		$pdf_html .= '</td>';
        $pdf_html .= '<td width="40">';
		$pdf_html .= $conceptos[$i]['unidad'];
		$pdf_html .= '</td>';
        $pdf_html .= '<td width="50">';
		$pdf_html .= $conceptos[$i]['placas'];
		$pdf_html .= '</td>';
 	    $pdf_html .= '<td width="40" align="left">';
		$pdf_html .= number_format($conceptos[$i]['cantidad_ordenada'], 3, '.', ',');
		$pdf_html .= '</td>';		
        $pdf_html .= '<td width="40" align="right">';
		$pdf_html .= number_format($conceptos[$i]['precio_venta'], 2, '.', ',');
		$pdf_html .= '</td>';        
		$pdf_html .= '<td width="60" align="right">';
		$pdf_html .= number_format($conceptos[$i]['importe'], 2, '.', ',');
		$pdf_html .= '</td>';
		$pdf_html .= '</tr>';		
	   	$pdf_html .= '</table>';	
	}
	

$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter','landscape');

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
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';


$replace_what = array('á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ');
$replace_with = array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&Ntilde;');

$pdf_content = str_replace($replace_what, $replace_with, $pdf_content);

$pdf->loadHtml($pdf_content);

$output_options = array();

$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);

file_put_contents($file_path . $file_name . '.pdf', $pdf->output());

//Desconectarse al servicio de datos.
include '\xampp\htcore\scripts\\' . $db_srv . '\user_disconnect.php';

//$pdf->Output('../edi/' . $documento[1]['serie'] . $documento[1]['folio'] . '.pdf');

//Emitir PDF
/*if ($_GET['html'] == 1) {
	print $pdf_header;
	print $pdf_html;
	print $pdf_footer;
} else {
	header('Accept-Ranges: bytes');
	$pdf->Output();
}*/
?>