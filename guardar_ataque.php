<?include "connections/config.php";?>
<?php
$html = '';
$trn_id_rel   = $_POST['trn_id_tem'];
$metodo_tem   = $_POST['metodo_tem'];
$hora_ini     = $_POST['hora_ini'];
$hora_fin     = $_POST['hora_fin'];
$fase_id      = $_POST['fase_id_agi']; 
$etapa_id     = $_POST['etapa_id_agi']; 
$u_id = $_SESSION['u_id'];

if (isset($trn_id_rel)){
   mysqli_multi_query ($mysqli, "CALL arg_ataque_guardar(".$trn_id_rel.", ".$metodo_tem.", ".$fase_id.", ".$etapa_id.", '".$hora_ini."', '".$hora_fin."', ".$u_id.")") OR DIE (mysqli_error($mysqli));   
  
   $resultado = $mysqli->query("SELECT
                                   *
                                FROM 
                                    arg_muestras_ataque
                                WHERE trn_id_rel = ".$trn_id_rel." 
                                AND metodo_id = ".$metodo_tem." 
                                AND fase_id = ".$fase_id." 
                                AND etapa_id = ".$etapa_id) or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La etapa de Ataque Quimico ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         $mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 }
?>