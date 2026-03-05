<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$u_id_editar = $_POST['userid_add'];
$accion = $_POST['accion'];

if (isset($u_id_editar)){
    $perfiles = array();
    $perfiles_user = $mysqli->query("SELECT perfil_id FROM `arg_usuarios_perfiles` WHERE u_id = ".$u_id_editar." AND activo = 1");
    while ($resultado = $perfiles_user->fetch_assoc()){
        $p_id = $resultado['perfil_id'];
        $perfiles[] = $p_id;
    }
    if($accion == 1){
        $datos_user = $mysqli->query("SELECT DISTINCT perfil_id, descripcion AS perfil FROM arg_perfiles") or die(mysqli_error($mysqli));
        $html.=  "<table class='table text-black' id='datos_agregar_perfil'>
                                <thead class='thead-warning' align='center'>
                                <tr class='table-info' align='left'>
                                <th>Perfil</th>
                                <th>Accion</th>";                              
               $html.=  "</tr></thead><tbody>"; 
                 
               $cont = 0;
               while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        $perfil_id = $res['perfil_id'];
                        $perfil    = $res['perfil'];
                        $css = "";
                        if (in_array($perfil_id,$perfiles)){
                            $css = "disabled";
                        }
                        $html.="<tr>
                                    <td style='display:none;'> <input type='number' class='form-control' id='perfil_id_add".$cont."' value=".$perfil_id."></td>
                                    <td style='display:none;'> <input type='number' class='form-control' id='user_id_add".$cont."' value=".$u_id_editar."></td>
                                    <td>".$perfil."</td>                             
                                    <td><div class='form-check'>
                                            <input type='checkbox' $css class='form-check-input' id='perfil_add".$cont."' value='0' onClick='ActivarCasillaAdd(".$cont.");'>
                                            <label class='form-check-label' for='perfil_add".$cont."'></label>
                                        </div>
                                    </td>";  
                         }
    }
    
    if($accion == 0){
        $datos_user = $mysqli->query("SELECT
                                       	 up.perfil_id, up.u_id
                                        ,per.perfil_id, per.descripcion AS perfil
                                        ,up.fecha_inicial, up.fecha_final
                                        ,(CASE WHEN up.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                                    FROM
                                    	arg_usuarios_perfiles AS up 
                                    LEFT JOIN arg_perfiles AS per
                                    	ON up.perfil_id = per.perfil_id
                                    WHERE up.activo = 1 AND u_id =".$u_id_editar) or die(mysqli_error());
    
        $html.=  "<table class='table text-black' id='datos_eliminar_perfil'>
                                <thead class='thead-warning' align='center'>
                                <tr class='table-info' align='left'>
                                <th>Perfil</th>
                                <th>Fecha Inicial</th>
                                <th>Fecha Final</th>
                                <th>Activo</th>
                                <th>Desactivar</th>";
                               
               $html.=  "</tr></thead><tbody>"; 
                 
                 $cont = 0;
                 while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        $perfil_id = $res['perfil_id'];
                        $u_id_del  = $res['u_id'];
                        $perfil    = $res['perfil'];
                        $fecha_ini = $res['fecha_inicial'];
                        $fecha_fin = $res['fecha_final'];
                        $activo    = $res['activo'];
                                          
                        $html.="<tr>
                                    <td style='display:none;'> <input type='number' class='form-control' id='perfil_id_del".$cont."' value=".$perfil_id."></td>
                                    <td style='display:none;'> <input type='number' class='form-control' id='user_id_del".$cont."' value=".$u_id_del."></td>
                                    <td>".$perfil."</td>
                                    <td>".$fecha_ini."</td>
                                    <td>".$fecha_fin."</td>
                                    <td>".$activo."</td>                                   
                                    <td><div class='form-check'>
                                            <input type='checkbox' class='form-check-input' id='perfil_del".$cont."' value='0' onClick='ActivarCasillaDel(".$cont.");'>
                                            <label class='form-check-label' for='perfil_del".$cont."'></label>
                                        </div>
                                    </td>";   
                 }
    }  
    $html .= " </tr></tbody></table></div>";
 } 
$mysqli -> set_charset("utf8");
echo utf8_encode($html);
?>