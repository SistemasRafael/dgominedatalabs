<? include "connections/config.php";

$ejercicio = $_POST['ejercicio'];
$mes = $_POST['mes'];
$metodo = $_POST['metodo'];
$unidadmina = $_POST['mina'];


switch ($metodo){
    case 3: $nombre_metodo = 'Au';
    break;
    case 6: $nombre_metodo = 'Ag';
    break;
}

$datos_bancos_detalle = $mysqli->query("CALL arg_rpt_blancos($unidadmina,$ejercicio, $mes, $metodo)");
$html_det = "";

    $html_det .= "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>  
                                        <th colspan='1' scope='1'></th>           
                                        <th colspan='2' scope='col2'>Blanco Reactivo</th>  
                                        <th colspan='2' scope='col2'>Muestra Precedente</th>     
                                        <th colspan='1' scope='col1'></th> 
                                        <th colspan='2' scope='col2'  bgcolor='#bfb604'>Blanco Preparado</th>                                         
                                        <th colspan='2' scope='col2'  bgcolor='#bfb604'>Muestra Precedente</th>";
    $html_det .= "</tr>";
    $html_det .= "<tr class='table-warning' justify-content: center;> 
                            <th scope='col1'>Fecha Recepción</th>
                            <th scope='col1' bgcolor='#72bbe4'>Id Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1' bgcolor='#72bbe4'>Ley $nombre_metodo (ppm)</th>                            
                            <th scope='col1' bgcolor='#72bbe4'>Fecha Recepción</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                        </tr>
                       
                               </thead>
                               <tbody>";

    while ($fila = $datos_bancos_detalle->fetch_assoc()) {
        $num = 1;
       
        $html_det .= "<tr>";
        $html_det .= "<td colspan='1'>" . $fila['fecha_orden'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_reactivo'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['br_resultado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_reactivo_prec'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['brpre_resultado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['fecha_orden'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado_resultado'] . "</td>";        
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado_prec'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['pre_res_preparado'] . "</td>";
        $html_det .= "</tr>";
    }

    $html_det .= "</tbody></table></div></div>";
echo utf8_encode("$html_det");
