<?include "connections/config.php";?>
<?php
$html = '';
$trn_id_rel_dg   = $_POST['trn_id_dg'];
$metodo_dg       = $_POST['metodo_dg'];
$cantidad_dg     = $_POST['cantidad_dg'];
$u_id_dg         = $_SESSION['u_id'];

if (isset($trn_id_rel_dg)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_digestionGuardar(".$trn_id_rel_dg.", ".$metodo_dg.", ".$cantidad_dg.", ".$u_id_dg.")") OR DIE (mysqli_error($mysqli));   
  
   $resultado = $mysqli->query("SELECT
                                    se.trn_id as trn_id_batch, se.trn_id_rel
                                FROM 
                                    arg_muestras_digestion se
                                WHERE se.trn_id_rel = ".$trn_id_rel_dg." AND se.metodo_id = ".$metodo_dg) or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La etapa de Digestión ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         //$mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 }
?>