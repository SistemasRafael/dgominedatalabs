<?
/**
 * AT01_1.php v0.1
 * ----------------------------------------
 * Reporte PDF para Factura de Venta.
 **/

//Configuración central de sistema.
//include '../../common/eds_settings.php';
//$EDS_INCLUDE_QRCODE = 1;
//include '\xampp\htcore\eds_core.php';

//Conectarse al servicio de datos.
//include '\xampp\htcore\scripts\\' . $db_srv . '\user_connect.php';

include "connections/config.php";

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

$documento1 = $mysqli->query("". $trn_id) or die(mysqli_error());
$documento = $documento1 ->fetch_array(MYSQLI_ASSOC);

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

$ejercicio_path = '\xampp\htdocs\__pro\\' . $EDS_CUENTA . '\store\edi\\' . $documento[1]['ejercicio'];
$periodo_path = '\xampp\htdocs\__pro\\' . $EDS_CUENTA . '\store\edi\\' . $documento[1]['ejercicio'] . '\\' . $documento[1]['periodo'];

if (!is_dir($ejercicio_path))
	mkdir($ejercicio_path);

if (!is_dir($periodo_path))
	mkdir($periodo_path);

$fetch_url = 'http://edata.mx/?f=29.' . $trn_id;
$qr_relative_url = '../edi/' . $documento[1]['ejercicio'] . '/' . $documento[1]['periodo'] . '/' . $documento[1]['serie'] . $documento[1]['folio'] . '.png';

QRcode::png($fetch_url, $qr_relative_url, QR_ECLEVEL_L, 2, 3);

$pdf = new HTML2FPDF();

// Encabezado

$img_factor = 0.95;

$pdf_header = '';

$pdf_header .= '<CXY X="1" Y="0"></CXY>';
$pdf_header .= '<img width="' . (floor(850 * $img_factor)) . '" height="' . (floor(215 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_01_encabezado.jpg">';

$par_x = 70;
$par_y = 4;

$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 0) . '"></CXY>';
$pdf_header .= '<FONT COLOR="#666666"><strong>Matriz</strong></FONT> <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 2) . '"></CXY>';
$pdf_header .= $empresa[1]['nombre'] . ' <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 4) . '"></CXY>';
$pdf_header .= $empresa[1]['direccion'] . ' <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 6) . '"></CXY>';
$pdf_header .= ucfirst(strtolower($empresa[1]['ciudad'])) . ', ' . ucfirst(strtolower($empresa[1]['estado'])) . ', ';
$pdf_header .= ucfirst(strtolower($empresa[1]['pais'])) . ' ' . $empresa[1]['codigo_postal'];
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 8) . '"></CXY>';
$pdf_header .= 'Tel.: (662) 214 5115';
//$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 12) . '"></CXY>';
//$pdf_header .= 'info@techosyparedes.com.mx';

$par_x = 110;
$par_y = 4;

$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 0) . '"></CXY>';
$pdf_header .= '<FONT COLOR="#666666"><strong>Expedido en</strong></FONT> <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 2) . '"></CXY>';
$pdf_header .= $empresa[1]['direccion'] . ' <br />';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 4) . '"></CXY>';
$pdf_header .= ucfirst(strtolower($empresa[1]['ciudad'])) . ', ' . ucfirst(strtolower($empresa[1]['estado'])) . ', ';
$pdf_header .= ucfirst(strtolower($empresa[1]['pais'])) . ' ' . $empresa[1]['codigo_postal'];
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 6) . '"></CXY>';
$pdf_header .= 'Tel.: (662) 214 5115' . ' <br />';

$pdf_header .= '<cfs FONTSIZE="7"></cfs>';

