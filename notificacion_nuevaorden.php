<?include "connections/config.php";?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';


$html = '';
$trn_id = $_POST['trn_id'];

///echo $trn_id;
if (isset($trn_id)){
   //mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicio(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));  
   $resultado = $mysqli->query("SELECT o.trn_id, o.folio as orden, o.fecha, 
                                    	 SUM(`od`.`cantidad`) AS muestras
                                        , u.codigo as usuario, u.email
                                    FROM arg_ordenes o
                                    LEFT JOIN `arg_ordenes_detalle` od
                                    	ON o.trn_id = od.trn_id_rel
                                    LEFT JOIN arg_usuarios u
                                    	ON u.u_id = o.usuario_id
                                    WHERE 
                                    	o.trn_id = ".$trn_id."
                                    GROUP BY  orden, o.fecha, u.codigo, u.email") or die(mysqli_error());
             
  }

 $mysqli -> set_charset("utf8");
  
if ($resultado->num_rows > 0) {
    //Enviar correo
    $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $empleado_email = $datos_in['email'];
    $fecha_orden = date("Y-m-d", strtotime($datos_in['fecha']));
    $folio = $datos_in['orden'];
    $usuario = $datos_in['usuario'];
    $muestras = $datos_in['muestras'];
    //$html = date("Y-m-d", strtotime($fecha_orden));
    //$supervisor1 = 'nancy.talamantes@argonautgold.com';
    $supervisor2 = 'Lilia.Rivera@argonautgold.com';
    //$supervisor3 = 'Cecilia.Gomez@argonautgold.com';
    $supervisor4 = 'laboratoriomro@argonautgold.com>';
    $laboratorio = 'cesar.palomo@argonautgold.com';    
    $copy = 'danira.romero@argonautgold.com';           
    $geo1 = 'carlos.gandara@argonautgold.com';
    $geo2 = 'eloy.tinajera@argonautgold.com';
    $geo3 = 'Manuel.orozco@argonautgold.com';
    //$geo4 = 'Ana.robles@argonautgold.com';
    //$geo5 = 'Alan.guerrero@argonautgold.com';
    $geo6 = 'geologia.sa@argonautgold.com';
    				
    				
                    $_subject = "Nueva Orden de Trabajo.";
                    $_recipient = $empleado_email;//$empleado_email;	
                    
                    $_body = "El usuario <strong>".$usuario." </strong> ha generado una nueva orden de trabajo con folio <strong>".$folio."</strong> y fecha <strong>".$fecha_orden."</strong><br><br>";
                    $_body .= "TOTAL DE MUESTRAS:"."<br>".$muestras."<br>"."<br>";
    				$_body .= "<br>Para dar seguimiento a la orden de trabajo ingrese a:<br><br><br>";    
                    $_body .= "http://192.168.10.112/dgominedatalabs/index.php<br><br>";	
                    $_body .= "Atte: LABORATORIO QUIMICO DE ARGONAUT GOLD";

                   
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
                        //$mail->addAddress($supervisor1); 
                        $mail->addAddress($supervisor2);
                        //$mail->addAddress($supervisor3);
                        $mail->addAddress($laboratorio);
                        $mail->addAddress($geo1); 
                        $mail->addAddress($geo2); 
                        $mail->addAddress($geo3); 
                        //$mail->addAddress($geo4); 
                        //$mail->addAddress($geo5); 
                        $mail->addAddress($geo6); 
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body   = $body;
                        $mail->send();
                        echo 'Se envió correctamente el mensaje';
                    } catch (Exception $e) {
                        echo "Error al enviar mensaje: {$mail->ErrorInfo}";
                 }
}
//echo utf8_encode($html);

?>