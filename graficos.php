<?php
include "connections/config.php";
$hoy = date("Y-m-d"); 
//echo $hoy;

        $datos_est = $mysqli->query("SELECT ene.trn_id, folio, ene.fecha AS fecha_solicitado, ent.fecha_inicio AS fecha_visita, usu.nombre, org.nombre AS empresa
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
                                        ON un.unidad_id = ent.unidad_id");
    //$result->query($query);
   // $result =  $datos_est->fetch_assoc();
    
    $data = array();
foreach ($datos_est as $row) {
        $data[] = $row;
}

//mysqli_close($conn);

echo json_encode($data);
			        
                            	//while ($fila = $datos_v->fetch_assoc()) {}
                
    ///echo "<script> dash(".$unidad_id.");</script>"; 
   
              
?>
