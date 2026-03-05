<?php

require_once("connections/config.php");
//$veh_id = 2;
 //echo 'entro';
function datos_vehic($veh_id){
	 
      if (isset($veh_id)){
            //echo 'entro2';
        $resultado = $mysqli->query("SELECT veh_id, placas, marca FROM arg_vehiculos WHERE veh_id=3");
        if ($resultado->num_rows > 0) {                                            
     	      while ($fila = $resultado->fetch_assoc()) {                                             	   
      		  //$_SESSION['marca_id']= $fila['marca']; 
              $array = '<a id='marca_id' value='.$fila['marca']).'></a>'
              //$array = array('marca' => $fila['marca'][0]); 
              //$array = array('marca' => $fila['marca']); 
         }
        //var_dump ($array);
         //$nombre_usuario = $array['nombre']." ".$array['last'];
        // $apellido_usuario = $usuario['last'];
       // echo $nombre_usuario;
        // var_dump ($nombre_usuario);
         //var_dump ($apellido_usuario);       
      // (die);
   	    }
       }
       else{ 
         	$array=0;
       } 
                            //  (die);    
	 return ($array);
} 
?>
