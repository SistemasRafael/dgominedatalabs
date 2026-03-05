<?include "connections/config.php";?>
<?php
$html = '';
$trn_id_ph     = $_POST['trn_id_ph'];
$trn_id_rel_ph = $_POST['trn_id_rel_ph'];
$total_ph        = $_POST['total_ph'];
$u_id          = $_SESSION['u_id'];
  /// echo $trn_id_ph;
if (isset($trn_id_ph)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_humedad_peso_guardar(".$trn_id_ph.", ".$trn_id_rel_ph.", ".$total_ph.", ".$u_id.")") OR DIE (mysqli_error($mysqli));  

   $resultado = $mysqli->query("SELECT
                                       se.trn_id as trn_id_batch
                                      ,se.trn_id_rel
                                      ,ROUND(se.peso_humedo, 2) AS peso_humedo
                                      ,ROUND(se.peso_seco, 2) AS peso_seco
                                      ,ROUND(se.porcentaje, 2) AS porcentaje
                                      ,om.folio as muestra
                                  FROM 
                                      arg_muestras_humedad se
                                      LEFT JOIN arg_ordenes_muestras om
                                            ON se.trn_id = om.trn_id_rel
                                            AND se.trn_id_rel = om.trn_id
                                  WHERE se.trn_id = ".$trn_id_ph." AND se.peso_humedo = 0") or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
             $html =  "<div class='col-md-12 col-lg-12'>
                           <table class='table text-black' id='tabla_humedad'>
                                <thead class='thead-light'>
                                <tr class = 'table-info' >
                                    <th colspan='1'>No.</th>
                                    <th colspan='5'>Muestra</th>
                                    <th colspan='5'>Peso Húmedo KG</th>
                                </thead>
                                <tbody>";
             $con = 0;
             while ($res_muestras = $resultado->fetch_assoc()) {
                    $con = $con+1;
                    $trn_id_batch   = $res_muestras['trn_id_batch'];
                    $trn_id_rel     = $res_muestras['trn_id_rel'];
                    $muestra        = $res_muestras['muestra'];           
                    $html.="<tr> 
                               <td>".$con."</td>                    
                               <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                               <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                               <td colspan='5'>".$muestra."</td> 
                               <td colspan='5'> <input type='number' name='peso_hum".$con."' id='peso_hum".$con."' class='form-control'  /> </td>
                               <td> <button type='button'class='btn btn-info' id='boton_save_hum' onclick='humedad_peso_guardar(".$trn_id_ph.", ".$trn_id_rel.", ".$con.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                               </button></td>
                            </tr>"; 
                }
             $html .= "</tbody></table></div>";
        }
    else{
         $resultado_det_hum = $mysqli->query(" SELECT
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
                                              WHERE se.trn_id = ".$trn_id_ph." AND porcentaje = 0
                                            ORDER BY om.folio ") or die(mysqli_error());
             //echo $query;
        if ($resultado_det_hum->num_rows > 0) {
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
            $con = 0;
             while ($res_muestras = $resultado_det_hum->fetch_assoc()) {
               // echo 'llego';
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
                                    <td> <button type='button'class='btn btn-info' id='boton_save_hum' onclick='humedad_guardar(".$trn_id_ph.", ".$trn_id_muestra.", ".$con.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                    </button></td>
                                </tr>";
                }
             $html .= "</tbody></table></div>";
        }
        else{
            $html = 'El método ha finalizado.';
        }
    }
         //$mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
  //}
}

?>