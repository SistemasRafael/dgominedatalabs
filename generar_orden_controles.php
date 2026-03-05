<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$trn_id = $_POST['trn_id'];
    
if (isset($trn_id)){
    mysqli_multi_query ($mysqli, "CALL generar_orden_controles (".$trn_id.")") OR DIE (mysqli_error($mysqli));
                
                            //echo $query;
                            //die();
             $resultado = $mysqli->query("SELECT
                                            material_id
                                          FROM 
                                            arg_materiales_referencia
                                          WHERE material_id = ".$material_id) or die(mysqli_error());
             //echo $query;
    if ($resultado->num_rows > 0) {
        $html = 'Se inició la orden exitosamente.';
    }
    else{
        $html = 'Hubo un error, reintente por favor.';
    }
  }
  
 $mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>