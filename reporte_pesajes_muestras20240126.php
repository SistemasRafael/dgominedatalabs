<? include "connections/config.php";
$metodo_id = $_POST['metodo_sel'];
$orden = $_POST['orden'];
$tipo_id = $_POST['tipo_mues'];
$fecha_i = $_POST['fecha_i'];
$fecha_f = $_POST['fecha_f'];

$query = "CALL arg_rpt_pesajesMuestras(" . $tipo_id . ", " . $orden . ", " . $metodo_id. ", '" . $fecha_i . "', '" . $fecha_f   . "')";
mysqli_multi_query($mysqli, $query);
$result = $mysqli->store_result();

$html_det = "";

$num = 1;
while ($fila = $result->fetch_row()) {
    $html_det .= "<tr>";
    $html_det .= "<td>" . $num . "</td>";
    $html_det .= "<td>" . $fila[0] . "</td>";
    $html_det .= "<td>" . $fila[1] . "</td>";
    $html_det .= "<td>" . $fila[2] . "</td>";
    //$html_det .= "<td>" . $fila[3] . "</td>";
    $html_det .= "<td>" . $fila[4] . "</td>";
    $html_det .= "<td>" . $fila[5] . "</td>";
    $html_det .= "<td>" . $fila[6] . "</td>";
    $html_det .= "<td>" . $fila[7] . "</td>";
    $html_det .= "<td>" . $fila[8] . "</td>";
    $html_det .= "<td>" . $fila[9] . "</td>";
    $html_det .= "<td>" . $fila[10] . "</td>";
    $html_det .= "<td>" . $fila[11] . "</td>";
    $html_det .= "<td>" . $fila[12] . "</td>";
    $html_det .= "<td>" . $fila[13] . "</td>";
    $html_det .= "<td>" . $fila[14] . "</td>";
    $html_det .= "<td>" . $fila[15] . "</td>";
    $html_det .= "<td>" . $fila[16] . "</td>";
    $html_det .= "<td>" . $fila[17] . "</td>";
    $html_det .= "</tr>";
    $num = $num + 1;
}

echo ("$html_det");
