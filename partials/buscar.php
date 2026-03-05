<?php
	
//Configuración central de sistema.
//include "/connections/config.php";
include '\xampp\htdocs\registro\connections\config.php';

    $salida = "";

    //$query = "SELECT * FROM busqueza_avanzada WHERE Name NOT LIKE '' ORDER By Id_no LIMIT 25";
$resultado = $mysqli->query("SELECT id, nombre FROM arg_organizaciones LIMIT 15") or die(mysqli_error());
    if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);
        $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT id, nombre FROM arg_organizaciones WHERE nombre LIKE '%".strip_tags($q)."%'") or die(mysqli_error());
    }

   /* if ($resultado->num_rows>0) {
    	$salida.="<table class='tabla_datos'>
    			<thead>
    				<tr id='titulo'>
    					
    					
    					
    				</tr>

    			</thead>
    			

    	<tbody>";

    	while ($fila = $resultado->fetch_assoc()) {
 	   
    		$salida.="<tr>
                        <td id='nombre_empresa' width=100%>".$fila['nombre']."</td>
                        
    				</tr>";

    	}
    	$salida.="</tbody></table>";
    }


    echo $salida;*/
    
if ($resultado->num_rows > 0) {
  // echo 'entro';
   while ($row = $resultado->fetch_assoc()) {
  // while ($row = $result->fetch_assoc()) {  
        //echo 'entro';
        $html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'" id="id'.$row['org_id'].'">'.utf8_encode($row['nombre']).'</a></div>';
        //'<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'">'.utf8_encode($row['nombre']).'</a></div>';
   //     $html .= '<div><a class="suggest-element" data="'.utf8_encode($nombre_empr['nombre']).'"></a></div>';
    
    }
}
echo $html;


?>