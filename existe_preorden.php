<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$unidad_id_sel = $_POST['unidad_id_s'];
$banco_sel     = $_POST['banco_s'];
$voladura_sel     = $_POST['voladura_s'];
     
if (isset($banco_sel)){
         $resultado = $mysqli->query("SELECT COUNT(*) as existe
                                      FROM arg_preordenes pre
                                      WHERE 
                                     	  pre.unidad_id = ".$unidad_id_sel."
                                          AND pre.banco_id = ".$banco_sel."
                                          AND pre.voladura_id = ".$voladura_sel
                                    ) or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
   while ($row = $resultado->fetch_assoc()) {  
        $existe =($row['existe']);  
        if ($existe > 0){
            $html .= "existe"; 
        }
    }
}

echo $html;
?>