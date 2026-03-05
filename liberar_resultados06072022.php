<?
include "connections/config.php";

$html = '';
$trn_id_a      = $_POST['trn_id_a'];
$metodo_id_a   = $_POST['metodo_id_a'];
$u_id_a        = $_POST['u_id_a'];
$unidad_id_ree = $_POST['unidad_id_a'];

IF (isset($trn_id_a)){ 
    mysqli_multi_query ($mysqli, "CALL arg_prc_liberarResultados (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.", ".$unidad_id_ree.")") OR DIE (mysqli_error($mysqli));
    
     if($result = mysqli_store_result($mysqli)){
		mysqli_free_result($result);
    } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
    
    mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados (".$trn_id_a.", ".$metodo_id_a.", 2".")") OR DIE (mysqli_error($mysqli));
    $result = mysqli_store_result($mysqli);      
    $count_sat = $result->num_rows;
    if ($count_sat <> 0){        
        $html = ' Se he liberado la orden de trabajo.';            
    }
    else{
        
        $html = 'Hubo un error, por favor reintente.';
    }
    
    echo $html;
    
}
       
?>