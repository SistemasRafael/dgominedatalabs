<? include "connections/config.php"; ?>
<?php

    if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);
        $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT orden
                                     FROM `listado_ordenes_trabajo` 
                                     WHERE orden LIKE '%".strip_tags($q)."%'") 
                              OR DIE(mysqli_error());
    }

if ($resultado->num_rows > 0) {
    $html.="<div id='ordenes'> 
            <table>
  	           <tbody>";    
    while ($row = $resultado->fetch_assoc()) {
       $html .= '<div>
                    <a class="suggest-element" data="'.utf8_encode($row['orden']).'" id="id'.$row['orden'].'">'.utf8_encode($row['orden']).'</a>
                </div>';      
    }
    $html .= '</tbody></table></div>';
}
echo $html;
?>