
<meta charset="UTF-8" >

<?php include "connections/config.php";?>

<?php
$html = '';
$trn_id    = $_POST['trn_id'];
$metodo_id = $_POST['metodo'];
$fase_id   = $_POST['fase'];
$etapa_id  = $_POST['etapa'];
$u_id      = $_SESSION['u_id'];
$unidad_id = $_POST['unidad_tem'];



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
                                    (CASE WHEN ord.tipo = 0 THEN 1 ELSE 0 END) AS reensaye
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
        if($etapa_id == 27){
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
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_ordenes_humedad hum
                                                   WHERE
                                                        hum.trn_id_batch = ".$trn_id
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT
                                                        	 hum.trn_id_batch
                                                            ,hum.trn_id_rel
                                                            ,peso_charola
                                                            ,mm.folio  AS muestra
                                                        FROM `arg_ordenes_humedad` hum
                                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                        	ON hum.trn_id_batch = mm.trn_id_rel
                                                            AND hum.trn_id_rel = mm.trn_id
                                                        WHERE 
                                                            hum.trn_id_batch = ".$trn_id) 
                                                or die(mysqli_error($mysqli)); 
                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            //$muestra_control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mm.folio AS muestra
                                                        FROM 
                                                        	arg_ordenes_transquebradora mq
                                                            LEFT JOIN arg_ordenes_muestrasMetalurgia mm
                                                                ON mq.trn_id_batch = mm.trn_id_rel
                                                                AND mq.trn_id_rel = mm.trn_id
                                                               
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id
                                                         ) or die(mysqli_error($mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            
                            $query = "INSERT INTO arg_ordenes_humedad (trn_id_batch, trn_id_rel, peso_charola, peso_humedo, peso_seco, porcentaje, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, 0, 0, 0, 0, $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa 27 pesaje charolas
   
   //Fin etapa 27 pesaje húmedo
   if($etapa_id == 28){
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
                                        
                                        <th>Peso Charola g</th>
                                        <th>Peso H&uacutemedo g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;                  
                                  
                  $peso_det = $mysqli->query("SELECT
                                                 hum.trn_id_batch
                                                ,hum.trn_id_rel
                                                ,peso_humedo
                                                ,mm.folio_interno  AS muestra
                                            FROM `arg_ordenes_humedad` hum
                                                LEFT JOIN ordenes_metalurgia AS mm
                                                    ON hum.trn_id_batch = mm.trn_id_batch
                                                    AND hum.trn_id_rel = mm.trn_id_rel
                                            WHERE 
                                                hum.trn_id_batch = ".$trn_id."
                                                AND mm.metodo_id = ".$metodo_id."
                                                AND hum.peso_humedo = 0") 
                                            or die(mysqli_error($mysqli));                                    
                
                if ($peso_det->num_rows > 0) {         
                  while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_ch".$con."' value='' class='form-control'/> </td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardarHumCh(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                   
                    }                    
                }
                else{
                    $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mm.folio_interno AS muestra
                                                        FROM 
                                                        	arg_ordenes_transquebradora mq
                                                            LEFT JOIN ordenes_metalurgia mm
                                                                ON mq.trn_id_batch = mm.trn_id_batch
                                                                AND mq.trn_id_rel  = mm.trn_id_rel
                                                                AND mq.metodo_id   = mm.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id
                                                         ) or die(mysqli_error($mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            
                            $query = "INSERT INTO arg_ordenes_humedad (trn_id_batch, trn_id_rel, peso_charola, peso_humedo, peso_seco, porcentaje, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, 0, 0, 0, 0, $u_id)";                            
                            $mysqli->query($query);
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_ch".$con."' value='' class='form-control'/> </td>
                                         <td> <input type='number' id='peso_met".$con."' value='' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardarHumCh(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                        }
                }
            }
            
   }//Fin etapa 28
   if($etapa_id == 1){
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
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                                     
                  $peso_det = $mysqli->query("SELECT
                	                               hum.trn_id_batch
                                                   ,hum.trn_id_rel
                                                   ,peso_seco
                                                   ,mm.folio  AS muestra
                                             FROM `arg_ordenes_humedad` hum
                                                  LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                       ON hum.trn_id_batch = mm.trn_id_rel
                                                       AND hum.trn_id_rel = mm.trn_id
                                             WHERE 
                                                  hum.trn_id_batch = ".$trn_id."
                                                  AND hum.peso_seco = 0") 
                                                or die(mysqli_error($mysqli)); 
                                    
                        
                  while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            $html.=" <tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                   
                } 
            }
    }      //Fin etapa 27 pesaje charolas
   
   //Metodo densidad   
   if($fase_id == 20 & $etapa_id == 5 & $metodo_id == 29){
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
                                         <th>Volumen</th>
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_ordenes_densidad den
                                                   WHERE
                                                        den.trn_id_batch = ".$trn_id
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT
                                                        	 hum.trn_id_batch
                                                            ,hum.trn_id_rel
                                                            ,peso
                                                            ,mm.folio  AS muestra
                                                        FROM `arg_ordenes_densidad` hum
                                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                        	ON hum.trn_id_batch = mm.trn_id_rel
                                                            AND hum.trn_id_rel  = mm.trn_id
                                                        WHERE 
                                                            hum.trn_id_batch = ".$trn_id) 
                                                or die(mysqli_error($mysqli)); 
                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            //$muestra_control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>                                         
                                         <td> <input value='11.9' disabled id='peso_met_cha".$con."' class='form-control'/></td>
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mm.folio AS muestra
                                                        FROM 
                                                        	arg_ordenes_transquebradora mq
                                                            LEFT JOIN arg_ordenes_muestrasMetalurgia mm
                                                                ON mq.trn_id_batch = mm.trn_id_rel
                                                                AND mq.trn_id_rel = mm.trn_id
                                                               
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id
                                                         ) or die(mysqli_error( $mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            
                            $query = "INSERT INTO arg_ordenes_densidad (trn_id_batch, trn_id_rel, peso_charola, peso, densidad, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, 0, 0, 0, $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met_cha".$con."' class='form-control' />11.9</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del m�todo densidad

   //Metodo GRAN
   if($fase_id == 20 & $etapa_id == 5 & $metodo_id == 5){
    echo 'entro';
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  //$nombrecampo = 'Malla +1/2';
                  
                  $peso_all_act = $mysqli->query("SELECT
         	                                    (CASE  
                                                      WHEN `p_malla12`   = 0 THEN 'p_malla12=0'                                                      
                                                      WHEN `p_malla38`   = 0 THEN 'p_malla38=0'                                                      
                                                      WHEN `p_malla14`   = 0 THEN 'p_malla14=0'                                                      
                                                      WHEN `p_malla10`   = 0 THEN 'p_malla10=0'
                                                      WHEN `p_malla50`   = 0 THEN 'p_malla50=0'
                                                      WHEN `p_malla100`  = 0 THEN 'p_malla100=0'                                                      
                                                      WHEN `p_mallamenos100`  = 0 THEN 'p_mallamenos100=0'
                                                ELSE '9999' END) AS peso_malla
                                                      
                                        FROM 
                                            `arg_ordenes_granulometria` hum
                                            LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                ON hum.trn_id_batch = mm.trn_id_rel
                                                AND hum.trn_id_rel = mm.trn_id
                                        WHERE 
                                            hum.trn_id_batch  = ".$trn_id) or die(mysqli_error($mysqli));             
                            $elpeso = $peso_all_act->fetch_assoc();
                            $elpeso1 = $elpeso['peso_malla'];   
                  
                  switch ($elpeso1){
                                case 'p_malla12=0': $nombrecampo = 'Malla +1/2';
                                break;
                                case 'p_malla38=0': $nombrecampo = 'Malla +3/8';
                                break;
                                case 'p_malla14=0': $nombrecampo = 'Malla +1/4';
                                break;
                                case 'p_malla10=0': $nombrecampo = 'Malla +10';
                                break;  
                                case 'p_malla50=0': $nombrecampo = 'Malla +50';
                                break;         
                                case 'p_malla100=0': $nombrecampo = 'Malla +100';                                
                                break; 
                                case 'p_mallamenos100=0': $nombrecampo = 'Malla -100';
                                break; 
                                case '': $nombrecampo = 'Malla +1/2';
                            }
                  
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
                                        <th>".$nombrecampo."</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_ordenes_granulometria den
                                                   WHERE
                                                        den.trn_id_batch = ".$trn_id
                                                   ) or die(mysqli_error($mysqli));
                 // echo   $peso_tomar;
                  
                  if ($existen_peso->num_rows > 0) { 
                            
                            $peso_det = $mysqli->query("SELECT
                         	                               hum.trn_id_batch
                                                          ,hum.trn_id_rel
                                                          ,mm.folio  AS muestra
                                                        FROM 
                                                            `arg_ordenes_granulometria` hum
                                                            LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                                ON hum.trn_id_batch = mm.trn_id_rel
                                                                AND hum.trn_id_rel = mm.trn_id
                                                        WHERE 
                                                            hum.trn_id_batch  = ".$trn_id."
                                                            AND ".$elpeso1) 
                                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            //$muestra_control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                         <td>".$muestra_folio."</td>                            
                                         <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mm.folio AS muestra
                                                        FROM 
                                                        	arg_ordenes_transquebradora mq
                                                            LEFT JOIN arg_ordenes_muestrasMetalurgia mm
                                                                ON mq.trn_id_batch = mm.trn_id_rel
                                                                AND mq.trn_id_rel = mm.trn_id                                                               
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id
                                                         ) or die(mysqli_error($mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];
                            
                            $query = "INSERT INTO arg_ordenes_granulometria (trn_id_batch, trn_id_rel, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $u_id)";
                            $mysqli->query($query); 
                            //echo 'llego'.$query; 
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }//Fin etapa pesaje del m�todo densidad
   
   //Pesaje del m�todo CN-HOT3
   if($fase_id == 7 & $etapa_id == 5 & $metodo_id == 33){
    //echo 'denuevo'.$fase_id.$metodo_id;
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {
                        if($reensaye == 0){
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                         }
                         else{
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_rel AS trn_id_batch
                                                        	,mq.trn_id_muestra AS trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_reensayes_metal mq
                                                                ON mc.trn_id = mq.trn_id_rel
                                                                AND mc.trn_id_rel = mq.trn_id_muestra
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_rel  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                            
                         }                                  
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        if ($reensaye == 0){
                            mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenIniciocianurado ($trn_id,$metodo_id,$u_id, $unidad_id)") OR DIE (mysqli_error($mysqli));
                            $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        }
                        else{
                             $resultado_mues = $mysqli->query("SELECT 
                        	  	                                 mq.trn_id_rel AS trn_id_batch
                                                            	,mq.trn_id_muestra AS trn_id_rel
                                                                ,mq.metodo_id
                                                                ,mq.folio_interno AS muestra
                                                                ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	
                                                            ordenes_reensayes_metal mq
                                                        WHERE
                                                        	mq.trn_id_rel  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        }
                        
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            $control       = $res_muestras['control'];
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del m�todo cianurado

   //Metodo método CB_DIGAg 35 pesaje   
   if($fase_id == 24 & $etapa_id == 5 & $metodo_id == 35){
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
                                    <th>Peso g</th>
                                    <th></th>                                
                            </thead>
                        <tbody>";
                            
              $con = 1;
               
              $existen_peso = $mysqli->query("SELECT *                                                    
                                               FROM 
                                                    arg_muestras_cianurado mc
                                               WHERE
                                                    mc.trn_id = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND mc.peso = 0"
                                               ) or die(mysqli_error($mysqli));
                
              if ($existen_peso->num_rows > 0) {                        
                    $peso_det = $mysqli->query("SELECT
                                                         hum.trn_id AS trn_id_batch
                                                        ,hum.trn_id_rel
                                                        ,peso
                                                        ,mm.folio  AS muestra
                                                    FROM `arg_muestras_cianurado` hum
                                                    LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                        ON hum.trn_id       = mm.trn_id_rel
                                                        AND hum.trn_id_rel  = mm.trn_id
                                                    WHERE 
                                                        hum.trn_id = ".$trn_id."
                                                        AND hum.metodo_id = ".$metodo_id."
                                                        AND peso = 0")
                                            or die(mysqli_error($mysqli)); 
                                
                    
                    while ($res_muestras = $peso_det->fetch_assoc()) {
                        //$con = $res_muestras['posicion'];
                        $trnid_batch   = $res_muestras['trn_id_batch'];
                        $trnid_rel     = $res_muestras['trn_id_rel'];
                        $muestra_folio = $res_muestras['muestra'];
                        //$muestra_control = $res_muestras['control'];
                        //$peso_actual   = $res_muestras['peso'];
                        $html.="<tr>                                  
                                     <td>".$con."</td> 
                                     <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                     <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                           
                                     <td>".$muestra_folio."</td>                                         
                                     <td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                     <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                    $resultado_mues = $mysqli->query("SELECT 
                                                         mq.trn_id_batch
                                                        ,mq.trn_id_rel
                                                        ,mq.metodo_id
                                                        ,mm.folio AS muestra
                                                    FROM 
                                                        arg_ordenes_transquebradora mq
                                                        LEFT JOIN arg_ordenes_muestrasMetalurgia mm
                                                            ON mq.trn_id_batch = mm.trn_id_rel
                                                            AND mq.trn_id_rel = mm.trn_id
                                                           
                                                    WHERE
                                                        mq.trn_id_batch  = ".$trn_id."  
                                                        AND mq.metodo_id = ".$metodo_id
                                                     ) or die(mysqli_error($mysqli));
                    
                    while ($res_muestras = $resultado_mues->fetch_assoc()) {
                        //$con = $res_muestras['posicion'];
                        $trnid_batch   = $res_muestras['trn_id_batch'];
                        $trnid_rel     = $res_muestras['trn_id_rel'];
                        $muestra_folio = $res_muestras['muestra'];
                        
                        $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                        "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";

                        $mysqli->query($query);  
                        $html.="<tr>
                                     <td>".$con."</td> 
                                     <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                     <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                     <td>".$muestra_folio."</td>
                                     <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                     <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                <span class='fa fa-cloud fa-1x'></span>
                                          </button></td>
                                </tr>";
                        $con = $con+1;
                    }
              }
        } 
    } 
}      //Fin etapa pesaje del método CB_DIGAg 35

//Inicia fase Digestión Acida método CB_DIGAg (35)
if($fase_id == 24 && $etapa_id == 30){
    while ($res = $resultado->fetch_assoc()) {
         $metodo = $res['metodo'];
         $fase   = $res['fase'];
         $etapa  = $res['etapa']; 
         $Object = new DateTime(); 
         $fecha_hora_ini = $Object->format("d/m/Y h:m p");
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
                                 <th>Hora de Finalizaci&oacuten</th>
                                 <th></th>                                                         
                     </thead>
                     <tbody>
                     <tr>";                             
                         $html.="                                                           
                             <td> <input type='datetime-local' class='form-control' value='' id='hora_inicio'></td>
                             <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_final'></td>
                             <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='agitacion_guardar(".$trn_id.", ".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                      <span class='fa fa-cloud fa-1x'></span>
                                  </button>
                             </td>
                     </tr>";
   }// Termina etapa Digestión Acida método CB_DIGAg (35)

   //Inicia fase filtración de muestras método CB_DIGAg (35)
if($fase_id == 24 && $etapa_id == 31){
    while ($res = $resultado->fetch_assoc()) {
         $metodo = $res['metodo'];
         $fase   = $res['fase'];
         $etapa  = $res['etapa']; 
     }  
     
    $inicio_filtrmuestras = $mysqli->query("SELECT 
                                                date_format(da.fecha_final, '%Y-%m-%d %h:%i') AS fecha,
                                                date_format(now(), '%Y-%m-%d %H:%i') AS fecha_fin
                                            FROM 
                                                arg_ordenes_bitacora_detalle mq 
                                                 LEFT JOIN arg_muestras_digestionAcida da
                                                	ON mq.trn_id_rel = da.trn_id_rel                                                                                             AND da.fase_id = mq.fase_id
                                                    AND da.etapa_id = mq.etapa_id                                                          
                                            WHERE
                                                mq.trn_id_rel  = ".$trn_id."  
                                                AND mq.metodo_id = ".$metodo_id."
                                                AND mq.fase_id = ".$fase_id."
                                                AND mq.etapa_id = 30"
                                            ) or die(mysqli_error($mysqli));
                                            
    $fechaInicio_fil = $inicio_filtrmuestras->fetch_assoc();
    $fecha_hora_ini = $fechaInicio_fil['fecha'];
    $fecha_hora_final = $fechaInicio_fil['fecha_fin'];

    $userFiltra = $mysqli->query("SELECT 
                                    nombre AS userFiltra
                                  FROM 
                                    arg_usuarios u                                                           
                                  WHERE
                                    u.u_id = $u_id"
                                ) or die(mysqli_error($mysqli));
                                            
    $userFiltraC = $userFiltra->fetch_assoc();
    $userFiltraNombre = $userFiltraC['userFiltra'];                                        
                                                  
     $html =  "<table class='table text-black' id='datos_temperatura'>
                     <thead class='thead-info' align='left'>
                         <tr class='table-info'>
                                 <th colspan='4'>".$metodo."</th>
                         </tr>
                         <tr class='table-info'>
                                 <th colspan='4'>Fase: ".$fase." Etapa: ".$etapa."</th>
                         </tr>
                         <tr class='table-info' align='left'>
                                 <th>Hora de Inicio</th>
                                 <th>Hora de Finalizaci&oacuten</th>                                 
                                 <th>Usuario Finaliza</th>
                                 <th></th>                                                         
                     </thead>
                     <tbody>
                     <tr>";                             
                         $html.="                                                           
                             <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_inicio' disabled></td>
                             <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_final."' id='hora_final' disabled></td>
                             <td> <input type='Text' class='form-control' value='".$userFiltraNombre."' id='userFin' disabled></td>
                             <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='agitacion_guardar(".$trn_id.", ".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                      <span class='fa fa-cloud fa-1x'></span>
                                  </button>
                             </td>
                     </tr>";
   }// Termina etapa filtración de muestras método CB_DIGAg (35)

   //Pesaje del método %ACT_CB (9)
   if($fase_id == 23 & $etapa_id == 5){
    //echo 'denuevo'.$fase_id.$metodo_id;
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
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {
                        if($reensaye == 0){
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                         }
                         else{
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_rel AS trn_id_batch
                                                        	,mq.trn_id_muestra AS trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_reensayes_metal mq
                                                                ON mc.trn_id = mq.trn_id_rel
                                                                AND mc.trn_id_rel = mq.trn_id_muestra
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_rel  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                            
                         }                                  
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        if ($reensaye == 0){
                            mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenIniciocianuradoDgo ($trn_id,$metodo_id,$u_id, $unidad_id)") OR DIE (mysqli_error($mysqli));
                            $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        }
                        else{
                             $resultado_mues = $mysqli->query("SELECT 
                        	  	                                 mq.trn_id_rel AS trn_id_batch
                                                            	,mq.trn_id_muestra AS trn_id_rel
                                                                ,mq.metodo_id
                                                                ,mq.folio_interno AS muestra
                                                                ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	
                                                            ordenes_reensayes_metal mq
                                                        WHERE
                                                        	mq.trn_id_rel  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        }
                        
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            $control       = $res_muestras['control'];
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del método %ACT_CB
   
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
                $fecha_hora_ini = $Object->format("d/m/Y h:m p");
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
                                        <th>Hora de Finalizaci&oacuten</th>
                                        <th></th>                                                         
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.="                                                           
                                    <td> <input type='datetime-local' class='form-control' value='' id='hora_inicio'></td>
                                    <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_final'></td>
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
                                       
                                        <th>Hora de Finalizaci&oacuten de Centrifugado</th>
                                        <th></th>    
                                        <th></th>                                                      
                            </thead>
                            <tbody>
                            <tr>";                             
                                $html.=" 
                                    <td> <input type='datetime-local' class='form-control' value='".$fecha_hora."' id='hora_final_cen'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='centrifugado_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>
                                    <td> </td>
                            </tr>";
          }//terminar agitacion     

        //Inicia fase de ACT cianuro metodo ACT_CB
       if($fase_id == 23 && $etapa_id == 16){
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
                                     <th>Temperatura de SLN BUFFER</th>
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
       }//terminar cianuracion metodo ACT_CB
     
       
    //Inicia fase Agitacion metodo %ACT_CB
    if($fase_id == 23 && $etapa_id == 17){
        while ($res = $resultado->fetch_assoc()) {
             $metodo = $res['metodo'];
             $fase   = $res['fase'];
             $etapa  = $res['etapa']; 
             $Object = new DateTime(); 
             $fecha_hora_ini = $Object->format("d/m/Y h:m p");
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
                                     <th>Hora de Finalizaci&oacuten</th>
                                     <th></th>                                                         
                         </thead>
                         <tbody>
                         <tr>";                             
                             $html.="                                                           
                                 <td> <input type='datetime-local' class='form-control' value='' id='hora_inicio'></td>
                                 <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_final'></td>
                                 <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='agitacion_guardar(".$trn_id.", ".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                          <span class='fa fa-cloud fa-1x'></span>
                                      </button>
                                 </td>
                         </tr>";
       }//terminar agitacion del metodo 9 %ACT_CB

          //Inicia lectura de absorci�n atomica �m�todo cianuracion: exportar/importar csv
         if($etapa_id == 7){             
            while ($res = $resultado->fetch_assoc()) {   
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa'];
            }  
                  
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
                                               <button type='input' class='btn btn-success' value='upload_quebr' name='action_quebr' >
                                                <span class='fa fa-file-excel-o fa-2x'> Importar </span>
                                              </button>
                                            </form>    
                                        </th>";
        }
        
        //Método Au_LibreAA 27. pesaje
        if($fase_id == 9 & $etapa_id == 5 & $metodo_id == 27){
        while ($res = $resultado->fetch_assoc()) {            
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  $nombrecampo = 'Peso muestra g';
                                
                  $peso_all_act = $mysqli->query("SELECT COUNT(*) AS peso                                                 
                                                FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id IN (0, 4)
                                                    AND mc.peso = 0") or die(mysqli_error($mysqli));             
                                $elpeso = $peso_all_act->fetch_assoc();
                                $peso_sig = $elpeso['peso'];  
                                
                    $peso_all_ori = $mysqli->query("SELECT COUNT(*) AS peso_ori                                                    
                                                 FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                 WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id = 0
                                                    AND mc.peso_original = 0") or die(mysqli_error($mysqli));             
                                $elpeso_orig = $peso_all_ori->fetch_assoc();
                                $peso_or = $elpeso_orig['peso_ori'];         
                                      
                    if($peso_sig > 0)  {                        
                        $nombrecampo  = 'Peso muestra g';
                        $peso_sig_fil = 'peso = 0 AND tipo_id IN (0,4)';
                    }
                    elseif($peso_or > 0){
                        $nombrecampo  = 'Peso Original';
                        $peso_sig_fil = 'peso_original = 0 AND tipo_id = 0';
                    }
                    else{
                        $peso_all_actu = $mysqli->query("SELECT COUNT(*) AS peso200                                                    
                                                FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id = 200
                                                    AND peso_malla200 is null") or die(mysqli_error($mysqli));             
                                $elpeso = $peso_all_actu->fetch_assoc();
                                $peso_sig2 = $elpeso['peso200'];
                                
                         if($peso_sig2 > 0){
                            $nombrecampo = 'Peso malla+200 g';
                            $peso_sig_fil = 'peso_malla200 is null AND mq.tipo_id = 200';
                         }
                         else{
                            $peso_sig_fil = 'peso = 0 AND tipo_id <> 200';
                            $nombrecampo = 'Peso de muestra g';
                         }
                    }        
                  
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>".$nombrecampo."</th>                                        
                                        <th></th>            
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado mc                                                        
                                                        LEFT JOIN ordenes_metalurgia mq
                                                           ON mc.trn_id = mq.trn_id_batch
                                                           AND mc.trn_id_rel = mq.trn_id_rel
                                                           AND mc.metodo_id = mq.metodo_id
                                                   WHERE
                                                        mc.trn_id = ".$trn_id."
                                                        AND mc.metodo_id = ".$metodo_id."
                                                        AND ".$peso_sig_fil
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND ".$peso_sig_fil."
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenIniciocianurado ($trn_id,$metodo_id,$u_id, $unidad_id)") OR DIE (mysqli_error($mysqli));
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                                            ,mq.tipo_id
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY 
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            $control       = $res_muestras['control'];
                            $tipo_muestra  = $res_muestras['tipo_id'];
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                                 "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";
                            
                            $mysqli->query($query);  
                            
                            if($tipo_muestra == 0 or $tipo_muestra == 4){
                                $html.="<tr>
                                             <td>".$con."</td> 
                                             <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                             <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                             <td>".$muestra_folio."</td>
                                             <td>".$control."</td>
                                             <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                  </button></td>
                                        </tr>";
                                $con = $con+1;
                            }
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del m�todo cianurado
   
   //M�todo Au_LibreAA 27. pesaje Incuarte
   if($fase_id == 9 & $etapa_id == 19){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Incuarte mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.incuarte = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.incuarte = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin Incuarte
        
        //Inicia fundicion EFGRA2
       if($fase_id == 11 && $etapa_id == 8){ 
            while ($res = $resultado->fetch_assoc()) {
                $metodo =  $res['metodo'];
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
                                $result_h = $mysqli->query("SELECT  ins_id, nombre FROM arg_instrumentos WHERE fase_id = 11 AND etapa_id = 8") or die(mysqli_error( $mysqli));   
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
        
        //M�todo Au_LibreAA 27. pesaje PAYON
       if($fase_id == 9 & $etapa_id == 6){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso Pay&oacuten g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_payon = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_payon = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin peso payon
        
       //M�todo Au_LibreAA 27. peso dor�
       if($fase_id == 8 & $etapa_id == 20){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso Dor&eacute mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_dore = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_dore = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin peso payon
        
        //Inicia copelado
       if($etapa_id == 9){
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
                                $result_h = $mysqli->query("SELECT  ins_id as ins_id_cop, nombre FROM arg_instrumentos WHERE fase_id = 11 AND etapa_id = 9 
                                                            UNION ALL
                                                            SELECT  ins_id as ins_id_cop, nombre FROM arg_instrumentos WHERE fase_id = 11 AND etapa_id = 8 ") or die(mysqli_error($mysqli));   
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
        }// Fin copelado
        
        //Inicia fase de Ataque Quimico
       if($etapa_id == 22){
           while ($res = $resultado->fetch_assoc()) {
                $metodo = $res['metodo'];
                $fase   = $res['fase'];
                $etapa  = $res['etapa']; 
                $Object = new DateTime(); 
                $fecha_hora_ini = $Object->format("d/m/Y h:i:s a");
            }                
            $html =  "<table class='table text-black' id='tabla_pesaje_met'>
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
          }//terminar ataque quimico
          
    //Pesaje método 2 EF-GRAV2      
    if($fase_id == 11 && $etapa_id == 5 && $metodo_id == 2){
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
                                        <th>Folio Interno</th>
                                        <th>Control</th>
                                        <th>Peso g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso = 0"                                                        
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {      
                    if($reensaye == 0){                  
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                    }
                    else{
                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 3) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.posicion") 
                                                or die(mysqli_error($mysqli)); 
                    }   
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($reensaye == 0){

                        mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenIniciocianuradoDgo ($trn_id,$metodo_id,$u_id, $unidad_id)") OR DIE (mysqli_error($mysqli));
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia WHEN mq.tipo_id = 1 THEN folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY 
                                                            mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                        
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            $control       = $res_muestras['control'];
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                         <td>".$control."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
                  else{ // Es reensaye
                        $resultado_mues = $mysqli->query("SELECT 
                                                                 mq.trn_id_rel AS trn_id_batch
                                                                ,mq.trn_id_muestra AS trn_id_rel
                                                                ,mq.metodo_id
                                                                ,mq.folio_interno AS muestra
                                                                ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia WHEN mq.tipo_id = 1 THEN folio_interno ELSE mq.control END) AS control
                                                            FROM 
                                                                ordenes_reensayes_metal mq
                                                            WHERE
                                                                mq.trn_id_rel  = ".$trn_id."  
                                                                AND mq.metodo_id = ".$metodo_id."
                                                            ORDER BY 
                                                                mq.posicion"
                                                            ) or die(mysqli_error($mysqli));

                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            //$con = $res_muestras['posicion'];
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            $control       = $res_muestras['control'];

                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                    "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0,  $u_id)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                <td>".$con."</td> 
                                <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                <td>".$muestra_folio."</td>
                                <td>".$control."</td>
                                <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                        <span class='fa fa-cloud fa-1x'></span>
                                </button></td>
                                </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del método EFGRAV2 metodo_id = 2  
   
   //Pesaje método 2 EF-GRAV2 etapa de calcinado     
   if($fase_id == 11 && $etapa_id == 32 && $metodo_id == 2){        
        while ($res = $resultado->fetch_assoc()) {
            $metodo = $res['metodo'];
            $fase   = $res['fase'];
            $etapa  = $res['etapa']; 
            $Object = new DateTime(); 
            $fecha_hora_ini = $Object->format("d/m/Y h:m p");
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
                                    <th>Hora de Finalizaci&oacuten</th>
                                    <th></th>                                                         
                        </thead>
                        <tbody>
                        <tr>";                             
                            $html.="                                                           
                                <td> <input type='datetime-local' class='form-control' value='' id='hora_inicio'></td>
                                <td> <input type='datetime-local' class='form-control' value='".$fecha_hora_ini."' id='hora_final'></td>
                                <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='agitacion_guardar(".$trn_id.", ".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                         <span class='fa fa-cloud fa-1x'></span>
                                     </button>
                                </td>
                        </tr>";
    }
                            
   
   //Método EF-Grav2 2 Incuarte
   if($fase_id == 10 & $etapa_id == 19){
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
                                        <th>Folio Interno</th>
                                        <th>Control</th>
                                        <th>Incuarte mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.incuarte = 0
                                                        AND den.reensaye = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {    
                    if($reensaye == 0){                            
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.incuarte = 0
                                                            AND mc.reensaye = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));
                    }
                    else{
                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 3) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.incuarte = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                         AND pul.reensaye = 0
                                                      ORDER BY ot.posicion") 
                                                or die(mysqli_error($mysqli)); 
                    }                                
                        
                    while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin Incuarte EF-Grav2 metodo 2
        
       //M�todo EF-Grav2 2 peso PAYON
       if($fase_id == 11 & $etapa_id == 6){
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
                                        <th colspan='6'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJOOOO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Folio Interno</th>
                                        <th>Control</th>
                                        <th>Peso Pay&oacuten g</th>
                                        <th></th> 
                                         <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_payon = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {    
                    if($reensaye == 0){                       
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_payon = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                    }
                    else{
                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 3) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso_payon = 0 
                                                         AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.posicion") 
                                                or die(mysqli_error($mysqli)); 
                    }    
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }//Fin si existen aun pesos de payon = 0
                else{
                    if ($reensaye == 0){
                        $resultado = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,ROUND(mc.peso_payon, 3) AS peso_payon
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));       
                    }
                    else{
                        $resultado = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso_payon, 3) AS peso_payon,
                                                         ot.folio_interno as muestra,                                                              
                                                         met.nombre as metodo_nombre,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                        LEFT JOIN arg_metodos met
                                                                ON met.metodo_id = pul.metodo_id
                                                      WHERE
                                                         pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                      ORDER BY ot.posicion") or die(mysqli_error($mysqli));
                    }
                    $cont = 0;
                         while ($res_muestras = $resultado->fetch_assoc()) {
                                $cont = $cont+1;
                                $trnid_batch_met = $res_muestras['trn_id_batch'];
                                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                                $muestra_met     = $res_muestras['muestra'];     
                                $metodo          = $res_muestras['metodo_nombre'];
                                $peso            = $res_muestras['peso_payon'];
                                $control = $res_muestras['control'];

                                if ($peso < 20 || $peso > 50){
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
                                        if ($supervisor == 1){
                                           $html .="<td> <input type='number' id='peso_pay".$cont."' value='".$peso_actual."' class='form-control'/> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardarEditCarb(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$cont.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                  </button>
                                             </td>";
                                        }                                                                           
                                        $html.="</tr>"; 
                            }
                     $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizarCarb(".$trnid_batch_met.",".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                                    <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                                </button></td>";
                     $html.="</div>";
                     $html .= "</tbody></table></div>";
                }
                
            } // Fin detalle de orden
        }//Fin peso payon EF-Grav2 2
        
       //M�todo EF-Grav2 2 peso dor�
       if($fase_id == 10 & $etapa_id == 20){
      
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
                                        <th colspan='5'>".$metodo." FasEEEEEEEE: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Folio Interno</th>
                                        <th>Control</th>
                                        <th>Peso Dor&eacute mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_dore = 0
                                                        AND den.reensaye = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {    
                  //  echo 'holaaaaaaaa';
                    if($reensaye == 0){                     
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.reensaye = 0
                                                            AND mc.peso_dore = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                    }
                    else{
                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso_dore, 3) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso_dore = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                         AND pul.reensaye = 0
                                                      ORDER BY ot.posicion") 
                                                or die(mysqli_error($mysqli)); 
                    }   
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($reensaye == 0){
                        $resultado = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,ROUND(mc.peso_dore, 3) AS peso_dore
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id." 
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND reensaye = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));       
                    }
                    else{
                      //  echo 'Aaca';
                        $resultado = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso_dore, 3) AS peso_dore,
                                                         ot.folio_interno as muestra,                                                              
                                                         met.nombre as metodo_nombre,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                        LEFT JOIN arg_metodos met
                                                                ON met.metodo_id = pul.metodo_id
                                                      WHERE
                                                         pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                         AND reensaye = 0
                                                      ORDER BY ot.posicion") or die(mysqli_error($mysqli));
                    }
                    $con = 0;
                         while ($res_muestras = $resultado->fetch_assoc()) {
                                $con = $con+1;
                                $trnid_batch_met = $res_muestras['trn_id_batch'];
                                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                                $muestra_met     = $res_muestras['muestra'];     
                                $metodo          = $res_muestras['metodo_nombre'];
                                $peso            = $res_muestras['peso_dore'];
                                                
                                $html.="<td>".$con."</td> 
                                        <td style='display:none;'> <input type='input' id='trnid_batch_pay".$con."' value='".$trnid_batch_met."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trnid_rel_pay".$con."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                                        <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                                        <td>".$muestra_met."</td>
                                        <td>".$peso."</td>";
                                        if ($supervisor == 1){
                                           $html .="<td> <input type='number' id='peso_pay".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardarEditCarb(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>                
                                                  </button>
                                             </td>";
                                        }                                                                           
                                        $html.="</tr>"; 
                            }
                     $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizarCarb(".$trnid_batch_met.",".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                                    <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                                </button></td>";
                     $html.="</div>";
                     $html .= "</tbody></table></div>";
                }
            } // Fin detalle de orden
        }//Fin peso dor� EF-Grav2 2
        
        //Metodo EF-Grav2 2 peso oro
       if($fase_id == 10 & $etapa_id == 21){
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
                                        <th colspan='6'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Folio Interno</th>
                                        <th>Control</th>
                                        <th>Peso Au mg</th>
                                        <th></th>   
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.reensaye = 0
                                                        AND den.capturado = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {          
                    if($reensaye == 0){             
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.capturado = 0
                                                            AND mc.reensaye = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                    }
                    else{
                        $peso_det = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso, 3) AS peso,
                                                         ot.folio_interno as muestra,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                      WHERE
                                                         pul.peso_oro = 0 AND pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                         AND pul.reensaye = 0
                                                      ORDER BY ot.posicion") 
                                                or die(mysqli_error($mysqli)); 
                    }    
                    while ($res_muestras = $peso_det->fetch_assoc()) {
                        $trnid_batch   = $res_muestras['trn_id_batch'];
                        $trnid_rel     = $res_muestras['trn_id_rel'];
                        $muestra = $res_muestras['muestra'];
                        $control = $res_muestras['control'];
                        //$peso_actual   = $res_muestras['peso'];
                        $html.="<tr>                                  
                                    <td>".$con."</td> 
                                    <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                    <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                    <td>".$muestra."</td>  
                                    <td>".$control."</td>  
                                    <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                    </td>
                                </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($reensaye == 0){
                        $resultado = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,ROUND(mc.peso_oro, 3) AS peso_oro
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.reensaye = 0
                                                        ORDER BY mq.posicion") 
                                                or die(mysqli_error($mysqli));       
                    }
                    else{
                        $resultado = $mysqli->query("SELECT
                                                	     ot.trn_id_rel AS trn_id_batch,
                                                         ot.trn_id_muestra AS trn_id_rel,
                                                         ROUND(pul.peso_oro, 3) AS peso_oro,
                                                         ot.folio_interno as muestra,     
                                                         ot.control AS control,                                                         
                                                         met.nombre as metodo_nombre,
                                                         ot.control
                                                      FROM 
                                                        arg_muestras_cianurado pul
                                                        LEFT JOIN ordenes_reensayes_metal ot
                                                            ON  pul.trn_id = ot.trn_id_rel
                                                            AND pul.trn_id_rel = ot.trn_id_muestra
                                                            AND pul.metodo_id = ot.metodo_id
                                                        LEFT JOIN arg_metodos met
                                                                ON met.metodo_id = pul.metodo_id
                                                      WHERE
                                                         pul.trn_id = ".$trn_id." 
                                                         AND pul.metodo_id = ".$metodo_id." 
                                                         AND pul.reensaye = 0
                                                      ORDER BY ot.posicion") or die(mysqli_error(   $mysqli));
                    }
                    $con = 0;
                         while ($res_muestras = $resultado->fetch_assoc()) {
                                $con = $con+1;
                                $trnid_batch_met = $res_muestras['trn_id_batch'];
                                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                                $muestra_met     = $res_muestras['muestra'];     
                                $metodo          = $res_muestras['metodo_nombre'];
                                $peso            = $res_muestras['peso_oro'];
                                $control         = $res_muestras['control'];
                                                
                                $html.="<td>".$con."</td> 
                                        <td style='display:none;'> <input type='input' id='trnid_batch_pay".$con."' value='".$trnid_batch_met."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trnid_rel_pay".$con."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                                        <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                                        <td>".$muestra_met."</td>
                                        <td>".$control."</td>
                                        <td>".$peso."</td>";
                                        if ($supervisor == 1){
                                           $html .="<td> <input type='number' id='peso_pay".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_payon_guardarEditCarb(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>                
                                                  </button>
                                             </td>";
                                        }                                                                           
                                        $html.="</tr>"; 
                            }
                     $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizarCarb(".$trnid_batch_met.",".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                                    <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                                </button></td>";
                     $html.="</div>";
                     $html .= "</tbody></table></div>";
                }
            }
        }//Fin peso dor� EF-Grav2 2
        
        //Pesaje del m�todo IMPUREZAS
   if($fase_id == 6 & $etapa_id == 5 & $metodo_id == 28){
        while ($res = $resultado->fetch_assoc()) {  
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  echo 'entro imP';
                  $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='5'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No  .</th>
                                        <th>Muestra</th>
                                        <th>Peso mg</th> 
                                        <th></th>                        
                                </thead>
                            <tbody>";                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_impurezas den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso = 0"                                                        
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {
                    echo 'primerif ';
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_impurezas mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso = 0
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
                else{
                    if ($tipo_can == 1){ //Porcentaje
                      echo 'entro otro';
                        $limite = (($cantidad_muestras*$total)/100);
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                       
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                            
                            // $control       = $res_muestras['control'];
                            
                            $query = "INSERT INTO arg_muestras_impurezas (trn_id, trn_id_rel, metodo_id, peso)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0)";
                            $mysqli->query($query);  
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra_folio."</td>                             
                                         <td>".$muestra_folio."</td>
                                      
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del m�todo Impurezas
   if($fase_id == 6 & $etapa_id == 4 & $metodo_id == 28){
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
                                        <th colspan='4'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='5'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>Temperatura</th>
                                        <th></th>                               
                                </thead>
                            <tbody>
                            <td> <input type='input' class='form-control' id='cantidad_dig'></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_dig' onclick='digestion_guardar(".$trn_id.", ".$metodo_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                         </button>
                                    </td>";                                
        }          
   }      //Fin etapa pesaje del m�todo Impurezas
   
   //Pesaje del m�todo 31 Au_LibreGR - si llevo la primera muestra aparte
   /*if($fase_id == 18 & $etapa_id == 5 & $metodo_id == 31){
        while ($res = $resultado->fetch_assoc()) {  
                  $tipo_can          = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total             = $res['total'];
                  $trn_id            = $res['trn_id_rel'];
                  $metodo_id         = $res['metodo_id'];
                  $metodo            = $res['metodo'];
                  $fase              = $res['fase'];
                  $etapa             = $res['etapa'];
                  
                  $peso_all_ori = $mysqli->query("SELECT COUNT(*) AS peso_ori                                                    
                                                 FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                 WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id = 0
                                                    AND mc.peso_original = 0") or die(mysqli_error());             
                                $elpeso_orig = $peso_all_ori->fetch_assoc();
                                $peso_or = $elpeso_orig['peso_ori'];         
                                      
                    if($peso_or > 0){
                        $nombrecampo  = 'Peso Original';
                        $peso_sig_fil = 'peso_original = 0 AND tipo_id = 0';
                    }
                    else{
                        $peso_all_actu = $mysqli->query("SELECT COUNT(*) AS peso200                                                    
                                                FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id = 200
                                                    AND peso_malla200 is null") or die(mysqli_error());             
                                $elpeso = $peso_all_actu->fetch_assoc();
                                $peso_sig2 = $elpeso['peso200'];
                                
                         if($peso_sig2 > 0){
                            $nombrecampo = 'Peso malla+200 g';
                            $peso_sig_fil = 'peso_malla200 is null AND mq.tipo_id = 200';
                         }
                         else{
                            $peso_sig_fil = 'peso = 0 AND tipo_id IN (99, 1, 2)';
                            $nombrecampo = 'Peso de muestra g';
                         }
                    }     
                  
                  $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>
                                    <tr class='table-info'>
                                        <th colspan='4'>".$metodo." Fase: ".$fase." Etapa: ".$etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='4'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>".$nombrecampo."</th> 
                                        <th></th>                
                                </thead>
                            <tbody>";                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                        INNER JOIN ordenes_metalurgia mq
                                                            ON den.trn_id = mq.trn_id_batch
                                                            AND den.trn_id_rel = mq.trn_id_rel
                                                            AND den.metodo_id = mq.metodo_id
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND ".$peso_sig_fil                                                    
                                                   ) or die(mysqli_error());
                    
                  if ($existen_peso->num_rows > 0) {
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno 
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN mq.folio_interno
                                                                   WHEN mq.tipo_id = 99 THEN mq.folio_interno 
                                                            ELSE mq.control END) AS muestra
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND ".$peso_sig_fil."
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error());  
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>                          
                                         <td>".$muestra."</td>
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenIniciocianurado ($trn_id,$metodo_id,$u_id, $unidad_id)") OR DIE (mysqli_error($mysqli));
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                                            ,mq.tipo_id
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                        ORDER BY 
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error());
                       
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                             
                            $tipo_mues       = $res_muestras['tipo_id'];
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0, $u_id)";
                            $mysqli->query($query);  
                          //  echo $query;
                            if ($tipo_mues == 0){
                                
                                $html.="<tr>
                                             <td>".$con."</td> 
                                             <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>                             
                                             <td>".$muestra_folio."</td>
                                             <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                             <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                  </button></td>
                                        </tr>";
                                $con = $con+1;                            
                            }
                        }
                  }
            } 
        } 
   } */
   //Fin etapa pesaje del m�todo 31 Au_LibreGR
       
   //Pesaje del m�todo 31 Au_LibreGR
   if($fase_id == 18 & $etapa_id == 5 & $metodo_id == 31){
        while ($res = $resultado->fetch_assoc()) {  
            $nombrecampo  = 'Peso Original';
            $peso_all_ori = $mysqli->query("SELECT COUNT(*) AS peso_ori                                                    
                                                 FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                 WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND ol.tipo_id = 0
                                                    AND mc.peso_original = 0") or die(mysqli_error($mysqli));             
                                $elpeso_orig = $peso_all_ori->fetch_assoc();
                                $peso_or = $elpeso_orig['peso_ori'];        
                                      
                   /* if($peso_sig > 0)  {                        
                        $nombrecampo  = 'Peso malla+200 g';
                        $peso_sig_fil = 'peso = 0 AND tipo_id IN (0,4)';
                    }
                    else*/if($peso_or > 0){
                        $nombrecampo  = 'Peso Original';
                        $peso_sig_fil = 'peso_original = 0 AND tipo_id = 0';
                    }
                    else{
                        $peso_all_actu = $mysqli->query("SELECT COUNT(*) AS peso200                                                    
                                                FROM 
                                                    `arg_muestras_cianurado` mc
                                                     LEFT JOIN ordenes_metalurgia ol
                                                    	ON mc.trn_id = ol.trn_id_batch
                                                        AND mc.trn_id_rel = ol.trn_id_rel
                                                        AND mc.metodo_id = ol.metodo_id
                                                WHERE 
                                                    mc.trn_id  = ".$trn_id."
                                                    AND mc.metodo_id = ".$metodo_id."
                                                    AND peso = 0
                                                    AND tipo_id = 0") or die(mysqli_error($mysqli));             
                                $elpeso = $peso_all_actu->fetch_assoc();
                                $peso_sig2 = $elpeso['peso200'];
                                
                         if($peso_sig2 > 0){
                            $nombrecampo = 'Peso Malla+200 g';
                            $peso_sig_fil = 'peso = 0 AND tipo_id = 0';
                         }
                         else{
                            
                            $peso_sig_fil = 'peso = 0 AND tipo_id <> 0';
                            $nombrecampo = 'Peso de muestra g';
                         }
                    }  
                        
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
                                        <th colspan='5'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>
                                    <tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>Control</th>
                                        <th>".$nombrecampo."</th> 
                                        <th></th>                
                                </thead>
                            <tbody>";                                
                  $con = 1;
                  
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                   arg_muestras_cianurado den
                                                        LEFT JOIN ordenes_metalurgia mq
                                                        ON den.trn_id = mq.trn_id_batch
                                                        AND den.trn_id_rel = mq.trn_id_rel
                                                        AND den.metodo_id = mq.metodo_id
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND ".$peso_sig_fil                                                       
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {
                            $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND ".$peso_sig_fil."
                                                        ORDER BY 
                                                            mq.bloque, mq.folio_interno") 
                                                or die(mysqli_error($mysqli));  
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>                          
                                         <td>".$muestra."</td>
                                         <td>".$control."</td>
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
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
                        $resultado_mues = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	ordenes_metalurgia mq
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id = ".$metodo_id."
                                                            AND mq.tipo_id = 0
                                                        ORDER BY
                                                            mq.bloque, mq.posicion"
                                                         ) or die(mysqli_error($mysqli));
                       
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra_folio = $res_muestras['muestra'];                             
                            $control       = $res_muestras['control']; 
                            
                            $query = "INSERT INTO arg_muestras_cianurado (trn_id, trn_id_rel, metodo_id, peso, u_id)".
                                                               "VALUES ($trnid_batch, $trnid_rel, $metodo_id, 0, $u_id)";
                            $mysqli->query($query);  
                          
                            $html.="<tr>
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>                             
                                         <td>".$muestra_folio."</td>
                                         <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button></td>
                                    </tr>";
                            $con = $con+1;
                        }
                  }
            } 
        } 
   }      //Fin etapa pesaje del m�todo 31 Au_LibreGR
   
   //M�todo Au_LibreGr 31 pesaje Incuarte
   if($fase_id == 18 & $etapa_id == 19 & $metodo_id == 31){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Incuarte mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.incuarte = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.incuarte = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin Incuarte
        
        //M�todo Au_LibreGr 31. pesaje PAYON
       if($fase_id == 18 & $etapa_id == 6 & $metodo_id == 31){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso Pay&oacuten g</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_payon = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_payon = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin peso payon
   
   //M�todo Au_LibreGR 31 peso dor�
   if($fase_id == 19 & $etapa_id == 20 & $metodo_id == 31){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso Dor&eacute mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_dore = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN mq.folio_interno
                                                              ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_dore = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin peso dor�
        
        
   //M�todo Au_LibreGR 31 peso oro
   if($fase_id == 19 & $etapa_id == 21 & $metodo_id == 31){
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
                                        <th>Folio Interno</th>
                                        <th>Muestra</th>
                                        <th>Peso Oro mg</th>
                                        <th></th>                                
                                </thead>
                            <tbody>";
                                
                  $con = 1;
                   
                  $existen_peso = $mysqli->query("SELECT *                                                    
                                                   FROM 
                                                        arg_muestras_cianurado den
                                                   WHERE
                                                        den.trn_id = ".$trn_id."
                                                        AND den.metodo_id = ".$metodo_id."
                                                        AND den.peso_oro = 0"
                                                   ) or die(mysqli_error($mysqli));
                    
                  if ($existen_peso->num_rows > 0) {                        
                        $peso_det = $mysqli->query("SELECT 
                    	  	                                 mq.trn_id_batch
                                                        	,mq.trn_id_rel
                                                            ,mq.metodo_id
                                                            ,mq.folio_interno AS muestra
                                                            ,(CASE WHEN mq.tipo_id = 0 THEN mq.folio_interno 
                                                              ELSE mq.control END) AS control
                                                        FROM 
                                                        	arg_muestras_cianurado mc
                                                            LEFT JOIN ordenes_metalurgia mq
                                                                ON mc.trn_id = mq.trn_id_batch
                                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                                AND mc.metodo_id = mq.metodo_id
                                                        WHERE
                                                        	mq.trn_id_batch  = ".$trn_id."  
                                                            AND mq.metodo_id =  ".$metodo_id."
                                                            AND mc.peso_oro = 0
                                                        ORDER BY mq.bloque, mq.posicion") 
                                                or die(mysqli_error($mysqli));                                    
                        
                        while ($res_muestras = $peso_det->fetch_assoc()) {
                            $trnid_batch   = $res_muestras['trn_id_batch'];
                            $trnid_rel     = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            $control = $res_muestras['control'];
                            //$peso_actual   = $res_muestras['peso'];
                            $html.="<tr>                                  
                                         <td>".$con."</td> 
                                         <td style='display:none;'> <input type='input' id='trnid_batch_met".$con."' value='".$trnid_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trnid_rel_met".$con."' value='".$trnid_rel."'/>".$muestra."</td>                           
                                         <td>".$muestra."</td>  
                                         <td>".$control."</td>  
                                          <td> <input type='number' id='peso_met".$con."' class='form-control' /> </td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch.",".$trnid_rel.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                              </button>
                                         </td>
                                    </tr>";                                   
                        $con = $con+1;
                     }
                }
            }
        }//Fin peso oro metodo 31 Au_LibreGR
       
}   //Fin
    $html .=  "</tbody></thead></table> ";     
}
$mysqli -> set_charset("utf8");
echo($html);
?>

