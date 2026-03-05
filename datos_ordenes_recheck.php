<? include "connections/config.php"; ?>
<?php

$fecha_inicial = $_POST['fecha_inicial_ex'];
$fecha_final = $_POST['fecha_final_ex'];
$orden_recheck = $_POST['orden'];
$muestra = $_POST['tipo_mues'];

$query = "CALL arg_rpt_ordenesRecheck('" . $fecha_inicial . "', '" . $fecha_final . "', " . $orden_recheck . ", '" . $muestra . "')";
mysqli_multi_query($mysqli, $query);
$html_det = "";
$result = $mysqli->store_result();

$num = 1;
while ($fila = $result->fetch_row()) {
    $html_det .= "<tr>";
    $html_det .= "<td>" . $num . "</td>";
    $html_det .= "<td>" . $fila[0] . "</td>";
    $html_det .= "<td>" . $fila[1] . "</td>";
    $html_det .= "<td>" . $fila[3] . "</td>";
    $html_det .= "<td>" . $fila[4] . "</td>";
    $html_det .= "<td>" . $fila[5] . "</td>";

    $html_det .= "<td>" . $fila[6] . "</td>";
    $html_det .= "<td>" . $fila[7] . "</td>";
    $html_det .= "<td>" . $fila[8] . "</td>";

    $html_det .= "<td>" . $fila[9] . "</td>";
    $html_det .= "</tr>";
    $num = $num + 1;
}

echo $html_det;
