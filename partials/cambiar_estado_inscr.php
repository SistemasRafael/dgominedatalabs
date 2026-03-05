<script>
    function calificaciones(u_id_califica, trn_id)
            {
                var trn_id = trn_id;
                var uid_califica = u_id_califica+'&trn_id='+trn_id;                
                var print_d = '<?php echo "\calificaciones.php?uid_califica="?>';                                
                    window.location.href = print_d+uid_califica;
            } 
    function regresa()
            {
                var print_d = '<?php echo "\inscritos.php?cal_id=0"?>';                
                    window.location.href = print_d;
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

if ($estado_id == 999){
    $datos_uid = $mysqli->query("SELECT
                                        u_id
                                     FROM
                                        arg_calendario_usuarios
                                     WHERE trn_id = ".$trn_id) or die(mysqli_error());
    $u_id_cal = $datos_uid ->fetch_array(MYSQLI_ASSOC);
    $u_id_califica = $u_id_cal['u_id'];
    echo "<script> calificaciones($u_id_califica, $trn_id);</script>"; 
}
else{
    if ($estado_id == 6){
        mysqli_multi_query ($mysqli, "CALL arg_curso_finalizar(".$trn_id.", ".$u_id.")") OR DIE (mysqli_error($mysqli));
    
        $query = "UPDATE arg_calendario_usuarios SET estado_id = ".$estado_id." WHERE trn_id = ".$trn_id;
        $mysqli->query($query);
        
        $datos_uid = $mysqli->query("SELECT
                                        u_id
                                     FROM
                                        arg_calendario_usuarios
                                     WHERE trn_id = ".$trn_id) or die(mysqli_error());
        $u_id_cal = $datos_uid ->fetch_array(MYSQLI_ASSOC);
        $u_id_califica = $u_id_cal['u_id'];
        
        echo "<script> calificaciones($u_id_califica, $trn_id);</script>"; 
    }
    //echo $query;
    //Autoriza o rechaza
    if ($estado_id == 2 or $estado_id == 3){        
        $query = "UPDATE arg_calendario_usuarios SET estado_id = ".$estado_id." WHERE trn_id = ".$trn_id;
        $mysqli->query($query) ;
        $datos_inscripcion = $mysqli->query("SELECT
                                            	 u.nombre
                                                ,cc.fecha
                                                ,u.email
                                                ,et.estado
                                            FROM arg_calendario_usuarios cu
                                            LEFT JOIN arg_calendario_cursos cc
                                            	ON cc.cal_id = cu.cal_id
                                            LEFT JOIN arg_usuarios u
                                            	ON u.u_id = cu.u_id
                                            LEFT JOIN arg_estados et
                                            	ON et.estado_id = cu.estado_id
                                            WHERE trn_id = ".$trn_id) or die(mysqli_error());
        $datos_i = $datos_inscripcion ->fetch_array(MYSQLI_ASSOC);
        $correo_user = $datos_i['email'];
        $nombre_user = $datos_i['nombre'];
        $fecha_cur = $datos_i['fecha'];
        $estado_inscripcion = $datos_i['estado'];
    
    				include("crearPdfimagen.php"); 
    				require("PHPMailer_v51/class.phpmailer.php");
    				$mail = new PHPMailer();
    				$mail->From = "danira.romero@argonautgold.com";
    				$mail->FromName = "Bitacora de Registro";
    				$mail->Subject = "Envio desde Bitacora de Registro de Argonaut Gold INC.";			
    				$mail->AddBCC("".$correo_user."");
            
    				$mail->ContentType = "text/html";
    				$body = "Su registro para el curso de Induccion Completa con fecha <strong>".$fecha_cur."</strong> ha sido <strong>".$estado_inscripcion."</strong><br><br>";				
                    $body .= "Atte: Bitácora de Registro de Argonaut Gold INC";
    				$mail->Body = $body;
    				$mail->Send(); 
    }
        echo "<script> regresa();</script>"; 
   }
              
?>
