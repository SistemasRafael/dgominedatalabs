<?include "connections/config.php";?>
<?php
$html = '';
$metodo_id = $_POST['metodo_id'];

if (isset($metodo_id)){
 $mysqli -> set_charset("utf8");   
    $html .="<table class='table table-striped' id='materiales'>
    			  <thead>
    				<tr class='table-secondary' justify-content: center; id='titulo'>
                        <th scope='col1'>Tipo Control</th>
                        <th scope='col1'>Nombre</th>
    				</tr>
    			  </thead>
    	          <tbody>";
    
        $resultado = $mysqli->query("SELECT * FROM metodos_controles
                                     WHERE activo = 1 AND metodo_id = ".$metodo_id." ORDER BY tipo_control") or die(mysqli_error());
        
        if ($resultado->num_rows > 0) {
            while ($res = $resultado->fetch_assoc()) {
                $html.="<tr>";
                    $html.="<td>".$res['tipo_Control']."</td>";
                    $html.="<td>".$res['nombre']."</td>";
                $html.="</tr>";
                }
        }
    	$html.="</tbody></table>";      
}
echo utf8_encode($html);
//die();
?>