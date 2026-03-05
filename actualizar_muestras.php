<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$preorden_sel  = $_POST['preorden'];
$unidad_id = $_POST['unidad_id'];
$contador = 1;//$_POST['unidad_id'];
if($unidad_id == 2){
    $preorden_sel = '9'.$preorden_sel;    
}
      
if (isset($preorden_sel)){
         $resultado = $mysqli->query("SELECT trn_id, folio
                                      FROM 
                                        arg_ordenes_muestras mu
                                      WHERE 
                                     	  mu.folio LIKE '".$preorden_sel."%'
                                          AND mu.tipo_id = 0
                                      ORDER BY mu.folio"
                                    ) or die(mysqli_error());
  }    
if ($resultado->num_rows > 0) {
 
   while ($row = $resultado->fetch_assoc()) {  
        $folio_act   = $row['folio'];
        $trn_muestra = $row['trn_id'];  
        $html .= ("<label for=\"$trn_muestra\"><input id=\"$folio_act\" type=\"checkbox\" value=\"$folio_act\" />$folio_act</label>");
        // ("<option value=$trn_muestra>$folio_act</option>"); 
    }
    //$html .= "</div>";
}

echo $html;
?>