<?include "connections/config.php";?>
<?php
 
$estado = $_POST['estado'];
$id = $_POST['id'];

$cambia = $mysqli->query(
    "UPDATE arg_controles_materiales SET activo = $estado WHERE id = ".$id
) or die(mysqli_error($mysqli));

echo ('Se cambió exitosamente.');