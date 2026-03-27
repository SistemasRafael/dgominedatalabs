<?php include "connections/config.php";?>
<?php
$html = '';
$trn_id_rel   = $_POST['trn_id_tem'];
$metodo_tem   = $_POST['metodo_tem'];
$hora_ini     = $_POST['hora_ini'];
$hora_fin     = $_POST['hora_fin']; 

$fase_id      = $_POST['fase_id_agi']; 
$etapa_id     = $_POST['etapa_id_agi']; 
$u_id = $_SESSION['u_id'];

if (isset($trn_id_rel)) {
    if ($metodo_tem == 35){ // Met CB_DIGAa Dgo(35)
        mysqli_multi_query ($mysqli, "CALL arg_prc_digestionAcida_guardar(".$trn_id_rel.", ".$metodo_tem.", ".$fase_id.", ".$etapa_id.", '".$hora_ini."', '".$hora_fin."', ".$u_id.")") OR DIE (mysqli_error($mysqli));   
        
        $resultado = $mysqli->query("SELECT
                                        COUNT(*) AS cantidad
                                        FROM 
                                            arg_muestras_cianuradoAgitacion
                                        WHERE trn_id_rel = ".$trn_id_rel." AND metodo_id = ".$metodo_tem." AND fase_id = ".$fase_id." AND etapa_id = ".$etapa_id) or die(mysqli_error($mysqli));

        if ($resultado->num_rows > 0) {
            $html =  'La etapa ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
    }
    elseif  ($metodo_tem == 2){ // Met CB_DIGAa Dgo(35)
        mysqli_multi_query ($mysqli, "CALL arg_prc_digestionAcida_guardar(".$trn_id_rel.", ".$metodo_tem.", ".$fase_id.", ".$etapa_id.", '".$hora_ini."', '".$hora_fin."', ".$u_id.")") OR DIE (mysqli_error($mysqli));   
        
        $resultado = $mysqli->query("SELECT
                                        COUNT(*) AS cantidad
                                        FROM 
                                            arg_muestras_calcinado
                                        WHERE trn_id_rel = ".$trn_id_rel." AND metodo_id = ".$metodo_tem." AND fase_id = ".$fase_id." AND etapa_id = ".$etapa_id) or die(mysqli_error($mysqli));
                    //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La etapa ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
    }
    else{            
        mysqli_multi_query ($mysqli, "CALL arg_cianurado_guardar(".$trn_id_rel.", ".$metodo_tem.", ".$fase_id.", ".$etapa_id.", '".$hora_ini."', '".$hora_fin."', ".$u_id.")") OR DIE (mysqli_error($mysqli));   
        
        $resultado = $mysqli->query("SELECT
                                        COUNT(*) AS cantidad
                                        FROM 
                                            arg_muestras_cianuradoAgitacion
                                        WHERE trn_id_rel = ".$trn_id_rel." AND metodo_id = ".$metodo_tem." AND fase_id = ".$fase_id." AND etapa_id = ".$etapa_id) or die(mysqli_error($mysqli));
                                        
        if ($resultado->num_rows > 0) {
            $html =  'La etapa de Agitado ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
    }     
 }

 echo utf8_encode($html);
?>