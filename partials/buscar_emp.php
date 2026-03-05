<?php
include '\xampp\htdocs\registro\connections\config.php';

$html = '';
$key = $_POST['key'];

$top = $mysqli->query("SELECT org_id, nombre FROM arg_organizaciones WHERE nombre LIKE '%".strip_tags($key)."%'") or die(mysqli_error());
          
if ($top->num_rows > 0) {
  // echo 'entro';
   while ($row = $top->fetch_assoc()) {
        $html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'" id="id'.$row['org_id'].'">'.utf8_encode($row['nombre']).'</a></div>';
    }
}
echo $html;
?>