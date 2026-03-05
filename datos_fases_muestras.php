<?include "connections/config.php";?>
<?php
$html = '';
$metodo_id = $_POST['metodo_id'];
$trn_batch = $_POST['trn_batch'];
$fase_id = $_POST['fase_id'];
$unidad_id = $_SESSION['unidad_id'];

$mysqli -> set_charset("utf8");
//echo $trn_batch;

  if (isset($trn_batch)) {
   // echo $trn_batch;
    if ($fase_id == 1){
        $resultado = $mysqli->query("SELECT 
                                        folio_batch, folio_muestra, fase, secado, porcentaje_quebrado, porcentaje_pulverizado, tipo_id, trn_id_batch
                                     FROM 
                                        dash_preparacion_muestras
                                     WHERE
                                       tipo_id = 0 AND trn_id_batch = ".($trn_batch)." ORDER BY folio_muestra"
                                     ) or die(mysqli_error()); 
        
        if ($resultado->num_rows > 0) { 
                    
                        $html .=    "<div class='card' >
                                        <table class='table text-black' id='datos_fases'>
                                            <thead class='table-info' align='left'>
                                            <tr class='table-info' align='center'
                                            >
                                                <th>No.</th>
                                                <th>Orden</th>
                                                <th>Fase</th>
                                                <th>Muestra</th>
                                                <th>Secado Kg</th>
                                                <th>% Quebrado</th>
                                                <th>% Pulerizado</th>                                    
                                            </thead>
                                            <tbody>
                                    ";
                        $cont = 1;
                        while ($row = $resultado->fetch_assoc()){
                            $html .="<tr>                                   
                                                <td>".$cont."</td>  
                                                <td>".$row['folio_batch']."</td>  
                                                <td>".$row['fase']."</td> 
                                                <td>".$row['folio_muestra']."</td>  
                                                <td>".$row['secado']."</td>                            
                                                <td>".$row['porcentaje_quebrado']."</td> 
                                                <td>".$row['porcentaje_pulverizado']."</td> 
                                    </tr>";   
                        $cont = $cont+1;
                        }
                     $html .= "</div>";
        }
     }
     else{
         $resultado_etapa = $mysqli->query("SELECT 
                                            DISTINCT etapa_id, etapa, tipo_id, trn_id_batch, metodo_id, fase_id
                                         FROM 
                                            dash_fases_resultados
                                         WHERE
                                            tipo_id = 0 AND trn_id_batch = ".($trn_batch)." AND metodo_id = ".$metodo_id." AND fase_id = ".$fase_id
                                         ) or die(mysqli_error());
        
         while ($row_etapa = $resultado_etapa->fetch_assoc()){
                $etapa_id = $row_etapa['etapa_id'];
                $etapa = $row_etapa['etapa'];
                    
                    if ($etapa_id == 5 or $etapa_id == 7){
                        $resultado_met = $mysqli->query("SELECT 
                                                         folio_batch, folio_muestra, fase, etapa_id, etapa, tipo_id, trn_id_batch, metodo_id, fase_id
                                                        ,(CASE ".$etapa_id." WHEN 5 THEN Pesaje WHEN 7 THEN absorcion END) AS resultado, Pesaje_payon
                                                     FROM 
                                                        dash_fases_resultados
                                                     WHERE
                                                      etapa_id = ".$etapa_id." AND tipo_id = 0 AND trn_id_batch = ".($trn_batch)." AND metodo_id = ".$metodo_id." AND fase_id = ".$fase_id." ORDER BY folio_muestra"
                                                     ) or die(mysqli_error());
                            if($etapa_id == 5){
                                $encad = 'Pesaje';
                                $encad1 = 'Pesaje Payon';
                             } 
                             else{
                                $encad = 'Absorcion';
                             }
                                    $html .=    "<div class='card'>
                                                    <table class='table text-black' id='datos_fases'>
                                                        <thead class='table-info' align='left'>
                                                        <tr class='table-info' align='left'>
                                                            <th colspan='6'>ETAPA: ".$etapa."</th>
                                                        </tr>
                                                        <tr class='table-info' align='center'>
                                                            <th>No.</th>
                                                            <th>Orden</th>
                                                            <th>Fase</th>
                                                            <th>Muestra</th>
                                                            <th>".$encad."</th> 
                                                            <th>".$encad1."</th>                                   
                                                        </thead>
                                                        <tbody>
                                                ";
                                    $cont = 1;
                                    while ($row = $resultado_met->fetch_assoc()){
                                        $html .="<tr>                                   
                                                            <td>".$cont."</td>  
                                                            <td>".$row['folio_batch']."</td>  
                                                            <td>".$row['fase']."</td> 
                                                            <td>".$row['folio_muestra']."</td>  
                                                            <td>".$row['resultado']."</td> 
                                                            <td>".$row['Pesaje_payon']."</td> 
                                                </tr>";   
                                    $cont = $cont+1;
                                    }
                                 $html .= "</div>";
                             } //Fin etapa 5  y 7
                             
                        if($etapa_id == 16){
                            $resultado_met = $mysqli->query("SELECT
                                                         folio_batch, folio_muestra, fase, etapa_id, etapa, tipo_id, trn_id_batch, metodo_id, fase_id
                                                        ,(CASE ".$etapa_id." WHEN 16 THEN temperatura_cianurado ELSE 0 END) AS resultado
                                                     FROM 
                                                        dash_fases_resultados
                                                     WHERE
                                                      etapa_id = ".$etapa_id." AND tipo_id = 0 AND trn_id_batch = ".($trn_batch)." AND metodo_id = ".$metodo_id." AND fase_id = ".$fase_id." LIMIT 1"
                                                     ) or die(mysqli_error());
                           
                                $encad = 'Temperatura';
                                    $html .=    "
                                                    <table class='table text-black' id='datos_fases'>
                                                        <thead class='table-info' align='left'>
                                                        <tr class='table-info' align='center'>
                                                            <th colspan='5'>ETAPA: ".$etapa."</th>
                                                        </tr>
                                                        <tr class='table-info' align='left'>
                                                            <th>No.</th>
                                                            <th>Orden</th>
                                                            <th>Fase</th>
                                                            <th>Muestra</th>
                                                            <th>".$encad."</th>                                  
                                                        </thead>
                                                        <tbody>
                                                ";
                                    $cont = 1;
                                    while ($row = $resultado_met->fetch_assoc()){
                                        $html .="<tr>                                   
                                                            <td>".$cont."</td>  
                                                            <td>".$row['folio_batch']."</td>  
                                                            <td>".$row['fase']."</td> 
                                                            <td>".$row['folio_muestra']."</td>  
                                                            <td>".$row['resultado']."</td> 
                                                </tr>";   
                                    $cont = $cont+1;
                                    }
                                
                             }
                             
                             if ($etapa_id == 17){
                                
                                     $resultado_met = $mysqli->query("SELECT
                                                         folio_batch, fase, etapa_id, etapa, tipo_id, trn_id_batch, metodo_id, fase_id
                                                        ,Agitado_inicio
                                                        ,Agitado_final
                                                     FROM 
                                                        dash_fases_resultados
                                                     WHERE
                                                      etapa_id = ".$etapa_id." AND tipo_id = 0 AND trn_id_batch = ".($trn_batch)." AND metodo_id = ".$metodo_id." AND fase_id = ".$fase_id."  LIMIT 1 "
                                                     ) or die(mysqli_error());
                           
                                $encad = 'Temperatura';
                                    $html .=    "
                                                    <table class='table text-black' id='datos_fases'>
                                                        <thead class='table-info' align='left'>
                                                        <tr class='table-info' align='left'>
                                                            <th colspan='5'>ETAPA: ".$etapa."</th>
                                                        </tr>
                                                        <tr class='table-info' align='center'>
                                                            <th>No.</th>
                                                            <th>Orden</th>
                                                            <th>Fase</th>
                                                            <th>Agitado Inicio</th>
                                                            <th>Agitado Fin</th>                                  
                                                        </thead>
                                                        <tbody>
                                                ";
                                    $cont = 1;
                                    while ($row = $resultado_met->fetch_assoc()){
                                        $html .="<tr>                                   
                                                            <td>".$cont."</td>  
                                                            <td>".$row['folio_batch']."</td>  
                                                            <td>".$row['fase']."</td> 
                                                            <td>".$row['Agitado_inicio']."</td>  
                                                            <td>".$row['Agitado_final']."</td> 
                                                </tr>";   
                                    $cont = $cont+1;
                                    }
                               
                             }
                             
                              if ($etapa_id == 18){
                                
                                     $resultado_met = $mysqli->query("SELECT
                                                         folio_batch, fase, etapa_id, etapa, tipo_id, trn_id_batch, metodo_id, fase_id                                                        
                                                        ,Centrifugado_final
                                                     FROM 
                                                        dash_fases_resultados
                                                     WHERE
                                                      etapa_id = ".$etapa_id." AND tipo_id = 0 AND trn_id_batch = ".($trn_batch)." AND metodo_id = ".$metodo_id." AND fase_id = ".$fase_id."  LIMIT 1 "
                                                     ) or die(mysqli_error());
                           
                                $encad = 'Centrifugado';
                                    $html .=    "
                                                    <table class='table text-black' id='datos_fases'>
                                                        <thead class='table-info' align='left'>
                                                        <tr class='table-info' align='center'>
                                                            <th colspan='4'>ETAPA: ".$etapa."</th>
                                                        </tr>
                                                        <tr class='table-info' align='left'>
                                                            <th>No.</th>
                                                            <th>Orden</th>
                                                            <th>Fase</th>
                                                            <th>Fecha Fin</th>                                  
                                                        </thead>
                                                        <tbody>
                                                ";
                                    $cont = 1;
                                    while ($row = $resultado_met->fetch_assoc()){
                                        $html .="<tr>                                   
                                                            <td>".$cont."</td>  
                                                            <td>".$row['folio_batch']."</td>  
                                                            <td>".$row['fase']."</td> 
                                                            <td>".$row['Centrifugado_final']."</td> 
                                                </tr>";   
                                    $cont = $cont+1;
                                    }
                                
                             }
                    
                    
                    }        $html .= "</div>";                      
        
     }
     //$mysqli -> set_charset("utf8");
     echo ($html);
}

/* <a type='button' onclick='llama_datos();'><i class='fa fa-fire' aria-hidden='true'>".$row_det['fase']."</i></a>                      
                      $html .= "<div class='col-12'>
                                     <div class='col-1'>
                                     </div>
                                     <div class='row justify-content-between top'>      ";
                        while ($row_det = $resultado_detalle->fetch_assoc()){
                          
                        $html .= "                         
                                <div class='row d-flex icon-content'>                                     
                                    <div class='d-flex flex-column'>
                                        <a type='button' onclick='llama_datos();'><i class='fa fa-fire' aria-hidden='true'>".$row_det['fase']."</i></a>
                                    </div>
                                </div>
                           ";
                        
                       } */

?>