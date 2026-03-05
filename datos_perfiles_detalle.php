<?include "connections/config.php";?>
<?php
//$html = '';
$u_id = $_SESSION['u_id'];
$perfil_id = $_POST['perfil_id'];
$dir_id = $_POST['dir_id'];
    
if (isset($perfil_id)){
    
    if($dir_id == 1){
        $datos_user = $mysqli->query("SELECT 
                                             DISTINCT directiva_id, valor,
                                              me.menu AS nombre_directiva,
                                             'Menu' AS descripcion_directiva,
                                              p.descripcion AS perfil
                                     FROM `arg_usuarios_privilegios` up
                                     LEFT JOIN arg_perfiles AS p
                                        ON p.perfil_id = up.perfil_id
                                     LEFT JOIN 	arg_sys_menus me
                                     	ON me.menu_id = up.valor
                                     WHERE up.perfil_id = ".$perfil_id."
                                        AND up.directiva_id =".$dir_id) or die(mysqli_error());
                 $html.=  "<table class='table text-black' id='datos_perfiles'>
                                                <thead class='thead-warning' align='center'>"; 
                                 $html.="<tr class='table-warning' align='left'>
                                                        <th>Perfil</th>                                                                                
                                                        <th>Menú</th> 
                                                        <th>Descripción</th>                       
                                                </thead>
                                                <tbody>";
                                 $cont = 0;
                                 while ($res = $datos_user->fetch_assoc()) {
                                        $cont = $cont+1;
                                        //$perfil_id = $res['perfil_id'];
                                        $perfil    = $res['perfil'];
                                        $directiva = $res['descripcion_directiva']; 
                                        $nombre_directiva = $res['nombre_directiva'];                             
                                        $html.="<tr>  
                                                  
                                                   <td>".$perfil."</td> 
                                                   <td>".$directiva."</td>                            
                                                   <td>".$nombre_directiva."</td>                                
                                                </tr>"; 
                                    }
                             $html .= "</tbody></table></div>";
    }
    if($dir_id == 2){
       $datos_user = $mysqli->query("SELECT 
                                             DISTINCT directiva_id, valor,
                                              me.transaccion AS nombre_directiva,
                                             'Transacción' AS descripcion_directiva,
                                              p.descripcion AS perfil
                                     FROM `arg_usuarios_privilegios` up
                                     LEFT JOIN arg_perfiles AS p
                                        ON p.perfil_id = up.perfil_id
                                     LEFT JOIN 	arg_sys_menus_transacciones me
                                     	ON me.transaccion_id = up.valor                                     
                                     WHERE up.perfil_id = ".$perfil_id."
                                        AND up.directiva_id = ".$dir_id) or die(mysqli_error());
        
         $html.=  "<table class='table text-black' id='datos_perfiles'>
                                <thead class='thead-warning' align='center'>"; 
                 $html.="<tr class='table-warning' align='left'>
                                        <th>Perfil</th>                                                                                
                                        <th>Menú</th> 
                                        <th>Descripción</th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        //$perfil_id = $res['perfil_id'];
                        $perfil    = $res['perfil'];
                        $directiva = $res['descripcion_directiva']; 
                        $nombre_directiva = $res['nombre_directiva'];                             
                        $html.="<tr> 
                                   <td>".$perfil."</td> 
                                   <td>".$directiva."</td>                             
                                   <td>".$nombre_directiva."</td>                                
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
        
    } 
    if($dir_id == 3){
       $datos_user = $mysqli->query("SELECT
                                            	 me.menu_id
                                                ,me.menu
                                                ,mt.transaccion_id
                                                ,mt.transaccion
                                                ,bt.etapa_id
                                                ,p.descripcion AS perfil
                                                ,'Botón' AS descripcion_directiva 
                                                ,(CASE WHEN bt.etapa_id = 0 THEN 'Todos' ELSE et.nombre END) AS nombre_directiva                                               
                                            FROM
                                            	arg_sys_menus_transacciones AS mt
                                                INNER JOIN arg_sys_transacciones_botones bt
                                                    ON mt.transaccion_id = bt.transaccion_id
                                                LEFT JOIN arg_sys_menus AS me
                                                	ON me.menu_id = mt.menu_id
                                                LEFT JOIN arg_etapas et
                                                    ON et.etapa_id = bt.etapa_id
                                                LEFT JOIN arg_perfiles p
                                                	ON p.perfil_id = ".$perfil_id."                                          
                                           WHERE bt.etapa_id IN (SELECT DISTINCT valor 
                                                                           FROM arg_usuarios_privilegios
                                                                     WHERE directiva_id = 3 AND perfil_id = ".$perfil_id.")"
                                        ) or die(mysqli_error());
        $html.=  "<table class='table text-black' id='datos_perfiles'>
                                <thead class='thead-warning' align='center'>"; 
                 $html.="<tr class='table-warning' align='left'>
                                        <th>Perfil</th>                                                                                
                                        <th>Directiva</th> 
                                        <th>Transacción</th> 
                                        <th>Descripción</th>                       
                                </thead>
                                <tbody>";
                 $cont = 0;
                 while ($res = $datos_user->fetch_assoc()) {
                        $cont = $cont+1;
                        //$perfil_id = $res['perfil_id'];
                        $perfil    = $res['perfil'];
                        $directiva = $res['descripcion_directiva']; 
                        $transaccion = $res['transaccion']; 
                        $nombre_directiva = $res['nombre_directiva'];                             
                        $html.="<tr>  
                                  
                                   <td>".$perfil."</td> 
                                   <td>".$directiva."</td>  
                                   <td>".$transaccion."</td>                              
                                   <td>".$nombre_directiva."</td>                                
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
    } 
       
        
        
  }
  
$mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>