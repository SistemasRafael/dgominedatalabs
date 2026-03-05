<?include "connections/config.php";?>

<!--<link href="http://192.168.20.3:81/__pro/argonaut/boostrapp/css/check.css" rel="stylesheet">--!>
<link href="http://192.168.20.22/MineData-Labs/css/check.css" rel="stylesheet"> 
<?php
$html = '';
$trn_id = $_POST['trn_id'];
$u_id = $_SESSION['u_id'];
$hoy = date('d/m/Y');

if (isset($trn_id)){
 $mysqli -> set_charset("utf8");
 
    $limite = 100;
    $existen_humedad = $mysqli->query("SELECT * FROM arg_muestras_humedad se WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
                
     if ($existen_humedad->num_rows == 0) {
            $resultado_mues = $mysqli->query("SELECT
                                                 folio AS muestra
                                                ,trn_id_rel
                                                ,trn_id as trn_muestra                                                  
                                            FROM 
                                                arg_ordenes_muestras
                                            WHERE tipo_id = 0 AND trn_id_rel = ".$trn_id."
                                            ORDER BY folio")   or die(mysqli_error());
                 $html =  "<div class='col-md-12 col-lg-12'>
                           <table class='table text-black' id='tabla_humedad'>
                                <thead class='thead-light'>
                                <tr class = 'table-info' >
                                    <th colspan='1'>No.</th>
                                    <th colspan='5'>Muestra</th>
                                    <th colspan='5'>Peso Húmedo KG</th>
                                <tbody>";
                 while ($res_muestras = $resultado_mues->fetch_assoc()) {
                        $con = $con+1;
                        $trn_id_muestra = $res_muestras['trn_muestra'];   
                        
                        $query = "INSERT INTO arg_muestras_humedad (trn_id, trn_id_rel, peso_humedo, peso_seco, porcentaje, comentario, u_id_ph, fecha_ph, u_id, fecha)".
                                                               "VALUES ($trn_id, $trn_id_muestra, 0, 0, 0,'', $u_id, '$hoy', $u_id, '')";
                        $mysqli->query($query);
                                 
                        $html.="<tr>
                                    <td>".$con."</td>
                                    <td style='display:none;'> <input type='input' id='trn_batch".$con."' value='".$res_muestras['trn_id_batch']."' /></td>
                                    <td style='display:none;'> <input type='input' id='trn_rel".$con."' value='".$res_muestras['trn_id_rel']."' /></td>
                                    <td colspan='5'>".$res_muestras['muestra']."</td>                                            
                                    <td colspan='5'>  <input type='number' name='peso_hum".$con."' id='peso_hum".$con."' class='form-control' /> </td>                                    
                                    <td> <button type='button'class='btn btn-info' id='boton_save_hum' onclick='humedad_peso_guardar(".$trn_id.", ".$trn_id_muestra.", ".$con.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                    </button></td>
                                </tr>";
                    }
      }
      else{
            $resultado_peso_hum = $mysqli->query(" SELECT
                                                   se.trn_id as trn_id_batch
                                                  ,se.trn_id_rel AS trn_muestra
                                                  ,ROUND(se.peso_humedo, 2) AS peso_humedo
                                                  ,ROUND(se.peso_seco, 2) AS peso_seco
                                                  ,ROUND(se.porcentaje, 2) AS porcentaje
                                                  ,om.folio AS muestra
                                              FROM 
                                                  arg_muestras_humedad se
                                                  LEFT JOIN arg_ordenes_muestras om
                                                        ON se.trn_id = om.trn_id_rel
                                                        AND se.trn_id_rel = om.trn_id
                                              WHERE se.trn_id = ".$trn_id." AND peso_humedo = 0
                                            ORDER BY folio")   or die(mysqli_error());
        
            if ($resultado_peso_hum->num_rows > 0) {                
                $html =  "<div class='col-md-12 col-lg-12'>
                           <table class='table text-black' id='tabla_humedad'>
                                <thead class='table-info'>
                                <tr class = 'table-info' >
                                    <th colspan='1'>No.</th>
                                    <th colspan='5'>Muestra</th>
                                    <th colspan='5'>Peso Húmedo KG</th>
                                </thead>
                                <tbody>";
                
                while ($res_muestras_humedo = $resultado_peso_hum->fetch_assoc()) {
                        $con = $con+1;
                        $trn_id_muestra = $res_muestras_humedo['trn_muestra'];                                   
                        $html.="<tr>
                                    <td>".$con."</td>
                                    <td style='display:none;'> <input type='input' id='trn_batch".$con."' value='".$res_muestras_humedo['trn_id_batch']."' /></td>
                                    <td style='display:none;'> <input type='input' id='trn_rel".$con."' value='".$res_muestras_humedo['trn_id_rel']."' /></td>
                                    <td colspan='5'>".$res_muestras_humedo['muestra']."</td>                                            
                                    <td colspan='5'>  <input type='number' name='peso_hum".$con."' id='peso_hum".$con."' class='form-control' /> </td> 
                                    <td> <button type='button'class='btn btn-info' id='boton_save_hum' onclick='humedad_peso_guardar(".$trn_id.", ".$trn_id_muestra.", ".$con.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                    </button></td>
                                </tr>";
                }
            }
            else{
                $resultado_hum = $mysqli->query("SELECT
                                                   se.trn_id as trn_id_batch
                                                  ,se.trn_id_rel AS trn_muestra
                                                  ,ROUND(se.peso_humedo, 2) AS peso_humedo
                                                  ,ROUND(se.peso_seco, 2) AS peso_seco
                                                  ,ROUND(se.porcentaje, 2) AS porcentaje
                                                  ,om.folio AS muestra
                                              FROM 
                                                  arg_muestras_humedad se
                                                  LEFT JOIN arg_ordenes_muestras om
                                                        ON se.trn_id = om.trn_id_rel
                                                        AND se.trn_id_rel = om.trn_id
                                              WHERE se.trn_id = ".$trn_id." AND peso_seco = 0
                                            ORDER BY folio")   or die(mysqli_error());
                
                $html =  "<div class='col-md-12 col-lg-12'>
                           <table class='table text-black' id='tabla_humedad'>
                                <thead class='table-info'>
                                <tr class = 'table-info' >
                                    <th colspan='1'>No.</th>
                                    <th colspan='5'>Muestra</th>
                                    <th colspan='5'>Peso Húmedo KG</th>
                                    <th colspan='5'>Peso Seco KG</th>
                                    <th colspan='4'>% Humedad</th>
                                </thead>
                                <tbody>";                                
                
            while ($res_muestras = $resultado_hum->fetch_assoc()) {
                        $con = $con+1;
                        $trn_id_muestra = $res_muestras['trn_muestra'];
                        $pes_hum = $res_muestras['peso_humedo'];                                   
                        $html.="<tr>
                                    <td>".$con."</td>
                                    <td style='display:none;'> <input type='input' id='trn_batch".$con."' value='".$res_muestras['trn_id_batch']."' /></td>
                                    <td style='display:none;'> <input type='input' id='trn_rel".$con."' value='".$res_muestras['trn_id_rel']."' /></td>
                                    <td colspan='5'>".$res_muestras['muestra']."</td>                                            
                                    <td colspan='5'>  <input type='number' name='peso_hum".$con."' id='peso_hum".$con."' onchange='calcula_porc_hum(".$con.")' class='form-control' disabled value = '".$pes_hum."' /> </td> 
                                    <td colspan='4'>  <input type='number' name='peso_sec".$con."' id='peso_sec".$con."' onchange='calcula_porc_hum(".$con.")' class='form-control' /> </td>
                                    <td colspan='4'> <input type='number' name='porc_hum".$con."' id='porc_hum".$con."' class='form-control' disabled/> </td>
                                    <td> <button type='button'class='btn btn-info' id='boton_save_hum' onclick='humedad_guardar(".$trn_id.", ".$trn_id_muestra.", ".$con.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                    </button></td>
                                </tr>";
            }
        }
     }
      $html .= "</tbody></table></div>";
      echo utf8_encode($html);           
}//Termina humedad
  
?>