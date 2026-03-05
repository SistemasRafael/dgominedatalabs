<?php
	
//Configuración central de sistema.
include "connections/config.php";

    $salida = "";
    $unidad_id = $_SESSION['unidad_id'];
    //$cons = $_GET['consulta'];

    if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);
        $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT folio_inicial, od.trn_id
                                     FROM arg_ordenes o
                                     LEFT JOIN arg_ordenes_detalle od
                                        ON o.trn_id = od.trn_id_rel
                                     WHERE unidad_id = ".$unidad_id." AND folio_inicial LIKE '%".strip_tags($q)."%'") or die(mysqli_error());
    }

if ($resultado->num_rows > 0) {
    $html.="<table class='tabla_datos'>
    	          <tbody>";    
    while ($row = $resultado->fetch_assoc()) {
       $html .= '<tr> <div> <td width=20%> <a class="suggest-element" data="'.utf8_encode($row['folio_inicial']).'" id="id'.$row['trn_id'].'" onclick="cargar_orden('.$row['trn_id'].",".$unidad_id.')">'.utf8_encode($row['folio_inicial']).'</a>'.'</td>';
       $html .= '</tr>';
    
    }
    $html .= '</tbody></table></br></br></br></br>';
}
echo $html;

?>