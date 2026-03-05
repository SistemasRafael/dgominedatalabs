<?
include "connections/config.php";

$html = '';
$trn_id_a      = $_POST['trn_id_a'];
$metodo_id_a   = $_POST['metodo_id_a'];
$u_id_a        = $_POST['u_id_a'];

if (isset($trn_id_a)){
    
     $datos_orden = $mysqli->query("SELECT
                                           ord.tipo AS tipo_orden
                                       FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel
                                       WHERE det.trn_id = ".$trn_id_a
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
    
    if($orden_encabezado['tipo_orden'] == 2){
         mysqli_multi_query ($mysqli, "CALL arg_prc_deshacerRevision (".$trn_id_a.", ".$metodo_id_a.", ".$u_id_a.")") OR DIE (mysqli_error($mysqli));
         $html = ' Se he eliminado la revision del batch.';   
           
    }else{
        $html = 'Este tipo de orden no se puede deshacer. Reintente';
    }
        
    echo $html;
    
}
       
?>