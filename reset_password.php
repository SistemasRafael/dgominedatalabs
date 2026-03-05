<?php include "connections/config.php";  
?>

<script>
 function redireccion($email)
            {
                 var email = $email;
                 
                 var print_d = '<?php echo "\confirma_pass.php?email="?>'+email;                
                 window.location.href = print_d;
            }
</script>
<?
function enviar_reset($codigo_res, $email){     
				require("PHPMailer_v51/class.phpmailer.php");
				$mail = new PHPMailer();
				$mail->From = "bitacora.arg@argonautgold.com";
				$mail->FromName = "Bitacora de Registro";
				$mail->Subject = "Argonaut Recuperacion";			
				$mail->AddBCC("".$email."");
        
				$mail->ContentType = "text/html";
				$body = "Hemos recibido una solicitud de recuperación de contraseña para el usuario <strong>".$email."</strong><br>";
				$body .= "<br><br>";
				$body .= " <font color='red'>".$codigo_res."</font><br><br>";
                $body .= "Ingresa el código para reestablecer la contraseña";
                $body .= ", si no reconoces la solicitud, alguien probablemente escribió erroneamente tu correo. Puedes ignorar este correo.<br><br>";
                $body .= "Atte: Bitácora de Registro de Argonaut Gold INC";
				$mail->Body = $body;
				//$mail->AddAttachment("imgYaqui/pdf/weatherlink.pdf", "Weatherlink_Yaqui.pdf");
				$mail->Send(); 
                echo "<script> redireccion('$email');</script>";
    }

$codigo_res = rand();
$email = $_GET['email'];
//echo $email;
//echo $codigo_res;

$query = "UPDATE arg_usuarios SET codigo_reset = ".$codigo_res." WHERE email = '".$email."'";
$mysqli->query($query) ;

if ($codigo_res <> ''){
    echo enviar_reset($codigo_res, $email);
}


          