$pdf_header .= '<CXY X="10" Y="24"></CXY>';
$pdf_header .= '<table width="490">';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= '[' . $documento[1]['cliente_codigo'] . '] ' . $documento[1]['cliente'] . ' <br />';
$pdf_header .= 'RFC: ' . $documento[1]['cliente_rfc'] . ' <br />';
$pdf_header .= $documento[1]['cliente_calle'] . ' ' . $documento[1]['cliente_numext'] . ' ' . $documento[1]['cliente_numint'];
if ($documento[1]['cliente_colonia'] != '' && $documento[1]['cliente_colonia'] =! ' ') {
	$pdf_header .= ', Col. ';
	$pdf_header .= $documento[1]['cliente_colonia'];
}
$pdf_header .= ' <br />';
$pdf_header .= $documento[1]['cliente_ciudad'] . ', ' . $documento[1]['cliente_estado'] . ', ' . $documento[1]['cliente_pais'] . ' CP: ' . $documento[1]['cliente_cp'] . ' <br />';
if ($documento[1]['cliente_telefono1'] != '' && $documento[1]['cliente_telefono1'] != ' ')
{
	$pdf_header .= 'Teléfono : ' . $documento[1]['cliente_telefono1'] . ' <br />';
}
if ($documento[1]['cliente_email'] != '' && $documento[1]['cliente_email'] != ' ')
{
	$pdf_header .= 'Correo Electrónico: ' . $documento[1]['cliente_email'] . ' <br />';
}
$pdf_header .= 'REPRESENTANTE DE VENTAS: ' . $documento[1]['rep_nombre'] . ' <br />';
$pdf_header .= ' <br />';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<cfs FONTSIZE="9"></cfs>';
$pdf_header .= '<CXY X="170" Y="6"></CXY>';
$pdf_header .= '<table width="135">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= '<font color="#ffffff">';
$pdf_header .= '<b>';
$pdf_header .= $documento[1]['movimiento'];
$pdf_header .= '</b>';
$pdf_header .= '</font>';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<cfs FONTSIZE="12"></cfs>';
$pdf_header .= '<CXY X="170" Y="12"></CXY>';
$pdf_header .= '<table width="135">';
$pdf_header .= '<tr>';
$pdf_header .= '<td align="center">';
$pdf_header .= '<font color="#ff0000">';
$pdf_header .= '<b>';
$pdf_header .= $documento[1]['serie'] . ' ' . $documento[1]['folio'];
$pdf_header .= '</b>';
$pdf_header .= '</font>';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<cfs FONTSIZE="6"></cfs>';

$pdf_header .= '<CXY X="9" Y="50"></CXY>';
$pdf_header .= 'No. Certificado: ' . $documento[1]['no_certificado'];
$pdf_header .= ', Certificado SAT: ' . $documento[1]['certificado_sat'];
$pdf_header .= ', Fecha emisión: ';
$pdf_header .= substr($documento[1]['fecha'], 0, 4);
$pdf_header .= '-';
$pdf_header .= substr($documento[1]['fecha'], 4, 2);
$pdf_header .= '-';
$pdf_header .= substr($documento[1]['fecha'], 6, 2);
$pdf_header .= 'T';
$pdf_header .= substr($documento[1]['fecha'], 8, 2);
$pdf_header .= ':';
$pdf_header .= substr($documento[1]['fecha'], 10, 2);
$pdf_header .= ':';
$pdf_header .= substr($documento[1]['fecha'], 12, 2);
$pdf_header .= ', Fecha timbrado: ';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 0, 4);
$pdf_header .= '-';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 4, 2);
$pdf_header .= '-';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 6, 2);
$pdf_header .= 'T';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 8, 2);
$pdf_header .= ':';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 10, 2);
$pdf_header .= ':';
$pdf_header .= substr($documento[1]['fecha_timbrado'], 12, 2);
$pdf_header .= ', RFC: <strong>' . $empresa[1]['rfc'].'</br>' . '</strong>';

$pdf_header .= '<CXY X="9" Y="52"></CXY>';
$pdf_header .= 'UUID: <font color="#ff0000"><strong>' . $documento[1]['cfdi_uuid'] . '</strong></font>';

$pdf_header .= '<cfs FONTSIZE="7"></cfs>';

$pdf_header .= '<CXY X="150" Y="30"></CXY>';
$pdf_header .= '<table>';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= 'Condiciones:';
$pdf_header .= '</td>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['condicion'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= 'Cuenta de Pago:';
$pdf_header .= '</td>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['cuenta_pago'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= 'Método de Pago:';
$pdf_header .= '</td>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['metodo_pago'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= 'Plazo:';
$pdf_header .= '</td>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['plazo'] . ' DÍAS';
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '<tr>';
$pdf_header .= '<td>';
$pdf_header .= 'Almacén:';
$pdf_header .= '</td>';
$pdf_header .= '<td>';
$pdf_header .= $documento[1]['almacen'];
$pdf_header .= '</td>';
$pdf_header .= '</tr>';
$pdf_header .= '</table>';

