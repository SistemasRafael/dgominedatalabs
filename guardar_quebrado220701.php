<?include "connections/config.php";?>
<?php
$html = '';
$trn_id = $_POST['trn_id_que'];
$trn_id_rel = $_POST['trn_id_rel_que'];
$peso = $_POST['total_peso_que'];
$peso_malla = $_POST['total_mal_que'];
$porc_que = $_POST['total_porc_que'];
$coment = $_POST['coment_que'];
$final = $_POST['fin'];
$u_id = $_SESSION['u_id'];
$con = 0;

if (isset($trn_id)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_ordenQuebrado(".$trn_id.", ".$trn_id_rel.", ".$peso.", ".$peso_malla.", ".$porc_que.", ".$u_id.", ".$final.", '".$coment."')") OR DIE (mysqli_error($mysqli));  
   
   $tipo_orden = $mysqli->query("SELECT (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye 
                                      FROM arg_ordenes ord
                                      LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                      WHERE odet.trn_id = ".$trn_id) or die(mysqli_error());             
   $tipo_ord = $tipo_orden->fetch_assoc();
   $reensaye = $tipo_ord['reensaye'];
         
   if($reensaye == 0){            
             $resultado = $mysqli->query("SELECT
                                              se.trn_id as trn_id_batch, se.trn_id_rel
                                              ,ROUND(se.peso, 2) AS peso
                                              ,ROUND(se.peso_malla, 2) AS peso_malla
                                              ,ROUND(se.porcentaje, 2) AS porcentaje
                                              ,se.comentario
                                              ,om.folio_interno as muestra
                                          FROM 
                                                arg_muestras_quebrado se
                                                LEFT JOIN ordenes_transacciones om
                                                    ON se.trn_id = om.trn_id_batch
                                                    AND se.trn_id_rel = om.trn_id_rel
                                          WHERE se.porcentaje = 0 AND se.trn_id = ".$trn_id) or die(mysqli_error());
                }
                else{
                    $resultado = $mysqli->query("SELECT
                                               se.trn_id as trn_id_batch, se.trn_id_rel
                                              ,ROUND(se.peso, 2) AS peso
                                              ,ROUND(se.peso_malla, 2) AS peso_malla
                                              ,ROUND(se.porcentaje, 2) AS porcentaje
                                              ,se.comentario
                                              ,om.folio_interno as muestra
                                          FROM 
                                              arg_muestras_quebrado se
                                              LEFT JOIN ordenes_reensayes om
                                                    ON se.trn_id = om.trn_id_rel
                                                    AND se.trn_id_rel = om.trn_id_muestra
                                          WHERE se.porcentaje = 0 AND se.trn_id = ".$trn_id) or die(mysqli_error());
                }
                if ($resultado->num_rows > 0) {
                    $html =  "<div class='col-md-12 col-lg-12'>
                               <table class='table text-black' id='tabla_quebrado'>
                                    <thead class='table-info' align='left'>
                                    <tr>
                                        <th>Muestra</th>
                                        <th>Peso</th>
                                        <th>Malla</th>
                                        <th>%</th>
                                        <th>Comentario</th>
                                    </thead>
                                    <tbody>";
                    $con = 0;
                    while ($res_muestras = $resultado->fetch_assoc()) {
                            $con = $con+1;
                            $trn_id_batch = $res_muestras['trn_id_batch'];
                            $trn_id_rel = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            //$peso_mu_que = $res_muestras['peso'];
                           // $peso_malla_que = $res_muestras['peso_malla'];
                            //$porc_que = $res_muestras['porcentaje'];
                            $coment_que = $res_muestras['comentario'];                   
                            $html.="<tr>                    
                                       <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                    <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                    <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                                    <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' value='".$peso_mu_que."' onchange='calcula_porc(".$con.")' /> </td>
                                                    <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' value='".$peso_malla_que."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                    <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                                    <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' value='".$coment_que."' class='form-control' disabled /></td>
                                                    <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id.", ".$trn_id_rel.", ".$con.")' >
                                                            <span class='fa fa-cloud fa-1x'></span>
                                                    </button></td>
                                    </tr>"; 
                        }
                     $html .= "</tbody></table></div>";
                }
                else{
                    $html = 'Ha finalizado la etapa de quebrado.';
                    echo ($html);
                }
                
         
            if ($final == 1){
                
                if($reensaye == 0){            
                        $resultado_quebr = $mysqli->query("SELECT
                                                               se.trn_id as trn_id_batch, se.trn_id_rel
                                                              ,ROUND(se.peso, 2) AS peso
                                                              ,ROUND(se.peso_malla, 2) AS peso_malla
                                                              ,ROUND(se.porcentaje, 2) AS porcentaje
                                                              ,se.comentario
                                                              ,om.folio_interno as muestra
                                                          FROM 
                                                              arg_muestras_quebrado se
                                                              LEFT JOIN ordenes_transacciones om
                                                                    ON se.trn_id = om.trn_id_batch
                                                                    AND se.trn_id_rel = om.trn_id_rel
                                                          WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
                }
                else{
                        $resultado_quebr = $mysqli->query("SELECT
                                                               se.trn_id as trn_id_batch, se.trn_id_rel
                                                              ,ROUND(se.peso, 2) AS peso
                                                              ,ROUND(se.peso_malla, 2) AS peso_malla
                                                              ,ROUND(se.porcentaje, 2) AS porcentaje
                                                              ,se.comentario
                                                              ,om.folio_interno as muestra
                                                          FROM 
                                                              arg_muestras_quebrado se
                                                              LEFT JOIN ordenes_reensayes om
                                                                    ON se.trn_id = om.trn_id_rel
                                                                    AND se.trn_id_rel = om.trn_id_muestra
                                                          WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
                }
                
                if ($resultado_quebr->num_rows > 0) {
                    $html =  "<div class='col-md-12 col-lg-12'>
                               <table class='table text-black' id='tabla_quebrado'>
                                    <thead class='table-info' align='left'>
                                    <tr>
                                        <th>Muestra</th>
                                        <th>Peso</th>
                                        <th>Malla</th>
                                        <th>%</th>
                                        <th>Comentario</th>
                                    </thead>
                                    <tbody>";
                     $con = 0;
                     while ($res_muestras = $resultado_quebr->fetch_assoc()) {
                            $con = $con+1;
                            $trn_id_batch = $res_muestras['trn_id_batch'];
                            $trn_id_rel = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $peso_mu_que = $res_muestras['peso'];
                            $peso_malla_que = $res_muestras['peso_malla'];
                            $porc_que = $res_muestras['porcentaje'];
                            $coment_que = $res_muestras['comentario'];                   
                            $html.="<tr>                    
                                       <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                    <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                    <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                                    <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' value='".$peso_mu_que."' disabled /> </td>
                                                    <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' value='".$peso_malla_que."' class='form-control' disabled /> </td>
                                                    <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' value='".$porc_que."' class='form-control' disabled/> </td>
                                                    <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' value='".$coment_que."' class='form-control' disabled /></td>
                                                    <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                            <span class='fa fa-cloud fa-1x'></span>
                                                    </button></td>
                                    </tr>"; 
                        }
                     $html .= "</tbody></table></div>";
                }
                // $mysqli -> set_charset("utf8");
            }
           echo utf8_encode($html);
   }
?>