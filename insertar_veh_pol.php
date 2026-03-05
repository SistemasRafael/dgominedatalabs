<?php
include '\xampp\htdocs\registro\connections\config.php';


//$html   = '';


 

//Documento poliza importar
/*if ($placa == '' || $marca == '' || $modelo == '' || $poliza == '' || $expira_veh == '' || $path_pol == '' ){
    $html = 'Error: Debe capturar toda la información.';
}
else
{
   
    */
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $placa  = $mysqli->real_escape_string($_POST['placa']);
        $marca  = $mysqli->real_escape_string($_POST['marca']);
        $modelo = $mysqli->real_escape_string($_POST['modelo']);
        $color  = $mysqli->real_escape_string($_POST['color']);
        $poliza = $mysqli->real_escape_string($_POST['poliza']);
        $expira_veh = $mysqli->real_escape_string($_POST['expira_veh']);
        $org_id = $_SESSION['org_id'];
        
        $id_max_ve = $mysqli->query("SELECT max(veh_id) FROM arg_vehiculos");
        $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    	$id_max_veh = $id_maximo['max(veh_id)'];
        $id_max_veh = $id_max_veh+1;
        
        $placa_dup = $mysqli->query("SELECT placas FROM arg_vehiculos WHERE placas = '".$placa."'");
        $placa_dupli = $placa_dup->fetch_array(MYSQLI_ASSOC);
    	$placa_duplic = $placa_dupli['placas'];
        $placa_duplicada = $placa_duplic;
        
        $rfc_org = $mysqli->query("SELECT rfc FROM arg_organizaciones WHERE org_id = ".$org_id);
        $rfc_org1 = $rfc_org->fetch_array(MYSQLI_ASSOC);
    	$rfc_org2 = $rfc_org1['rfc'];
        $rfc = $rfc_org2;
        
       if ($placa == $placa_duplicada){
            echo 'Error: La placa se encuentra duplicada. Reintente por favor';
       }
        else{
            $uploadDir = 'upload/vehiculos/'.$rfc;
             if (file_exists($uploadDir) <> 'true')
             { 
                    umask(0);
                    mkdir ($uploadDir); 
             }
                              
            $filePath = $uploadDir.'/';
            $fileName = $_FILES['file']['name'];
            $tmpName  = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $moverarchivo = $filePath.$fileName;
          
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
            else{
                
                $query = "INSERT INTO arg_vehiculos (veh_id, placas, marca, modelo, color, poliza, org_id, path, fecha_expira ) ".
                         "VALUES ($id_max_veh, '$placa', '$marca', '$modelo', '$color', '$poliza', $org_id, '$moverarchivo', '$expira_veh')";
                                    
                 $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                 $resultado = $mysqli->query("SELECT veh_id, placas, marca FROM arg_vehiculos WHERE org_id = ".$org_id." ORDER BY veh_id DESC") or die(mysqli_error());
                 
                if ($resultado->num_rows > 0) {
                    echo 'Se agregó correctamente el vehículo';
                    while ($row = $resultado->fetch_assoc()) {  
                        $nombre =($row['placas']);        
                        $nomenclatura = $row['veh_id'];
                        $html .= 'echo ("<option value='.$nomenclatura.'>'.$nombre.'</option>")';    
                    }
                }
            }
        }
     }
         
//echo $html;

?>