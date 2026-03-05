<?include "connections/config.php";?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';


$html = '';
$material_id = $_POST['material_ref'];

echo $material_id;
if (isset($material_id)){
   //mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicio(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));  
   $resultado = $mysqli->query("SELECT 
                                	mr.material_id, mr.nombre, un.nombre AS unidad_mina, us.nombre AS usuario, REPLACE(mr.`file_path`, ' ', '%20'), met.nombre AS metodo, us.email, CURDATE() AS fecha
                                FROM
                                	`arg_controles_materiales` mr
                                    LEFT JOIN arg_empr_unidades un
                                    	ON un.unidad_id = mr.unidad_id
                                    LEFT JOIN arg_usuarios us
                                    	ON us.u_id = mr.u_id
                                    LEFT JOIN arg_metodos AS met
                                        ON met.metodo_id = mr.metodo_id
                                WHERE
                                	mr.material_id = ".$material_id) or die(mysqli_error());
             
  }

 $mysqli -> set_charset("utf8");
  
if ($resultado->num_rows > 0) {
    //Enviar correo
    $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $empleado_email = $datos_in['email'];
    $fecha = $datos_in['fecha'];// date("Y-m-d", strtotime($datos_in['fecha']));
    $nombre_mat = $datos_in['nombre'];
    $usuario = $datos_in['usuario'];
    $metodo = $datos_in['metodo'];
    $path = $datos_in['file_path'];
    //$html = date("Y-m-d", strtotime($fecha_orden));
    $supervisor1 = 'nancy.talamantes@argonautgold.com';
    $supervisor2 = 'Lilia.Rivera@argonautgold.com';
    $supervisor3 = 'Cecilia.Gomez@argonautgold.com';
    $laboratorio = 'cesar.palomo@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';    
    				
    				
                    $_subject = "ASIGNACION DE MATERIAL DE REFERENCIA.";
                    $_recipient = $empleado_email;//$empleado_email;	
                    
                    $_body = "El usuario <strong>".$usuario." </strong> ha creado un nuevo material de referencia <strong>".$nombre_mat."</strong> aplica para el METODO <strong>".$metodo."</strong><br><br>";
                    $_body .= "Disponible para su uso a partir del :<strong>"."<br>".$fecha."</strong><br>"."<br>";
    				$_body .= "<br>Para ver el certificado favor de ingresar a MineData-Labs:<br><br><br>";  
                      
                    $_body .= "http://192.168.10.112/dgominedatalabs/".$path."<br><br>";	
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
                        $mail->addAddress($supervisor1); 
                        $mail->addAddress($supervisor2);
                        $mail->addAddress($supervisor3);
                        $mail->addAddress($laboratorio);
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body   = $body;
                        $mail->send();
                        echo 'Se envió correctamente el mensaje';
                    } catch (Exception $e) {
                        echo "Error al enviar mensaje: {$mail->ErrorInfo}";
                 }
}
//echo >utf8_encode($html);

?>