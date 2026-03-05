<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$u_id_ver = $_POST['u_id_ver'];
    
if (isset($u_id_ver)){
        $datos_user = $mysqli->query("SELECT DISTINCT
                                        pv.perfil_id,
                                        pv.perfil,
                                        pv.nombre,
                                        up.fecha_inicial,
                                        up.fecha_final,
                                        (CASE WHEN up.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo,
                                    ure.nombre AS usuario_resp
                                    FROM
                                        `perfiles_privilegios` AS pv
                                    LEFT JOIN arg_usuarios_perfiles AS up
                                    ON
                                        pv.perfil_id = up.perfil_id AND pv.u_id = up.u_id
                                    LEFT JOIN arg_usuarios AS ure
                                    ON
                                        ure.u_id = up.u_id_resp
                                    WHERE
                                        pv.u_id = ".$u_id_ver) or die(mysqli_error());
        //$datos_user_perfiles = $datos_user ->fetch_array(MYSQLI_ASSOC);
        //$usuario = $datos_user['nombre'];
        
        $html.=  "<table class='table text-black' id='tabla_pesaje_met'>
                                <thead class='thead-info' align='center'>"; 
                 $html.="<tr class='table-info' align='left'>
                                        <th>Usuario</th>
                                        <th>Perfil</th> 
                                        <th>Activo</th> 
                                        <th>Feha Inicial</th> 
                                        <th>Fecha Final</th>                                        
                                        <th>Menús</th>
                                        <th>Transacción</th> 
                                        <th>Atributos</th>                   
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        $perfil_id = $res['perfil_id'];
                        $perfil    = $res['perfil'];
                        $usuario   = $res['nombre'];
                        $activo    = $res['activo'];
                        $fecha_ini   = $res['fecha_inicial'];
                        $fecha_fin   = $res['fecha_final'];                             
                        $html.="<tr>                    
                                   <td>".$usuario."</td> 
                                   
                                   <td>".$perfil."</td> 
                                   <td>".$activo."</td>
                                   <td>".$fecha_ini."</td>
                                   <td>".$fecha_fin."</td>                             
                                   <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='ver_detalle_perfil(".$perfil_id.", 1)' >
                                            <span class='fa fa-eye fa-1x'>
                                            </span>
                                        </button>
                                   </td>    
                                   <td> <button type='button'class='btn btn-primary' align='center' id='boton_save_pay' onclick='ver_detalle_perfil(".$perfil_id.", 2)' >
                                            <span class='fa fa-eye fa-1x'></span>
                                        </button>
                                   </td>    
                                   <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='ver_detalle_perfil(".$perfil_id.", 3)' >
                                            <span class='fa fa-eye fa-1x'></span>
                                        </button>
                                   </td>                                   
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
        
  }
  
$mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>