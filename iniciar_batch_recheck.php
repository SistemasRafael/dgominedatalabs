<?include "connections/config.php";?>
<?php

$html = '';
$trn_id = $_POST['trn_id'];
$u_id = $_SESSION['u_id'];

if (isset($trn_id)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicioRecheck(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));  
   
   $resultado = $mysqli->query("SELECT
                                    ob.trn_id_rel
                                FROM 
                                    arg_ordenes_bitacora ob
                                    LEFT JOIN arg_ordenes_detalle od
                                        ON ob.trn_id_rel = od.trn_id
                                WHERE od.trn_id = ".$trn_id." LIMIT 1 ") or die(mysqli_error());
             
                               if ($resultado->num_rows > 0) {
                                    $html = 'Se inició el batch exitosamente.';
                               }
                               else{
                                    $html = 'Hubo un error, reintente por favor.';
                               }
   echo utf8_encode($html);
  }
  
$mysqli -> set_charset("utf8");
 
?>