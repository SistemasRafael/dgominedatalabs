<?include "connections/config.php";?>
<?php
$html = '';
$trnid_folio = $_POST['trnid_folio'];
$trnid_m     = $_POST['trnid_orden'];
$trnidrel_m  = $_POST['trnid_muestra'];
$metodo_sel  = $_POST['metodo_id'];
$fase_sel    = $_POST['fase'];
$etapa_sel   = $_POST['etapa'];
$cantidad    = $_POST['cantidad_metodo'];
$final       = $_POST['fin_met'];
$u_id        = $_SESSION['u_id'];

//echo 'llego';
//echo $final;
//echo $u_id; 
if (isset($trnid_m)){
    //echo 'entRo';
   //if ($fase_sel == 2 && $etapa_sel == 5){
        mysqli_multi_query ($mysqli, "CALL arg_prc_ordenSobrelimPeso(".$trnid_folio.", ".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli));  
   //}     
        $resultado_efaa = $mysqli->query(" SELECT metodo, fase, etapa
                                           FROM ordenes_fases_etapas
                                           WHERE trn_id_rel = ".$trnid_m." AND fase_id = ".$fase_sel." AND etapa_id = ".$etapa_sel
                                         ) or die(mysqli_error());
        
       $tipo_orden = $mysqli->query("SELECT 
                                          (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye 
                                         ,odet.folio_interno
                                         ,ord.unidad_id
                                      FROM arg_ordenes ord
                                      LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                      WHERE odet.trn_id = ".$trnid_m) or die(mysqli_error());             
       $tipo_ord = $tipo_orden->fetch_assoc();
       $reensaye = $tipo_ord['reensaye'];
       $unidad_id = $tipo_ord['unidad_id'];
       $orden_trabajo = $tipo_ord['folio_interno'];
        
       if ($etapa_sel == 5){           
             $resultado_mues_tot = $mysqli->query("SELECT
                                                       sl.trn_id,
                                                       sl.trn_id_rel AS trn_id_batch,
                                                       sl.trn_id_batch AS trn_origen,
                                                       sl.trn_id_muestra,
                                                       ade.muestra_geologia AS muestra,
                                                       sl.folio_interno
                                                   FROM
                                                       arg_muestras_sobrelimites AS sl
                                                       LEFT JOIN ordenes_transacciones ade 
                                                            ON sl.trn_id_muestra = ade.trn_id_rel
                                                            AND sl.trn_id_batch = ade.trn_id_batch
                                                   WHERE
                                                       sl.trn_id_rel = ".$trnid_m."
                                                       AND sl.metodo_id = 1
                                                       AND sl.peso = 0
                                                   ORDER BY 
                                                       sl.folio_interno") or die(mysqli_error());
                                        
            if ($resultado_mues_tot->num_rows > 0) {
                $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
                $metodo_codigo = $datos_gen['metodo'];
                $metodo_fase = $datos_gen['fase'];
                $metodo_etapa = $datos_gen['etapa'];
                
                $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>"; 
           
                            $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso g</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $con = 1;
                  
                 while ($res_muestras_to = $resultado_mues_tot->fetch_assoc()) {
                    $trnid_folio   = $res_muestras_to['trn_id'];
                    $trnid_batch   = $res_muestras_to['trn_id_batch'];
                    $trnid_origen  = $res_muestras_to['trn_origen'];
                    $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                    $muestra_folio = $res_muestras_to['muestra'];
                    $folio_interno = $res_muestras_to['folio_interno'];
                            $html.="<tr>
                                        <td>".$con."</td> 
                                        <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                        <td>".$muestra_folio."</td>
                                        <td>".$folio_interno."</td>
                                        <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                        <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$con.",".$unidad_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                            </button>
                                        </td>
                                    </tr>";
                    $con = $con+1;
                  }
                  
             $html .= "</tbody></table></div>";
            }//Fin de etapa 5 pesaje
            else{
                 $html = 'Ha finalizado la etapa.';
            }                                        
       }
            //Etapa de incuarte metodo 24 EFAAG-30
       if ($etapa_sel == 19){
                 $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
                 $metodo_codigo = $datos_gen['metodo'];
                 $metodo_fase = $datos_gen['fase'];
                 $metodo_etapa = $datos_gen['etapa'];
            
                 $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>";           
                             
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Incuarte mg</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $con = 1;
                 $resultado_mues_tot = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
}                                                           ade.muestra_geologia AS muestra,                                         
                                                            sl.folio_interno
                                                        FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON sl.trn_id_muestra = ade.trn_id_rel
                                                                AND sl.trn_id_batch = ade.trn_id_batch
                                                        WHERE
                                                            sl.trn_id_rel = ".$trnid_m."
                                                            AND sl.metodo_id = 1
                                                            AND sl.incuarte = 0
                                                        ORDER BY sl.folio_interno")   or die(mysqli_error());
                       
                if ($resultado_mues_tot->num_rows > 0) {      
                    while ($res_muestras_to = $resultado_mues_tot->fetch_assoc()) {
                        $trnid_folio   = $res_muestras_to['trn_id'];
                        $trnid_batch   = $res_muestras_to['trn_id_batch'];
                        $trnid_origen  = $res_muestras_to['trn_origen'];
                        $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                        $muestra_folio = $res_muestras_to['muestra'];
                        $folio_interno = $res_muestras_to['folio_interno'];
                        $html.="<tr>
                                    <td>".$con."</td> 
                                    <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                    <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                    <td>".$muestra_folio."</td>
                                    <td>".$folio_interno."</td>
                                    <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$con.",".$unidad_id.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                                </tr>";
                        $con = $con+1;
                    }
                    $html .= "</tbody></table></div>";
                }
                else
                    $html = 'Ha finalizado la etapa.';
            }//Fin de etapa 19 pesaje Incuarte
            
            //Inicia pesaje de payon
             //Etapa de fundicion metodo 24 EFAAG-30
            if ($etapa_sel == 6){
                 $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
                 $metodo_codigo = $datos_gen['metodo'];
                 $metodo_fase = $datos_gen['fase'];
                 $metodo_etapa = $datos_gen['etapa'];
            
                 $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>";
                            $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso Pay&oacuten g</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $con = 1;
                 $resultado_mues_tot = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
                                                            sl.folio_interno,
                                                            ade.muestra_geologia AS muestra
                                                      FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON sl.trn_id_muestra = ade.trn_id_rel
                                                                AND sl.trn_id_batch  = ade.trn_id_batch
                                                      WHERE
                                                            sl.trn_id_rel = ".$trnid_m."
                                                            AND sl.metodo_id = 1
                                                            AND sl.peso_payon = 0
                                                      ORDER BY sl.folio_interno")   or die(mysqli_error());
                       
                if ($resultado_mues_tot->num_rows > 0) {      
                    while ($res_muestras_to = $resultado_mues_tot->fetch_assoc()) {
                            $trnid_folio   = $res_muestras_to['trn_id'];
                            $trnid_batch   = $res_muestras_to['trn_id_batch'];
                            $trnid_origen  = $res_muestras_to['trn_origen'];
                            $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                            $muestra_folio = $res_muestras_to['muestra'];
                            $folio_interno = $res_muestras_to['folio_interno'];
                            $html.="<tr>
                                        <td>".$con."</td> 
                                        <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                        <td>".$muestra_folio."</td>
                                        <td>".$folio_interno."</td>
                                        <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                        <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$con.",".$unidad_id.")' >
                                                <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                        </td>
                                    </tr>";
                            $con = $con+1;
                    }
                    $html .= "</tbody></table></div>";
                }
                else
                    $html = 'Ha finalizado la etapa.';
            }//Fin de etapa 19 pesaje Incuarte
            
            //Etapa de peso dore metodo 1 EFGRA-30
            if ($etapa_sel == 20){
                 $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
                 $metodo_codigo = $datos_gen['metodo'];
                 $metodo_fase = $datos_gen['fase'];
                 $metodo_etapa = $datos_gen['etapa'];
            
                $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>";           
                             
                            $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso Dor&eacute</th>
                                        <th></th>                       
                            </thead>
                            <tbody>";
                 $con = 1;
                 $resultado_mues_tot = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
                                                            sl.folio_interno,
                                                            ade.muestra_geologia AS muestra
                                                        FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON sl.trn_id_muestra = ade.trn_id_rel
                                                                AND sl.trn_id_batch  = ade.trn_id_batch                                                            
                                                        WHERE
                                                            sl.trn_id_rel = ".$trnid_m."
                                                            AND sl.metodo_id = ".$metodo_sel."
                                                            AND sl.peso_dore = 0
                                                            AND sl.trn_id_muestra NOT IN (SELECT 
                                                                                                trn_id_muestra 
                                                                                          FROM 
                                                                                                arg_muestras_reensaye
                                                                                          WHERE 
                                                                                                trn_id_batch = ".$trnid_m."
                                                                                                AND metodo_id = 1 )
                                                        ORDER BY sl.folio_interno") or die(mysqli_error());
                       
                if ($resultado_mues_tot->num_rows > 0) {      
                    while ($res_muestras_to = $resultado_mues_tot->fetch_assoc()) {
                          $trnid_folio   = $res_muestras_to['trn_id'];
                          $trnid_batch   = $res_muestras_to['trn_id_batch'];
                          $trnid_origen  = $res_muestras_to['trn_origen'];
                          $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                          $muestra_folio = $res_muestras_to['muestra'];
                          $folio_interno = $res_muestras_to['folio_interno'];
                          $html.="<tr>
                                    <td>".$con."</td> 
                                    <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                    <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                    <td>".$muestra_folio."</td>
                                    <td>".$folio_interno."</td>
                                    <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$con.",".$unidad_id.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                                </tr>";
                          $con = $con+1;
                    }
                    $html .= "</tbody></table></div>";
                }
                else
                    $html = 'Ha finalizado la etapa.';
            }//Fin de etapa 20 pesaje Dore
            
             //Inicia peso oro
        if ($etapa_sel == 21){
                 $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
                 $metodo_codigo = $datos_gen['metodo'];
                 $metodo_fase = $datos_gen['fase'];
                 $metodo_etapa = $datos_gen['etapa'];
            
                $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>";           
                
                $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso Oro mg</th>
                                        <th></th>      
                         </tr>                 
                         </thead>
                         <tbody>";
                 $con = 1;                 
                 $peso_det = $mysqli->query("SELECT
                                                sl.trn_id,
                                                sl.trn_id_rel AS trn_id_batch,
                                                sl.trn_id_batch AS trn_origen,
                                                sl.trn_id_muestra,
                                                sl.folio_interno,
                                                ade.muestra_geologia AS muestra
                                            FROM
                                                arg_muestras_sobrelimites AS sl
                                                LEFT JOIN ordenes_transacciones ade 
                                                    ON sl.trn_id_muestra = ade.trn_id_rel
                                                    AND sl.trn_id_batch  = ade.trn_id_batch
                                            WHERE
                                                sl.trn_id_rel = ".$trnid_m."
                                                AND sl.peso_oro = 0
                                                AND sl.metodo_id = ".$metodo_sel."
                                                AND sl.trn_id_muestra NOT IN (SELECT 
                                                                                  trn_id_muestra 
                                                                              FROM 
                                                                                  arg_muestras_reensaye
                                                                              WHERE 
                                                                                  trn_id_batch = ".$trnid_m."
                                                                                  AND metodo_id = 1)
                                            ORDER BY  sl.folio_interno") or die(mysqli_error());                 
                                                  
                       if ($peso_det->num_rows > 0) {   
                            while ($res_muestras_to = $peso_det->fetch_assoc()) {
                                $trnid_folio   = $res_muestras_to['trn_id'];
                                $trnid_batch   = $res_muestras_to['trn_id_batch'];
                                $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                                $muestra_folio = $res_muestras_to['muestra'];
                                $folio_interno = $res_muestras_to['folio_interno'];
                                $html.="<tr>
                                            <td>".$con."</td> 
                                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                            <td>".$muestra_folio."</td>
                                            <td>".$folio_interno."</td>
                                            <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$con.",".$unidad_id.")' >
                                                     <span class='fa fa-cloud fa-1x'></span>
                                                 </button>
                                            </td>
                                        </tr>";
                                $con = $con+1;
                            }
                        $html .= "</tbody></table></div>";
                       }                       
                       else{
                            $html = 'Ha finalizado la etapa.';
                       }
        }//Finaliza peso oro
        
        $mysqli -> set_charset("utf8");
         //echo utf8_encode($html);
         echo ($html);
  }
  
?>