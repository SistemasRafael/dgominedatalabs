<?php
include "connections/config.php";
$html = '';
$trn_id    = $_POST['trn_id'];
$metodo_id = $_POST['metodo'];
$fase_id   = $_POST['fase'];
$etapa_id  = $_POST['etapa'];
$u_id  = $_SESSION['u_id'];
$unidad_id = $_POST['unidad_tem'];

//echo 'aqui'.$fase_id.' '.$trn_id.$etapa_id;
if (isset($trn_id)){    
        $resultado = $mysqli->query("SELECT
                                	      ob.trn_id_rel
                                         ,ob.metodo_id
                                         ,ob.fase_id
                                         ,ob.etapa_id
                                         ,f.nombre as fase, et.nombre as etapa
                                         ,(l.cantidad_muestras+l.posiciones) AS total
                                         ,fe.cantidad_tipo
                                         ,(CASE fe.cantidad_tipo WHEN 1 THEN 'PORCIENTO' WHEN 0 THEN 'UNIDADES' WHEN 2 THEN 'CICLOS' END) AS tipo_cantidad_letra
                                         ,fe.cantidad_muestras
                                         ,met.nombre as metodo
                                    FROM 
                                        arg_ordenes_bitacora_detalle ob
                                        LEFT JOIN metodos_fases_etapas fe
                                        	ON fe.fase_id = ob.fase_id
                                            AND fe.etapa_id = ob.etapa_id
                                            AND fe.metodo_id = ob.metodo_id
                                        LEFT JOIN arg_fases f
                                        	ON f.fase_id = ob.fase_id
                                        LEFT JOIN arg_etapas et
                                        	ON et.etapa_id = fe.etapa_id
                                        LEFT JOIN ordenes_metodos_lista l
                                            ON l.trn_id_rel = ob.trn_id_rel
                                            AND l.metodo_id = ob.metodo_id
                                        LEFT JOIN arg_metodos met
                                            ON met.metodo_id = ob.metodo_id
                                    WHERE
                                        ob.trn_id_rel = ".$trn_id."
                                        AND ob.metodo_id = ".$metodo_id."
                                        AND ob.fase_id = ".$fase_id."
                                        AND ob.etapa_id = ".$etapa_id."
                                    ORDER BY ob.fecha DESC
                                    LIMIT 1") or die(mysqli_error($mysqli));
                                    
   $tipo_orden = $mysqli->query("SELECT 
                                    (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                    ,odet.folio_interno
                                FROM arg_ordenes ord
                                LEFT JOIN arg_ordenes_detalle odet
                                    ON ord.trn_id =  odet.trn_id_rel
                                WHERE odet.trn_id = ".$trn_id) or die(mysqli_error($mysqli));             
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
                                    	AND up.u_id = ".$u_id) or die(mysqli_error($mysqli));             
   $validar_supervi = $validar_superv->fetch_assoc();
   $supervisor = $validar_supervi['perfil_id'];   
        
    if ($resultado->num_rows > 0) {
        //Inicia metodo con pesaje de la fase 2
        if($fase_id == 2 && $etapa_id == 5){
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='5'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_resultados pul
                                                   WHERE
                                                        pul.trn_id = ".$trn_id." 
                                                        AND pul.metodo_id = ".$metodo_id
                                                   ) or die(mysqli_error($mysqli));
                    
                 if ($existen_peso->num_rows > 0) {
                        if($reensaye == 0){
                            $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_batch,
                                                         ot.trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.posicion,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_transacciones ot
                                                            ON  pul.trn_id = ot.trn_id_batch
                                                            AND pul.trn_id_rel = ot.trn_id_rel
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." AND pul.metodo_id = ".$metodo_id." ORDER BY ot.folio_interno") 
                                                  or die(mysqli_error($mysqli));                    
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
                                        
                                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error($mysqli)); 
                                    }
                                    else{
                                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes_recheck ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error($mysqli)); 
                                    }
                        }
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td>".$muestra_control."</td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($tipo_can == 1){ //Porcentaje
                        //echo 'porc aqui'.$reensaye;
                        $limite = (($cantidad_muestras*$total)/100);
                        //echo $limite;
                        if ($reensaye == 0){                            
                            $resultado_mues = $mysqli->query("SELECT * FROM (SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade.control
                                                                            FROM
                                                                                ordenes_transacciones ade
                                                                            WHERE
                                                                                ade.tipo_id = 0 AND ade.trn_id_batch = ".$trn_id."
                                                                            UNION ALL
                                                                            SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade1.control
                                                                            FROM
                                                                                ordenes_transacciones ade1
                                                                            WHERE
                                                                                ade1.tipo_id <> 0 AND ade1.metodo_id = ".$metodo_id." AND ade1.trn_id_batch = ".$trn_id."
                                                                            ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                            LIMIT ".$limite.") AS x 
                                                        ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                        }
                        else{
                            $resultado_mues = $mysqli->query("SELECT
                                                                    trn_id_rel AS trn_id_batch,
                                                                    trn_id_muestra AS trn_id_rel,
                                                                    folio_interno AS muestra,
                                                                    control                                                  
                                                              FROM 
                                                                    `ordenes_reensayes`                                                                      
                                                              WHERE trn_id_rel = ".$trn_id." 
                                                                    AND metodo_id = ".$metodo_id."
                                                              ORDER BY folio_interno"
                                                             ) or die(mysqli_error($mysqli));
                        }
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];
                            $query = "INSERT INTO arg_muestras_resultados (trn_id, trn_id_rel, metodo_id, peso, peso_payon, absorcion, validacion_tipo, porcentaje, reensaye)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0, 0, 0, 0, 0, 0)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$muestra_control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
                  if ($tipo_can == 0){//Unidades
                        $limite = $cantidad_muestras;
                        
                        if ($reensaye == 0){
                            $resultado_mues = $mysqli->query(" SELECT * FROM  
                                                                    (SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade.control
                                                                            FROM
                                                                                ordenes_transacciones ade
                                                                            WHERE
                                                                                ade.tipo_id = 0 AND ade.trn_id_batch = ".$trn_id."
                                                                            UNION ALL
                                                                            SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade1.control
                                                                            FROM
                                                                                ordenes_transacciones ade1
                                                                            WHERE
                                                                                ade1.tipo_id <> 0 AND ade1.metodo_id = ".$metodo_id." AND ade1.trn_id_batch = ".$trn_id."
                                                                    ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                LIMIT ".$limite.") AS x ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                        }
                        else{                            
                            $resultado_mues = $mysqli->query(" SELECT * FROM  
                                                                (SELECT
                                                                    	trn_id_rel AS trn_id_batch,                                                                        
                                                                        trn_id_muestra AS trn_id_rel,
                                                                        folio_interno as muestra,
                                                                        control                                                  
                                                                    FROM 
                                                                        `ordenes_reensayes`                                                                        
                                                                    WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id."
                                                                    ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                LIMIT ".$limite.") AS x ORDER BY muestra")   or die(mysqli_error($mysqli));
                        }
                         while ($res_muestras = $resultado_mues->fetch_assoc()) {                                              
                            $html.="<tr>
                                     <td style='display:none;'> <input type='input' id='trn_batch_met".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_met".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                                </tr>"; 
                            $con = $con+1;
                        }    
                  }
                  
                  if ($tipo_can == 2){//Ciclos 
                  //echo 'enctro ciclo';
                     $bloques = round($total/$cantidad_muestras);
                     $con = 1;
                     $posicion = $cantidad_muestras;
                             
                     while ($con <= $bloques){
                         if ($posicion > $total){
                            $posicion = $total;                        
                         }
                         if ($reensaye == 0){
                            $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade.control
                                                                            FROM
                                                                                ordenes_transacciones ade
                                                                            WHERE
                                                                                ade.tipo_id = 0 AND ade.trn_id_batch = ".$trn_id."
                                                                            UNION ALL
                                                                            SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade1.control
                                                                            FROM
                                                                                ordenes_transacciones ade1
                                                                            WHERE
                                                                                ade1.tipo_id <> 0 AND ade1.metodo_id = ".$metodo_id." AND ade1.trn_id_batch = ".$trn_id."
                                                                                AND posicion = ".$posicion.") AS x 
                                                        ORDER BY bloque, posicion") or die(mysqli_error($mysqli));
                         }
                         else{
                            $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT
                                                        	trn_id_rel AS trn_id_batch,
                                                            trn_id_muestra AS trn_id_rel,
                                                            folio_interno as muestra,
                                                            control                                                 
                                                        FROM 
                                                            `ordenes_reensayes` 
                                                        WHERE tipo_id = 0 AND trn_id_rel = ".$trn_id." AND metodo_id = ".$metodo_id.") AS x 
                                                        ORDER BY muestra") or die(mysqli_error($mysqli));
                         }             
                         
                       if ($resultado_mues->num_rows > 0) {
                           $res_muestras = $resultado_mues->fetch_assoc();
                           $trn_id_batch = $res_muestras['trn_id_batch'];
                           $trn_id_rel   = $res_muestras['trn_id_rel'];
                           $muestra      = $res_muestras['muestra'];
                           $muestra_control = $res_muestras['control'];
                           
                           $query = "INSERT INTO arg_muestras_resultado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                           $mysqli->query($query);  
                                                                    
                                $html.="<tr>                                   
                                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra_control."' disabled></td> 
                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>                                           
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                                 </button>
                                            </td>
                                        </tr>";                                        
                                 $con      = $con+1;   
                                 $posicion = $posicion+$cantidad_muestras;
                        }
                        else{
                            $posicion = $posicion+1;
                        }
                   }
                }        
            }                        
         }         
       }//Fin etapa 5 pesaje muestras de la fase 1
       //Inicia fundicion
       if($fase_id == 2 && $etapa_id == 8){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_temperatura'>
                            <thead class='thead-info' align='center'>
                                <tr class='table-info'>
                                    <th colspan='6'>".$metodo."</th>
                                </tr>
                                <tr class='table-info'>
                                    <th colspan='6'>Fase: ".$fase." Etapa: ".$etapa."</th>
                                </tr>
                                <tr class='table-warning' align='center'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                        <th>Instrumento</th>
                                        <th>Temperatura</th>
                                        <th></th>
                                        <th></th>                      
                            </thead>
                            <tbody>
                            <tr>";                             
                                $result_h = $mysqli->query("SELECT  ins_id, nombre FROM arg_instrumentos WHERE fase_id = 2 AND etapa_id = 8") or die(mysqli_error($mysqli));   
                                $html.="<td><select name='ins_id' id='ins_id' class='form-control'>";                          
                                while ( $row2 = $result_h ->fetch_assoc()) {
                                    $instrumento = $row2['nombre'];
                                    $ins_id = $row2['ins_id'];
                                    $html.="<option value='$ins_id'>$instrumento</option>";
                                }
                                $html.="</select></td>                                                               
                                    <td> <input type='input' class='form-control' id='cantidad_tem'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='temperatura_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
        }
        //Pesaje payon Ensaye a fuego
        if($fase_id == 2 && $etapa_id == 6){
        while ($res = $resultado->fetch_assoc()) {
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tab_datos_payon'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." - ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>            
                           
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Pay�n g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 0;
                  if ($reensaye == 0){
                        $existen_peso_pay = $mysqli->query("SELECT
                                                	         ot.trn_id_batch,
                                                             ot.trn_id_rel,
                                                             ROUND(pul.peso_payon, 2) AS peso,
                                                             ot.folio_interno as muestra,
                                                             ot.control
                                                           FROM 
                                                           arg_muestras_resultados pul
                                                           LEFT JOIN ordenes_transacciones ot
                                                                ON pul.trn_id = ot.trn_id_batch
                                                                AND pul.trn_id_rel = ot.trn_id_rel
                                                                AND ot.tipo_id = 0
                                                           WHERE
                                                              pul.peso_payon = 0 
                                                              AND ot.tipo_id = 0
                                                              AND pul.metodo_id = ".$metodo_id." 
                                                              AND pul.trn_id = ".$trn_id."
                                                        
                                                           UNION ALL
                                                                SELECT
                                                    	         ot1.trn_id_batch,
                                                                 ot1.trn_id_rel,
                                                                 ROUND(mr.peso_payon, 2) AS peso,
                                                                 ot1.folio_interno as muestra,
                                                                 ot1.control
                                                               FROM 
                                                               arg_muestras_resultados mr
                                                               LEFT JOIN ordenes_transacciones ot1
                                                                    ON mr.trn_id = ot1.trn_id_batch
                                                                    AND mr.trn_id_rel = ot1.trn_id_rel
                                                                    AND ot1.tipo_id <> 0
                                                               WHERE
                                                                    mr.peso_payon = 0
                                                                    AND ot1.tipo_id <> 0
                                                                    AND mr.metodo_id = ".$metodo_id." 
                                                                    AND mr.trn_id = ".$trn_id."
                                                              ORDER BY muestra") or die(mysqli_error($mysqli));
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
                                        $existen_peso_pay = $mysqli->query("SELECT
                                                	         ot.trn_id_rel AS trn_id_batch,
                                                             ot.trn_id_muestra AS trn_id_rel,
                                                             ROUND(pul.peso_payon, 2) AS peso,
                                                             ot.folio_interno AS muestra  
                                                           FROM 
                                                           arg_muestras_resultados pul
                                                           LEFT JOIN ordenes_reensayes ot
                                                                ON pul.trn_id = ot.trn_id_rel
                                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                                AND pul.metodo_id = ot.metodo_id
                                                           WHERE
                                                              pul.peso_payon = 0 
                                                              AND pul.metodo_id = ".$metodo_id." 
                                                              AND pul.trn_id = ".$trn_id."
                                                           ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                                    }
                                    else{
                                        $existen_peso_pay = $mysqli->query("SELECT
                                                	         ot.trn_id_rel AS trn_id_batch,
                                                             ot.trn_id_muestra AS trn_id_rel,
                                                             ROUND(pul.peso_payon, 2) AS peso,
                                                             ot.folio_interno AS muestra  
                                                           FROM 
                                                           arg_muestras_resultados pul
                                                           LEFT JOIN ordenes_reensayes_recheck ot
                                                                ON pul.trn_id = ot.trn_id_rel
                                                                AND pul.trn_id_rel = ot.trn_id_muestra
                                                                AND pul.metodo_id = ot.metodo_id
                                                           WHERE
                                                              pul.peso_payon = 0 
                                                              AND pul.metodo_id = ".$metodo_id." 
                                                              AND pul.trn_id = ".$trn_id."
                                                           ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                                    }
                  }
                  
                 if ($existen_peso_pay->num_rows > 0) {                                       
                        while ($res_muestras_pay = $existen_peso_pay->fetch_assoc()) {
                            $con           = $con+1;    
                            $trnid_batch   = $res_muestras_pay['trn_id_batch'];
                            $trnid_rel     = $res_muestras_pay['trn_id_rel'];
                            $muestra_folio = $res_muestras_pay['muestra'];
                            $control = $res_muestras_pay['control'];
                            $peso_actual   = $res_muestras_pay['peso_payon'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_pay".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_pay".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>                                         
                                         <td> <input type='number' id='peso_pay".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";
                        }
                }
                else{
                    /*$datos_gen = $resultado ->fetch_array(MYSQLI_ASSOC);
                    $metodo_codigo = $datos_gen['metodo'];
                    $metodo_fase = $datos_gen['fase'];
                    $metodo_etapa = $datos_gen['etapa'];*/
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
                                                se.trn_id = ".$trn_id."
                                                AND se.metodo_id = ".$metodo_id."
                                                AND ".$fase_id." = 2
                                             ORDER BY 
                                                om.folio_interno"
                                            ) or die(mysqli_error($mysqli));
                    }else{
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
                                                              ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
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
                                                           ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                                    }
                        
                    }
                    
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
                                             <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardarEdit(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$cont.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                  </button>
                                             </td>";
                                        }                                                                           
                                        $html.="</tr>"; 
                            }
                     $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizar(".$trnid_batch_met.",".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                                    <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                                </button></td>";
                     $html.="</div>";
                     $html .= "</tbody></table></div>";
                }
            }
        }
       //Inicia copelado
       if($fase_id == 2 && $etapa_id == 9){
           while ($res = $resultado->fetch_assoc()) {   
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_copelado'>
                            <thead class='thead-info' align='center'>
                                <tr class='table-info'>
                                        <th colspan='1'>".$metodo."</th>
                                        <th colspan='2'>".$fase."</th>
                                        <th colspan='2'>".$etapa."</th>
                                </tr>
                                <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                        <th>Horno</th>
                                        <th colspan='4'>Temperatura</th>        
                            </thead>
                            <tbody>
                            <tr>";                             
                                $result_h = $mysqli->query("SELECT  ins_id as ins_id_cop, nombre FROM arg_instrumentos WHERE fase_id = 2 AND etapa_id = 9") or die(mysqli_error($mysqli));   
                                $html.="<td><select name='ins_id_cop' id='ins_id_cop' class='form-control'>";                          
                                while ( $row2 = $result_h ->fetch_assoc()) {
                                    $instrumento_cop = $row2['nombre'];
                                    $ins_id_cop = $row2['ins_id_cop'];
                                    $html.="<option value='$ins_id_cop'>$instrumento_cop</option>";
                                }
                                $html.="</select></td>                                                               
                                    <td> <input type='input' class='form-control' id='cantidad_cop'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_cop' onclick='copelado_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
        }        
        //Inicia digesti�n Ensaye a fuego
       if($fase_id == 2 && $etapa_id == 4){         
           while ($res = $resultado->fetch_assoc()) {               
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_digestion'>
                           <thead class='thead-info' align='center'>
                                <tr class='table-info'>
                                        <th colspan='1'>".$metodo."</th>
                                        <th colspan='2'>".$fase."</th>
                                        <th colspan='2'>".$etapa."</th>
                                </tr>
                                <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                </tr>                            
                                <tr class='table-info' align='left'>
                                        <th colspan='4'>Temperatura:</th>        
                            </thead>
                            <tbody>
                            <tr>"; 
                                $html.="<td> <input type='input' class='form-control' id='cantidad_dig'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_dig' onclick='digestion_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
        }
        //Inicia metodo con pesaje de la fase 6 via humeda
        if($fase_id == 6 && $etapa_id == 5){
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='5'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>    
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  $existen_peso = $mysqli->query("SELECT
                                            	    *
                                                  FROM 
                                                    arg_muestras_resultados pul
                                                  WHERE
                                                     pul.trn_id = ".$trn_id." 
                                                     AND pul.metodo_id = ".$metodo_id
                                                  ) or die(mysqli_error($mysqli));
            
                 if ($existen_peso->num_rows > 0) {
                        if($reensaye == 0){                        
                            $peso_det = $mysqli->query("SELECT
                                                    	     ot.trn_id_batch,
                                                             ot.trn_id_rel,
                                                             ROUND(pul.peso, 2) AS peso,
                                                             ot.folio_interno as muestra,
                                                             ot.posicion,
                                                             ot.control
                                                          FROM 
                                                            arg_muestras_resultados pul
                                                            LEFT JOIN ordenes_transacciones ot
                                                                ON  pul.trn_id = ot.trn_id_batch
                                                                AND pul.trn_id_rel = ot.trn_id_rel
                                                          WHERE
                                                             pul.metodo_id = ".$metodo_id." AND pul.peso = 0 AND pul.trn_id = ".$trn_id."
                                                          ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                        }
                        else{
                            /*$peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                      AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error()); */
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
                                        
                                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error($mysqli)); 
                                    }
                                    else{
                                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes_recheck ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error($mysqli)); 
                                    }
                        }            
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];                            
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$muestra_control."</td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($tipo_can == 1){ //Porcentaje
                        //echo 'porc';
                        $limite = (($cantidad_muestras*$total)/100);
                        //echo $limite;
                        if ($reensaye == 0){
                            $resultado_mues = $mysqli->query("SELECT * FROM  ( SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade.control
                                                                            FROM
                                                                                ordenes_transacciones ade
                                                                            WHERE
                                                                                ade.tipo_id = 0 AND ade.trn_id_batch = ".$trn_id."
                                                                            UNION ALL
                                                                            SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade1.control
                                                                            FROM
                                                                                ordenes_transacciones ade1
                                                                            WHERE
                                                                                ade1.tipo_id <> 0 AND ade1.metodo_id = ".$metodo_id." AND ade1.trn_id_batch = ".$trn_id."
                                                                            ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                            LIMIT ".$limite.") AS x 
                                                        ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                        }
                        else{
                            
                                /*$resultado_mues = $mysqli->query("SELECT
                               	                                        trn_id_rel AS trn_id_batch,                                                                                
                                                                        trn_id_muestra AS trn_id_rel,
                                                                        folio_interno AS muestra,
                                                                        control                                                  
                                                                  FROM 
                                                                      `ordenes_reensayes`                                                                      
                                                                  WHERE 
                                                                        trn_id_rel = ".$trn_id." 
                                                                        AND metodo_id = ".$metodo_id
                                                                )   or die(mysqli_error());*/
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
                                            $resultado_mues = $mysqli->query("SELECT
                               	                                        trn_id_rel AS trn_id_batch,                                                                                
                                                                        trn_id_muestra AS trn_id_rel,
                                                                        folio_interno AS muestra,
                                                                        control                                                  
                                                                  FROM 
                                                                      `ordenes_reensayes`                                                                      
                                                                  WHERE 
                                                                        trn_id_rel = ".$trn_id." 
                                                                        AND metodo_id = ".$metodo_id) or die(mysqli_error($mysqli));
                                    }
                                    else{
                                        $resultado_mues = $mysqli->query("SELECT
                                                	         ot.trn_id_rel AS trn_id_batch,
                                                             ot.trn_id_muestra AS trn_id_rel,
                                                             ot.folio_interno AS muestra,  
                                                             ot.control
                                                           FROM 
                                                              ordenes_reensayes_recheck ot                                                               
                                                           WHERE
                                                              ot.metodo_id = ".$metodo_id." 
                                                              AND ot.trn_id_rel = ".$trn_id."
                                                           ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                                    }
                        }
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            $query = "INSERT INTO arg_muestras_resultados (trn_id, trn_id_rel, metodo_id, peso, peso_payon, absorcion, validacion_tipo, porcentaje, reensaye)".
                                                               " VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0, 0, 0, 0, 0, 0)";
                            $mysqli->query($query);
                                                        
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
                  if ($tipo_can == 0){//Unidades
                        $limite = $cantidad_muestras;
                        $resultado_mues = $mysqli->query(" SELECT * FROM  (SELECT
                                                    	trn_id_batch,
                                                        bloque,
                                                        posicion,
                                                        trn_id_rel,
                                                        folio_interno as muestra                                                   
                                                    FROM 
                                                        `ordenes_transacciones` WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id."
                                                    ORDER BY (FLOOR (1+RAND()*".$total."))
                                                    LIMIT ".$limite.") AS x ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                         while ($res_muestras = $resultado_mues->fetch_assoc()) {
                                              
                            $html.="<tr>
                                     <td style='display:none;'> <input type='input' id='trn_batch_met".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_met".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                                </tr>"; 
                            $con = $con+1;
                        }    
                  }
                  
                  if ($tipo_can == 2){//Ciclos 
                  //echo 'enctro ciclo';
                     $bloques = round($total/$cantidad_muestras);
                     $con = 1;
                     $posicion = $cantidad_muestras;
                             
                     while ($con <= $bloques){
                         if ($posicion > $total){
                            $posicion = $total;                        
                         }                     
                         $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT
                                                        	trn_id_batch,
                                                            bloque,                                                            
                                                            posicion,
                                                            trn_id_rel,
                                                            folio_interno as muestra                                                   
                                                        FROM 
                                                            `ordenes_transacciones` 
                                                        WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id." AND posicion = ".$posicion.") AS x 
                                                        ORDER BY bloque, posicion") or die(mysqli_error($mysqli));
                     
                       if ($resultado_mues->num_rows > 0) {
                           $res_muestras = $resultado_mues->fetch_assoc();
                           $trn_id_batch = $res_muestras['trn_id_batch'];
                           $trn_id_rel   = $res_muestras['trn_id_rel'];
                           $muestra      = $res_muestras['muestra'];
                           
                           $query = "INSERT INTO arg_muestras_resultado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                           $mysqli->query($query);  
                                                                    
                                $html.="<tr>                                   
                                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>                                           
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                                 </button>
                                            </td>
                                        </tr>";                                        
                                 $con      = $con+1;   
                                 $posicion = $posicion+$cantidad_muestras;
                        }
                        else{
                            $posicion = $posicion+1;
                        }
                   }
                }        
            }                        
         }         
       }//Fin fase 6 y etapa 5 pesaje muestras
       //Inicia metodo con pesaje de la fase 7
        if($fase_id == 7 && $etapa_id == 5){
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='5'>M�todo: f".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>    
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>Peso</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  $existen_peso = $mysqli->query("SELECT
                                            	     * 
                                                  FROM 
                                                    arg_muestras_resultados pul
                                                  WHERE
                                                     pul.trn_id = ".$trn_id." 
                                                     AND pul.metodo_id = ".$metodo_id."
                                                 ") or die(mysqli_error($mysqli));
            
                 if ($existen_peso->num_rows > 0) {
                        if($reensaye == 0){                        
                            $peso_det = $mysqli->query("SELECT
                                            	     ot.trn_id_batch,
                                                     ot.trn_id_rel,
                                                     ROUND(pul.peso, 2) AS peso,
                                                     ot.folio_interno as muestra,
                                                     ot.posicion,
                                                     ot.control
                                                  FROM 
                                                    arg_muestras_resultados pul
                                                    LEFT JOIN ordenes_transacciones ot
                                                        ON  pul.trn_id = ot.trn_id_batch
                                                        AND pul.trn_id_rel = ot.trn_id_rel
                                                  WHERE
                                                     pul.metodo_id = ".$metodo_id." AND pul.peso = 0 AND pul.trn_id = ".$trn_id."
                                                  ORDER BY ot.folio_interno") or die(mysqli_error($mysqli));
                        }
                        else{
                            $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 2) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_resultados pul
                                                        LEFT JOIN ordenes_reensayes ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                      AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.folio_interno") 
                                                or die(mysqli_error($mysqli)); 
                        }                    
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];                            
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$muestra_control."</td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($tipo_can == 1){ //Porcentaje
                        $limite = (($cantidad_muestras*$total)/100);
                        //echo $limite;
                        if ($reensaye == 0){
                            $resultado_mues = $mysqli->query("SELECT * FROM  ( SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade.control
                                                                            FROM
                                                                                ordenes_transacciones ade
                                                                            WHERE
                                                                                ade.tipo_id = 0 AND ade.trn_id_batch = ".$trn_id."
                                                                            UNION ALL
                                                                            SELECT
                                                                                trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                ade1.control
                                                                            FROM
                                                                                ordenes_transacciones ade1
                                                                            WHERE
                                                                                ade1.tipo_id <> 0 AND ade1.metodo_id = ".$metodo_id." AND ade1.trn_id_batch = ".$trn_id."
                                                                            ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                            LIMIT ".$limite.") AS x 
                                                        ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                        }
                        else{
                                $resultado_mues = $mysqli->query("SELECT * FROM (SELECT
                                                                            	trn_id_rel AS trn_id_batch,                                                                                
                                                                                trn_id_muestra AS trn_id_rel,
                                                                                folio_interno AS muestra,
                                                                                control                                                  
                                                                            FROM 
                                                                                `ordenes_reensayes`                                                                      
                                                                            WHERE trn_id_rel = ".$trn_id." AND metodo_id = ".$metodo_id."
                                                                            ORDER BY (FLOOR (1+RAND()*".$total."))
                                                                            LIMIT ".$limite.") AS x 
                                                                ORDER BY folio_interno")   or die(mysqli_error($mysqli));
                        }
                        $con = 1;
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];
                            $query = "INSERT INTO arg_muestras_resultados (trn_id, trn_id_rel, metodo_id, peso, peso_payon, absorcion, validacion_tipo, porcentaje, reensaye)".
                                                               " VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0, 0, 0, 0, 0, 0)";
                                                               
                                                               
                            $mysqli->query($query);
                                                        
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$muestra_control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
                  if ($tipo_can == 0){//Unidades
                        $limite = $cantidad_muestras;
                        $resultado_mues = $mysqli->query(" SELECT * FROM  (SELECT
                                                    	trn_id_batch,
                                                        bloque,
                                                        posicion,
                                                        trn_id_rel,
                                                        folio_interno as muestra                                                   
                                                    FROM 
                                                        `ordenes_transacciones` WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id."
                                                    ORDER BY (FLOOR (1+RAND()*".$total."))
                                                    LIMIT ".$limite.") AS x ORDER BY bloque, posicion")   or die(mysqli_error($mysqli));
                         while ($res_muestras = $resultado_mues->fetch_assoc()) {
                                              
                            $html.="<tr>
                                     <td style='display:none;'> <input type='input' id='trn_batch_met".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_met".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                                </tr>"; 
                            $con = $con+1;
                        }    
                  }
                  
                  if ($tipo_can == 2){//Ciclos 
                  //echo 'enctro ciclo';
                     $bloques = round($total/$cantidad_muestras);
                     $con = 1;
                     $posicion = $cantidad_muestras;
                             
                     while ($con <= $bloques){
                         if ($posicion > $total){
                            $posicion = $total;                        
                         }                     
                         $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT
                                                        	trn_id_batch,
                                                            bloque,                                                            
                                                            posicion,
                                                            trn_id_rel,
                                                            folio_interno as muestra                                                   
                                                        FROM 
                                                            `ordenes_transacciones` 
                                                        WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id." AND posicion = ".$posicion.") AS x 
                                                        ORDER BY bloque, posicion") or die(mysqli_error($mysqli));
                     
                       if ($resultado_mues->num_rows > 0) {
                           $res_muestras = $resultado_mues->fetch_assoc();
                           $trn_id_batch = $res_muestras['trn_id_batch'];
                           $trn_id_rel   = $res_muestras['trn_id_rel'];
                           $muestra      = $res_muestras['muestra'];
                           
                           $query = "INSERT INTO arg_muestras_resultado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                           $mysqli->query($query);  
                                                                    
                                $html.="<tr>                                   
                                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>                                           
                                            <td> <button type='button'class='btn btn-primary' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                                 </button>
                                            </td>
                                        </tr>";                                        
                                 $con      = $con+1;   
                                 $posicion = $posicion+$cantidad_muestras;
                        }
                        else{
                            $posicion = $posicion+1;
                        }
                   }
                }        
            }                        
         }         
       }//Fin fase 6 y etapa 5 pesaje muestras
       
        //Inicia fase de cianuracion - cianuro
       if($fase_id == 7 && $etapa_id == 16){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_temperatura'>
                            <thead class='thead-info' align='left'>
                                <tr class='table-info'>
                                        <th colspan='6'>".$metodo."</th>
                                </tr>
                                <tr class='table-info'>
                                        <th colspan='6'>Fase: ".$fase." Etapa: ".$etapa."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                        <th>Temperatura de NaCN</th>
                                        <th></th>
                                        <th></th>                      
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.="</select></td>                                                               
                                    <td> <input type='input' class='form-control' id='cantidad_tem_cia'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='temperatura_guardar_cianuro(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
          }//terminar cianuracion
          
       //Inicia fase de cianuracion - Agitacion
       if($fase_id == 7 && $etapa_id == 17){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa']; 
                $Object = new DateTime(); 
                $fecha_hora_ini = $Object->format("d/m/Y h:i:s a");
            }                
            $html =  "<table class='table text-black' id='datos_temperatura'>
                            <thead class='thead-info' align='left'>
                                <tr class='table-info'>
                                        <th colspan='3'>".$metodo."</th>
                                </tr>
                                <tr class='table-info'>
                                        <th colspan='3'>Fase: ".$fase." Etapa: ".$etapa."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                        <th>Hora de Inicio</th>
                                        <th>Hora de Finalizaci�n</th>
                                        <th></th>                                                         
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.="                                                           
                                    <td> <input type='datetime-local' class='form-control' id='hora_inicio'></td>
                                    <td> <input type='input' class='form-control' value='".$fecha_hora_ini."' id='hora_final'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='agitacion_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
          }//terminar agitacion
          
        //Inicia fase de cianuracion - Centrifugado
       if($fase_id == 7 && $etapa_id == 18){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
                $Object = new DateTime(); 
                $fecha_hora = $Object->format("d-m-Y h:i:s a");  
            }                
            $html =  "<table class='table text-black' id='datos_temperatura'>
                            <thead class='thead-info' align='left'>
                                <tr class='table-info'>
                                        <th colspan='3'>".$metodo."</th>
                                </tr>
                                <tr class='table-info'>
                                        <th colspan='3'>Fase: ".$fase." Etapa: ".$etapa."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                       
                                        <th>Hora de Finalizaci�n de Centrifugado</th>
                                        <th></th>    
                                        <th></th>                                                      
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.=" 
                                    <td> <input type='dametime-local' class='form-control' value='".$fecha_hora."' id='hora_final_cen'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='centrifugado_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                                    <td> </td>
                            </tr>";
          }//terminar agitacion          
          
       //Inicia digesti�n v�a h�meda
       if($fase_id == 6 && $etapa_id == 4){
         
           while ($res = $resultado->fetch_assoc()) { 
               
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_digestion'>
                           <thead class='thead-info' align='left'>
                                <tr class='table-info'>
                                        <th colspan='1'>".$metodo."</th>
                                        <th colspan='2'>".$fase."</th>
                                        <th colspan='2'>".$etapa."</th>
                                </tr>                               
                                <tr class='table-info' align='left'>
                                        <th colspan='4'>Temperatura:</th>        
                            </thead>
                            <tbody>
                            <tr>"; 
                                $html.="<td> <input type='input' class='form-control' id='cantidad_dig'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_dig' onclick='digestion_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
        }
        //Inicia lectura de absorci�n atomica: exportar/importar csv
         if($etapa_id == 7){             
            while ($res = $resultado->fetch_assoc()) {   
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }  
                  $PHP_SELF = "";
                  $html =  "<table class='table text-black' id='datos_exportar'>
                                <thead class='table-secondary' align='center'>
                                <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                <tr>
                                    <th>EXPORTAR ARCHIVO .CSV </th>                                  
                                      <tr class='table-primary' align='left'>
                                        <th colspan='5'>
                                            <button type='button' class='btn btn-success' onclick='exportar_absorcion(".$trn_id.", ".$metodo_id.", ".$u_id.")' >
                                                <span class='fa fa-file-excel-o fa-2x'> Exportar </span>
                                            </button>
                                    </th>";
                  
                  $html .=  "<table class='table text-black' id='datos_importar'>
                                <thead class='table-secondary' align='center'>
                                <tr>
                                    <th>IMPORTAR ARCHIVO .CSV </th>
                                    <th>  </th>
                                    <tr class='table-primary' align='left'>
                                        <th colspan='4'>                                        
                                            <form name='importa' method='post' action='$PHP_SELF' enctype='multipart/form-data' >
                                                <input type='file' name='excel' />
                                                </br>
                                               <button type='input' class='btn btn-success' value='upload' name='action' >
                                                <span class='fa fa-file-excel-o fa-2x'> Importar </span>
                                              </button>
                                            </form>    
                                        </th>";
        } 
        
          //Inicia metodo con pesaje de la fase 7        
        if($etapa_id == 26 || $etapa_id == 24 || $etapa_id == 25){      
            while ($res = $resultado->fetch_assoc()) {   
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  $element           = $res['elemento'];
            }      
            echo $etapa;
                  
                  $html =  "<table class='table text-black' id='tabla_titulacion'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='5'>M&eacutetodo: ".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>    
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra Solucion</th>
                                        <th>".$element."</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  $existen_titulacion = $mysqli->query("SELECT
                                                    	     * 
                                                        FROM 
                                                            arg_ordenes_soluciones sol
                                                        WHERE
                                                             sol.trn_id_batch = ".$trn_id." 
                                                             AND sol.metodo_id = ".$metodo_id
                                                         ) or die(mysqli_error($mysqli));
                    
                 if ($existen_titulacion->num_rows > 0) {                                         
                        $titulacion_det = $mysqli->query("SELECT DISTINCT (ms.folio) as muestra
                                                            ,os.trn_id_batch
                                                            ,os.trn_id_rel                                                           
                                                            ,os.resultado             
                                                            ,ms.orden                                          
                                                          FROM `arg_ordenes_soluciones`  os
                                                          LEFT JOIN arg_ordenes_muestrasSoluciones ms
                                                          	ON os.trn_id_rel = ms.trn_id
                                                          WHERE
                                                             os.metodo_id =  ".$metodo_id." 
                                                             AND os.resultado = 0 
                                                             AND os.trn_id_batch = ".$trn_id."
                                                            ORDER BY
                                                               ms.orden ") or die(mysqli_error($mysqli));
                                        
                        while ($res_muestras = $titulacion_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                        
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_tit".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save_tit' onclick='met_titulacion_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }  
           }    
    
    $html .=  "</tbody></thead></table> ";     
    }
}

//echo ($html);

 $mysqli -> set_charset("utf8");
echo utf8_encode($html);


?>

