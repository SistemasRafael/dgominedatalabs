<? include "connections/config.php"; ?>
<?php

    if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);
        $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT folio AS muestra 
                                     FROM `arg_ordenes_muestras` 
                                     WHERE tipo_id = 0 AND folio LIKE '%".strip_tags($q)."%'") 
                              OR DIE(mysqli_error());
    }

if ($resultado->num_rows > 0) {
    $html.="<div id='datos_muestra'> 
            <table>
  	           <tbody>";    
    while ($row = $resultado->fetch_assoc()) {
       $html .= '<div>
                    <a class="suggest-element" data="'.utf8_encode($row['muestra']).'" id="id'.$row['muestra'].'">'.utf8_encode($row['muestra']).'</a>
                </div>';      
    }
    $html .= '</tbody></table></div>';
}
echo $html;
?>