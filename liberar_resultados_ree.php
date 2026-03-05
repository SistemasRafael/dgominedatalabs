<?
include "connections/config.php";

$html = '';
$trn_id_a = $_POST['trn_id_env'];
$metodo_id_a = $_POST['metodo_id_env'];

IF (isset($trn_id_a)){ 
    mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados (".$trn_id_a.", ".$metodo_id_a.", 1".")") OR DIE (mysqli_error($mysqli));
    if ($result = mysqli_store_result($mysqli)) {                
            $count = $result->num_rows;
            $html  = 'Se enviaron '.$count.' muestras a reensaye.';
    }
    else{
        $html  = 'No se enviaron muestras a reensayes.';
    }
    
      if($result=mysqli_store_result($mysqli)){
		mysqli_free_result($result);
} while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
    
    mysqli_multi_query ($mysqli, "CALL arg_consultar_resultados (".$trn_id_a.", ".$metodo_id_a.", 0".")") OR DIE (mysqli_error($mysqli));
    if ($result = mysqli_store_result($mysqli)) {                
            $count_sat = $result->num_rows;
            $html .= ' Se obtuvieron '.$count_sat.' resultados satisfactorios.';            
    }
    $html .= ' Se ha enviado la alerta de liberación al cliente, a continuación se descargarán los resultados.';  
    echo utf8_encode($html);
}
       
?>