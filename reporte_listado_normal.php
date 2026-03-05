<? include "connections/config.php";
$unidad_id = $_POST['unidad_id'];
$fecha_inicial = $_POST['fecha_inicial_ex'];
$fecha_final = $_POST['fecha_final_ex'];
$metodo_id = $_POST['metodo_id'];
$orden = $_POST['orden'];
$muestra = $_POST['muestra'];
$tipo_id = $_POST['tipo_id'];


$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final = date("Y-m-d", strtotime($fecha_final));

$query = "CALL arg_rpt_listadoNormal(" . $unidad_id . ", '" . $fecha_inicial . "', '" . $fecha_final . "', " . $metodo_id . ", '" . $orden . "', '" . $muestra . "' ," . $tipo_id . ")";
mysqli_multi_query($mysqli, $query);
$result = $mysqli->store_result();

$html_det = "";

$num = 1;
while ($fila = $result->fetch_row()) {
    $html_det .= "<tr>";
    $html_det .= "<td>" . $num . "</td>";
    $html_det .= "<td> <a href='orden_trabajo_lis.php?trn_id=" . $fila[5] . "' target='_blank'>" . $fila[3] . "</a></td>";
    $html_det .= "<td>" . $fila[0] . "</td>";
    $html_det .= "<td>" . $fila[1] . "</td>";
    $html_det .= "<td>" . $fila[2] . "</td>";
    $html_det .= "<td>" . $fila[5] . "</td>";
    $html_det .= "<td>" . $fila[7] . "</td>";
    $html_det .= "<td>" . $fila[8] . "</td>";
    $html_det .= "<td>" . $fila[11] . "</td>";
    $html_det .= "<td>" . $fila[9] . "</td>";
    $html_det .= "<td>" . $fila[12] . "</td>";
    $html_det .= "</tr>";
    $num = $num + 1;
}

echo ("$html_det");
