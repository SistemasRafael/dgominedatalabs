<?php
include '\xampp\htdocs\registro\connections\config.php';
/*

$html   = '';
$placa  = $_POST['placa'];
$marca  = $_POST['marca'];
$modelo = $_POST['modelo'];
$color  = $_POST['color'];
$poliza = $_POST['poliza'];
$expira_veh = $_POST['expira_veh'];
$path_pol = $_POST['path_pol'];
//$var_tmp = $_POST['var']
$org_id = $_SESSION['org_id'];

$id_max_ve = $mysqli->query("SELECT max(veh_id) FROM arg_vehiculos");
        $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    	$id_max_veh = $id_maximo['max(veh_id)'];
        $id_max_veh = $id_max_veh+1;
 
$rfc_org = $mysqli->query("SELECT rfc FROM arg_organizaciones WHERE org_id = ".$org_id);
        $rfc_org1 = $rfc_org->fetch_array(MYSQLI_ASSOC);
    	$rfc_org2 = $rfc_org1['rfc'];
        $rfc = $rfc_org2;
 
$placa_dup = $mysqli->query("SELECT placas FROM arg_vehiculos WHERE placas = '".$placa."'");
        $placa_dupli = $placa_dup->fetch_array(MYSQLI_ASSOC);
    	$placa_duplic = $placa_dupli['placas'];
        $placa_duplicada = $placa_duplic;*/

//Documento poliza importar
/*if ($placa == '' || $marca == '' || $modelo == '' || $poliza == '' || $expira_veh == '' || $path_pol == '' ){
    $html = 'Error: Debe capturar toda la información.';
}
else
{
    if ($placa == $placa_duplicada){
        $html = 'Error: Esa placa se encuentra duplicada. Reintente por favor';
    }
    else{*/
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $uploadDir = 'upload/teste';
         if (file_exists($uploadDir) <> 'true')
                { mkdir ($uploadDir); }
        //$uploadDir = 'upload/vehiculos/'.$rfc;                        
       /* if (file_exists($uploadDir) <> 'true')
        { 
            umask(0);
            mkdir ($uploadDir, 0777); 
        }*/
                                       
        $filePath = $uploadDir.'/';
        $fileName = $_FILES['file']['name'];//$path_pol;
        $tmpName  = $_FILES['file']['tmp_name'];
        $moverarchivo = $filePath.$fileName;
        echo $tmpName;
        echo $moverarchivo;
        $result = move_uploaded_file($tmpName, $moverarchivo);
        if (!$result) {
            echo "Error al intentar subir archivo";
            exit;
        }
       }
        die();
       
         /*   $filePath = $uploadDir;
                                 
            $fileName = $_FILES['poliza_img']['name'];
            $tmpName  = $_FILES['poliza_img']['tmp_name'];
            $fileSize = $_FILES['poliza_img']['size'];
            $fileType = $_FILES['poliza_img']['type'];
           
            unlink ($archivo_eliminar); 
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }*/
              
                                
                               
       // if (isset($placa)){
    
            /*$query = "INSERT INTO arg_vehiculos (veh_id, placas, marca, modelo, color, poliza, org_id, path, fecha_expira ) ".
                     "VALUES ($id_max_veh, '$placa', '$marca', '$modelo', '$color', '$poliza', $org_id, '$tmpName', '$expira_veh')";
                                
             $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
             $resultado = $mysqli->query("SELECT veh_id, placas, marca FROM arg_vehiculos WHERE org_id = ".$org_id." ORDER BY veh_id DESC") or die(mysqli_error());*/
       // }      
      /*  if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {  
            $nombre =($row['placas']);        
            $nomenclatura = $row['veh_id'];
            $html .= 'echo ("<option value='.$nomenclatura.'>'.$nombre.'</option>")';    
            }
        }*/
 //   }
//}
//echo $html;

?>