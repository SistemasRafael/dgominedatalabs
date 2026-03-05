<?include "connections/config.php";?>
<?php
$html = '';
$trnid_m    = $_POST['trnid_orden'];
$trnidrel_m = $_POST['trnid_muestra'];
$metodo_sel = $_POST['metodo_id'];
$fase_sel   = $_POST['fase'];
$etapa_sel  = $_POST['etapa'];
$cantidad   = $_POST['cantidad_metodo'];
$final      = $_POST['fin_met'];
$u_id       = $_SESSION['u_id'];

//echo 'llego';
//echo $final;
//echo $u_id;
if (isset($trnid_m)){
    //echo 'entRo';
   //if ($fase_sel == 2 && $etapa_sel == 5){
        mysqli_multi_query ($mysqli, "CALL arg_prc_ordenMetodoPeso(".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli));  
   //}     
        $resultado_efaa = $mysqli->query(" SELECT metodo, fase, etapa
                                           FROM ordenes_fases_etapas
                                           WHERE trn_id_rel = ".$trnid_m." AND fase_id = ".$fase_sel." AND etapa_id = ".$etapa_sel
                                         ) or die(mysqli_error());
        
       $tipo_orden = $mysqli->query("SELECT (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye 
                                      FROM arg_ordenes ord
                                      LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                      WHERE odet.trn_id = ".$trnid_m) or die(mysqli_error());             
       $tipo_ord = $tipo_orden->fetch_assoc();
       $reensaye = $tipo_ord['reensaye'];
        
        if ($reensaye == 0){
            $resultado = $mysqli->query("SELECT
                                             se.trn_id as trnid_batch_met
                                            ,se.trn_id_rel as trnid_rel_met
                                            ,met.nombre as metodo_nombre
                                            ,ROUND(se.peso, 2) AS peso_muestra
                                            ,ROUND(se.peso_payon, 2) AS peso_payon
                                            ,ROUND(se.absorcion, 2) AS absorcion
                                            ,om.folio_interno as muestra_met                                      
                                            ,om.muestra_geologia as muestra_interna_met
                                            ,om.control
                                         FROM 
                                            arg_muestras_resultados se
                                            LEFT JOIN ordenes_transacciones om
                                                ON se.trn_id = om.trn_id_batch
                                                AND se.trn_id_rel = om.trn_id_rel
                                            LEFT JOIN arg_metodos met
                                                ON met.metodo_id = se.metodo_id
                                         WHERE 
                                            se.trn_id = ".$trnid_m."
                                            AND se.metodo_id = ".$metodo_sel."
                                            AND (CASE ".$etapa_sel." WHEN 5 THEN se.peso = 0 WHEN 6 THEN se.peso_payon = 0 WHEN 7 THEN se.absorcion = 0 END)
                                         ORDER BY
                                            om.folio_interno"
                                    ) or die(mysqli_error());
        }
        else{
            $resultado = $mysqli->query("SELECT
                                             se.trn_id as trnid_batch_met
                                            ,se.trn_id_rel as trnid_rel_met
                                            ,met.nombre as metodo_nombre
                                            ,ROUND(se.peso, 2) AS peso_muestra
                                            ,ROUND(se.peso_payon, 2) AS peso_payon
                                            ,ROUND(se.absorcion, 2) AS absorcion
                                            ,om.folio_interno as muestra_met                                      
                                            ,om.muestra_geologia as muestra_interna_met
                                            ,om.control
                                         FROM 
                                            arg_muestras_resultados se
                                            LEFT JOIN ordenes_reensayes om
                                                ON se.trn_id = om.trn_id_rel
                                                AND se.trn_id_rel = om.trn_id_muestra
                                            LEFT JOIN arg_metodos met
                                                ON met.metodo_id = se.metodo_id
                                         WHERE 
                                            se.trn_id = ".$trnid_m."
                                            AND se.metodo_id = ".$metodo_sel."
                                            AND (CASE ".$etapa_sel." WHEN 5 THEN se.peso = 0 WHEN 6 THEN se.peso_payon = 0 WHEN 7 THEN se.absorcion = 0 END)
                                         ORDER BY
                                            om.folio_interno"
                                    ) or die(mysqli_error());
        }
        if ($resultado->num_rows > 0) {
            $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
            $metodo_codigo = $datos_gen['metodo'];
            $metodo_fase = $datos_gen['fase'];
            $metodo_etapa = $datos_gen['etapa'];
            
            $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>"; 
            if ($fase_sel == 2 && $etapa_sel == 5){
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                        $muestra_met     = $res_muestras['muestra_met'];
                        $muestra_control = $res_muestras['control']; 
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                    <td>".$muestra_control."</td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }//Fin de etapa 5 pesaje
            
            //Pesaje cianurado
            if ($fase_sel == 7 && $etapa_sel == 5){
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                        $muestra_met     = $res_muestras['muestra_met']; 
                        $muestra_control     = $res_muestras['control'];
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td>".$muestra_control."</td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }//Fin de etapa 5 pesaje de fase 7 cianurado
            
            if ($fase_sel == 6 && $etapa_sel == 5){
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                        $muestra_met     = $res_muestras['muestra_met'];
                        $muestra_control = $res_muestras['control'];
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td>".$muestra_control."</td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }//Fin de etapa 5 pesaje
            
            //Inicia pesaje de payon
            if ($fase_sel == 2 && $etapa_sel == 6){
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Payón</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                        $muestra_met     = $res_muestras['muestra_met'];     
                        $metodo          = $res_muestras['metodo_nombre'];
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }
        }
        else{
            $html = 'Ha finalizado la etapa.';
        }
        //$mysqli -> set_charset("utf8");
         echo utf8_encode($html);
  }
  
?>