<? include "connections/config.php"; ?>
<?php
//$html = '';
$trn_id_fin = $_POST['trnid_pay'];
$metodo_id  = $_POST['metodo_id_pay'];
$fase_id    = $_POST['fase_pay'];
$etapa_id   = $_POST['etapa_pay'];
$u_id       = $_SESSION['u_id'];

if (isset($trn_id_fin)) {

  mysqli_multi_query($mysqli, "CALL arg_prc_pesoPayonFinaliza($trn_id_fin,$metodo_id,$fase_id,$etapa_id, $u_id)") or die(mysqli_error($mysqli));
  
  $tipo_orden = $mysqli->query("SELECT 
                                    (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                    ,odet.folio_interno
                                FROM arg_ordenes ord
                                LEFT JOIN arg_ordenes_detalle odet
                                    ON ord.trn_id =  odet.trn_id_rel
                                WHERE odet.trn_id = ".$trn_id_fin) or die(mysqli_error());             
   $tipo_ord = $tipo_orden->fetch_assoc();
   $reensaye = $tipo_ord['reensaye'];
   $orden_trabajo = $tipo_ord['folio_interno'];   
   
    $html = 'La etapa ha finalizado';
    
    $html .=  "<table class='table text-black' id='tab_datos_payon'>
                                <thead class='thead-info' align='center'>                                    
                                    <tr class='table-warning' align='center'>
                                        <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>            
                           
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Payón g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
    
    $resultado = $mysqli->query("SELECT
                                                 se.trn_id as trnid_batch_met
                                                ,se.trn_id_rel as trnid_rel_met
                                                ,met.nombre as metodo_nombre
                                                ,ROUND(se.peso, 2) AS peso_muestra
                                                ,ROUND(se.peso_payon, 2) AS peso_payon
                                                ,ROUND(se.absorcion, 2) AS absorcion
                                                ,om.folio_interno as muestra_met                                      
                                                ,om.muestra_geologia as muestra_interna_met
                                                ,se.reensaye
                                             FROM 
                                                arg_muestras_resultados se
                                                LEFT JOIN ordenes_transacciones om
                                                    ON se.trn_id = om.trn_id_batch
                                                    AND se.trn_id_rel = om.trn_id_rel
                                                LEFT JOIN arg_metodos met
                                                    ON met.metodo_id = se.metodo_id
                                             WHERE 
                                                se.trn_id = ".$trn_id_fin."
                                                AND se.metodo_id = ".$metodo_id."
                                             ORDER BY 
                                                om.folio_interno"
                                            ) or die(mysqli_error());
                    
                         $cont = 0;
                         while ($res_muestras = $resultado->fetch_assoc()) {
                                $cont = $cont+1;
                                $trnid_batch_met = $res_muestras['trnid_batch_met'];
                                $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                                $muestra_met     = $res_muestras['muestra_met'];     
                                $metodo          = $res_muestras['metodo_nombre'];
                                $peso            = $res_muestras['peso_payon'];
                                $reensaye_mos    = $res_muestras['reensaye'];
                                if ($reensaye_mos == 3){
                                    $html .="<tr  style='color: #BD2819; background: #FDEBD0';>";
                                }
                                else{
                                    $html .="<tr>";
                                }
                                                              
                                $html.="<td>".$cont."</td> 
                                        <td style='display:none;'> <input type='input' id='trnid_batch_pay".$cont."' value='".$trnid_batch_met."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trnid_rel_pay".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                                        <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                                        <td>".$muestra_met."</td>
                                        <td>".$peso."</td>";                                                                                                          
                                        $html.="</tr>"; 
                            }
                    
                     $html .= "</tbody></table></div>";
    
}

$mysqli->set_charset("utf8");
echo utf8_encode($html);

?>