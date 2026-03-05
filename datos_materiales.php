<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$metodo_id = $_POST['metodo_id'];
$material_id_selecc = $_POST['material_id_selecc'];
$cantidad = $_POST['cantidad_des'];

  /*  $max_metodo_id = $mysqli->query("SELECT MAX(material_id) AS material_id FROM arg_controles_materiales") or die(mysqli_error());
    $max_meto = $max_metodo_id ->fetch_array(MYSQLI_ASSOC);
    $material_id = $max_meto['material_id'];
    $material_id = $material_id+1;*/
    
    $datos_material_ref = $mysqli->query("SELECT ley, desviacion_estandar FROM arg_materiales_referencia WHERE material_id = ".$material_id_selecc) or die(mysqli_error());
    $datos_ref = $datos_material_ref ->fetch_array(MYSQLI_ASSOC);
    $ley = $datos_ref['ley'];
    $desv_esta = $datos_ref['desviacion_estandar'];
    
if (isset($u_id)){
    $maximo = ($ley+($desv_esta*$cantidad));
    $minimo = ($ley-($desv_esta*$cantidad));
    $query = "INSERT INTO arg_controles_materiales (material_id, nombre, control_id, valor_ley, desv_esta, cantidad_desviacion, maximo, minimo, metodo_id, u_id) ".
             "VALUES ($material_id, '', 2, $ley, $desv_esta, $cantidad, $maximo, $minimo, $metodo_id, $u_id)";
             $mysqli->query($query) ;
                            //echo $query;
                            //die();
             $resultado = $mysqli->query("SELECT
                                            material_id
                                          FROM 
                                            arg_controles_materiales 
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
 
/*if ($resultado->num_rows > 0) {
    //Enviar correo
    $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $empleado_email = $datos_in['EmailAddress'];
    $fecha_reserva = $fecha_inicial;
    $fecha_reserva_final = $datos_in['fecha_inicial'];
    $ubicacion = $datos_in['nombre'];
    $username = $datos_in['username'];
    $html = 'Se realizó su reservación del día '.date("Y-m-d", strtotime($fecha_reserva)).' al '.date("Y-m-d", strtotime($fecha_reserva_final));
    //$office_correo = 'asseneth.soto@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';
    
    				require("PHPMailer_v51/class.phpmailer.php");
    				$mail = new PHPMailer();
    				$mail->From = "bitacora.arg@argonautgold.com";
    				$mail->FromName = "Argonaut Gold";
    				$mail->Subject = "Reservaciones de Argonaut Gold INC.";	
    				$mail->AddBCC("".$empleado_email."");
                    //$mail->AddBCC("".$office_correo."");
                    $mail->AddBCC("".$copy.""); 
            
    				$mail->ContentType = "text/html";
    				$body = "Usted ha reservado con el usuario ".$username." la ubicación <strong>".$ubicacion."</strong> desde el día <strong>".$fecha_reserva." </strong> hasta el día  <strong>".date("Y-m-d", strtotime($fecha_reserva_final))."</strong><br><br>";
    				$body .= "La ubicación contiene las siguientes herramientas:"."<br>".$herramienta;
                    $body .= "<br>Para realizar una nueva reservación ingrese a:<br>";    
                    $body .= "http://192.168.20.22/intranet-spa/calendario_reservas.php<br><br>";			
                    $body .= "Atte: Argonaut Gold INC";
                    $body = utf8_encode($body);
    				$mail->Body = $body;
    				$mail->Send(); 
}*/
echo utf8_encode($html);

?>