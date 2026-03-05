<?php
include "connections/config.php";
$hoy = date("Y-m-d"); 
//echo $hoy;
$datos_atiende = $mysqli->query("SELECT DISTINCT usu.email AS atiende_correo, usu.nombre 
                            FROM
                            	arg_entradas_estados ene
                                LEFT JOIN arg_entradas ent
                                	ON ene.trn_id = ent.trn_id
                                LEFT JOIN arg_usuarios usu
                                	ON usu.u_id = ent.usuario_id_atie
                            WHERE ene.estado_id = 1 AND ent.fecha_inicio >= '".$hoy."'")  or die(mysqli_error());


require("PHPMailer_v51/class.phpmailer.php");
$mail = new PHPMailer();

    while ($fila_correos = $datos_atiende->fetch_assoc()) {
        $atiende_correo = $fila_correos['atiende_correo'];
        $atiende_nombre = $fila_correos['nombre'];

        $datos_v = $mysqli->query("SELECT ene.trn_id, folio, ene.fecha AS fecha_solicitado, ent.fecha_inicio AS fecha_visita, usu.nombre, org.nombre AS empresa
                                        ,ati.nombre AS atiende, ati.email AS atiende_correo, est.estado, un.nombre AS mina                  
                                    FROM
                                	arg_entradas_estados ene
                                	LEFT JOIN arg_entradas ent
                                    	ON ene.trn_id = ent.trn_id
                                    LEFT JOIN arg_usuarios usu
                                    	ON usu.u_id = ent.usuario_id
                                    LEFT JOIN arg_organizaciones org
                                    	ON org.org_id = usu.org_id
                                    LEFT JOIN arg_usuarios ati
                                    	ON ati.u_id = ent.usuario_id_atie
                                    LEFT JOIN arg_estados est
                                        ON est.estado_id = ene.estado_id
                                    LEFT JOIN arg_empr_unidades un
                                        ON un.unidad_id = ent.unidad_id
                                WHERE ene.estado_id = 1 AND ent.fecha_inicio >= '".$hoy."' AND ati.email = '".$atiende_correo."'")  or die(mysqli_error());

				//include("crearPdfimagen.php"); 
				
				$mail->From = "bitacora.arg@argonautgold.com";
				$mail->FromName = "Bitacora de Registro";
				$mail->Subject = "Solicitudes pendientes de autorizar";		
                $mail->AddBCC("".$atiende_correo."");	
				//$mail->AddBCC("".$atiende_correo."");
        
				$mail->ContentType = "text/html";
				$body = $atiende_nombre." "."usted tiene las siguientes solicitudes pendientes por autorizar:<br><br>";	
                $body .= "<table colspan='8' style='background-color: #364370; color: #ffffff; padding: 3px; text-align: center; font-size: 20px;'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col'>Folio</th>
                                    <th scope='col'>Mina</th>
                                    <th scope='col'>Fecha Visita</th>
                                    <th scope='col'>Estado</th>
                                    <th scope='col'>Proveedor</th>
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>";           
                            	while ($fila = $datos_v->fetch_assoc()) {     
                            	   
                            $body .= "<tr>";  
                            		$body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['folio']."</td>";
                                    $body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['mina']."</td>";
                                    $body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['fecha_visita']."</td>";
                                    $body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['estado']."</td>";
                                    $body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['nombre']."</td>";
                                    $body.="<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>".$fila['empresa']."</td>";
                                    $datos_motivos = $mysqli->query("SELECT   
                                                                        nombre AS motivo
                                                               FROM
                               	                                    motivos_visitas
                                                               WHERE trn_id = ".$fila['trn_id']."");
                                    $body .= "<td style='background-color: #ffffff; color: #050505; padding: 3px; text-align: center; font-size: 18px;'>"; 
                                   while ($fila_mot = $datos_motivos->fetch_assoc()) {
                                        $body.="<a>".$fila_mot['motivo'].', '."</a>";
                                    }
                                    $body .= "</td>"; 
                                    $body .= "</tr>"; 
                            	}
                  $body.="</tbody></table>";		
                $body .= "<br>"."<br>"."<br>"."<br>"."<br>"."Atte: Bitácora de Registro de Argonaut Gold INC";
				$mail->Body = $body;
				$mail->Send();
    }
                echo $body;
    ///echo "<script> dash(".$unidad_id.");</script>"; 
   
              
?>
