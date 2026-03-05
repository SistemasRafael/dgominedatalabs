<? include "connections/config.php";
$fecha_inicial = $_POST['fecha_inicial_ex'];
$fecha_final = $_POST['fecha_final_ex'];

$fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
$fecha_final = date("d-m-Y", strtotime($fecha_final));

$query = "CALL arg_rpt_ResultadosMuestrasGeologia('', '" . $fecha_inicial . "', '" . $fecha_final . "')";
mysqli_multi_query($mysqli, $query);
$result = $mysqli->store_result();

$html_det = "<table class='table table-striped' id='motivos'>
    <thead>
        <tr class='table-info'>
            <th colspan='7'>Ordenes de trabajo: " . $unidad_mina . "</th>
            <th align='center' colspan='2'></th>
            <th></th>
        </tr>
        <tr class='table-info' justify-content: center;>
            <th scope='col1'>No.</th>
            <th scope='col1'>FOLIO</th>
            <th scope='col1'>BANVOL</th>
            <th scope='col1'>FECHA</th>
            <th scope='col1'>ORO</th>
            <th scope='col1'>PLATA</th>
            <th scope='col1'>FECHA RES ORO</th>
            <th scope='col1'>HORA RES ORO</th>
            <th scope='col1'>FECHA RES PLATA</th>
            <th scope='col1'>HORA RES PLATA</th>";
$html_det = "";

$num = 1;
while ($fila = $result->fetch_row()) {
    $html_det .= "<tr>";
    $html_det .= "<td>" . $num . "</td>";
    $html_det .= "<td>" . $fila[0] . "</a></td>";
    $html_det .= "<td>" . $fila[1] . "</td>";
    $html_det .= "<td>" . $fila[2] . "</td>";
    $html_det .= "<td>" . $fila[3] . "</td>";
    $html_det .= "<td>" . $fila[4] . "</td>";
    $html_det .= "<td>" . $fila[5] . "</td>";
    $html_det .= "<td>" . $fila[6] . "</td>";
    $html_det .= "<td>" . $fila[7] . "</td>";
    $html_det .= "<td>" . $fila[8] . "</td>";
    $html_det .= "</tr>";
    $num = $num + 1;
}
echo ("$html_det");
