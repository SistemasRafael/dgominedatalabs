<?include "connections/config.php";?>
<?php
$html = '';
$trnid_orig   = $_POST['trn_orig'];
$trnid_dest   = $_POST['trn_dest'];
$trnid_muestra = $_POST['trnid_mu'];
$metodo_tem   = $_POST['metodo_m'];

$u_id = $_SESSION['u_id'];

if (isset($trnid_dest)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_moverMuestraOrdenRee(".$trnid_orig.", ".$trnid_dest.", ".$trnid_muestra.",".$metodo_tem.", ".$u_id.")") OR DIE (mysqli_error($mysqli));   
  
   $resultado = $mysqli->query("SELECT
                                   *
                                FROM 
                                    ordenes_reensayes se
                                WHERE se.trn_id_rel = ".$trnid_dest." AND se.trn_id_muestra = ".$trnid_muestra." AND se.metodo_id = ".$metodo_tem) or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La muestra se ha cambiado satisfactoriamente.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         $mysqli -> set_charset("utf8");
     
        
  }
   echo utf8_encode($html);
// }
?>