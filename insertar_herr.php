<?php
include '\xampp\htdocs\registro\connections\config.php';

$html   = '';
$nombre_h  = $_POST['nombre_h'];
$marca_h  = $_POST['marca_h'];
$modelo_h = $_POST['modelo_h'];
$serie_h  = $_POST['serie_h'];
$org_id_h = $_SESSION['org_id'];

$id_max_herr = $mysqli->query("SELECT max(herr_id) FROM arg_herramientas");
        $id_maximoh = $id_max_herr->fetch_array(MYSQLI_ASSOC);
    	$id_max_herr = $id_maximoh['max(herr_id)'];
        $id_max_herr = $id_max_herr+1;
     
if (isset($nombre_h)){
        

        $query = "INSERT INTO arg_herramientas(herr_id, org_id, nombre, marca, modelo, serie ) ".
                 "VALUES ($id_max_herr, '$org_id_h', '$nombre_h', '$marca_h', '$modelo_h', '$serie_h')";
                            
         $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
         $resultado_h = $mysqli->query("SELECT herr_id, nombre FROM arg_herramientas WHERE herr_id = ".$id_max_herr) or die(mysqli_error());
  }      
if ($resultado_h->num_rows > 0) {
   while ($row = $resultado_h->fetch_assoc()) {  
        $nombre_he =($row['nombre']);        
        $herr_id = $row['herr_id'];
        $html .= 'echo ("<option value='.$herr_id.'>'.$nombre_he.'</option>")';    
    }
}

echo $html;
?>