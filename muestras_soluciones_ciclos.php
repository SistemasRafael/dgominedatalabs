<? include "connections/config.php"; 

$ciclos = $_POST['ciclos'];
$id = $_POST['id'];
$html = '';

if ($id > 0){
    $cambia = $mysqli->query("UPDATE arg_ordenes_muestrasSoluciones SET ciclica = ".$ciclos." WHERE id = " . $id
    ) or die(mysqli_error($mysqli));
    /*$query =  "UPDATE arg_ordenes_muestrasSoluciones SET ciclica = ".$ciclos." WHERE id = " . $id;    
    $mysqli->query($query) ;*/
  
    $html = 'Se cambió exitosamente.';
} 
echo $html;
?>