$pdf_header .= '<cfs FONTSIZE="8"></cfs>';

$pdf_header .= '<CXY X="155" Y="22"></CXY>';
$pdf_header .= '<strong>Fecha</strong>';
$pdf_header .= '<CXY X="181" Y="22"></CXY>';
$pdf_header .= '<strong>Vencimiento</strong>';

$pdf_header .= '<cfs FONTSIZE="9"></cfs>';

$pdf_header .= '<CXY X="152" Y="25.5"></CXY>';
$pdf_header .= $documento[1]['fecha_l'];
$pdf_header .= '<CXY X="182" Y="25.5"></CXY>';
$pdf_header .= $documento[1]['fecha_vencimiento'];

$pdf_header .= '<cfs FONTSIZE="9"></cfs>';

$pdf->htmlHeader = $pdf_header;
$pdf->tMargin = 55;

// Pie de página

$pdf_footer = '';

$pdf->footer_margin = -110;
$pdf->bMargin = 0;

$pdf_footer .= '<CXY X="1" Y="' . ($pdf->footer_margin + 22) . '"></CXY>';
$pdf_footer .= '<img width="' . (floor(850 * $img_factor)) . '" height="' . (floor(346 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_04_resumen.jpg">';

$pdf_footer .= '<cfs FONTSIZE="5"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 24) . '"></CXY>';
$pdf_footer.= 'Efectos fiscales al pago | Pago en una sola exhibición | Este comprobante es una representación impresa de un CFD | Persona Moral del Régimen General de Ley';

$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 27) . '"></CXY>';
$pdf_footer .= '<strong>Son: ' . $documento[1]['cantidad_letras'] . '</strong>';

$pdf_footer .= '<cfs FONTSIZE="5"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 31) . '"></CXY>';
$pdf_footer .= '<strong>Sello Digital</strong>';
$pdf_footer .= '<CXY X="9" Y="' . ($pdf->footer_margin + 33) . '"></CXY>';
$pdf_footer .= '<table>';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td width="530">';
$pdf_footer .= substr($documento[1]['sello'], 0, 90);
$pdf_footer .= ' ';
$pdf_footer .= substr($documento[1]['sello'], 90, strlen($documento[1]['sello']));
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 39) . '"></CXY>';
$pdf_footer .= '<strong>Observaciones</strong>';
$pdf_footer .= '<CXY X="9.1" Y="' . ($pdf->footer_margin + 41) . '"></CXY>';
$pdf_footer .= '<table>';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td width="390">';
$pdf_footer .= $documento[1]['comentarios'];
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

