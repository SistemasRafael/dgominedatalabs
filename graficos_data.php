<?php
include "connections/config.php";
$hoy = date("Y-m-d"); 
//echo $hoy;

        $datos_est = $mysqli->query("SELECT un.nombre AS mina, 10 AS cantidad                     
                                    FROM
                                	 arg_empr_unidades un
                                  ");
    //$result->query($query);
   // $result =  $datos_est->fetch_assoc();
    
    $data = array();
foreach ($datos_est as $row) {
        $data[] = $row;
}

//mysqli_close($conn);

echo json_encode($data);
			        
                            	//while ($fila = $datos_v->fetch_assoc()) {}
                
    ///echo "<script> dash(".$unidad_id.");</script>"; 
   
              
?>
