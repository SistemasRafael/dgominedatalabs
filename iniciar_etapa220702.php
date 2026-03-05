<?include "connections/config.php";?>

<!--<link href="http://192.168.20.3:81/__pro/argonaut/boostrapp/css/check.css" rel="stylesheet">--!>
<link href="http://192.168.20.22/intranet-spa/css/check.css" rel="stylesheet"> 
<?php
$html = '';
/*
$trn_id = $_GET['trn_id'];
$etapa_id = $_GET['etapa_id'];*/

$trn_id   = $_POST['trn_id'];
$etapa_id = $_POST['etapa_id'];

if (isset($trn_id)){
 $mysqli -> set_charset("utf8");
 //echo 'entr'.$etapa_id;    
        $resultado = $mysqli->query("SELECT
                                	      ob.trn_id_rel
                                         ,od.folio_interno AS orden_trabajo
                                         ,od.folio_inicial 
                                         ,ob.metodo_id
                                         ,ob.fase_id
                                         ,ob.etapa_id
                                         ,f.nombre as fase
                                         ,et.nombre as etapa
                                         ,l.cantidad_muestras AS total
                                         ,fe.cantidad_tipo
                                         ,(CASE fe.cantidad_tipo WHEN 1 THEN 'PORCIENTO' WHEN 0 THEN 'UNIDADES' WHEN 2 THEN 'CICLOS' END) AS tipo_cantidad_letra
                                         ,fe.cantidad_muestras
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
                                        LEFT JOIN arg_ordenes_detalle AS od
                                        	ON od.trn_id = ob.trn_id_rel
                                    WHERE
                                        ob.trn_id_rel = ".$trn_id."
                                        AND ob.etapa_id = ".$etapa_id."
                                    ORDER BY ob.fecha DESC
                                    LIMIT 1") or die(mysqli_error());
        
        if ($resultado->num_rows > 0) {
        //Inicia preparación con etapa 1 secado    
        if($etapa_id == 1){        
            if ($resultado->num_rows > 0) {
                while ($res = $resultado->fetch_assoc()) {
                    
                  $tipo_can = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total = $res['total'];
                  $trn_id = $res['trn_id_rel'];
                  $metodo_id = $res['metodo_id'];
                  $orden_trabajo = $res['orden_trabajo'];   
                  
                  if ($tipo_can == 1){   //Porcentajes
                        $limite = round((($cantidad_muestras*$total)/100)); 
                  }
                  if ($tipo_can == 2){
                        $limite = $cantidad_muestras;       
                  }                   
                 // echo 'lim'.$limite;
                  
                 $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT
                                                        trn_id_batch,
                                                        bloque,
                                                        posicion,
                                                        ot.trn_id_rel,
                                                        folio_interno,
                                                        om.folio as muestra             
                                                    FROM 
                                                        `ordenes_transacciones` ot 
                                                        LEFT JOIN arg_ordenes_muestras om
                                                        	ON ot.trn_id_batch = om.trn_id_rel
                                                            AND om.trn_id = ot.trn_id_rel   
                                                    WHERE ot.tipo_id = 0 AND trn_id_batch = ".$trn_id." AND metodo_id = ".$metodo_id."
                                                    ORDER BY (FLOOR (1+RAND()*".$total."))
                                                    LIMIT ".$limite.") AS x ORDER BY bloque, posicion") or die(mysqli_error());
                
                $html =  "<div class='col-md-10 col-lg-10'>
                           <table class='table text-black' id='tabla_peso'>
                                <thead class='table-info' align='center'>
                                <tr class='table-warning'>
                                    <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                </tr>
                                <tr class='table-info'>
                                    <th colspan='1'>No.</th>
                                    <th colspan='7'>Muestra</th>
                                    <th colspan='3'>Peso kg</th>
                                </thead>
                                <tbody>";
                 while ($res_muestras = $resultado_mues->fetch_assoc()) {
                        $con = $con+1;                    
                        $html.="<tr>
                                    <td>".$con."</td>
                                    <td style='display:none;'> <input type='input' id='trn_batch".$con."' value='".$res_muestras['trn_id_batch']."' /></td>
                                    <td style='display:none;'> <input type='input' id='trn_rel".$con."' value='".$res_muestras['trn_id_rel']."' /></td>
                                    <td colspan='6'>".$res_muestras['muestra']."</td>                                            
                                    <td colspan='3'>  <input type='number' name='peso".$con."' id='peso".$con."' class='form-control' /> </td> 
                                </tr>";
                    }
                 $html .= "</tbody></table></div>";
            }           
            echo utf8_encode($html);
        }        
    }
    
    if($etapa_id == 2){
       // echo 'eta2';
        while ($res = $resultado->fetch_assoc()) {                 
                  $tipo_can = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total = $res['total'];
                  $trn_id = $res['trn_id_rel'];
                  $metodo_id = $res['metodo_id'];
                  $orden_trabajo = $res['orden_trabajo'];
                  $folio_inicial = $res['folio_inicial'];
                  //echo 'cada ciclo de:'.$tipo_can;
                  //echo $cantidad_muestras.'tot';
                  //echo $total;
                  
                  $html =  "<table class='table text-black' id='tabla_quebrado'>
                                <thead class='table-info' align='center'>
                                <tr class='table-warning'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                </tr>                               
                                <tr>
                                    <th colspan='1'>Muestra</th>
                                    <th colspan='1'>Peso kg</th>
                                    <th colspan='1'>Malla kg</th>
                                    <th colspan='1'>%</th>
                                    <th colspan='2'>Comentario</th>                                    
                                </thead>
                                <tbody>";
                                
                  $con = 0;
                  $existen_quebr = $mysqli->query("SELECT
                                            	     ot.trn_id_batch,
                                                     ot.trn_id_rel,
                                                     ROUND(pul.peso, 2) AS peso,
                                                     ROUND(pul.peso_malla, 2) AS peso_malla,
                                                     ROUND(pul.porcentaje, 2) AS porcentaje,
                                                     pul.comentario,
                                                     ot.folio_interno as muestra  
                                                  FROM 
                                                    arg_muestras_quebrado pul
                                                    LEFT JOIN ordenes_transacciones ot
                                                        ON  pul.trn_id = ot.trn_id_batch
                                                        AND pul.trn_id_rel = ot.trn_id_rel
                                                  WHERE
                                                    pul.peso = 0 AND pul.peso_malla = 0 AND  pul.trn_id = ".$trn_id) or die(mysqli_error());
            
                 if ($existen_quebr->num_rows > 0) {
                        while ($res_muestras = $existen_quebr->fetch_assoc()) {
                            $con = $con+1;    
                            $trn_id_batch = $res_muestras['trn_id_batch'];
                            $trn_id_rel = $res_muestras['trn_id_rel'];
                            $muestra = $res_muestras['muestra'];
                            //$peso_mu_que = $res_muestras['peso'];
                            //$peso_malla_que = $res_muestras['peso_malla'];
                            //$porc_que = $res_muestras['porcentaje'];
                            $coment_que = $res_muestras['comentario'];  
                            $html.="<tr>                                  
                                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$muestra."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' value='".$peso_mu_que."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' value='".$peso_malla_que."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' value='".$porc_que."' class='form-control' disabled/> </td>
                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' value='".$coment_que."' class='form-control' disabled /></td>
                                            <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                                    </tr>"; 
                     }
                }
                else{           
                  if ($tipo_can == 1){ //Porcentaje
                        $limite = (($cantidad_muestras*$total)/100);
                        $resultado_mues = $mysqli->query(" SELECT * FROM  (SELECT
                                                                            	trn_id_batch,
                                                                                bloque,
                                                                                posicion,
                                                                                trn_id_rel,
                                                                                folio_interno as muestra                                                   
                                                                            FROM 
                                                                                `ordenes_transacciones` WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id."
                                                                                 AND muestra_geologia = '".$folio_inicial."'                                                                          
                                                                            ORDER BY (FLOOR (1+RAND()*".$total."))
                                                            LIMIT ".$limite.") AS x ORDER BY bloque, posicion")   or die(mysqli_error());
                        while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $html.="<tr>
                                         <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                         <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                         <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                         <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                         <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                         <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                         <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                         <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                 <span class='fa fa-cloud fa-1x'></span>
                                         </button></td>
                                    </tr>";
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
                                                    LIMIT ".$limite.") AS x ORDER BY bloque, posicion")   or die(mysqli_error());
                         while ($res_muestras = $resultado_mues->fetch_assoc()) {
                            $con = $con+1;                  
                            $html.="<tr>
                                     <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                            <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                                </tr>"; 
                        }    
                  }
                  
                  if ($tipo_can == 2){//Ciclos 
                     //echo 'enctro ciclo';
                     $bloques = floor($total/$cantidad_muestras);
                     /*echo $bloques.'aq';                     
                     echo $total.'tot';
                     echo $cantidad_muestras.'can';*/
                     $con = 1;
                     $posicion = $cantidad_muestras;                         
                     //echo $folio_inicial;              
                     //echo 'bloques'.$bloques.'blo';
                     //echo 'pos'.$posicion.'pos';
                     
                     //Insertando la primer posición
                     $resultado_mues1 = $mysqli->query("SELECT * FROM  (SELECT
                                                                        	trn_id_batch,
                                                                            bloque,                                                            
                                                                            posicion,
                                                                            trn_id_rel,
                                                                            folio_interno as muestra                                                   
                                                                        FROM 
                                                                            `ordenes_transacciones` 
                                                                        WHERE 
                                                                              tipo_id = 0 AND trn_id_batch = ".$trn_id."
                                                                              AND muestra_geologia = '".$folio_inicial."'
                                                                       ORDER BY muestra_geologia) AS x 
                                                           ") or die(mysqli_error());
                         
                     if ($resultado_mues1->num_rows > 0) {
                               $res_muestras = $resultado_mues1->fetch_assoc();
                               $trn_id_batch = $res_muestras['trn_id_batch'];
                               $trn_id_rel = $res_muestras['trn_id_rel'];
                               $muestra = $res_muestras['muestra'];
                               
                               $query = "INSERT INTO arg_muestras_quebrado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                   "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                               $mysqli->query($query);  
                                                                        
                                    $html.="<tr>                                   
                                                <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                                <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                                <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                                <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                        <span class='fa fa-cloud fa-1x'></span>
                                                </button></td>
                                            </tr>";
                            }
                            
                     $bl = 1;
                     $pos_sel;
                     //echo 'BLOQUES='.$bloques;
                     if ($bloques >= 1)
                     {                         
                         while ($bl <= $bloques){
                             
                             $folio_inicial= $folio_inicial+($cantidad_muestras-1);
                             //echo $folio_inicial;              
                             $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT 
                                                                                	trn_id_batch,
                                                                                    bloque,                                                            
                                                                                    posicion,
                                                                                    trn_id_rel,
                                                                                    folio_interno as muestra,
                                                                                    muestra_geologia,
                                                                                    tipo_id              
                                                                                FROM 
                                                                                    `ordenes_transacciones` 
                                                                                WHERE trn_id_batch = ".$trn_id." AND muestra_geologia = '".$folio_inicial."'
                                                                                
                                                                             ) AS x 
                                                                                ORDER BY bloque, posicion")   or die(mysqli_error());
                         
                           if ($resultado_mues->num_rows > 0) {
                               $res_muestras = $resultado_mues->fetch_assoc();
                               $trn_id_batch = $res_muestras['trn_id_batch'];
                               $trn_id_rel = $res_muestras['trn_id_rel'];
                               $muestra = $res_muestras['muestra'];
                               
                              // if ($res_muestras['tipo_id'] == 0){
                                   
                                   $query = "INSERT INTO arg_muestras_quebrado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                       "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                                   $mysqli->query($query);  
                                                                            
                                        $html.="<tr>                                   
                                                    <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                    <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                    <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                                    <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                    <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                    <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                                    <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                                    <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                            <span class='fa fa-cloud fa-1x'></span>
                                                    </button></td>
                                                </tr>";
                                                
                                         $con = $con+1;   
                                         //$folio_inicial = $folio_inicial+$cantidad_muestras;
                                         $bl = $bl+1 ;
                                }
                             else{
                                    $folio_inicial = $folio_inicial+1;
                                    $resultado_mues = $mysqli->query("SELECT * FROM  (SELECT 
                                                                                	trn_id_batch,
                                                                                    bloque,                                                            
                                                                                    posicion,
                                                                                    trn_id_rel,
                                                                                    folio_interno as muestra,
                                                                                    muestra_geologia,
                                                                                    tipo_id              
                                                                                FROM 
                                                                                    `ordenes_transacciones` 
                                                                                WHERE trn_id_batch = ".$trn_id." AND muestra_geologia = '".$folio_inicial."'
                                                                                
                                                                             ) AS x 
                                                                                ORDER BY bloque, posicion")   or die(mysqli_error());
                                
                                    if ($resultado_mues->num_rows > 0) {
                                       $res_muestras = $resultado_mues->fetch_assoc();
                                       $trn_id_batch = $res_muestras['trn_id_batch'];
                                       $trn_id_rel = $res_muestras['trn_id_rel'];
                                       $muestra = $res_muestras['muestra'];
                                       
                                      // if ($res_muestras['tipo_id'] == 0){
                                           
                                           $query = "INSERT INTO arg_muestras_quebrado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                               "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                                           $mysqli->query($query);  
                                                                                    
                                                $html.="<tr>                                   
                                                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                                                            <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                                    <span class='fa fa-cloud fa-1x'></span>
                                                            </button></td>
                                                        </tr>";
                                                        
                                                 $con = $con+1;   
                                                 //$folio_inicial = $folio_inicial+$cantidad_muestras;
                                                 $bl = $bl+1 ;
                                }
                                
                                
                           }
                 }
                } 
              }//final del else  
            } 
            $html .= "</tbody></table>"; 
                echo utf8_encode($html); 
         }  
       }
        //<td style='display:none;'> <input type='input' value='".$res_muestras['trn_id_rel']."'/></td>   colspan='2'
    
        //Inicia pulverizado
        if($etapa_id == 3){     
             
            while ($res = $resultado->fetch_assoc()) {                   
                $con = 0;
                $orden_trabajo = $res['orden_trabajo'];
                $html =  "<table class='table text-black' id='tabla_pulverizado'>
                                        <thead class='table-info' align='left'>
                                         <tr class='table-warning' align='center'>
                                            <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                         </tr> 
                                        <tr>
                                            <th colspan='1'>Muestra</th>
                                            <th colspan='1'>Peso g</th>
                                            <th colspan='1'>Malla g</th>
                                            <th colspan='1'>% Pulv</th>
                                            <th colspan='2'>Comentario</th>    
                                        </tr>                                    
                                        </thead>
                                        <tbody>";  
            
                $existen_pulv = $mysqli->query("SELECT
                                        	     ot.trn_id_batch,
                                                 ot.trn_id_rel,
                                                 ROUND(pul.peso, 2) AS peso,
                                                 ROUND(pul.peso_malla, 2) AS peso_malla,
                                                 ROUND(pul.porcentaje, 2) AS porcentaje,
                                                 pul.comentario,
                                                 ot.folio_interno as muestra  
                                            FROM 
                                                arg_muestras_pulverizado pul
                                                LEFT JOIN ordenes_transacciones ot
                                                    ON  pul.trn_id = ot.trn_id_batch
                                                    AND pul.trn_id_rel = ot.trn_id_rel
                                            WHERE
                                                pul.porcentaje = 0 AND pul.trn_id = ".$trn_id) or die(mysqli_error());
            
               if ($existen_pulv->num_rows > 0) {
                    while ($res_muestras = $existen_pulv->fetch_assoc()) {
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
                            <td> <button type='button'class='btn btn-primary' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                        <span class='fa fa-cloud fa-1x'></span>
                                 </button>
                            </td>
                        </tr>"; 
                    }
            }
            else{          
                  $tipo_can = $res['cantidad_tipo'];
                  $cantidad_muestras = $res['cantidad_muestras']; 
                  $total = $res['total'];
                  $trn_id = $res['trn_id_rel'];
                  $metodo_id = $res['metodo_id'];
                  $folio_inicial = $res['folio_inicial'];
                  
                  //echo $folio_inicial;
                  //$posicion = $cantidad_muestras;   
                  $con = 1;   
                  //echo 'total'.$total;    
                  //echo $tipo_can;
                  if ($tipo_can == 2){ 
                        $bloques = floor($total/$cantidad_muestras);
                  }
                  //echo 'bloq'.$bloques;
                  //echo $cantidad_muestras.'ca';
                  
                  //Insertando la primer posición
                  $resultado_mues = $mysqli->query("SELECT
                                                                    	trn_id_batch,
                                                                        bloque,                                                            
                                                                        posicion,
                                                                        trn_id_rel,
                                                                        folio_interno as muestra,
                                                                        muestra_geologia                                                  
                                                                    FROM 
                                                                        `ordenes_transacciones` 
                                                                    WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND muestra_geologia = '".$folio_inicial."'
                                                                     ")   or die(mysqli_error());
                                                                //Le quito el tipo = 0 revisar con nancy: 2704/2022 
                  if ($resultado_mues->num_rows > 0) {
                        $res_muestras = $resultado_mues->fetch_assoc();
                        $trn_id_batch = $res_muestras['trn_id_batch'];
                        $trn_id_rel = $res_muestras['trn_id_rel'];
                        $muestra = $res_muestras['muestra'];
                                   
                        $query = "INSERT INTO arg_muestras_pulverizado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                               "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                        $mysqli->query($query); 
                            $html.="<tr>                                   
                                        <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                        <td style='display:none;'> <input type='input' id='trn_batch_rel".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                        <td> <input type='input' name='trn_rel_p".$con."' class='form-control' id='trn_rel_p".$con."' value='".$muestra."' disabled></td> 
                                        <td> <input type='number' name='peso_pul".$con."' id='peso_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                        <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                        <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' disabled/> </td>
                                        <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' disabled /></td>
                                        <td> <button type='button'class='btn btn-primary' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                <span class='fa fa-cloud fa-1x'></span>
                                             </button></td>
                                    </tr>"; 
                                    $con = $con+1;
                  }
                  
                  $bl = 1;
                 // echo $bloques.'blo';
                  if ($bloques >= 1){
                        while ($bl <= $bloques){
                                $folio_inicial= $folio_inicial+($cantidad_muestras-1);
                                //echo $folio_inicial;
                                $resultado_mues = $mysqli->query("SELECT
                                                                                    	trn_id_batch,
                                                                                        bloque,                                                            
                                                                                        posicion,
                                                                                        trn_id_rel,
                                                                                        folio_interno as muestra                                                   
                                                                                    FROM 
                                                                                        `ordenes_transacciones` 
                                                                                    WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND muestra_geologia = '".$folio_inicial."'
                                                                                    LIMIT 1")   or die(mysqli_error());
                                                                //Le quito el tipo = 0 revisar con nancy: 2704/2022 
                                if ($resultado_mues->num_rows > 0) {
                                   $res_muestras = $resultado_mues->fetch_assoc();
                                   $trn_id_batch = $res_muestras['trn_id_batch'];
                                   $trn_id_rel = $res_muestras['trn_id_rel'];
                                   $muestra = $res_muestras['muestra'];
                                   
                                   $query = "INSERT INTO arg_muestras_pulverizado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                          "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                                   $mysqli->query($query);  
                                                                            
                                        $html.="<tr>                                   
                                                    <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                    <td style='display:none;'> <input type='input' id='trn_batch_rel".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                    <td> <input type='input' name='trn_rel_p".$con."' class='form-control' id='trn_rel_p".$con."' value='".$muestra."' disabled></td> 
                                                    <td> <input type='number' name='peso_pul".$con."' id='peso_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                                    <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                                    <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' disabled/> </td>
                                                    <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' disabled /></td>
                                                    <td> <button type='button'class='btn btn-primary' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                            <span class='fa fa-cloud fa-1x'></span>
                                                    </button></td>
                                                </tr>"; 
                                         $con = $con+1;   
                                         //$posicion = $posicion+$cantidad_muestras;
                                         $bl = $bl+1;
                                }
                                else{
                                    $folio_inicial = $folio_inicial+1;
                                    $resultado_mues = $mysqli->query("SELECT
                                                                                    	trn_id_batch,
                                                                                        bloque,                                                            
                                                                                        posicion,
                                                                                        trn_id_rel,
                                                                                        folio_interno as muestra                                                   
                                                                                    FROM 
                                                                                        `ordenes_transacciones` 
                                                                                    WHERE tipo_id = 0 AND trn_id_batch = ".$trn_id." AND muestra_geologia = '".$folio_inicial."'
                                                                                    LIMIT 1")   or die(mysqli_error());
                                                                //Le quito el tipo = 0 revisar con nancy: 2704/2022 
                                if ($resultado_mues->num_rows > 0) {
                                   $res_muestras = $resultado_mues->fetch_assoc();
                                   $trn_id_batch = $res_muestras['trn_id_batch'];
                                   $trn_id_rel = $res_muestras['trn_id_rel'];
                                   $muestra = $res_muestras['muestra'];
                                   
                                   $query = "INSERT INTO arg_muestras_pulverizado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)".
                                                                          "VALUES ($trn_id_batch, $trn_id_rel, 0, 0, 0, '')";
                                   $mysqli->query($query);  
                                                                            
                                        $html.="<tr>                                   
                                                    <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                                    <td style='display:none;'> <input type='input' id='trn_batch_rel".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                                    <td> <input type='input' name='trn_rel_p".$con."' class='form-control' id='trn_rel_p".$con."' value='".$muestra."' disabled></td> 
                                                    <td> <input type='number' name='peso_pul".$con."' id='peso_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                                    <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                                    <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' disabled/> </td>
                                                    <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' disabled /></td>
                                                    <td> <button type='button'class='btn btn-primary' onclick='pulverizado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                                            <span class='fa fa-cloud fa-1x'></span>
                                                    </button></td>
                                                </tr>"; 
                                         $con = $con+1;   
                                         //$posicion = $posicion+$cantidad_muestras;
                                         $bl = $bl+1;
                                }
                                
                            }
                        }
                 }
            }            
          }      
        echo utf8_encode($html);//<td style='display:none;'> <input type='input' value='".$res_muestras['trn_id_rel']."'/></td>   colspan='2'
    }//Termina pulverizado
  }
}
?>