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

//echo $final;
//echo $u_id;
if (isset($trnid_m)){
   //if ($fase_sel == 2 && $etapa_sel == 5){
        mysqli_multi_query ($mysqli, "CALL arg_prc_ordenTitulacionPeso(".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli));  
   //}     
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
       
       $datos_met = $mysqli->query("SELECT nombre AS nombre_metodo, elemento FROM arg_metodos WHERE metodo_id = $metodo_sel") or die(mysqli_error($mysqli));
       $datos_meto = $datos_met->fetch_assoc();
       $nombre_metodo = $datos_meto['nombre_metodo'];
       $elemento = $datos_meto['elemento'];    
        
       $resultado = $mysqli->query("SELECT
                                             os.trn_id_batch as trnid_batch_met
                                            ,os.trn_id_rel as trnid_rel_met
                                            ,met.nombre as metodo_nombre
                                            ,ROUND(os.resultado, 2) AS peso_muestra                  
                                            ,om.folio as muestra
                                         FROM 
                                            arg_ordenes_soluciones os
                                            LEFT JOIN arg_ordenes_muestrasSoluciones om
                                                ON os.trn_id_rel = om.trn_id
                                            LEFT JOIN arg_metodos met
                                                ON met.metodo_id = os.metodo_id
                                         WHERE 
                                            os.trn_id_batch = ".$trnid_m."
                                            AND os.metodo_id = ".$metodo_sel."
                                            AND os.resultado = 0
                                         ORDER BY                                            
                                            om.orden"
                                    ) or die(mysqli_error());
               
        if ($resultado->num_rows > 0) {
            $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
            $metodo_codigo = $datos_gen['metodo'];
            $metodo_fase   = $datos_gen['fase'];
            $metodo_etapa  = $datos_gen['etapa'];
            
            
            $html =  "<table class='table text-black' id='tabla_titulacion'>
                                <thead class='thead-info' align='center'>
                                   <tr class='table-info'>
                                        <th colspan='5'>M&eacutetodo: ".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                                    </tr>
                                    <tr class='table-warning' align='center'>
                                        <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                                    </tr>"; 
            if ($etapa_sel == 23 || $etapa_sel == 24 || $etapa_sel == 25 || $etapa_sel == 26){
                 $html.="<tr class='table-info' align='left'>
                                        <th>No.</th>
                                        <th>Muestra</th>
                                        <th>".$elemento."</th>
                                        <th></th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res_muestras = $resultado->fetch_assoc()) {
                        $cont = $cont+1;
                        $trnid_batch_met = $res_muestras['trnid_batch_met'];
                        $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                        $muestra_met     = $res_muestras['muestra'];
                                                      
                        $html.="<tr>                    
                                   <td>".$cont."</td> 
                                   <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                   <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                                   <td>".$muestra_met."</td>
                                   <td> <input type='number' id='peso_tit".$cont."' class='form-control' /> </td>
                                   <td> <button type='button'class='btn btn-primary' id='boton_save_tit' onclick='met_titulacion_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
            
            }//Fin de etapa titulacion            
            $html .= "</tbody></table></div>"; 
        }        
        else{
            $html = 'Ha finalizado la etapa.';
        }
        //$mysqli -> set_charset("utf8");
        //echo utf8_encode($html);        
        $mysqli -> set_charset("utf8");
        echo ($html);
  }
  
?>