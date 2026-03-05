<?php
include "connections/config.php";
$trn_id = $_GET['trn_id'];
//echo $trn_id;
$datos_vis = $mysqli->query("SELECT folio,  un.nombre AS mina, us.nombre as proveedor, us.email AS proveedor_correo, ati.nombre as atiende
                                ,ati.email AS atiende_correo, o.nombre as empresa, ae.fecha_inicio
                                FROM arg_entradas ae
                                LEFT JOIN arg_empr_unidades un
                                    ON un.unidad_id = ae.unidad_id
                                LEFT JOIN arg_usuarios us
                                    ON us.u_id = ae.usuario_id
                                LEFT JOIN arg_usuarios ati
                                    ON ae.usuario_id_atie = ati.u_id                    
                                LEFT JOIN arg_organizaciones o
                                    ON o.org_id = us.org_id
                                WHERE ae.trn_id = ".$trn_id) or die(mysqli_error());
$datos = $datos_vis ->fetch_array(MYSQLI_ASSOC);
$folio = $datos['folio'];
$proveedor = $datos['proveedor'];
$empresa = $datos['empresa'];
$fecha = $datos['fecha_inicio'];
$atiende_correo = $datos['atiende_correo'];
$proveedor_correo = $datos['proveedor_correo'];

				include("crearPdfimagen.php"); 
				require("PHPMailer_v51/class.phpmailer.php");
				$mail = new PHPMailer();
				$mail->From = "bitacora.arg@argonautgold.com";
				$mail->FromName = "Bitacora de Registro";
				$mail->Subject = "Envio desde Bitacora de Registro";			
				$mail->AddBCC("".$atiende_correo."");
				$mail->AddBCC("".$proveedor_correo."");
        
				$mail->ContentType = "text/html";
				$body = "Has recibido una solicitud de visita con folio <strong>".$folio."</strong> del proveedor <strong>".$proveedor."</strong> empresa <strong>".$empresa."</strong> con fecha <strong>".$fecha."</strong><br>";
				$body .= "<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>";
				$body .= " <font color='red'>http://192.168.20.3:81/registro/app.php?trn_id=</font>".$trn_id."<br><br>";
                $body .= "Atte: Bitácora de Registro de Argonaut Gold INC";
				$mail->Body = $body;
				//$mail->AddAttachment("imgYaqui/pdf/weatherlink.pdf", "Weatherlink_Yaqui.pdf");
				$mail->Send(); 
				/*echo("<p><font face=\"verdana\" size=\"3\" color=\"FFFFFF\">Mensajes html enviado correctamente!, ultimo correo enviado a las:</font></p>");
				echo "<font face=\"verdana\" size=\"3\" color=\"FFFFFF\">";
				echo date('H').":";
				echo date('i').":";
				echo date('s'); 
				echo "</font>";*/
    //echo "<script> redireccion($trn_id);</script>"; 
    //header("location: http://192.168.20.3:81/registro/app.php?trn_id=53");
    //exit;
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = '/app.php?trn_id='.$trn_id;
    $ruta = $host.$uri.$extra;
    //echo $ruta;
    header("Location: http://$ruta");
    exit;         
              
?>
