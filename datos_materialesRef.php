<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$nombre = $_POST['nombre'];
$ley = $_POST['ley'];
$desv_esta = $_POST['desviacion_est'];

    $max_metodo_id = $mysqli->query("SELECT MAX(material_id) AS material_id FROM arg_materiales_referencia") or die(mysqli_error());
    $max_meto = $max_metodo_id ->fetch_array(MYSQLI_ASSOC);
    $material_id = $max_meto['material_id'];
    $material_id = $material_id+1;
    
if (isset($u_id)){
    //$maximo = ($ley+($desv_esta*$cantidad));
    //$minimo = ($ley-($desv_esta*$cantidad));
    $query = "INSERT INTO arg_materiales_referencia (material_id, nombre, ley, desviacion_estandar, u_id) ".
             "VALUES ($material_id, '$nombre', $ley, $desv_esta, $u_id)";
             $mysqli->query($query) ;
                            //echo $query;
                            //die();
             $resultado = $mysqli->query("SELECT
                                            material_id
                                          FROM 
                                            arg_materiales_referencia
                                          WHERE material_id = ".$material_id) or die(mysqli_error());
             //echo $query;
    if ($resultado->num_rows > 0) {
        $html = 'Se registro exitosamente.';
    }
    else{
        $html = 'Hubo un error, reintente por favor.';
    }
  }
  
 $mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>