<?include "connections/config.php";?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';

$html = '';
$u_id = $_SESSION['u_id'];
$trn_id = $_POST['trn_id'];

if (isset($trn_id)){
    
   $datos_orden = $mysqli->query("SELECT
                                         ord.tipo
                                  FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel
                                  WHERE det.trn_id = ".$trn_id
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
    
   if($orden_encabezado['tipo'] == 5){ 
        mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicioSobr(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));
   }
   else{
        mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicio(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli)); 
   } 
   $resultado = $mysqli->query("SELECT
                                    ob.trn_id_rel, od.folio_interno, od.cantidad, od.folio_inicial, od.folio_final, ob.fecha, u.email
                                FROM 
                                    arg_ordenes_bitacora ob
                                    LEFT JOIN arg_ordenes_detalle od
                                        ON ob.trn_id_rel = od.trn_id
                                     LEFT JOIN arg_ordenes o
                                    	ON o.trn_id = od.trn_id_rel
                                    LEFT JOIN arg_usuarios u
                                    	ON u.u_id = o.usuario_id
                                WHERE od.trn_id = ".$trn_id." AND ob.fase_id = 1 LIMIT 1 ") or die(mysqli_error());
             
                               if ($resultado->num_rows > 0) {
                                    $html = 'Se inició el batch exitosamente.';
                               }
                               else{
                                    $html = 'Hubo un error, reintente por favor.';
                               }
    echo utf8_encode($html);
  }
  
$mysqli -> set_charset("utf8");
 
if ($resultado->num_rows > 0) {
    //Enviar correo
    $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $fecha          = date("Y-m-d", strtotime($datos_in['fecha']));
    $cantidad       = $datos_in['cantidad'];
    $folio_interno  = $datos_in['folio_interno'];
    $folio_inicial  = $datos_in['folio_inicial'];
    $folio_final    = $datos_in['folio_final'];
    $empleado_email = $datos_in['email'];
    $copy = 'danira.romero@argonautgold.com';
    
    //$fecha_orden = date("Y-m-d", strtotime($datos_in['fecha']));
    //$folio = $datos_in['orden'];
    //$usuario = $datos_in['usuario'];
    //$muestras = $datos_in['muestras'];
    //$html = date("Y-m-d", strtotime($fecha_orden));
    $supervisor1 = 'nancy.talamantes@argonautgold.com';
    $supervisor2 = 'Lilia.Rivera@argonautgold.com';
    $supervisor3 = 'Cecilia.Gomez@argonautgold.com';
    $laboratorio = 'cesar.palomo@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';    
    				
    				
                    $_subject = "Inicio de Orden de Trabajo.";
                    $_recipient = $empleado_email;//$empleado_email;	
                    
                    $body = "Se ha iniciado la orden de trabajo <strong>".$folio_interno."</strong> con fecha <strong>".$fecha."</strong><br><br>";
    				$body .= "Los cantidad de muestras recibidas en esta orden es de <strong>".$cantidad."</strong> con folio inicial del <strong>"."<br>".$folio_inicial." al folio ".$folio_final;
                    $body .= "</strong><br><br><br>Para realizar una nueva solicitud de análisis ingrese a:<br>";    
                    $body .= "http://192.168.10.112/dgominedatalabs/index.php<br><br>";			
                    $body .= "Atte: LABORATORIO QUIMICO DE ARGONAUT GOLD";

                   
                   // $body = $_body;
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
                        //echo 'Se envió correctamente el mensaje';
                    } catch (Exception $e) {
                        echo "Error al enviar mensaje: {$mail->ErrorInfo}";
                 }

}

?>