<?
include "connections/config.php";

$html = '';
$trn_id_a      = $_POST['trn_id_a'];
$metodo_id_a   = $_POST['metodo_id_a'];
$u_id_a        = $_POST['u_id_a'];
$unidad_id_ree = $_POST['unidad_id_a'];

IF (isset($trn_id_a)){
    
     $datos_orden = $mysqli->query("SELECT
                                            (CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                            ,ord.tipo
                                       FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel
                                       WHERE det.trn_id = ".$trn_id_a
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
    
     if($orden_encabezado['tipo'] == 2){
         mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosSol (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
         $html = ' Se he liberado la orden de trabajo.';   
           
    }else if ($orden_encabezado['tipo'] == 6){
        
         mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosRech(".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
         $html = ' Se he liberado la orden de trabajo.';
         
    }else{
        if ($orden_encabezado['reensaye'] == 0){    
            mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultados (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));             
        }
        else{
             $origen_reen = $mysqli->query("SELECT 
                                                                        COUNT(*) AS existe_rech
                                                                   FROM 
                                                                       arg_muestras_reensaye mre
                                                                   WHERE mre.trn_id_rel = " . $trn_id_a . " 
                                                                   AND mre.metodo_id = (CASE WHEN " . $metodo_id_a . " = 0 
                                                                                        THEN mre.metodo_id ELSE " . $metodo_id_a . " 
                                                                                        END) 
                                                                   AND mre.trn_id_muestra IN (SELECT trn_id_rel 
                                                                                          FROM muestras_recheck)"
                                            ) or die(mysqli_error($mysqli));
                                    $existe_reche = $origen_reen->fetch_assoc();
                                    $existe_rec = $existe_reche['existe_rech'];
                                    
                        if ($existe_rec == 0){  
                            mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosRee (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
                        }
                        else{
                            mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosReeRech (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
                         }
        }
        
         if($result = mysqli_store_result($mysqli)){
    		mysqli_free_result($result);
        } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
        
        
         if ($orden_encabezado['reensaye'] == 0){    
            mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados (".$trn_id_a.", ".$metodo_id_a.", 2".")") OR DIE (mysqli_error($mysqli));
            $result = mysqli_store_result($mysqli);      
            $count_sat = $result->num_rows;
            if ($count_sat <> 0){        
                $html = ' Se he liberado la orden de trabajo.';            
            }
            else{
                
                $html = 'Hubo un error, por favor reintente.';
            }
         }
         else{
             mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados_ree (".$trn_id_a.", ".$metodo_id_a.", 1".")") OR DIE (mysqli_error($mysqli));
            $result_ree = mysqli_store_result($mysqli);      
            $count_ree = $result_ree->num_rows;
            if ($count_ree <> 0){        
                $html = ' Se he liberado la orden de trabajo.';            
            }
           
         }
      }
            
      echo $html;
        
    }
       
?>