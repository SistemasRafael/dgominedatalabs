<?include "connections/config.php";?>
<?php
//$html = '';
$u_id        = $_SESSION['u_id'];
$unidad_id   = $_POST['unidad_id'];
$banco_id    = $_POST['banco_id'];
$voladura_id = $_POST['voladura_id'];
$folio_ini = $_POST['folio_ini'];
$folio_act = $_POST['folio_act'];

    $max_banco_id = $mysqli->query("SELECT banco FROM arg_bancos WHERE banco_id = ".$banco_id) or die(mysqli_error());
    $max_ban = $max_banco_id ->fetch_array(MYSQLI_ASSOC);
    $banco = $max_ban['banco'];
    
if (isset($banco_id)){
        //Validar duplicados
        $validar_dupl = $mysqli->query("SELECT COUNT(voladura_id) AS voladura 
                                        FROM 
                                            arg_bancos_voladuras bvo
                                            LEFT JOIN arg_bancos ban
                                            	ON bvo.banco_id = ban.banco_id
                                        WHERE 
                                            bvo.banco_id = ".$banco_id." AND ban.unidad_id = ".$unidad_id." AND bvo.voladura_id = ".$voladura_id) or die(mysqli_error());
        $vol_dup = $validar_dupl ->fetch_array(MYSQLI_ASSOC);   
        $vol_duplicado = $vol_dup['voladura'];
          
        // echo $validar_dupl;                                                
        if ($vol_duplicado == 0){
                            $query = "INSERT INTO arg_bancos_voladuras (banco_id, banco, voladura_id, folio_inicial, folio_actual, u_id) ".
                                     "VALUES ($banco_id, '$banco', $voladura_id, $folio_ini, $folio_act , $u_id)";
                            $mysqli->query($query) ;
                            
                            $resultado = $mysqli->query("SELECT
                                                        	 banco
                                                          FROM 
                                                            arg_bancos_voladuras
                                                          WHERE banco_id = ".$banco_id." AND voladura_id = ".$voladura_id) or die(mysqli_error());
                                                          
                                                          //echo $query;
            if ($resultado->num_rows > 0) {
                $html = 'Se registró exitosamente.';
            }
            else{
                $html = 'Hubo un error, reintente por favor.';
            }
        }
        else{
            $html = 'La voladura '.$voladura_id.' para el banco '.$banco.' ya existe, favor de validar.';
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