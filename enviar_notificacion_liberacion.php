<?include "connections/config.php";?>
<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';

$html = '';
$u_id = $_SESSION['u_id'];
$trn_id      = $_POST['trn_id_l'];
$metodo_id_l = $_POST['metodo_id_l'];
$preeli      = $_POST['preel'];

//echo $trn_id.$metodo_id_l.$preeli;
if (isset($trn_id)){
    
    if ($preeli == 1){

           $enviada = $mysqli->query("SELECT COUNT(*) AS enviada
                                      FROM `arg_ordenes_bitacora_notificaciones` n                                  
                                           WHERE 
                                                n.etapa_id = 11
                                                AND n.metodo_id = ".$metodo_id_l."  
                                            	AND n.trn_id_rel = ".$trn_id) or die(mysqli_error());
           $existe_env = $enviada ->fetch_array(MYSQLI_ASSOC);
           $envio = $existe_env['enviada'];
           
           //echo $envio;
           
           if ($envio == 0) {
                $max_trn_id = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_bitacora_notificaciones") or die(mysqli_error());
                                         $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                         $trn_id_max = $ma_trn_id['trn_id'];
                                         $trn_id_max = $trn_id_max + 1;
                                         
                $resultado = $mysqli->query("SELECT 
                                                    `folio_interno`, o.folio as orden, o.fecha
                                                    ,me.nombre AS metodo, fase_id, etapa_id
                                            	    ,SUM(`cantidad`) AS muestras
                                                    ,u.email as usuario
                                            FROM `arg_ordenes_detalle` od
                                            LEFT JOIN arg_ordenes o
                                            	ON o.trn_id = od.trn_id_rel
                                            LEFT JOIN arg_ordenes_metodos m
                                            	ON m.trn_id_rel = od.trn_id
                                            LEFT JOIN arg_ordenes_bitacora_detalle bt
                                            	ON bt.trn_id_rel = od.trn_id
                                                AND etapa_id = 11
                                                AND bt.metodo_id = m.metodo_id
                                            LEFT JOIN arg_metodos me
                                            	ON m.metodo_id = me.metodo_id
                                            LEFT JOIN arg_usuarios u
                                            	ON u.u_id = o.usuario_id                                                             
                                           WHERE 
                                            	od.trn_id = ".$trn_id."
                                                AND m.metodo_id = ".$metodo_id_l."
                                           GROUP BY od.trn_id, o.folio, o.fecha, fase_id, etapa_id, u.email") or die(mysqli_error());
                   $datos = $resultado ->fetch_array(MYSQLI_ASSOC);
                                         $fase_ord = $datos['fase_id'];                      
                                         $etapa_ord = $datos['etapa_id'];
                                                
              $query = "INSERT INTO arg_ordenes_bitacora_notificaciones (trn_id, trn_id_rel, metodo_id, fase_id, etapa_id, u_id)".
                       "VALUES ($trn_id_max, $trn_id, $metodo_id_l, $fase_ord, $etapa_ord, $u_id)";
              $mysqli->query($query);
              //echo $query;
    
       if ($resultado->num_rows > 0) {
            //Enviar correo
           // $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
            $fecha = date("Y-m-d", strtotime($datos['fecha']));
            $cantidad = $datos['muestras'];
            $metodo = $datos['metodo'];
            $folio = $datos['folio_interno'];
            $orden = $datos['orden'];
            $cliente = $datos['usuario'];
            //$html = 'Se inició su orden de trabajo con folio de seguimiento '.$folio_interno;
            //$supervisor1 = 'nancy.talamantes@argonautgold.com';
            //$supervisor2 = 'cecilia.gomez@argonautgold.com';
            $supervisor3 = 'lilia.rivera@argonautgold.com';
            $supervisor4 = 'laboratoriomro@argonautgold.com';
            $supervisor5 = 'cesar.palomo@argonautgold.com';
            $geo1 = 'carlos.gandara@argonautgold.com';
            $geo2 = 'eloy.tinajera@argonautgold.com';
            $geo3 = 'Manuel.orozco@argonautgold.com';
            //$geo4 = 'Ana.robles@argonautgold.com';
            //$geo5 = 'Alan.guerrero@argonautgold.com';
            $geo6 = 'geologia.sa@argonautgold.com';            
            $copy = 'danira.romero@argonautgold.com';
            
	                $_subject = "Liberación de Batch de Trabajo: ".$folio;
                    $_recipient = $cliente;//$empleado_email;	
                    
                    $_body = "Se ha liberado el batch de trabajo con folio <strong>".$folio."</strong> para el método <strong>".$metodo."</strong> solicitada la fecha <strong>".$fecha."</strong><br><br>";
                    $_body .= "TOTAL DE MUESTRAS:"."<br><strong>".$cantidad."</strong><br>"."<br>";
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
                        //$mail->addAddress($supervisor2);
                        $mail->addAddress($supervisor3);                        
                        $mail->addAddress($supervisor4); 
                        $mail->addAddress($supervisor5);
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
    }
    }
    else{
        
            $enviada = $mysqli->query("SELECT COUNT(*) AS enviada
                                      FROM `arg_ordenes_bitacora_notificaciones` n                                  
                                           WHERE 
                                                n.etapa_id = 12
                                                AND n.metodo_id = ".$metodo_id_l."  
                                            	AND n.trn_id_rel = ".$trn_id) or die(mysqli_error());
           $existe_env = $enviada ->fetch_array(MYSQLI_ASSOC);
           $envio = $existe_env['enviada'];
           
           if ($envio == 0) {
                $max_trn_id = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_bitacora_notificaciones") or die(mysqli_error());
                                         $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                         $trn_id_max = $ma_trn_id['trn_id'];
                                         $trn_id_max = $trn_id_max + 1;
                                         
                $resultado = $mysqli->query("SELECT 
                                                    `folio_interno`, o.folio as orden, o.fecha
                                                    ,me.nombre AS metodo, fase_id, etapa_id
                                            	    ,SUM(`cantidad`) AS muestras
                                                    ,u.email as usuario
                                            FROM `arg_ordenes_detalle` od
                                            LEFT JOIN arg_ordenes o
                                            	ON o.trn_id = od.trn_id_rel
                                            LEFT JOIN arg_ordenes_metodos m
                                            	ON m.trn_id_rel = od.trn_id
                                            LEFT JOIN arg_ordenes_bitacora_detalle bt
                                            	ON bt.trn_id_rel = od.trn_id
                                                AND etapa_id = 12
                                                AND bt.metodo_id = m.metodo_id
                                            LEFT JOIN arg_metodos me
                                            	ON m.metodo_id = me.metodo_id
                                            LEFT JOIN arg_usuarios u
                                            	ON u.u_id = o.usuario_id                                                             
                                           WHERE 
                                            	od.trn_id = ".$trn_id."
                                                AND m.metodo_id = ".$metodo_id_l."
                                           GROUP BY od.trn_id, o.folio, o.fecha, fase_id, etapa_id, u.email") or die(mysqli_error());
                   $datos = $resultado ->fetch_array(MYSQLI_ASSOC);
                                         $fase_ord = $datos['fase_id'];                      
                                         $etapa_ord = $datos['etapa_id'];
                                                
              $query = "INSERT INTO arg_ordenes_bitacora_notificaciones (trn_id, trn_id_rel, metodo_id, fase_id, etapa_id, u_id)".
                       "VALUES ($trn_id_max, $trn_id, $metodo_id_l, $fase_ord, $etapa_ord, $u_id)";
              $mysqli->query($query);
              //echo $query;
    
       if ($resultado->num_rows > 0) {
            //Enviar correo
           // $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
            $fecha = date("Y-m-d", strtotime($datos['fecha']));
            $cantidad = $datos['muestras'];
            $metodo = $datos['metodo'];
            $folio = $datos['folio_interno'];
            $orden = $datos['orden'];
            $cliente = $datos['usuario'];
            //$html = 'Se inició su orden de trabajo con folio de seguimiento '.$folio_interno;
            //$supervisor1 = 'nancy.talamantes@argonautgold.com';
            //$supervisor2 = 'cecilia.gomez@argonautgold.com';
            $supervisor3 = 'lilia.rivera@argonautgold.com';
            $supervisor4 = 'laboratoriomro@argonautgold.com';
            $supervisor5 = 'cesar.palomo@argonautgold.com';
            $geo1 = 'carlos.gandara@argonautgold.com';
            $geo2 = 'eloy.tinajera@argonautgold.com';
            $geo3 = 'Manuel.orozco@argonautgold.com';
            //$geo4 = 'Ana.robles@argonautgold.com';
            //$geo5 = 'Alan.guerrero@argonautgold.com';
            $geo6 = 'geologia.sa@argonautgold.com';
            
            $copy = 'danira.romero@argonautgold.com';
            
	                $_subject = "Liberación de Batch de Trabajo: ".$folio;
                    $_recipient = $cliente;//$empleado_email;	
                    
                    $_body = "Se ha liberado el batch de trabajo con folio <strong>".$folio."</strong> para el método <strong>".$metodo."</strong> solicitada la fecha <strong>".$fecha."</strong><br><br>";
                    $_body .= "TOTAL DE MUESTRAS:"."<br><strong>".$cantidad."</strong><br>"."<br>";
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
                        //$mail->addAddress($supervisor2);
                        $mail->addAddress($supervisor3);                        
                        $mail->addAddress($supervisor4); 
                        $mail->addAddress($supervisor5);
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
    }
        
    }
 }
     
?>