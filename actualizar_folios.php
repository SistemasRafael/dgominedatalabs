<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$banco  = $_POST['banco'];
$voladura  = $_POST['voladura_id'];
$unidad_id = $_POST['unidad_id'];
     
if (isset($banco)){
         $resultado = $mysqli->query("SELECT LPAD(vol.folio_actual, 3,'0') AS folio_actual, vol.banco, LPAD(vol.voladura_id, 3,'0') AS voladura_id
                                      FROM arg_bancos_voladuras vol
                                      LEFT JOIN arg_bancos ban
                                     	  ON ban.banco_id = vol.banco_id
                                      WHERE 
                                     	  ban.unidad_id = ".$unidad_id."
                                          AND vol.banco_id = ".$banco."
                                          AND vol.voladura_id = ".$voladura
                                    ) or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
    $idtop = 0;
    //$nombretop = 'Seleccione';
    //$html .= //("<option value=$idtop>$nombretop</option>"); 
   while ($row = $resultado->fetch_assoc()) {  
        $folio_act =($row['banco'].$row['voladura_id'].$row['folio_actual']);  
        $html .= $folio_act;//("<option value=$voladura_id>$voladura_id</option>"); 
    }
}

echo $html;
?>