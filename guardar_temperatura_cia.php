<?include "connections/config.php";?>
<?php
$html = '';
$trn_id_rel   = $_POST['trn_id_tem'];
$metodo_tem   = $_POST['metodo_tem'];
$cantidad_tem = $_POST['cantidad_tem']; 
$u_id = $_SESSION['u_id'];

if (isset($trn_id_rel)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_temperaturaGuardarCia(".$trn_id_rel.", ".$metodo_tem.", ".$cantidad_tem.", ".$u_id.")") OR DIE (mysqli_error($mysqli));   
  
   $resultado = $mysqli->query("SELECT
                                   cantidad
                                FROM 
                                    arg_muestras_temperaturas se
                                WHERE se.trn_id_rel = ".$trn_id_rel." AND se.metodo_id = ".$metodo_tem) or die(mysqli_error());
             //echo $query;
        if ($resultado->num_rows > 0) {
            $html =  'La etapa de cianurado ha finalizado.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         $mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
  }
// }
?>