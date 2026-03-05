<?php
include '\xampp\htdocs\registro\connections\config.php';

//$html = '';
$u_id_p = $_POST['prov_id'];
$fecha = $_POST['fecha'];
$motivo = $_POST['motivo'];
///echo $motivo;
if (isset($u_id_p)){
    
      $vigencias_prov = $mysqli->query("SELECT (CASE tipo_id WHEN 1 THEN 'Poliza Seguro' WHEN 2 THEN 'INE' END) AS doc
                                                ,nombre, REPLACE(fecha_expira, '-', '') AS fecha 
                                          FROM arg_usuarios_documentos WHERE tipo_id IN(1,2) AND fecha_expira < '".$fecha."' AND u_id = ".$u_id_p) 
                                       or die(mysqli_error());
                                       
       $resultado_tipo = $mysqli->query("SELECT tipo_induccion FROM arg_actividad WHERE act_id = ".$motivo) or die(mysqli_error());
       $tipo_ind = $resultado_tipo->fetch_assoc();
       $tipo_in = $tipo_ind['tipo_induccion'];
       
       //echo $tipo_in;
                  
       $u_id_curso = $mysqli->query("SELECT tipo_induccion FROM arg_usuarios_cursos WHERE u_id = ".$u_id_p." AND tipo_induccion = ".$tipo_in) or die(mysqli_error());
      
       if ($vigencias_prov->num_rows > 0) {
    		$html.="<a>Documentos vencidos</a>";
        }
        else{
            if ($u_id_curso->num_rows == 0){
                $html.="<a>No cuenta con la induccion requerida</a>";
            }
            else{
                $resultado_imss = $mysqli->query("SELECT imss FROM usuarios_doc WHERE tipo_id = 1 AND u_id=".$u_id_p) or die(mysqli_error());
                $resultado_ine = $mysqli->query("SELECT imss FROM usuarios_doc WHERE tipo_id = 2 AND u_id=".$u_id_p) or die(mysqli_error());
            }
        }
  }      
        
if ($resultado_imss->num_rows > 0) {
$html.="<table class='tabla_datos' id=".$u_id_p.">";

    	while ($fila = $resultado_imss->fetch_assoc()) {
    	   $ine = $resultado_ine->fetch_assoc(); 	   
    		$html.="
                        <a >".$fila['imss'].'&nbsp &nbsp &nbsp &nbsp &nbsp'.$ine['imss']."</a>    					                 
    				  ";
        }
    	$html.="</table>";
}
echo $html;
?>