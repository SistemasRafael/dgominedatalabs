<?
include "connections/config.php";

$html = '';
$trn_id_a      = $_POST['trn_id_a'];
$metodo_id_a   = $_POST['metodo_id_a'];
$u_id_a        = $_POST['u_id_a'];
$unidad_id_ree = $_POST['unidad_id_a'];

IF (isset($trn_id_a)){
    
     $datos_orden = $mysqli->query("SELECT
                                        (CASE WHEN ord.tipo = 0 THEN 1 ELSE 0 END) AS reensaye
                                    FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel
                                       WHERE det.trn_id = ".$trn_id_a
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
   
    if($orden_encabezado['reensaye'] == 0){        
        mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosQuebr (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
    }
    else{
        mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultadosQuebr_Ree (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));   
    }             
    
     if($result = mysqli_store_result($mysqli)){
		mysqli_free_result($result);
    } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
    
     
     mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados_quebr (".$trn_id_a.", ".$metodo_id_a.", 3".")") OR DIE (mysqli_error($mysqli));
     $result = mysqli_store_result($mysqli);      
     $count_sat = $result->num_rows;
     if ($count_sat <> 0){        
            $html = ' Se he liberado la orden de trabajo.';            
     }
     else{            
            $html = 'Hubo un error, por favor reintente.';
        }
     }   
        
    echo $html;   
?>