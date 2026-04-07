<?php include "connections/config.php"; ?>
<?php
//$html = '';
$trn_id_fin = $_POST['trnid_pay'];
$metodo_id  = $_POST['metodo_id_pay'];
$fase_id    = $_POST['fase_pay'];
$etapa_id   = $_POST['etapa_pay'];
$u_id       = $_SESSION['u_id'];

if (isset($trn_id_fin)) {
    if($fase_id == 11 and $etapa_id == 6){

        mysqli_multi_query($mysqli, "CALL arg_prc_pesoPayonFinalizaCarb($trn_id_fin,$metodo_id,$fase_id,$etapa_id, $u_id)") or die(mysqli_error($mysqli));
        
        $tipo_orden = $mysqli->query("SELECT 
                                            (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                            ,odet.folio_interno
                                        FROM arg_ordenes ord
                                        LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                        WHERE odet.trn_id = ".$trn_id_fin) or die(mysqli_error($mysqli));             
        $tipo_ord = $tipo_orden->fetch_assoc();
        $reensaye = $tipo_ord['reensaye'];
        $orden_trabajo = $tipo_ord['folio_interno'];   
        
            $html = 'La etapa ha finalizado';
            
            $html .=  "<table class='table text-black' id='tabla_pesaje_met'>
                                        <thead class='thead-info' align='center'>                                    
                                            <tr class='table-warning' align='center'>
                                                <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                            </tr>            
                                
                                            <tr class='table-info' align='left'>
                                                <th>No.</th>
                                                <th>Muestra</th>
                                                <th>Control</th>
                                                <th>Peso Pay&oacuten g</th>
                                                <th></th>                                
                                        </thead>
                                    <tbody>";

            if($reensaye == 0){
                $resultado = $mysqli->query("SELECT 
                                                    mq.trn_id_batch AS trnid_batch_met
                                                    ,mq.trn_id_rel AS trnid_rel_met
                                                    ,mq.metodo_id
                                                    ,ROUND(mc.peso_payon, 3) AS peso
                                                    ,mq.folio_interno AS muestra_met
                                                    ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                    ,mc.reensaye
                                                FROM 
                                                    arg_muestras_cianurado mc
                                                    LEFT JOIN ordenes_metalurgia mq
                                                        ON mc.trn_id = mq.trn_id_batch
                                                        AND mc.trn_id_rel = mq.trn_id_rel
                                                        AND mc.metodo_id = mq.metodo_id
                                                WHERE
                                                    mq.trn_id_batch  = ".$trn_id_fin."  
                                                    AND mq.metodo_id =  ".$metodo_id."
                                                ORDER BY mq.posicion"
                                                    ) or die(mysqli_error($mysqli));
            }else{
                $resultado = $mysqli->query("SELECT
                                                ot.trn_id_rel AS trnid_batch_met,
                                                ot.trn_id_muestra AS trnid_rel_met,
                                                ROUND(pul.peso_payon, 3) AS peso,
                                                ot.folio_interno as muestra_met,
                                                ot.control
                                            FROM 
                                            arg_muestras_cianurado pul
                                            LEFT JOIN ordenes_reensayes_metal ot
                                                ON  pul.trn_id = ot.trn_id_rel
                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                AND pul.metodo_id = ot.metodo_id
                                            WHERE
                                                pul.trn_id = ".$trn_id_fin." 
                                                AND pul.metodo_id = ".$metodo_id."
                                            ORDER BY 
                                                ot.posicion"
                                            ) or die(mysqli_error($mysqli));
            }
                            
                                $cont = 0;
                                while ($res_muestras = $resultado->fetch_assoc()) {
                                        $cont = $cont+1;
                                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                                        $muestra_met     = $res_muestras['muestra_met'];     
                                    // $metodo          = $res_muestras['metodo_nombre'];
                                        $peso            = $res_muestras['peso'];
                                        $control    = $res_muestras['control'];
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
                                                <td>".$control."</td>
                                                <td>".$peso."</td>";                                                                                                          
                                                $html.="</tr>"; 
                                    }
                            
                            $html .= "</tbody></table></div>";
            
        }// Fin peso payon
    elseif($fase_id == 10 and $etapa_id == 20){//Peso Doré

        mysqli_multi_query($mysqli, "CALL arg_prc_pesoFinalizaCarb($trn_id_fin,$metodo_id,$fase_id,$etapa_id, $u_id)") or die(mysqli_error($mysqli));
        
        $tipo_orden = $mysqli->query("SELECT 
                                            (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                            ,odet.folio_interno
                                        FROM arg_ordenes ord
                                        LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                        WHERE odet.trn_id = ".$trn_id_fin) or die(mysqli_error($mysqli));             
        $tipo_ord = $tipo_orden->fetch_assoc();
        $reensaye = $tipo_ord['reensaye'];
        $orden_trabajo = $tipo_ord['folio_interno'];   
        
           // $html = 'La etapa ha finalizado';
            
            $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                        <thead class='thead-info' align='center'>                                    
                                            <tr class='table-warning' align='center'>
                                                <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                            </tr>            
                                
                                            <tr class='table-info' align='left'>
                                                <th>No.</th>
                                                <th>Muestra</th>
                                                <th>Control</th>
                                                <th>Peso Dor&eacute mg</th>
                                                <th></th>                                
                                        </thead>
                                    <tbody>";
            
            if($reensaye == 0){

                $resultado = $mysqli->query("SELECT 
                                                    mq.trn_id_batch AS trnid_batch_met
                                                    ,mq.trn_id_rel AS trnid_rel_met
                                                    ,mq.metodo_id
                                                    ,ROUND(mc.peso_dore, 3) AS peso
                                                    ,mq.folio_interno AS muestra_met
                                                    ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                    ,mc.reensaye
                                                FROM 
                                                    arg_muestras_cianurado mc
                                                    LEFT JOIN ordenes_metalurgia mq
                                                        ON mc.trn_id = mq.trn_id_batch
                                                        AND mc.trn_id_rel = mq.trn_id_rel
                                                        AND mc.metodo_id = mq.metodo_id
                                                WHERE
                                                    mq.trn_id_batch  = ".$trn_id_fin."  
                                                    AND mq.metodo_id =  ".$metodo_id."
                                                    AND mc.reensaye = 0
                                                ORDER BY mq.posicion"
                                                    ) or die(mysqli_error($mysqli));
            }
            else{
                $resultado = $mysqli->query("SELECT
                                                ot.trn_id_rel AS trnid_batch_met,
                                                ot.trn_id_muestra AS trnid_rel_met,
                                                ROUND(pul.peso_dore, 3) AS peso,
                                                ot.folio_interno as muestra_met,
                                                ot.control
                                            FROM 
                                            arg_muestras_cianurado pul
                                            LEFT JOIN ordenes_reensayes_metal ot
                                                ON  pul.trn_id = ot.trn_id_rel
                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                AND pul.metodo_id = ot.metodo_id
                                            WHERE
                                                pul.trn_id = ".$trn_id_fin." 
                                                AND pul.metodo_id = ".$metodo_id."
                                                AND pul.reensaye = 0
                                            ORDER BY 
                                                ot.posicion"
                                            ) or die(mysqli_error($mysqli));
            }
                            
                                $cont = 0;
                                while ($res_muestras = $resultado->fetch_assoc()) {
                                        $cont = $cont+1;
                                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                                        $muestra_met     = $res_muestras['muestra_met'];     
                                    // $metodo          = $res_muestras['metodo_nombre'];
                                        $peso            = $res_muestras['peso'];
                                        $control    = $res_muestras['control'];
                                        $reensaye_mos    = $res_muestras['reensaye'];
                                       
                                        $html .="<tr>";                           
                                        $html.="<td>".$cont."</td> 
                                                <td style='display:none;'> <input type='input' id='trnid_batch_pay".$cont."' value='".$trnid_batch_met."'/></td>  
                                                <td style='display:none;'> <input type='input' id='trnid_rel_pay".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                                                <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                                                <td>".$muestra_met."</td>
                                                <td>".$control."</td>
                                                <td>".$peso."</td>";                                                                                                          
                                                $html.="</tr>"; 
                                    }
                            
                            $html .= "</tbody></table></div>";
    }
    elseif($fase_id == 10 and $etapa_id == 21){//Peso Au

        mysqli_multi_query($mysqli, "CALL arg_prc_pesoFinalizaCarb($trn_id_fin,$metodo_id,$fase_id,$etapa_id, $u_id)") or die(mysqli_error($mysqli));
        
        $tipo_orden = $mysqli->query("SELECT 
                                            (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                            ,odet.folio_interno
                                        FROM arg_ordenes ord
                                        LEFT JOIN arg_ordenes_detalle odet
                                            ON ord.trn_id =  odet.trn_id_rel
                                        WHERE odet.trn_id = ".$trn_id_fin) or die(mysqli_error($mysqli));             
        $tipo_ord = $tipo_orden->fetch_assoc();
        $reensaye = $tipo_ord['reensaye'];
        $orden_trabajo = $tipo_ord['folio_interno'];   
        
           // $html = 'La etapa ha finalizado';
            
        $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                        <thead class='thead-info' align='center'>                                    
                                            <tr class='table-warning' align='center'>
                                                <th colspan='5'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                            </tr>            
                                
                                            <tr class='table-info' align='left'>
                                                <th>No.</th>
                                                <th>Muestra</th>
                                                <th>Control</th>
                                                <th>Peso Au mg</th>
                                                <th></th>                                
                                        </thead>
                                    <tbody>";
         
        if($reensaye == 0){
                                 
            $resultado = $mysqli->query("SELECT 
                                        mq.trn_id_batch AS trnid_batch_met
                                        ,mq.trn_id_rel AS trnid_rel_met
                                        ,mq.metodo_id
                                        ,ROUND(mc.peso_oro, 3) AS peso
                                        ,mq.folio_interno AS muestra_met
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                        ,mc.reensaye
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                        WHERE
                                            mq.trn_id_batch  = ".$trn_id_fin."  
                                            AND mq.metodo_id =  ".$metodo_id."
                                            AND mc.reensaye = 0
                                            ORDER BY mq.posicion"
                                    ) or die(mysqli_error($mysqli));
        }
        else{
            $resultado = $mysqli->query("SELECT
                                            ot.trn_id_rel AS trnid_batch_met,
                                            ot.trn_id_muestra AS trnid_rel_met,
                                            ROUND(pul.peso_oro, 3) AS peso,
                                            ot.folio_interno as muestra_met,
                                            ot.control
                                        FROM 
                                            arg_muestras_cianurado pul
                                        LEFT JOIN ordenes_reensayes_metal ot
                                            ON  pul.trn_id = ot.trn_id_rel
                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                            AND pul.metodo_id = ot.metodo_id
                                        WHERE
                                            pul.trn_id = ".$trn_id_fin." 
                                            AND pul.metodo_id = ".$metodo_id."
                                            AND pul.reensaye = 0
                                        ORDER BY 
                                            ot.posicion"
                                        ) or die(mysqli_error($mysqli));
        }
                            
                                $cont = 0;
                                while ($res_muestras = $resultado->fetch_assoc()) {
                                        $cont = $cont+1;
                                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                                        $muestra_met     = $res_muestras['muestra_met'];     
                                    // $metodo          = $res_muestras['metodo_nombre'];
                                        $peso            = $res_muestras['peso'];
                                        $control    = $res_muestras['control'];
                                        $reensaye_mos    = $res_muestras['reensaye'];
                                       
                                        
                                        $html .="<tr>";                                                                    
                                        $html.="<td>".$cont."</td> 
                                                <td style='display:none;'> <input type='input' id='trnid_batch_pay".$cont."' value='".$trnid_batch_met."'/></td>  
                                                <td style='display:none;'> <input type='input' id='trnid_rel_pay".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                                                <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                                                <td>".$muestra_met."</td>
                                                <td>".$control."</td>
                                                <td>".$peso."</td>";                                                                                                          
                                                $html.="</tr>"; 
                                    }
                            
                            $html .= "</tbody></table></div>";
    }
}

$mysqli->set_charset("utf8");
echo ($html);

?>