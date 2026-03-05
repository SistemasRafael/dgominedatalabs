<?php include "connections/config.php";
$mysqli -> set_charset("utf8");
$et = $mysqli->query(
    "SELECT DISTINCT nombre as nombre, etapa_id as etapa FROM arg_etapas"
) or die(mysqli_error($mysqli));
$array = array();
while ($fila = $et->fetch_row()){
    $array[$fila[1]] = $fila[0];
}
$datos = $mysqli->query(
    "CALL arg_rpt_ordenesReensaye(0,0)"
) or die(mysqli_error($mysqli));

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#motivos').DataTable({
        pagingType: 'full_numbers',
    });
});
</script>

<style>
@import url("https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css");

.tabla {
    display: grid;
    justify-content: center;
    align-items: center;
    padding-top: 5vmin;
    padding-left: 5vmin;
}

.tabla table{
    border: 2px;
    border-color: black;
}

</style>

<div class="container-fluid">
    <div class="container-fluid">
        <div id="datos-listado" class="tabla">
            <br /><br /><br /><br />
            <?
            $html_det = "<table class='table table-striped' id='motivos'>
                    <thead>                                
                         <tr class='table-info'>      
                            <th>Ordenes de trabajo" . $unidad_mina . "</th>     
                            <th></th>
                         </tr>
                        <tr class='table-info' justify-content: center;>     
                            <th scope='col1'>FECHA</th>
                            <th scope='col1'>ORDEN ORIGINAL</th>
                            <th scope='col1'>METODO</th>                                        
                            <th scope='col1'>ORDEN PADRE</th>
                            <th scope='col1'>ORDEN ENSAYE</th>
                            <th scope='col1'>ETAPA</th>                                        
                            <th scope='col1'>FOLIO MUESTRA</th>
                            <th scope='col1'>FOLIO INTERNO</th>
                            <th scope='col1'>ABSORCION</th>
                            <th scope='col1'>REENSAYE</th>
                            <th scope='col1'>REENSAYE MOTIVO</th>";
            $html_det .= "</tr>
                   </thead>
                   <tbody>";
            $num = 1;
            while ($fila = $datos->fetch_row()) { 
                $html_det .= "<tr>";
                    $html_det .= "<td>" . $fila[1] . "</td>";
                    //$html_det .= "<td>" . $fila[2] . "</td>";
                    $html_det .= "<td>" . $fila[4] . "</td>";
                    $html_det .= "<td>" . $fila[5] . "</td>";
                    $html_det .= "<td>" . $fila[6] . "</td>";
                    $html_det .= "<td>" . $fila[7] . "</td>";
                    $html_det .= "<td>" . $array[$fila[8]] . "</td>";
                    $html_det .= "<td>" . $fila[9] . "</td>";
                    $html_det .= "<td>" . $fila[10] . "</td>";
                    $html_det .= "<td>" . $fila[11] . "</td>";
                    $html_det .= "<td>" . $fila[12] . "</td>";
                    $html_det .= "<td>" . $fila[13] . "</td>";
                $html_det .= "</tr>";
            }
            $html_det .= "</tbody></table>";
            echo ("$html_det");
            ?>
        </div>
    </div>
</div>