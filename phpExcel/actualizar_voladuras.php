<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$banco  = $_POST['banco'];
$unidad_id = $_POST['unidad_id'];
     
if (isset($banco)){
         $resultado = $mysqli->query("SELECT voladura_id 
                                      FROM arg_bancos_voladuras vol
                                      LEFT JOIN arg_bancos ban
                                     	  ON ban.banco_id = vol.banco_id
                                      WHERE 
                                     	  ban.unidad_id = ".$unidad_id."
                                          AND vol.banco_id = ".$banco
                                    ) or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
    $idtop = 0;
    $nombretop = 'Seleccione';
    $html .= ("<option value=$idtop>$nombretop</option>"); 
   while ($row = $resultado->fetch_assoc()) {  
        $voladura_id =($row['voladura_id']);  
        $html .= ("<option value=$voladura_id>$voladura_id</option>"); 
    }
}

echo $html;
?>