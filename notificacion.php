<?include "connections/config.php";?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';


//$html = '';
$herramienta = 'Llega';
$u_id = $_POST['u_id'];
/*$fecha = $_POST['fecha'];
$tipo_id = $_POST['tipo_id'];
$ubic_id = $_POST['ubic_id'];*/

    /*$max_trn_id = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM reservas_ubicaciones") or die(mysqli_error());
    $max_trn = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
    $trn_id = $max_trn['trn_id'];
    $trn_id = $trn_id +1;
    $hoy = date("Ymd");
    //echo $trn_id;
    
    $level_us = $mysqli->query("SELECT level FROM users where UserID = ".$u_id) or die(mysqli_error());
    $level_use = $level_us ->fetch_array(MYSQLI_ASSOC);
    $level_user = $level_use['level'];
      
if (isset($u_id)){
        $total_trn_id = $mysqli->query("SELECT COUNT(trn_id) AS total_res FROM reservas_ubicaciones
                                      WHERE u_id = ".$u_id." AND fecha_inicial >= DATE_FORMAT(NOW(), '%Y%m%d') AND activo = 1") or die(mysqli_error());
        $total_reserv = $total_trn_id ->fetch_array(MYSQLI_ASSOC);
        $total_res = $total_reserv['total_res'];
        
        if($total_res >= 3 and $level_user <> 3)
        {
            $html = 'Usted ha superado el n�mero m�ximo de reservas.';
        }
        else{
             $query = "INSERT INTO reservas_ubicaciones (trn_id, tipo_id, ubic_id, fecha, fecha_inicial, fecha_final, hora_inicial, hora_final, u_id) ".
             "VALUES ($trn_id, $tipo_id, $ubic_id, '$hoy', '$fecha', '$fecha', 8, 5, $u_id)";
             $mysqli->query($query) ;
             
             $resultado = $mysqli->query("SELECT
                                        	 ru.fecha_inicial
                                            ,ub.nombre
                                            ,us.EmailAddress
                                            ,us.username
                                        FROM reservas_ubicaciones ru
                                        LEFT JOIN users us
                                        	ON us.UserID = ru.u_id
                                        LEFT JOIN ubicaciones ub
                                        	ON ub.tipo_id = ru.tipo_id
                                            AND ub.ubi_id = ru.ubic_id
                                        WHERE trn_id = ".$trn_id) or die(mysqli_error());
            
            $herr = $mysqli->query("SELECT herramienta, marca FROM ubicaciones_herr WHERE tipo_id = ".$tipo_id." AND ubi_id = ".$ubic_id) or die(mysqli_error());    
                 if ($herr->num_rows > 0) {
                    while ($herramientas = $herr->fetch_assoc()) {                        
                        $herramienta .= $herramientas['herramienta'].' '.$herramientas['marca']."<br>";
                    }
                 }
        }
  } 


 $mysqli -> set_charset("utf8");
  
if ($resultado->num_rows > 0) { */
    //Enviar correo
    /*$datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $empleado_email = $datos_in['EmailAddress'];
    $fecha_reserva = $datos_in['fecha_inicial'];
    $ubicacion = $datos_in['nombre'];
    $username = $datos_in['username'];*/
    $html = date("Y-m-d", strtotime($fecha_reserva));
    //$office_correo = 'asseneth.soto@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';    
    				
    				
                    $_subject = "Reservaciones de Argonaut Gold INC.";
                    $_recipient = $copy;//$empleado_email;	

            
    				/*$_body = "Usted ha reservado con el usuario ".$username." la ubicacion <strong>".$ubicacion."</strong> para el dia <strong>".$fecha_reserva."</strong><br><br>";
                    $_body .= "La ubicacion contiene las siguientes herramientas:"."<br>".$herramienta;
    				$_body .= "<br>Para realizar una nueva reservacion ingrese a:<br>";    
                    $_body .= "http://192.168.20.22/intranet-spa/calendario_reservas.php<br><br>";	*/		
                    $_body .= "Atte: Argonaut Gold INC";


                    $body = $_body;
                    $recipient = $_recipient;
                    $subject = $_subject;
                    $mail = new PHPMailer(true);
                    try {
                        $mail->SMTPDebug = 0;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'bitacora.arg@argonautgold.com';                     //SMTP username
                        $mail->Password   = 'Axioma$3112$';                               //SMTP password
                        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                        $mail->setFrom('bitacora.arg@argonautgold.com', 'Argonaut Gold');
                        $mail->addAddress($recipient);     //Add a recipient
                        $mail->addAddress($copy);    
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body   = $body;
                        $mail->send();
                        echo 'Se envió correctamente el mensaje';
                    } catch (Exception $e) {
                        echo "Error al enviar mensaje: {$mail->ErrorInfo}";
                 //   }
}
echo utf8_encode($html);

?>