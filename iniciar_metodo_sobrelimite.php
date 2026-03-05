<?include "connections/config.php";?>

<!--<link href="http://192.168.20.3:81/__pro/argonaut/boostrapp/css/check.css" rel="stylesheet">--!>
<!--<link href="http://192.168.20.22/intranet-spa/css/check.css" rel="stylesheet"> --!>
<?php
$html = '';
$trn_id    = $_POST['trn_id'];
$metodo_id = $_POST['metodo'];
$fase_id   = $_POST['fase'];
$etapa_id  = $_POST['etapa'];
$u_id      = $_SESSION['u_id'];
$unidad_id = $_POST['unidad_tem'];
/*$trn_id    = 415;
$metodo_id = 1;
$fase_id   = 11;
$etapa_id  = 19;
$u_id      = 1;
$unidad_id = 1;
echo 'aqui'.$fase_id.' '.$trn_id.'etapa;'.$etapa_id;*/
if (isset($trn_id)){ 
   // echo 'entor';
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
                                    LIMIT 1") or die(mysqli_error());
                                    
   $tipo_orden = $mysqli->query("SELECT 
                                    (CASE WHEN ord.trn_id_rel = 0 THEN 0 ELSE 1 END) AS reensaye
                                    ,odet.folio_interno
                                FROM arg_ordenes ord
                                LEFT JOIN arg_ordenes_detalle odet
                                    ON ord.trn_id =  odet.trn_id_rel
                                WHERE odet.trn_id = ".$trn_id) or die(mysqli_error());             
   $tipo_ord = $tipo_orden->fetch_assoc();
   $reensaye = $tipo_ord['reensaye'];
   $orden_trabajo = $tipo_ord['folio_interno'];
   
   
        
    if ($resultado->num_rows > 0) {
        //Inicia metodo con pesajeecho
        //echo $orden_trabajo;
        if($etapa_id == 5){
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  echo $etapa;
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
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
                                        <th>Folio Interno</th>
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                 $con = 1;
                
                $existen_peso = $mysqli->query("SELECT *
                                                    
                                                FROM arg_muestras_resultados sl
                                                WHERE 
                                                sl.trn_id = ".$trn_id."
                                                AND sl.metodo_id = 1
                                                AND sl.peso = 0;                                                "
                                                ) or die(mysqli_error());
                    
                 if ($existen_peso->num_rows > 0) {
                     
                            $peso_det = $mysqli->query("SELECT sl.trn_id 
                                                        ,sl.trn_id_rel AS trn_id_batch
                                                        ,sl.trn_id_muestra AS trn_id_rel
                                                        ,sl.peso 
                                                        ,ot.muestra_geologia AS muestra
                                                        ,ot.folio_interno                                                 
                                                FROM arg_muestras_sobrelimites sl
                                                LEFT JOIN ordenes_transacciones ot
                                                    ON sl.trn_id_muestra = ot.trn_id_rel
                                                WHERE 
                                                sl.trn_id_rel = ".$trn_id."
                                                AND sl.metodo_id = 1
                                                AND sl.peso = 0
                                                ORDER BY folio_interno") 
                                                or die(mysqli_error()); 
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_folio   = $res_muestras['trn_id'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $folio_interno = $res_muestras['folio_interno'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td>".$folio_interno."</td>
                                         <td> <input type='number' id='peso_sob".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.", ".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($tipo_can == 1){ //Porcentaje
                        //echo 'porc aqui';
                        $limite = (($cantidad_muestras*$total)/100);
                        //echo $limite;
                        $resultado_mues = $mysqli->query("SELECT
                                                                            sl.trn_id_rel AS trn_id_batch,
                                                                            sl.trn_id_batch AS trn_origen,
                                                                            sl.trn_id_muestra,
                                                                            ade.folio_interno AS folio_interno,
                                                                            ade.muestra_geologia AS muestra                                                                            
                                                                        FROM
                                                                            arg_muestras_sobrelimites AS sl
                                                                        LEFT JOIN ordenes_transacciones ade 
                                                                        ON
                                                                            sl.trn_id_muestra = ade.trn_id_rel
                                                                        WHERE
                                                                            sl.trn_id_rel = ".$trn_id."
                                                                            AND sl.metodo_id = 1"
                                                                          )   or die(mysqli_error());
                       
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];                            
                            $trnid_origen  = $res_muestras['trn_origen'];
                            $trnid_rel     = $res_muestras['trn_id_muestra'];
                            $muestra_folio = $res_muestras['muestra'];
                            $muestra_control = $res_muestras['control'];
                            /*echo $muestra_folio;
                            echo $trnid_batch;*/
                            $query = "INSERT INTO arg_muestras_resultados (trn_id, trn_id_rel, metodo_id, peso, peso_payon, absorcion, validacion_tipo, porcentaje, reensaye)".
                                                               "VALUES ($trnid_batch, $trnid_rel, 1, 0, 0, 0, 0, 0, 0)";
                            $mysqli->query($query);  
                            $query = "INSERT INTO arg_muestras_sobrelimites (trn_id, trn_id_rel, trn_id_batch, trn_id_muestra, metodo_id, tipo_id, material_id, peso_payon, absorcion, fecha, inicio_proceso)".
                                                               "VALUES (generar_trn_muestrasSobr(), $trnid_batch, $trnid_origen, $trnid_rel, 1, 0, 0, 0, 0, now(), 0)";
                            $mysqli->query($query);  
                            
                            $resultado_mues_tot = $mysqli->query("SELECT
                                                                            sl.trn_id,
                                                                            sl.trn_id_rel AS trn_id_batch,
                                                                            sl.trn_id_batch AS trn_origen,
                                                                            sl.trn_id_muestra,
                                                                            ade.folio_interno AS folio_interno,
                                                                            ade.muestra_geologia AS muestra
                                                                        FROM
                                                                            arg_muestras_sobrelimites AS sl
                                                                        LEFT JOIN ordenes_transacciones ade 
                                                                        ON
                                                                            sl.trn_id_muestra = ade.trn_id_rel
                                                                        WHERE
                                                                            sl.trn_id_rel = ".$trn_id."
                                                                            AND sl.metodo_id = 1
                                                                        ORDER BY ade.folio_interno"
                                                                          )   or die(mysqli_error());
                       
                                        while ($res_muestras_to = $resultado_mues_tot->fetch_assoc()) {
                                            $trnid_folio      = $res_muestras_to['trn_id'];
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
                                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                              </button></td>
                                                    </tr>";
                                        }
                                            $con = $con+1;
                                        
                        }
                  }
                  
                  }
                }        
            }                        
                
       //Fin etapa 5 pesaje muestras de la fase 1
       
      
       //Inicia Incuarte del método EFGRA30
       //Inicia metodo EFGRA30 con incuarte de la fase 9 Etapa 19
        if($etapa_id == 19){
           
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
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
                                        <th>Folio Interno</th>
                                        <th>Incuarte mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_sobrelimites pul
                                                   WHERE
                                                        pul.trn_id_rel = ".$trn_id." 
                                                        AND pul.incuarte = 0
                                                        AND pul.metodo_id = ".$metodo_id
                                                   ) or die(mysqli_error());
                    
                 if ($existen_peso->num_rows > 0) {
                     //echo 'oka';
                            $peso_det = $mysqli->query("SELECT
                                                                            sl.trn_id,
                                                                            sl.trn_id_rel AS trn_id_batch,
                                                                            sl.trn_id_batch AS trn_origen,
                                                                            sl.trn_id_muestra,
                                                                            ade.folio_interno AS folio_interno,
                                                                            ade.muestra_geologia AS muestra
                                                                        FROM
                                                                            arg_muestras_sobrelimites AS sl
                                                                        LEFT JOIN ordenes_transacciones ade 
                                                                        ON
                                                                            sl.trn_id_muestra = ade.trn_id_rel
                                                                        WHERE
                                                                            sl.trn_id_rel = ".$trn_id."
                                                                            AND sl.incuarte = 0
                                                                            AND sl.metodo_id = ".$metodo_id."
                                                                        ORDER BY ade.folio_interno"
                                                                          )   or die(mysqli_error());
                       
                                        while ($res_muestras_to = $peso_det->fetch_assoc()) {
                                             //echo 'vee';
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
                                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                              </button></td>
                                                    </tr>";
                                        }
                                            $con = $con+1;
                     }
                     /*Si quiero el boton a nivel renglon
                     <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_incuarte_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>*/ 
            }                                
       }//Fin etapa 19 pesaje incuarte muestras de la fase 9 METODO EFGRAV30
       
       //Inicia fundicion
       //if($fase_id == 2 && $etapa_id == 8){
        if($etapa_id == 8){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_metodo_sob'>
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
                                $result_h = $mysqli->query("SELECT  ins_id, nombre FROM arg_instrumentos WHERE etapa_id = 8 AND unidad_id = ".$unidad_id) or die(mysqli_error());   
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
        }//Finaliza etapa de fundicion
        
        //Pesaje payon Ensaye a fuego
        if($etapa_id == 6){
        while ($res = $resultado->fetch_assoc()) {
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." - ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>            
                            <tr class='table-info'>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso Pay&oacuten g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_sobrelimites pul
                                                   WHERE
                                                        pul.trn_id_rel = ".$trn_id." 
                                                        AND pul.peso_payon = 0
                                                        AND pul.metodo_id  = ".$metodo_id
                                                   ) or die(mysqli_error());
                    
                 if ($existen_peso->num_rows > 0) {
                     //echo 'oka';
                            $peso_det = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
                                                            ade.folio_interno AS folio_interno,
                                                            ade.muestra_geologia AS muestra
                                                        FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON
                                                                    sl.trn_id_muestra = ade.trn_id_rel
                                                        WHERE
                                                            sl.trn_id_rel = ".$trn_id."
                                                            AND sl.peso_payon = 0
                                                            AND sl.metodo_id = ".$metodo_id
                                                        )   or die(mysqli_error());
                       
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
                                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                              </button></td>
                                                    </tr>";
                                            $con = $con+1;
                                        }
                                            
                     }
            }                                
       }//Fin etapa 6 pesaje payon muestras METODO EFGRAV30              
                  
        
        
        //Pesaje dore Ensaye a fuego
        //if($fase_id == 2 && $etapa_id == 6){
        if($etapa_id == 20){
        while ($res = $resultado->fetch_assoc()) {
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." - ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>            
                            <tr class='table-info'>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Folio Interno</th>
                                        <th>Peso Dor&eacute mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  
                   $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_sobrelimites pul
                                                   WHERE
                                                        pul.trn_id_rel = ".$trn_id." 
                                                        AND pul.peso_dore = 0
                                                        AND pul.metodo_id  = ".$metodo_id
                                                   ) or die(mysqli_error());
                    
                 if ($existen_peso->num_rows > 0) {
                     //echo 'oka';
                            $peso_det = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
                                                            ade.folio_interno AS folio_interno,
                                                            ade.muestra_geologia AS muestra
                                                        FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON
                                                                    sl.trn_id_muestra = ade.trn_id_rel
                                                        WHERE
                                                            sl.trn_id_rel = ".$trn_id."
                                                            AND sl.peso_dore = 0
                                                            AND sl.metodo_id = ".$metodo_id."
                                                        ORDER BY ade.folio_interno"
                                                        )   or die(mysqli_error());
                       
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
                                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                              </button></td>
                                                    </tr>";
                                            $con = $con+1;
                                        }
                       }
            }
        }
        
         //Inicia peso oro
        if($etapa_id == 21){
        while ($res = $resultado->fetch_assoc()) {
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_sobr'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." - ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>            
                            <tr class='table-info'>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Peso Oro mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                  
                   $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_sobrelimites pul
                                                   WHERE
                                                        pul.trn_id_rel = ".$trn_id." 
                                                        AND pul.peso_oro = 0
                                                        AND pul.metodo_id  = ".$metodo_id
                                                   ) or die(mysqli_error());
                    
                 if ($existen_peso->num_rows > 0) {
                     //echo 'oka';
                            $peso_det = $mysqli->query("SELECT
                                                            sl.trn_id,
                                                            sl.trn_id_rel AS trn_id_batch,
                                                            sl.trn_id_batch AS trn_origen,
                                                            sl.trn_id_muestra,
                                                            ade.folio_interno AS muestra
                                                        FROM
                                                            arg_muestras_sobrelimites AS sl
                                                            LEFT JOIN ordenes_transacciones ade 
                                                                ON
                                                                    sl.trn_id_muestra = ade.trn_id_rel
                                                        WHERE
                                                            sl.trn_id_rel = ".$trn_id."
                                                            AND sl.peso_oro = 0
                                                            AND sl.metodo_id = ".$metodo_id
                                                        )   or die(mysqli_error());
                       
                                        while ($res_muestras_to = $peso_det->fetch_assoc()) {
                                            $trnid_folio   = $res_muestras_to['trn_id'];
                                            $trnid_batch   = $res_muestras_to['trn_id_batch'];
                                            $trnid_rel     = $res_muestras_to['trn_id_muestra'];
                                            $muestra_folio = $res_muestras_to['muestra'];
                                            $html.="<tr>
                                                         <td>".$con."</td> 
                                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                                         <td>".$muestra_folio."</td>
                                                         <td> <input type='number' id='peso_sob".$con."' class='form-control' /> </td>
                                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_sobr_guardar(".$trnid_folio.",".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                              </button></td>
                                                    </tr>";
                                            $con = $con+1;
                                        }
                       }
            }
        }//Finaliza peso oro
        
       //Inicia copelado
       if($etapa_id == 9){
           while ($res = $resultado->fetch_assoc()) {   
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }                
            $html =  "<table class='table text-black' id='datos_metodo_sob'>
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
                                $result_h = $mysqli->query("SELECT  ins_id as ins_id_cop, nombre FROM arg_instrumentos WHERE unidad_id = ".$unidad_id) or die(mysqli_error());   
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
        }   //Finaliza copelado             
        
       //Inicia fase de Ataque Quimico EFAAG-30
       if($etapa_id == 22){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa']; 
                $Object = new DateTime(); 
                $fecha_hora_ini = $Object->format("d/m/Y h:i:s a");
            }                
            $html =  "<table class='table text-black' id='datos_metodo_sob'>
                            <thead class='thead-info' align='left'>
                                <tr class='table-info'>
                                        <th colspan='3'>".$metodo."</th>
                                </tr>
                                <tr class='table-info'>
                                        <th colspan='3'>Fase: ".$fase." Etapa: ".$etapa."</th>
                                </tr>
                                <tr class='table-info' align='left'>
                                        <th>Hora de Inicio</th>
                                        <th>Hora de Finalizaci&oacuten</th>
                                        <th></th>                                                         
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.="                                                           
                                    <td> <input type='datetime-local' class='form-control' id='hora_inicio_at'></td>
                                    <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_final_at'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='ataque_guardar_sobr(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                            </tr>";
          }//terminar agitacion
             
          
    
}
}
//echo ($html);

$mysqli -> set_charset("utf8");
echo ($html);


?>

