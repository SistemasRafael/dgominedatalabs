<? include "connections/config.php"; ?>
<?php
$html = "";
//$unidad_id = $_SESSION['unidad_id'];
//$cons = $_GET['consulta'];
$q = ($_POST['consulta']);
//  echo $q;
  //  if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);
       // $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT orden FROM listado_ordenes_trabajo
                                    WHERE orden LIKE '%".strip_tags($q)."%'") 
                     OR DIE(mysqli_error());
 //  }

if ($resultado->num_rows > 0) {
    $html.="<div class='ordenes'>
            <table class='ordenes'>
    	          <tbody>";    
    while ($row = $resultado->fetch_assoc()) {
       //echo $row['orden'];
       $html .= '<div>
                        <a class="suggest-element" data="'.utf8_encode($row['orden']).'" id="id'.$row['orden'].'">'.utf8_encode($row['orden']).'</a>
                 </div>';
      // $html .= '<a class="suggest-element" id="'.$row['orden'].'" data='.($row['orden']).'>'.$row['orden'].'</a></td>';
       //$html .= '</td></tr>';
    
    }
    $html .= '</tbody></table></div>';
}
echo $html;
/*<a class="suggest-element" data="'.utf8_encode($row['orden']).'" id="id'.$row['trn_id'].'" onclick="cargar_orden('.$row['trn_id'].",".$unidad_id.')">'.utf8_encode($row['folio_inicial']).'</a>'.'</td>';
       $html .= '</tr>';*/
?>