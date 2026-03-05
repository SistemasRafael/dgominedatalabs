<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$fase_id = $_POST['fase_sel'];
$etapa_id = $_POST['etapa_sel'];
$orden = $_POST['orden_etapa'];
$cantidad_id = $_POST['cantidad_id_sel'];
$cantidad_muestras = $_POST['cantidad_muestras_sel'];
    
    /*$duplicado = $mysqli->query("SELECT COUNT(*) FROM arg_fases_etapas WHERE nombre = '".$nombre."'") or die(mysqli_error());
    $duplicado_nom = $duplicado ->fetch_array(MYSQLI_ASSOC);
    $duplicado_nombre = $duplicado_nom['nombre'];*/
    
if (isset($u_id)){
  /*  if ($duplicado_nombre == $nombre){
        $html = 'Error: La etapa ya existe, favor de validar.';
    }
    else{*/
        $query = "INSERT INTO arg_fases_etapas (fase_id, etapa_id, orden, cantidad_tipo, cantidad_muestras, u_id, comentarios) ".
                 "VALUES ($fase_id, $etapa_id, $orden, $cantidad_id, $cantidad_muestras, $u_id, '')";
                 $mysqli->query($query);
                              // echo $query;
                               // die();
                 $resultado = $mysqli->query("SELECT
                                                fase_id
                                              FROM 
                                                arg_fases_etapas
                                              WHERE fase_id = ".$fase_id) or die(mysqli_error());
                 //echo $query;
        if ($resultado->num_rows > 0) {
            $html = 'Se registro exitosamente.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
  //}
}
  
 $mysqli -> set_charset("utf8");
 
echo utf8_encode($html);

?>