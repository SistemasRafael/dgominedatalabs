<?include "connections/config.php";?>
<?php
$html = '';
$trn_id_rel_cp   = $_POST['trn_id_cp'];
$metodo_cop   = $_POST['metodo_cp'];
$cantidad_cop = $_POST['cantidad_cp'];
$ins_id_cp   = $_POST['ins_id_cp']; 
$u_id_cop = $_SESSION['u_id'];

if (isset($trn_id_rel_cp)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_copeladoGuardar(".$trn_id_rel_cp.", ".$metodo_cop.", ".$ins_id_cp.", ".$cantidad_cop.", ".$u_id_cop.")") OR DIE (mysqli_error($mysqli));   
  
   $resultado = $mysqli->query("SELECT
                                      se.cantidad
                                FROM 
                                    arg_muestras_copelado se
                                WHERE se.trn_id_rel = ".$trn_id_rel_cp." AND metodo_id = ".$metodo_cop) or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La etapa de copelado ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         $mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 }
?>