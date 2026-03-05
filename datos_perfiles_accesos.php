<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$perfil_id = $_POST['perfil_id'];
$u_id = $_POST['u_id'];
    
if (isset($perfil_id)){
         $nombre_per = $mysqli->query("SELECT descripcion FROM arg_perfiles WHERE perfil_id = ".$perfil_id) or die(mysqli_error());             
         $perfil_des = $nombre_per->fetch_assoc();
         $perfil = $perfil_des['descripcion'];
    
     /*   $existe_perfil = $mysqli->query("SELECT COUNT(*) AS existe FROM arg_usuarios_privilegios WHERE perfil_id = ".$perfil_id) or die(mysqli_error());             
        $exis = $existe_perfil->fetch_assoc();
        $perfil_existe = $exis['existe'];
        
        if ($perfil_existe == 0){*/
        
             $datos_user = $mysqli->query("SELECT DISTINCT directiva_id, descripcion_directiva FROM `perfiles_privilegios` WHERE directiva_id <> 0 ") or die(mysqli_error());
        //}
        //else{
             //$datos_user = $mysqli->query("SELECT DISTINCT directiva_id, descripcion_directiva, perfil_id, perfil FROM `perfiles_privilegios` WHERE  perfil_id = ".$perfil_id) or die(mysqli_error());
           
      //  }   
        $html.=  "<table class='table text-black' id='datos_perfiles_det'>
                                <thead class='thead-warning' align='center'>
                                <tr class='table-info' align='left'>
                                <th>Perfil</th>
                                <th>Directiva</th>
                                <th>Accesos</th>";
                                if($u_id == 1){
                                    $html.=  "<th>Agregar</th>";
                                    $html.=  "<th>Eliminar</th>";
                                }
               $html.=  "</tr></thead><tbody>"; 
                 
                 $cont = 0;
                 while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        $directiva_id = $res['directiva_id'];
                        $directiva = $res['descripcion_directiva'];                       
                        $html.="<tr>
                                        <td>".$perfil."</td>  
                                        <td>".$directiva."</td>                                      
                                        <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='ver_detalle_perfil(".$perfil_id.",".$directiva_id.")' >
                                                <span class='fa fa-eye fa-2x'>
                                                </span>
                                            </button>";
                        if($u_id == 1){
                                     $html.="<td> <button type='button'class='btn btn-success' id='boton_save_pay' onclick='agregar_directiva(".$perfil_id.",".$directiva_id.")' >
                                                <span class='fa fa-plus-square fa-2x'>
                                                </span>
                                            </button>";
                                     $html.="<td> <button type='button'class='btn btn-danger' id='boton_save_pay' onclick='eliminar_directiva(".$perfil_id.",".$directiva_id.")' >
                                                <span class='fa fa-trash fa-2x'>
                                                </span>
                                            </button>";
                         }
            }
            $html .= " </tr></tbody></table></div>";
 } 
$mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>