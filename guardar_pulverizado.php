<?include "connections/config.php";?>
<?php
$html = '';
$trn_id = $_POST['trn_id_pul'];
$trn_id_rel = $_POST['trn_id_relpul'];
$peso = $_POST['total_peso_pul'];
$peso_malla = $_POST['total_mal_pul'];
$porc_que = $_POST['total_porc_pul'];
$coment = $_POST['coment_pul'];
$final = $_POST['fin'];
$u_id = $_SESSION['u_id'];
$con = 0;
//echo $trn_id; echo $trn_id_batch; echo $peso; echo $peso_malla; echo $porc_que; echo $coment; echo $final;

if (isset($trn_id)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_ordenPulverizado(".$trn_id.", ".$trn_id_rel.", ".$peso.", ".$peso_malla.", ".$porc_que.", ".$u_id.",".$final.", '".$coment."')") OR DIE (mysqli_error($mysqli)); 
  
      $tipo_orden = $mysqli->query("SELECT 
                                            (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                            ,odet.folio_interno AS orden_trabajo 
                                      FROM arg_ordenes ord
                                      LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                      WHERE odet.trn_id = ".$trn_id) or die(mysqli_error());             
       $tipo_ord = $tipo_orden->fetch_assoc();
       $reensaye = $tipo_ord['reensaye'];
       $orden_trabajo = $tipo_ord['orden_trabajo'];

    if ($reensaye == 0){
        $resultado = $mysqli->query("SELECT
                                       se.trn_id as trn_id_batch, se.trn_id_rel
                                      ,ROUND(se.peso, 2) AS peso
                                      ,ROUND(se.peso_malla, 2) AS peso_malla
                                      ,ROUND(se.porcentaje, 2) AS porcentaje
                                      ,se.comentario
                                      ,om.folio_interno as muestra
                                  FROM 
                                      arg_muestras_pulverizado se
                                      LEFT JOIN ordenes_transacciones om
                                            ON se.trn_id = om.trn_id_batch
                                            AND se.trn_id_rel = om.trn_id_rel
                                  WHERE porcentaje = 0 AND se.trn_id = ".$trn_id) or die(mysqli_error());
     }
     else{
        $resultado = $mysqli->query("SELECT
                                               se.trn_id as trn_id_batch, se.trn_id_rel
                                              ,ROUND(se.peso, 2) AS peso
                                              ,ROUND(se.peso_malla, 2) AS peso_malla
                                              ,ROUND(se.porcentaje, 4) AS porcentaje
                                              ,se.comentario
                                              ,om.folio_interno as muestra
                                          FROM 
                                              arg_muestras_pulverizado se
                                              LEFT JOIN ordenes_reensayes om
                                                    ON se.trn_id = om.trn_id_rel
                                                    AND se.trn_id_rel = om.trn_id_muestra
                                          WHERE se.porcentaje = 0 
                                                AND se.trn_id = ".$trn_id) or die(mysqli_error());
     }
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  "<div class='col-md-12 col-lg-12'>
                       <table class='table text-black' id='tabla_pulverizado'>
                            <thead class='table-info' align='left'>
                            <tr class='table-warning'>
                                 <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr>                           
                            <tr class='table-info'>
                                <th colspan='1'>Muestra</th>
                                <th colspan='1'>Peso</th>
                                <th colspan='1'>Malla</th>
                                <th colspan='1'>% Pulv</th>
                                <th colspan='2'>Comentario</th>
                            </thead>
                            <tbody>";
             while ($res_muestras = $resultado->fetch_assoc()) {
                    $con = $con+1;
                    $trn_id_batch = $res_muestras['trn_id_batch'];
                    $trn_id_rel = $res_muestras['trn_id_rel'];
                    $muestra = $res_muestras['muestra'];
                    //$peso_mu_pulv = $res_muestras['peso'];
                    //$peso_malla_pulv = $res_muestras['peso_malla'];
                    $porc_pulv = $res_muestras['porcentaje'];
                    $coment = $res_muestras['comentario'];                  
                    $html.="<tr>
                                <td style='display:none;'> <input type='input' id='trn_batch_pul".$con."' value='".$trn_id_batch."'/></td>  
                                <td style='display:none;'> <input type='input' id='trn_rel_pul".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                <td> <input type='input' name='trn_rel_pul".$con."' class='form-control' id='trn_rel_pul".$con."' value='".$muestra."' disabled></td> 
                                <td> <input type='number' name='peso_pul".$con."' step='.01' id='peso_pul".$con."' class='form-control' value='".$peso_mu_pulv."' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' value='".$peso_malla_pulv."' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' value='".$porc_pulv."' disabled/> </td>
                                <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' value='".$coment."' disabled /></td>
                                <td> <button type='button'class='btn btn-primary' id='boton_save_pulverizado' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                             </button></td>
                            </tr>"; 
                }
             $html .= "</tbody></table></div>";
        }
        else{
            $html = 'Ha finalizado la etapa de pulverizado.';
            echo ($html);
        }
  
   if ($final == 1){        
        if ($reensaye == 0){
            $resultado_pul = $mysqli->query("SELECT
                                       se.trn_id as trn_id_batch, se.trn_id_rel
                                      ,ROUND(se.peso, 2) AS peso
                                      ,ROUND(se.peso_malla, 2) AS peso_malla
                                      ,ROUND(se.porcentaje, 2) AS porcentaje
                                      ,se.comentario
                                      ,om.folio_interno as muestra
                                  FROM 
                                      arg_muestras_pulverizado se
                                      LEFT JOIN ordenes_transacciones om
                                            ON se.trn_id = om.trn_id_batch
                                            AND se.trn_id_rel = om.trn_id_rel
                                  WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
        }
        else{
            $resultado_pul = $mysqli->query("SELECT
                                                se.trn_id as trn_id_batch, se.trn_id_rel
                                                ,ROUND(se.peso, 2) AS peso
                                                ,ROUND(se.peso_malla, 2) AS peso_malla
                                                ,ROUND(se.porcentaje, 2) AS porcentaje
                                                ,se.comentario
                                                ,om.folio_interno as muestra
                                            FROM 
                                                arg_muestras_pulverizado se
                                                LEFT JOIN ordenes_reensayes om
                                                    ON se.trn_id = om.trn_id_rel
                                                AND se.trn_id_rel = om.trn_id_muestra
                                            WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
        }
             //echo $query;
        if ($resultado_pul->num_rows > 0) {
            $html =  "<div class='col-md-12 col-lg-12'>
                       <table class='table text-black' id='tabla_pulverizado'>
                            <thead class='thead-light' align='left'>
                            <tr class='table-warning'>
                                 <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr>                    
                            <tr class='table-info'>
                                <th colspan='1'>Muestra</th>
                                <th colspan='1'>Peso</th>
                                <th colspan='1'>Malla</th>
                                <th colspan='1'>% Pulv</th>
                                <th colspan='2'>Comentario</th>
                            </thead>
                            <tbody>";
             while ($res_muestras = $resultado_pul->fetch_assoc()) {
                    $con = $con+1;
                    $trn_id_batch = $res_muestras['trn_id_batch'];
                    $trn_id_rel = $res_muestras['trn_id_rel'];
                    $muestra = $res_muestras['muestra'];
                    $peso_mu_pulv = $res_muestras['peso'];
                    $peso_malla_pulv = $res_muestras['peso_malla'];
                    $porc_pulv = $res_muestras['porcentaje'];
                    $coment = $res_muestras['comentario'];                  
                    $html.="<tr>
                                <td style='display:none;'> <input type='input' id='trn_batch_pul".$con."' value='".$trn_id_batch."'/></td>  
                                <td style='display:none;'> <input type='input' id='trn_rel_pul".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                <td> <input type='input' name='trn_rel_pul".$con."' class='form-control' id='trn_rel_pul".$con."' value='".$muestra."' disabled></td> 
                                <td> <input type='number' name='peso_pul".$con."' step='.01' id='peso_pul".$con."' class='form-control' value='".$peso_mu_pulv."' onchange='calcula_porc_pulv(".$con.")' disabled/> </td>
                                <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' value='".$peso_malla_pulv."' onchange='calcula_porc_pulv(".$con.")' disabled /> </td>
                                <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' value='".$porc_pulv."' disabled/> </td>
                                <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' value='".$coment."' disabled /></td>
                                <td> <button type='button'class='btn btn-primary' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                             </button></td>
                            </tr>"; 
                }
             $html .= "</tbody></table></div>";
        }
    }
         //$mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 // }
 }
?>