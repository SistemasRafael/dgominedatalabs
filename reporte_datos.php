<? include "connections/config.php";
$fecha_inicial = $_POST['fecha_inicial_ex'];
$fecha_final = $_POST['fecha_final_ex'];

$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final = date("Y-m-d", strtotime($fecha_final));
$tipo_id = $_POST['tipo_id'];

$query = "CALL arg_rpt_reporteGeosql('$fecha_inicial','$fecha_final', $tipo_id)";
mysqli_multi_query($mysqli, $query);
$result = $mysqli->store_result();

$html_det = "";

$num = 1;
while ($fila = $result->fetch_row()) {
    $html_det .= "<tr>";
    $html_det .= "<td>" . $num . "</td>";
    $html_det .= "<td>" . $fila[2] . "</a></td>";
    $html_det .= "<td>" . $fila[3] . "</td>";
    $html_det .= "<td>" . $fila[4] . "</td>";
    $html_det .= "</tr>";
    $num = $num + 1;
}
echo ("$html_det");
