<? include "connections/config.php"; 

$orden = $_POST['orden'];
$id = $_POST['id'];
$html = '';
$orden_existente = $mysqli->query("SELECT COUNT(*) AS existe FROM arg_ordenes_muestrasSoluciones WHERE area_id = 1 AND orden = ".$orden);
$exist = $orden_existente->fetch_assoc();
$orden_existe = $exist['existe'];
//echo 'soy'.$orden;

if ($orden_existe == 0) {
    $cambia = $mysqli->query(
        "UPDATE arg_ordenes_muestrasSoluciones SET orden = $orden WHERE id = " . $id
    ) or die(mysqli_error($mysqli));
    $html = 'Se cambió exitosamente.';
} else {
    $html = 'La posición ya está asignada.';
}
echo $html;
?>