$pdf_footer .= '<CXY X="9" Y="' . ($pdf->footer_margin + 54) . '"></CXY>';
$pdf_footer .= '<img width="' . (floor(100 * $img_factor)) . '" height="' . (floor(42 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/CFD22.jpg">';

$pdf_footer .= '<CXY X="9" Y="' . ($pdf->footer_margin + 84) . '"></CXY>';
$pdf_footer .= 'FACTURA '.$documento[1]['serie'] . ' ' . $documento[1]['folio'];

$pdf_footer .= '<CXY X="38" Y="' . ($pdf->footer_margin + 53) . '"></CXY>';
$pdf_footer .= '<a href="' . $fetch_url . '"><img width="70" height="70" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/edi/' . $documento[1]['ejercicio'] . '/' . $documento[1]['periodo'] . '/' . $documento[1]['serie'] . $documento[1]['folio'] . '.png"></a>';

$pdf_footer .= '<cfs FONTSIZE="6"></cfs>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 68) . '"></CXY>';
$pdf_footer .= '<strong>Descarga el Comprobante:</strong>';
$pdf_footer .= '<CXY X="10" Y="' . ($pdf->footer_margin + 71) . '"></CXY>';
$pdf_footer .= $fetch_url;
$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';

if ($documento[1]['referencia_pago'] != '' && $documento[1]['referencia_pago'] != ' ')
{
	$pdf_footer .= '<CXY X="61" Y="' . ($pdf->footer_margin + 50) . '"></CXY>';
	$pdf_footer .= '<strong>Referencia para pago: ' . $documento[1]['referencia_pago'] . '</strong>';
}

if ($documento[1]['codigo'] != 'XT02') {
	$pdf_footer .= '<CXY X="61" Y="' . ($pdf->footer_margin + 55) . '"></CXY>';
	$pdf_footer .= '<strong>PAGARÉ</strong>';
	
	$pdf_footer .= '<cfs FONTSIZE="5"></cfs>';
	
	$pdf_footer .= '<CXY X="60" Y="' . ($pdf->footer_margin + 58) . '"></CXY>';
	
	$pdf_footer .= '<table>';
	$pdf_footer .= '<tr>';
	$pdf_footer .= '<td width="540">';
	$pdf_footer .= 'Hermosillo, Sonora, a ' . $documento[1]['fecha_l'] . ' DEBO (MOS) Y PAGARE (MOS) INCONDICIONALMENTE LA CANTIDAD DE $' . number_format($documento[1]['total'], 2, '.', ',') . ' (Son: ' . $documento[1]['cantidad_letras'] . '), POR ESTE PAGARE A FAVOR DE ' . strtoupper($empresa[1]['nombre']) . '. EN CASO DE NO SER LIQUIDADO ESTE ADEUDO EL DIA ' . $documento[1]['fecha_vencimiento'] . ' SE CAUSARA UN 5% DE INTERESES MENSUAL DE LA CANTIDAD CONSIGNADA EN ESTE PAGARE HASTA LA FECHA EN QUE SE LIQUIDE LA DEUDA Y LOS INTERESES GENERADOS Y ACEPTO (MOS) QUE MIS (NUESTRAS) COMPRAS FUTURAS QUEDARAN SUJETAS A LAS POLITICAS DE CREDITO DEL ACREEDOR, SE RESERVA EL DERECHO A COBRAR EL 20% SOBRE CHEQUES DEVUELTOS, EN LOS TERMINOS DE ART. 193 DE LA LEY GENERAL DE TITULOS Y OPERACIONES DE CREDITO.';
	$pdf_footer .= '</td>';
	$pdf_footer .= '</tr>';
	$pdf_footer .= '<tr>';
	$pdf_footer .= '<td width="540" align="center">';
	$pdf_footer.= '<br />
<br />
<br />
<br />
______________________________________________________<br />';
	
	$pdf_footer .= '</td>';
	$pdf_footer .= '</tr>';
	$pdf_footer .= '</table>';
	
	$pdf_footer .= '<CXY X="60" Y="' . ($pdf->footer_margin + 80) . '"></CXY>';
	$pdf_footer .= '<table>';
	$pdf_footer .= '<tr>';
	$pdf_footer .= '<td width="540" align="center">';
	$pdf_footer .= '<strong>Nombre Legible y Firma del Cliente</strong>';
	$pdf_footer .= '</td>';
	$pdf_footer .= '</tr>';
	$pdf_footer .= '</table>';
	
	$pdf_footer .= '<CXY X="60" Y="' . ($pdf->footer_margin + 82) . '"></CXY>';
	$pdf_footer .= '<table>';
	$pdf_footer .= '<tr>';
	$pdf_footer .= '<td width="540" align="center">';
	$pdf_footer .= 'ACEPTO DE CONFORMIDAD<br />
' . $documento[1]['cliente'] . '<br /> 
' . $documento[1]['cliente_calle'] . ' ' . $documento[1]['cliente_ciudad']. ', ' . $documento[1]['cliente_estado'];
	$pdf_footer .= '</td>';
	$pdf_footer .= '</tr>';
	$pdf_footer .= '</table>';
}

$pdf_footer .= '<cfs FONTSIZE="10"></cfs>';

$pdf_footer .= '<CXY X="157" Y="' . ($pdf->footer_margin + 27) . '"></CXY>';
$pdf_footer .= '<FONT COLOR="#ffffff">';
$pdf_footer .= '<b>IMPORTE</b>';
$pdf_footer .= '<CXY X="157" Y="' . ($pdf->footer_margin + 31) . '"></CXY>';
$pdf_footer .= '<b>IVA ' . $documento[1]['porc_iva'] . '%</b>';
$pdf_footer .= '<CXY X="157" Y="' . ($pdf->footer_margin + 36) . '"></CXY>';
$pdf_footer .= '<b>TOTAL</b>';
$pdf_footer .= '</FONT>';

$pdf_footer .= '<CXY X="180" Y="' . ($pdf->footer_margin + 26) . '"></CXY>';
$pdf_footer .= '<table width="120">';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td width="20">';
$pdf_footer .= '%cur_symbol%';
$pdf_footer .= '</td>';
$pdf_footer .= '<td width="100" align="right">';
$pdf_footer .= '%doc_importe%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= '';
$pdf_footer .= '</td>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= '%doc_impuestos%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';

$pdf_footer .= '<tr>';
$pdf_footer .= '<td>';
$pdf_footer .= '%cur_symbol%';
$pdf_footer .= '</td>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= '%doc_total%';
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer .= '<cfs FONTSIZE="7"></cfs>';

$pdf_footer .= '<CXY X="128" Y="' . ($pdf->footer_margin + 36) . '"></CXY>';
$pdf_footer .= '<b>PAGOS</b>';
$pdf_footer .= '<CXY X="128" Y="' . ($pdf->footer_margin + 40) . '"></CXY>';
$pdf_footer .= '<b>SALDO</b>';

$pdf_footer .= '<CXY X="136" Y="' . ($pdf->footer_margin + 36) . '"></CXY>';
$pdf_footer .= '<table width="70">';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td align="right">';
$pdf_footer .= number_format($documento[1]['pagos'], 2, '.', ',');
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '<tr>';
$pdf_footer .= '<td align="right">';
if ($documento[1]['saldo'] > 0.01)
{
	$pdf_footer .= number_format($documento[1]['saldo'], 2, '.', ',');
}
else
{
	$pdf_footer .= '0.00';
}
$pdf_footer .= '</td>';
$pdf_footer .= '</tr>';
$pdf_footer .= '</table>';

$pdf_footer .= '<cfs FONTSIZE="10"></cfs>';

$pdf->htmlBeforePageText = '';

//$pdf->htmlFooter = $pdf_footer;

$conceptos = eds_data_query("SET TEXTSIZE 2147483647", $sys_link, $db_srv);

//Contenido
$query = "
SELECT
	 [id] = ROW_NUMBER() OVER (ORDER BY det_id ASC, codigo DESC)
	,trn_id
	,det_id
	,codigo
	,nombre
	,[pedimento] = ''
	,presentacion
	,unidad
	,unidad_codigo
	,volumen
	,[cantidad_facturada] = cantidad_facturada
	,[precio_unitario] = (
		CASE 
			WHEN cantidad_facturada <> 0 THEN (importe / cantidad_facturada) 
			ELSE precio_unitario 
		END
	)
	,importe
	,[comentarios] = CONVERT(VARCHAR(MAX), comentarios)
	,tipo
    
    ,[precio_lista] = precio_unitario
FROM 
	eds_ven_transacciones_detalle_impresion 
WHERE trn_id = " . $trn_id . " 
ORDER BY 
	det_id ASC, com_id, codigo DESC";

$conceptos = eds_data_query($query, $sys_link, $db_srv);

$renglones= 0;
$registros_por_pagina = 30;
$pagina_nueva = 1;
$pagina_abierta = 0;
$registros_pagina_actual = 0;
$linea_para_final = 25;

$detalle_filas = count($conceptos);

$os_cur_pos = 0;
$os_row_length = 175;
$os_total_rows = ceil(strlen($documento[1]['cadena_original']) / $os_row_length);
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
		
		if ($pagina_abierta == 1)
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
	}
	
	if ($pagina_nueva == 1)
	{
		$pdf->AddPage();
		
		$pdf_html = '';
		
		$pdf_html .= '<cfs FONTSIZE="7"></cfs>';
		
		if (abs($documento[1]['saldo']) < 0.01 AND ($documento[1]['cancelado'] == 1))
		{
			$sello_size = getimagesize('http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_03_sello.jpg');
			$sello_factor = $sello_size[1] / $sello_size[0];
			$sello_width = 300;
			
			$pdf_html .= '<CXY X="55" Y="85"></CXY>';
			$pdf_html .= '<img width="' . (floor($sello_width * $img_factor)) . '" height="' . (floor(($sello_width * $sello_factor) * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_03_sello.jpg">';
		}
        
	if ($documento[1]['cancelado'] == 0)
		{
		    
			$sello_size = getimagesize('http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/cancelado.jpg');
			$sello_factor = $sello_size[1] / $sello_size[0];
			$sello_width = 300;
			
			$pdf_html .= '<CXY X="55" Y="85"></CXY>';
			$pdf_html .= '<img width="' . (floor($sello_width * $img_factor)) . '" height="' . (floor(($sello_width * $sello_factor) * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/cancelado.jpg">';
		}
        
		$pdf_html .= '<CXY X="1" Y="55"></CXY>';
		$pdf_html .= '<img width="' . (floor(850 * $img_factor)) . '" height="' . (floor(34 * $img_factor)) . '" src="http://127.0.0.1/__pro/' . $EDS_CUENTA . '/store/images/facturav3.1_02_detalle.jpg">';
		
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
        
        $pdf_html .= '<td width="70" align="right">';
        $pdf_html .= 'P. Lista';
        $pdf_html .= '</td>';
        $pdf_html .= '<td width="70" align="right">';
        $pdf_html .= 'P. Especial';
        $pdf_html .= '</td>';
        
        $pdf_html .= '<td width="70" align="right">';
        $pdf_html .= 'Importe';
        $pdf_html .= '</td>';
        $pdf_html .= '</tr>';
        $pdf_html .= '</table>';
        
		/*
        $pdf_html .= '<table>';
		$pdf_html .= '<tr>';
		$pdf_html .= '<td width="25">';
		$pdf_html .= 'No.';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="50" align="right">';
		$pdf_html .= 'Cantidad';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="100">';
		$pdf_html .= 'Código';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="280">';
		$pdf_html .= 'Descripción';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="100">';
		$pdf_html .= 'Unidad';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="80" align="right">';
		$pdf_html .= 'P. Unit.';
		$pdf_html .= '</td>';
		$pdf_html .= '<td width="80" align="right">';
		$pdf_html .= 'Importe';
		$pdf_html .= '</td>';
		$pdf_html .= '</tr>';
		$pdf_html .= '</table>';
        */
		$pdf_html .= '</FONT>';
		
		$pdf_html .= '<CXY X="10" Y="63"></CXY>';
		
		$pdf_html .= '<table>';
		
		$pagina_nueva = 0;
		$pagina_abierta = 1;
	}
	
	switch($os_printing)
	{
		case 0:
			if ($conceptos[$i]['tipo'] == 'R')
				$renglones++;
			
            $pdf_html .= '<tr>';
    		$pdf_html .= '<td width="25" valign="top">';
    		$pdf_html .= ($i + ($p * $registros_por_pagina));
    		$pdf_html .= '</td>';
    		$pdf_html .= '<td width="100" valign="top">';
    		$pdf_html .= $conceptos[$i]['codigo'];
    		$pdf_html .= '</td>';
    		$pdf_html .= '<td width="220" valign="top">';
    		$pdf_html .= $conceptos[$i]['nombre'];
    		/*if ($conceptos[$i]['comentarios'] != '' && $conceptos[$i]['comentarios'] != ' ') {
    			$pdf_html .= ' <br />';
    			$pdf_html .= $conceptos[$i]['comentarios'];
    		}*/
    		$pdf_html .= '</td>';
    		$pdf_html .= '<td width="105" align="left" valign="top">';
    		$pdf_html .= strtoupper($conceptos[$i]['presentacion']).' '.number_format($conceptos[$i]['volumen'], 2, '.', ',').' '.$conceptos[$i]['unidad'];
    		$pdf_html .= '</td>';
     	    $pdf_html .= '<td width="70" align="right" valign="top">';
    		$pdf_html .= number_format($conceptos[$i]['cantidad_facturada'], 2, '.', ',');
    		$pdf_html .= '</td>';
    		
            $pdf_html .= '<td width="70" align="right" valign="top">';
    		$pdf_html .= number_format($conceptos[$i]['precio_lista'], 2, '.', ',');
    		$pdf_html .= '</td>';
            $pdf_html .= '<td width="70" align="right" valign="top">';
    		$pdf_html .= number_format($conceptos[$i]['precio_unitario'], 2, '.', ',');
    		$pdf_html .= '</td>';
            
    		$pdf_html .= '<td width="70" align="right" valign="top">';
    		$pdf_html .= number_format($conceptos[$i]['importe'], 2, '.', ',');
    		$pdf_html .= '</td>';
    		$pdf_html .= '</tr>';
            
            /*
			$pdf_html .= '<tr>';
			$pdf_html .= '<td width="25" align="center" valign="top">';
			$pdf_html .= ($conceptos[$i]['tipo'] == 'R')? $renglones:'';
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="50" align="right" valign="top">';
			$pdf_html .= ($conceptos[$i]['tipo'] == 'R')? number_format($conceptos[$i]['cantidad_facturada'], 2, '.', ','):'';
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="100" valign="top">';
			$pdf_html .= $conceptos[$i]['codigo'];
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="280" valign="top">';
			$pdf_html .= $conceptos[$i]['nombre'];
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="100" valign="top">';
			if ($conceptos[$i]['tipo'] == 'R')
			{
				$vol = number_format($conceptos[$i]['volumen'], 2, '.', ',');
				$vol = str_replace('.00', '', $vol);
				
				$pdf_html .= $conceptos[$i]['presentacion'] . ' ';
				$pdf_html .= $vol . ' ';
				$pdf_html .= $conceptos[$i]['unidad_codigo'];
			}
			else
			{
				$pdf_html .= '';
			}
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="80" align="right" valign="top">';
			$pdf_html .= ($conceptos[$i]['tipo'] == 'R')? number_format($conceptos[$i]['precio_unitario'], 2, '.', ','):'';
			$pdf_html .= '</td>';
			$pdf_html .= '<td width="80" align="right" valign="top">';
			$pdf_html .= ($conceptos[$i]['tipo'] == 'R')? number_format($conceptos[$i]['importe'], 2, '.', ','):'';
			$pdf_html .= '</td>';
			$pdf_html .= '</tr>';
			*/
            
			if ($i == count($conceptos))
				$os_printing = 1;
			break;
		case 1:
			$pdf_html .= '</table>';
			
			$pdf_html .= '<cfs FONTSIZE="5"></cfs>';
			
			$pdf_html .= '<table>';
			$pdf_html .= '<tr>';
			$pdf_html .= '<td>';
			$pdf_html .= 'Cadena Original del SAT:';
			$pdf_html .= '</td>';
			$pdf_html .= '</tr>';
			
			$os_printing = 2;
			break;
		default:
			$pdf_html .= '<tr>';
			$pdf_html .= '<td>';
			$pdf_html .= substr($documento[1]['cadena_original'], $os_cur_pos, $os_row_length);
			$pdf_html .= '</td>';
			$pdf_html .= '</tr>';
			
			$os_cur_pos += $os_row_length;
			break;
	}
	
	if ($i >= $detalle_filas && $pagina_abierta == 1)
	{
		$pdf_html .= '</table>';
		
		$pdf_html .= '<cfs FONTSIZE="7"></cfs>';
		
		$pdf_html .= str_replace(
			array(
				'%cur_symbol%'
				,'%doc_importe%'
				,'%doc_impuestos%'
				,'%doc_total%'
			)
			,array(
				'$'
				,number_format($documento[1]['importe'], 2, '.', ',')
				,number_format($documento[1]['impuestos'], 2, '.', ',')
				,number_format($documento[1]['total'], 2, '.', ',')
			)
			,$pdf_footer
		);
		
		$pdf->WriteHTML($pdf_html);
		$pagina_abierta = 0;
	}
}

//Desconectarse al servicio de datos.
include '\xampp\htcore\scripts\\' . $db_srv . '\user_disconnect.php';

$pdf->Output('../edi/' . $documento[1]['ejercicio'] . '/' . $documento[1]['periodo'] . '/' . $documento[1]['serie'] . $documento[1]['folio'] . '.pdf');

//Emitir PDF
if ($_GET['html'] == 1) {
	print $pdf_header;
	print $pdf_html;
	print $pdf_footer;
} else {
	header('Accept-Ranges: bytes');
	$pdf->Output();
}
?>