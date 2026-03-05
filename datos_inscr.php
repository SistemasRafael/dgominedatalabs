<script>
    function regresa(satisf)
            {
            satisf = satisf;
            
                if (satisf == 1){
                    var print_d = '<?php echo "\calendario.php?motivo=4"?>';                
                    window.location.href = print_d;
                }
                else{
                    var print_d = '<?php echo "\calendario.php?motivo=5"?>';                
                    window.location.href = print_d;
                }
            } 
</script>

<?php
include "connections/config.php";

$html = '';
$u_id = $_POST['u_id'];
$cal_id = $_POST['cal_id'];
//echo $veh_id;
    $max_trn_id = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_calendario_usuarios") or die(mysqli_error());
    $max_trn = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
    $trn_id = $max_trn['trn_id'];
    $trn_id = $trn_id +1;
    $hoy = date("Ymd"); 
if (isset($u_id)){
         $query = "INSERT INTO arg_calendario_usuarios (trn_id, u_id, cal_id, tipo_induccion, fecha) ".
         "VALUES ($trn_id, $u_id, $cal_id, 1, '$hoy')";
         $mysqli->query($query) ;
         
         $resultado = $mysqli->query("SELECT * FROM arg_calendario_usuarios WHERE trn_id=".$trn_id) or die(mysqli_error());
  }      
        
if ($resultado->num_rows > 0) {
    $html.="Se ha inscrito correctamente";
    
    //Enviar correo
    $datos_ins = $mysqli->query("SELECT org.nombre AS empresa, us.nombre, cc.fecha
                                    FROM arg_calendario_usuarios cu                                   
                                    LEFT JOIN arg_usuarios us
                                        ON cu.u_id = us.u_id
                                    LEFT JOIN arg_calendario_cursos cc
                                        ON cc.cal_id = cu.cal_id
                                    LEFT JOIN arg_organizaciones org
                                    	ON org.org_id = us.org_id
                                    WHERE cu.trn_id = ".$trn_id) or die(mysqli_error());
    $datos_in = $datos_ins ->fetch_array(MYSQLI_ASSOC);
    $proveedor_sol = $datos_in['nombre'];
    $empresa_sol = $datos_in['empresa'];
    $fecha_cur = $datos_in['fecha'];
    $seguridad = 'danira.romero@argonautgold.com';
    
    				include("crearPdfimagen.php"); 
    				require("PHPMailer_v51/class.phpmailer.php");
    				$mail = new PHPMailer();
    				$mail->From = "danira.romero@argonautgold.com";
    				$mail->FromName = "Bitacora de Registro";
    				$mail->Subject = "Envio desde Bitacora de Registro de Argonaut Gold INC.";			
    				$mail->AddBCC("".$seguridad."");
            
    				$mail->ContentType = "text/html";
    				$body = "El proveedo <strong>".$proveedor_sol."</strong> de la empresa <strong>".$empresa_sol."</strong> ha solicitado su curso de induccion con fecha <strong>".$fecha_cur."</strong><br>";
    				$body .= "<br>Para autorizar la induccion favor de ingresar a la bitacora de visitas:<br><br>";    
                    $body .= "http://192.168.20.3:81/registro/index.php <br><br>";			
                    $body .= "Atte: Bitacora de Registro de Argonaut Gold INC";
    				$mail->Body = $body;
    				//$mail->AddAttachment("imgYaqui/pdf/weatherlink.pdf", "Weatherlink_Yaqui.pdf");
    				$mail->Send(); 
    echo "<script> regresa(1);</script>";
}else{
    echo "<script> regresa(0);</script>";
}

echo $html;
  
?>