<?
include "connections/config.php";

$html = '';
$trn_id_sol     = $_POST['trn_id_sol'];
$metodo_id_sol  = $_POST['metodo_id_sol'];
$u_id_sol       = $_POST['u_id_sol'];

if (isset($trn_id_sol)){
    
     $datos_orden = $mysqli->query("SELECT
                                           ord.tipo AS tipo_orden
                                       FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel
                                       WHERE det.trn_id = ".$trn_id_sol
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
    
    if($orden_encabezado['tipo_orden'] == 0 || $orden_encabezado['tipo_orden'] == 1 || $orden_encabezado['tipo_orden'] == 6){
         mysqli_multi_query ($mysqli, "CALL arg_prc_deshacerRevisionSol (".$trn_id_sol.", ".$metodo_id_sol.", ".$u_id_sol.")") OR DIE (mysqli_error($mysqli));
         $html = ' Se he eliminado la revision del batch.';   
           
    }else{
        $html = 'Este tipo de orden no se puede deshacer. Reintente';
    }
        
    echo $html;
    
}
       
?>