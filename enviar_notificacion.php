<?include "connections/config.php";?>
<?php
$html = '';
//$u_id = $_SESSION['u_id'];
$trn_id = $_POST['trn_id'];

///echo $trn_id;
if (isset($trn_id)){
   //mysqli_multi_query ($mysqli, "CALL arg_prc_OrdenInicio(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));  
   $resultado = $mysqli->query("SELECT `folio_interno`, o.folio as orden, o.fecha
                                    	, SUM(`cantidad`) AS muestras
                                        , u.nombre as usuario
                                    FROM `arg_ordenes_detalle` od
                                    LEFT JOIN arg_ordenes o
                                    	ON o.trn_id = od.trn_id_rel
                                    LEFT JOIN arg_usuarios u
                                    	ON u.u_id = o.usuario_id
                                    WHERE 
                                    	o.trn_id = ".$trn_id) or die(mysqli_error());
             
                              
    //echo utf8_encode($html);
  }
  
//$mysqli -> set_charset("utf8");
 
if ($resultado->num_rows > 0) {
    //Enviar correo
    $datos_in = $resultado ->fetch_array(MYSQLI_ASSOC);
    $fecha = $datos_in['fecha'];
    $cantidad = $datos_in['muestras'];
    $usuario = $datos_in['usuario'];
    $folio = $datos_in['folio_interno'];
    $orden = $datos_in['orden'];
    //$html = 'Se inició su orden de trabajo con folio de seguimiento '.$folio_interno;
    $office_correo = 'nancy.talamantes@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';
   // $copy = 'nancy.talamantes@argonautgold.com';
    
    				require("PHPMailer_v51/class.phpmailer.php");
    				$mail = new PHPMailer();
    				$mail->From = "bitacora.arg@argonautgold.com";
    				$mail->FromName = "Argonaut Gold";
    				$mail->Subject = "Nueva de Orden";	
    				//$mail->AddBCC("".$empleado_email."");
                    $mail->AddBCC("".$office_correo."");
                    $mail->AddBCC("".$copy.""); 
            
    				$mail->ContentType = "text/html";
    				$body = "El usuario <strong>".$usuario."</strong> ha creado la orden de trabajo con folio <strong>".$folio."</strong> el día de <strong>".$fecha."</strong><br><br>";
    				$body .= "El total de muestras generadas en esta orden es de <strong>".$cantidad."</strong>"."<br>";
                    $body .= "</strong><br><br><br>Para dar seguimiento a la orden de análisis ingrese a:<br>";    
                    $body .= "http://192.168.20.22/MineData-Labs/index.php<br><br>";			
                    $body .= "Atte: Laboratorio Químico de Argonaut Gold INC";
                    $body = utf8_encode($body);
    				$mail->Body = $body;
    				$mail->Send(); 
               //echo $body;
}/*
if ($resultado->num_rows > 0) {
    $html = 'La orden de trabajo se ha generado satisfactoriamente ';
    echo ($html);
}*/
?>