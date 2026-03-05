<?php
include '\xampp\htdocs\registro\connections\config.php';

$html = '';
//$u_id = $_GET['u_id'];
//$motivo = $_GET['motivo'];
$u_id = $_POST['u_id'];
$motivo = $_POST['motivo'];
//echo $u_id;
//echo $motivo;
if (isset($u_id)){
         $resultado_tipo = $mysqli->query("SELECT tipo_induccion FROM arg_actividad WHERE act_id = ".$motivo) or die(mysqli_error());
         $tipo_ind = $resultado_tipo->fetch_assoc();
         $tipo_in = $tipo_ind['tipo_induccion'];
                  
         $u_id_curso = $mysqli->query("SELECT tipo_induccion FROM arg_usuarios_cursos WHERE u_id = ".$u_id." AND tipo_induccion = ".$tipo_in) or die(mysqli_error());
         $habilita = $u_id_curso->fetch_assoc();
         $user_habilitado = $habilita['tipo_induccion'];
}
      
if ($u_id_curso->num_rows == 0) {
    $html = 'NO';
}
else
    {
         $html = 'SI';
    }
echo $html;
?>