<script>
    function dash($unidad_id)
            {
                var unidad_id = $unidad_id
                var print_d = '<?php echo "\dashboard.php?tipo=3&unidad="?>';                
                    window.location.href = print_d+unidad_id;
            } 
</script>

<?php
include "connections/config.php";
$trn_id = $_GET['trn_id'];
$estado_id = $_GET['estado_id'];
$u_id = $_SESSION['u_id'];
$hoy = date("Y-m-d H:i:s"); 
//echo $trn_id;
//echo $estado_id;
$datos_est = $mysqli->query("SELECT estado
                                FROM arg_estados 
                                WHERE estado_id = ".$estado_id) or die(mysqli_error());
$estado_iden = $datos_est ->fetch_array(MYSQLI_ASSOC);
$estado = $estado_iden['estado'];

$query = "INSERT INTO arg_entradas_estados (trn_id, estado_id, fecha, comentario, u_id) ".
         "VALUES ($trn_id, $estado_id, '$hoy', '', $u_id)";
$mysqli->query($query) ;
//echo $query;

$datos_vis = $mysqli->query("SELECT folio,  un.nombre AS mina, us.nombre as proveedor, us.email AS proveedor_correo, ati.nombre as atiende
                                ,ati.email AS atiende_correo, o.nombre as empresa, ae.fecha_inicio, ae.unidad_id
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
$fecha = $datos['fecha_inicio'];
$atiende = $datos['atiende'];
$atiende_correo = $datos['atiende_correo'];
$proveedor_correo = $datos['proveedor_correo'];
$unidad_id = $datos['unidad_id'];

				//include("crearPdfimagen.php"); 
				require("PHPMailer_v51/class.phpmailer.php");
				$mail = new PHPMailer();
				$mail->From = "bitacora.arg@argonautgold.com";
				$mail->FromName = "Bitacora de Registro";
				$mail->Subject = "Envio desde Bitacora de Registro de Argonaut Gold INC.";			
				$mail->AddBCC("".$proveedor_correo."");
				$mail->AddBCC("".$atiende_correo."");
        
				$mail->ContentType = "text/html";
				$body = "Su solicitud con folio <strong>".$folio."</strong> y fecha ".$fecha." ha sido <strong>".$estado."</strong> por <strong>".$atiende."</strong><br>";
				$body .= "<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>";
				$body .= " <font color='red'>http://192.168.20.3:81/registro/app.php?trn_id=</font>".$trn_id."<br><br>";
                $body .= "Atte: Bitácora de Registro de Argonaut Gold INC";
				$mail->Body = $body;
				$mail->Send(); 
    echo "<script> dash(".$unidad_id.");</script>"; 
   
              
?>
