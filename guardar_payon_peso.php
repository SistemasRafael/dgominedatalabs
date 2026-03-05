<?include "connections/config.php";?>
<?php
$html = '';
$trnid_m    = $_POST['trnid_pay'];
$trnidrel_m = $_POST['trnid_muestra_pay'];
$metodo_sel = $_POST['metodo_id_pay'];
$fase_sel   = $_POST['fase_pay'];
$etapa_sel  = $_POST['etapa_pay'];
$cantidad   = $_POST['cantidad_met_pay'];
$final      = $_POST['fin_met_pay'];
$u_id       = $_SESSION['u_id'];

if (isset($trnid_m)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_ordenMetodoPeso(".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli));  
        
        $resultado_efaa = $mysqli->query(" SELECT metodo, fase, etapa
                                           FROM ordenes_fases_etapas
                                           WHERE trn_id_rel = ".$trnid_m." AND fase_id = ".$fase_sel." AND etapa_id = ".$etapa_sel
                                         ) or die(mysqli_error());
        
       $tipo_orden = $mysqli->query("SELECT 
                                         (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye 
                                        ,odet.folio_interno
                                      FROM arg_ordenes ord
                                      LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                      WHERE odet.trn_id = ".$trnid_m) or die(mysqli_error());             
       $tipo_ord = $tipo_orden->fetch_assoc();
       $reensaye = $tipo_ord['reensaye'];
       $orden_trabajo = $tipo_ord['folio_interno'];
       
       $validar_superv = $mysqli->query("SELECT
                                    	  up.u_id
                                         ,au.nombre
                                         ,(CASE WHEN up.perfil_id = 5 THEN 1 ELSE 0 END) AS perfil_id
                                         ,pe.descripcion
                                    FROM 	
                                    	arg_usuarios_perfiles up
                                        LEFT JOIN arg_perfiles AS pe
                                        	ON up.perfil_id = pe.perfil_id
                                        LEFT JOIN arg_usuarios AS au
                                        	ON au.u_id = up.u_id
                                    WHERE 
                                        up.perfil_id = 5
                                        AND up.activo = 1
                                    	AND up.u_id = ".$u_id) or die(mysqli_error());             
      $validar_supervi = $validar_superv->fetch_assoc();
      $supervisor = $validar_supervi['perfil_id'];
           
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
                                        AND ".$fase_sel." = 2
                                        AND (CASE ".$etapa_sel." WHEN 5 THEN se.peso = 0 WHEN 6 THEN se.peso_payon = 0 WHEN 7 THEN se.absorcion = 0 END)
                                     ORDER BY 
                                        om.folio_interno"
                                    ) or die(mysqli_error());
       }
       else{
            $origen_reen = $mysqli->query("SELECT 
                                                                        COUNT(*) AS existe_rech
                                                                   FROM 
                                                                       arg_muestras_reensaye mre
                                                                   WHERE mre.trn_id_rel = " . $trnid_m . " 
                                                                   AND mre.metodo_id = (CASE WHEN " . $metodo_sel . " = 0 
                                                                                        THEN mre.metodo_id ELSE " . $metodo_sel . " 
                                                                                        END) 
                                                                   AND mre.trn_id_muestra IN (SELECT trn_id_rel 
                                                                                          FROM muestras_recheck)"
                                    ) or die(mysqli_error($mysqli));
                                    $existe_reche = $origen_reen->fetch_assoc();
                                    $existe_rec = $existe_reche['existe_rech'];
                                    
                                    if ($existe_rec == 0){ 
                                        $resultado = $mysqli->query("SELECT
                                             se.trn_id as trnid_batch_met
                                            ,se.trn_id_rel as trnid_rel_met
                                            ,met.nombre as metodo_nombre
                                            ,ROUND(se.peso, 2) AS peso_muestra
                                            ,ROUND(se.peso_payon, 2) AS peso_payon
                                            ,ROUND(se.absorcion, 2) AS absorcion
                                            ,om.folio_interno as muestra_met                                      
                                            ,om.muestra_geologia as muestra_interna_met
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
                                            AND (CASE ".$etapa_sel." WHEN 5 THEN se.peso = 0 
                                                                     WHEN 6 THEN se.peso_payon = 0 
                                                                     WHEN 7 THEN se.absorcion = 0 
                                                 END)
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
                                         FROM 
                                            arg_muestras_resultados se
                                            LEFT JOIN ordenes_reensayes_recheck om
                                                ON se.trn_id = om.trn_id_rel
                                                AND se.trn_id_rel = om.trn_id_muestra
                                                AND se.metodo_id = om.metodo_id
                                            LEFT JOIN arg_metodos met
                                                ON met.metodo_id = se.metodo_id
                                         WHERE 
                                            se.trn_id = ".$trnid_m."
                                            AND se.metodo_id = ".$metodo_sel."
                                            AND (CASE ".$etapa_sel." WHEN 5 THEN se.peso = 0 
                                                                     WHEN 6 THEN se.peso_payon = 0 
                                                                     WHEN 7 THEN se.absorcion = 0 
                                                 END)
                                         ORDER BY 
                                            om.folio_interno"
                                        ) or die(mysqli_error());
                                    }
       }
        if ($resultado->num_rows > 0) {
            $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
            $metodo_codigo = $datos_gen['metodo'];
            $metodo_fase = $datos_gen['fase'];
            $metodo_etapa = $datos_gen['etapa'];
             //echo 'NTRO';
            
            if ($fase_sel == 2 && $etapa_sel == 5){
                 $html.=  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='4'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                   </tr>
                                   <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>"; 
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
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
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_peso_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }//Fin de etapa 5 pesaje
            
            //Inicia pesaje de payon
            if ($fase_sel == 2 && $etapa_sel == 6){
                 $html .=  "<table class='table text-black' id='tab_datos_payon'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='4'>".$metodo_codigo." Fase: ".$metodo_fase." - ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>"; 
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Payónnn</th>
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
                                   <td style='display:none;'> <input type='input' id='trnid_batch_pay".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_pay".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td> <input type='number' id='peso_pay".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
            }
        }
        else{
            $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
            $metodo_codigo = $datos_gen['metodo'];
            $metodo_fase = $datos_gen['fase'];
            $metodo_etapa = $datos_gen['etapa'];
           //$html = "La etapa ha finalizado."; 
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
                                        AND ".$fase_sel." = 2
                                     ORDER BY 
                                        om.folio_interno"
                                    ) or die(mysqli_error());
           }
           else{
                $origen_reen = $mysqli->query("SELECT 
                                                                        COUNT(*) AS existe_rech
                                                                   FROM 
                                                                       arg_muestras_reensaye mre
                                                                   WHERE mre.trn_id_rel = " . $trn_id . " 
                                                                   AND mre.metodo_id = (CASE WHEN " . $metodo_id . " = 0 
                                                                                        THEN mre.metodo_id ELSE " . $metodo_id . " 
                                                                                        END) 
                                                                   AND mre.trn_id_muestra IN (SELECT trn_id_rel 
                                                                                          FROM muestras_recheck)"
                                    ) or die(mysqli_error($mysqli));
                                    $existe_reche = $origen_reen->fetch_assoc();
                                    $existe_rec = $existe_reche['existe_rech'];
                                    
                                    if ($existe_rec == 0){                        
                                        $resultado = $mysqli->query("SELECT
                                                	         ot.trn_id_rel AS trnid_batch_met,
                                                             ot.trn_id_muestra AS trnid_rel_met,
                                                             ROUND(pul.peso_payon, 2) AS peso_payon,
                                                             ot.folio_interno AS muestra_met,
                                                             met.nombre as metodo_nombre
                                                           FROM 
                                                           arg_muestras_resultados pul
                                                           LEFT JOIN ordenes_reensayes ot
                                                                ON pul.trn_id = ot.trn_id_rel
                                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                                AND pul.metodo_id = ot.metodo_id
                                                           
                                                            LEFT JOIN arg_metodos met
                                                                ON met.metodo_id = pul.metodo_id
                                                           WHERE
                                                              pul.metodo_id = ".$metodo_id." 
                                                              AND pul.trn_id = ".$trn_id."
                                                              ORDER BY ot.folio_interno") or die(mysqli_error());
                                    }
                                    else{
                                        $resultado = $mysqli->query("SELECT
                                                	         ot.trn_id_rel AS trnid_batch_met,
                                                             ot.trn_id_muestra AS trnid_rel_met,
                                                             ROUND(pul.peso_payon, 2) AS peso_payon,
                                                             ot.folio_interno AS muestra_met,                                                               
                                                             met.nombre as metodo_nombre
                                                           FROM 
                                                           arg_muestras_resultados pul
                                                           LEFT JOIN ordenes_reensayes_recheck ot
                                                                ON pul.trn_id = ot.trn_id_rel
                                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                                AND pul.metodo_id = ot.metodo_id
                                                           LEFT JOIN arg_metodos met
                                                                ON met.metodo_id = pul.metodo_id
                                                           WHERE
                                                              pul.metodo_id = ".$metodo_id." 
                                                              AND pul.trn_id = ".$trn_id."
                                                           ORDER BY ot.folio_interno") or die(mysqli_error());
                                    }
           }
            $html .=  "<table class='table text-black' id='tab_datos_payon'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='4'>".$metodo_codigo." Fase: ".$metodo_fase." - ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>"; 
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Payónnn</th>
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
                        $peso            = $res_muestras['peso_payon'];
                        if ($peso < 20 || $peso > 45){
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
                                if ($supervisor == 1){
                                           $html .="<td> <input type='number' id='peso_pay".$cont."' value='".$peso_actual."' class='form-control'/> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardarEdit(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                  </button>
                                             </td>";
                                        }                                  
                        $html.="</tr>"; 
                    }
             $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizar(".$trnid_batch_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.")' >
                                            <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                        </button></td>";
             $html.="</div>";
             $html .= "</tbody></table></div>";
             
        }       
         //$mysqli -> set_charset("utf8");
         echo utf8_encode($html);
  }