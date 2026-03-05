<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$unidad_id = $_POST['unidad_id'];
$banco = $_POST['banco'];
$nombre = $_POST['nombre'];;

    $max_banco_id = $mysqli->query("SELECT MAX(banco_id) AS banco_id FROM arg_bancos") or die(mysqli_error());
    $max_ban = $max_banco_id ->fetch_array(MYSQLI_ASSOC);
    $banco_id = $max_ban['banco_id'];
    $banco_id = $banco_id+1;
    
if (isset($u_id)){
        //Validar duplicados
        $validar_dupl = $mysqli->query("SELECT COUNT(banco) AS ban 
                                        FROM 
                                            arg_bancos 
                                        WHERE 
                                            banco = '".$banco."' AND unidad_id = ".$unidad_id) or die(mysqli_error());
        $banco_dup = $validar_dupl ->fetch_array(MYSQLI_ASSOC);   
        $banco_duplicado = $banco_dup['ban'];
          
        // echo $validar_dupl;                                                
        if ($banco_duplicado == 0){
                            $query = "INSERT INTO arg_bancos (unidad_id, banco_id, banco, nombre, u_id) ".
                                     "VALUES ($unidad_id, $banco_id, '$banco', '$nombre', $u_id)";
                            $mysqli->query($query) ;
                            
                            $resultado = $mysqli->query("SELECT
                                                        	 banco
                                                          FROM 
                                                            arg_bancos 
                                                          WHERE banco = '".$banco."'") or die(mysqli_error());
                                                          
                                                          //echo $query;
            if ($resultado->num_rows > 0) {
                $html = 'Se registro exitosamente.';
            }
            else{
                $html = 'Hubo un error, reintente por favor.';
            }
        }
        else{
            $html = 'El banco ya existe, favor de validar.';
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