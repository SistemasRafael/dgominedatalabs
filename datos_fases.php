<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$nombre = $_POST['nombre'];

    $max_fase_id = $mysqli->query("SELECT MAX(fase_id) AS fase_id FROM arg_fases") or die(mysqli_error());
    $max_fas = $max_fase_id ->fetch_array(MYSQLI_ASSOC);
    $fase_id = $max_fas['fase_id'];
    $fase_id = $fase_id+1;
    
    $duplicado = $mysqli->query("SELECT nombre FROM arg_fases WHERE nombre = '".$nombre."'") or die(mysqli_error());
    $duplicado_nom = $duplicado ->fetch_array(MYSQLI_ASSOC);
    $duplicado_nombre = $duplicado_nom['nombre'];
    
if (isset($u_id)){
    if ($duplicado_nombre == $nombre){
        $html = 'Error: La fase ya existe, favor de validar.';
    }
    else{
        $query = "INSERT INTO arg_fases (fase_id, nombre, u_id) ".
                 "VALUES ($fase_id, '$nombre', $u_id)";
                 $mysqli->query($query);
                               //echo $query;
                                //die();
                 $resultado = $mysqli->query("SELECT
                                                fase_id
                                              FROM 
                                                arg_fases
                                              WHERE fase_id = ".$fase_id) or die(mysqli_error());
                 //echo $query;
        if ($resultado->num_rows > 0) {
            $html = 'Se registro exitosamente.';
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
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