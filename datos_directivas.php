<?include "connections/config.php";?>
<?php
$html = '';
$u_id = $_SESSION['u_id'];
$perfil_id = $_POST['perfil_id'];
$dir_id = $_POST['dir_id'];
    
if (isset($perfil_id)){
        
        if ($dir_id == 1){
            $datos_menus = $mysqli->query("SELECT menu_id, menu FROM arg_sys_menus 
                                           WHERE menu_id NOT IN (SELECT DISTINCT menu_id 
                                                                 FROM perfiles_privilegios 
                                                                 WHERE directiva_id = 1 AND perfil_id = ".$perfil_id.")") or die(mysqli_error());
            $html.=  "<table class='table text-black' id='datos_dir_tabla'>
                                <thead class='thead-warning' align='center'>"; 
                 $html.="<tr class='table-warning' align='left'>
                                        <th>Menu Id</th>                                        
                                        <th>Menu</th> 
                                        <th>Seleccionar</th>                       
                                </thead>
                                <tbody>";
                 $c = 0;
                 while ($res = $datos_menus->fetch_assoc()) {
                        $c = $c+1;
                        $menu_id = $res['menu_id'];
                        $menu    = $res['menu'];                           
                        $html.="<tr>
                                   <td style='display:none;'> <input type='number' class='form-control' id='perfil_id".$c."' value=".$perfil_id."></td> 
                                   <td style='display:none;'> <input type='number' class='form-control' id='dir_id".$c."' value=".$dir_id."></td>
                                   <td> <input type='number' class='form_control' id='valor".$c."' value=".$menu_id." disabled></td>
                                   <td>".$menu."</td>
                                   <td><div class='form-check'>
                                    <input type='checkbox' class='form-check-input' id='menu".$c."' value='0' onClick='ActivarCasilla(".$c.");'>
                                        <label class='form-check-label' for='menu".$c."'></label>
                                        </div>
                                  </td>                             
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
        }
        if ($dir_id == 2){
            $datos_menus = $mysqli->query("SELECT
                                            	 me.menu_id
                                                ,me.menu
                                                ,mt.transaccion_id
                                                ,mt.transaccion
                                            FROM
                                            	arg_sys_menus_transacciones AS mt
                                                LEFT JOIN arg_sys_menus AS me
                                                	ON me.menu_id = mt.menu_id
                                           WHERE mt.transaccion_id NOT IN (SELECT  DISTINCT valor 
                                                                           FROM arg_usuarios_privilegios
                                                                           WHERE directiva_id = 2 AND perfil_id = ".$perfil_id.")") or die(mysqli_error());
            $html.=  "<table class='table text-black' id='datos_dir_tabla'>
                                <thead class='thead-warning' align='center'>"; 
                 $html.="<tr class='table-warning' align='left'>                                                                             
                                        <th>Menu</th> 
                                        <th>Transacción</th>  
                                        <th>Seleccionar</th>                     
                                </thead>
                                <tbody>";
                 $c = 0;
                 while ($res = $datos_menus->fetch_assoc()) {
                        $c = $c+1;
                        $menu_id = $res['menu_id'];
                        $menu    = $res['menu'];
                        $transaccion_id = $res['transaccion_id'];
                        $transaccion = $res['transaccion'];                         
                        $html.="<tr>  
                                  
                                   <td style='display:none;'> <input type='number' class='form-control' id='perfil_id".$c."' value=".$perfil_id."></td> 
                                   <td style='display:none;'> <input type='number' class='form-control' id='dir_id".$c."' value=".$dir_id."></td>
                                   <td style='display:none;'> <input type='number' class='form_control' id='valor".$c."' value=".$transaccion_id." disabled></td>
                                   <td>".$menu."</td>
                                   <td>".$transaccion."</td>
                                   <td><div class='form-check'>
                                    <input type='checkbox' class='form-check-input' id='menu".$c."' value='0' onClick='ActivarCasilla(".$c.");'>
                                        <label class='form-check-label' for='menu".$c."'></label>
                                        </div>
                                  </td>                             
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
        }
          if ($dir_id == 3){
            $datos_menus = $mysqli->query("SELECT
                                                 mt.transaccion_id
                                                ,mt.transaccion
                                                ,bt.etapa_id
                                                ,(CASE WHEN bt.etapa_id = 0 THEN 'Todos' ELSE et.nombre END) AS boton                                                
                                            FROM
                                            
                                            	arg_sys_menus_transacciones AS mt
                                                INNER JOIN arg_sys_transacciones_botones bt
                                                    ON mt.transaccion_id = bt.transaccion_id
                                                LEFT JOIN arg_etapas et
                                                    ON et.etapa_id = bt.etapa_id
                                           WHERE bt.etapa_id NOT IN (SELECT DISTINCT valor 
                                                                           FROM arg_usuarios_privilegios
                                                                           WHERE directiva_id = 3 AND perfil_id = ".$perfil_id.")") or die(mysqli_error());
            $html.=  "<table class='table text-black' id='datos_dir_tabla'>
                                <thead class='thead-warning' align='center'>"; 
                 $html.="<tr class='table-warning' align='left'>                                                                             
                                        <th>Menu</th> 
                                        <th>Transacción</th>  
                                        <th>Seleccionar</th>                     
                                </thead>
                                <tbody>";
                 $c = 0;
                 while ($res = $datos_menus->fetch_assoc()) {
                        $c = $c+1;                       
                        $etapa_id = $res['etapa_id'];                        
                        $transaccion = $res['transaccion'];
                        $boton = $res['boton'];                        
                        $html.="<tr>
                                   <td style='display:none;'> <input type='number' class='form-control' id='perfil_id".$c."' value=".$perfil_id."></td> 
                                   <td style='display:none;'> <input type='number' class='form-control' id='dir_id".$c."' value=".$dir_id."></td>
                                   <td style='display:none;'> <input type='number' class='form_control' id='valor".$c."' value=".$etapa_id." disabled></td>                                
                                   <td>".$transaccion."</td>
                                   <td>".$boton."</td>
                                   <td><div class='form-check'>
                                    <input type='checkbox' class='form-check-input' id='menu".$c."' value='0' onClick='ActivarCasilla(".$c.");'>
                                        <label class='form-check-label' for='menu".$c."'></label>
                                        </div>
                                  </td>                             
                                </tr>"; 
                    }
             $html .= "</tbody></table></div>";
        }
        
  }
  
$mysqli -> set_charset("utf8");
echo utf8_encode($html);

?>