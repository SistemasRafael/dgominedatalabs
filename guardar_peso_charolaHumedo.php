<?php include "connections/config.php";?>
<?php
$html = '';
$trnid_m    = $_POST['trnid_orden'];
$trnidrel_m = $_POST['trnid_muestra'];
$metodo_sel = $_POST['metodo_id'];
$fase_sel   = $_POST['fase'];
$etapa_sel  = $_POST['etapa'];

$cantidad_ch = $_POST['cantidad_metodo_ch'];
$cantidad    = $_POST['cantidad_metodo'];
$final       = $_POST['fin_met'];
$u_id        = $_SESSION['u_id'];

//echo 'llego';
//echo $final;
//echo $u_id;
if (isset($trnid_m)){
        mysqli_multi_query ($mysqli, "CALL arg_prc_pesoCharolaHumedo(".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad_ch.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli)); 
       
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
      
       if ($metodo_sel == 30){       
            $resultado = $mysqli->query("SELECT
     	                                     hum.trn_id_batch 
                                            ,hum.trn_id_rel
                                            ,mm.folio  AS muestra
                                        FROM 
                                            `arg_ordenes_humedad` hum
                                            LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                                ON hum.trn_id_batch = mm.trn_id_rel
                                                AND hum.trn_id_rel = mm.trn_id
                                        WHERE 
                                            hum.trn_id_batch = ".$trnid_m."
                                            AND (CASE WHEN ".$etapa_sel." = 28 THEN hum.peso_humedo = 0 
                                                      WHEN ".$etapa_sel." = 1 THEN hum.peso_seco = 0 
                                            END)") 
                                                or die(mysqli_error());
        }                                  
       
       if ($resultado->num_rows > 0) {
            //Peso humedo metodo Hum% 30
            $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
            $metodo = $datos_gen['metodo'];
            $fase   = $datos_gen['fase'];
            $etapa  = $datos_gen['etapa'];
                  
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
                                
                  $cont = 0;
                                                                
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trn_id_batch'];
                        $trnid_rel_met   = $res_muestras['trn_id_rel'];
                        $muestra_met     = $res_muestras['muestra'];
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td> <input type='number' id='peso_ch".$cont."' value='' class='form-control'/> </td>
                                   <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_peso_guardarHumCh(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
       }//Fin de etapa 5 pesaje de metodo humedad
       else{
            $html = 'Ha finalizado la etapa.';
        }
         $mysqli -> set_charset("utf8");     
         echo ($html);
 }
?>