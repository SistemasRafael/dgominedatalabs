
<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
	}

	.btnSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }

    .circulos{
    	padding-top: 5em;
    }
    
    img{
      max-width: 100%;
    }
</style>
<?php
if(($_SESSION['LoggedIn']) <> ''){
    $u_id = $_SESSION['u_id'];
    $uid_califica = $_GET['uid_califica'];
    $trn_id = $_GET['trn_id'];
    
    $check_empl = $mysqli->query("SELECT division, org_id FROM arg_usuarios WHERE u_id = ".$u_id);
   	$row_empl = $check_empl->fetch_array(MYSQLI_ASSOC);
   	$usuario_empleado = $row_empl['division']; 
    
    $total_cursos = $mysqli->query("SELECT COUNT(*) AS total_cursos, u_id FROM arg_usuarios_cursos WHERE trn_id = ".$trn_id);
   	$total_cursos_cont = $total_cursos->fetch_array(MYSQLI_ASSOC);
   	$total_cursos_contar = $total_cursos_cont['total_cursos'];
    $u_id_proveedor = $total_cursos_cont['u_id'];
    
    $check_prov = $mysqli->query("SELECT nombre, org_id FROM arg_usuarios WHERE u_id = ".$u_id_proveedor);
   	$row_prov = $check_prov->fetch_array(MYSQLI_ASSOC);
   	$usuario_proveedor = $row_prov['nombre']; 
    
    if (isset($_POST['guarda_calif'])){ 
                    $x = 1;
                    $z = 0;
                    $query = "UPDATE arg_calendario_usuarios SET estado_id = 7 WHERE trn_id = ".$trn_id;
                        $mysqli->query($query);
                    while ($x <> 0){        
                        $ac = $x;                        
                        $curso_id_n = 'curso_id'.$x;
                        $curso_cal = $_POST[$x];
                        $curso_id_v = $_POST[$curso_id_n];
                        $x = $x+1;
                        $query = "UPDATE arg_usuarios_cursos SET calificacion = ".$curso_cal." WHERE trn_id = ".$trn_id." AND curso_id = ".$curso_id_v;
                        $mysqli->query($query);
                        if($curso_cal >= 70){
                            mysqli_multi_query ($mysqli, "CALL arg_vigencia_procesar (".$trn_id.", ".$curso_id_v.", ".$uid_califica.")") OR DIE (mysqli_error($mysqli));                            
                        }
                        
                        if ($x>$total_cursos_contar){
                            $x = 0;
                        }
                        
                    }
        }
             mysqli_multi_query ($mysqli, "CALL visor_califica (".$u_id.", ".$uid_califica.")") OR DIE (mysqli_error($mysqli));           
        //echo $org_id;
        if ($usuario_empleado == 'empleado'){
                  ?>  
                <div class="container">   
                <form method="post" action="calificaciones.php?uid_califica=<?echo $uid_califica;?>&trn_id=<?echo $trn_id;?>" name="califform" id="califform">  
                <fieldset>
                 <h3>Evaluación del proveedor: <strong><?echo $usuario_proveedor?></strong></h3></br>
                            
                        <div class="container">      
                             <div class="col-md-9 col-lg-9">   
                              <h4>Tipo de Inducción Completa</h4></br>                            
                             </div>
                             <div class="col-md-2 col-lg-2">
                                <input type="submit" class="btn btn-primary" name="guarda_calif" id="guarda_calif" value="Guardar Calificaciones" />
                                </br></br>
                             </div>
                        </div>
                     <?
                     $p = 0;      
                   $html_en.="<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>
                                    <th scope='col'>Descripción</th>
                                    <th scope='col'>Elaborado</th>
                                    <th scope='col'>Calificacion</th>
                                    <th scope='col'>Sticker</th>
                                    <th scope='col'>Vigencia</th>
                                  </tr>
                              </thead>
                              <tbody id='detalle'>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                            $p = $p+1;
                                             $html_en.="<tr>                                                
                                                <td style='width:60%'> ".$row["nombre"]."</td>                                                
                                                <td style='width:5%'> ".$row["fecha"]."</td>
                                                <td style='width:10%'> <input type='text' style='width:80%' name = ".$p." value = '".$row["calificacion"]."'></td>
                                                <td style='width:5%'> ".$row["folio_sti"]."</td>
                                                <td style='width:10%'> ".$row["expira"]."</td>
                                                <input type='hidden'  name = 'curso_id".$p."' value='".$row["curso_id"]."'>
                                                
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                echo ("$html_en");
            ?>
            </div> 
               </fieldset>  
            </form> 
            <?
           }
           else{
                ?>
                <div class="container">   
                
                 <!--</a> <h3>Evaluación del proveedor: <strong><?echo $usuario_proveedor?></strong></h3></br>-->
                            
                        <div class="container">      
                             <div class="col-md-9 col-lg-9">   
                              <h4>Tipo de Inducción Completa</h4></br>                            
                             </div>
                             
                        </div>
                     <?
                     $p = 0;      
                   $html_en.="<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>
                                    <th scope='col'>Descripción</th>
                                    <th scope='col'>Elaborado</th>
                                    <th scope='col'>Calificacion</th>
                                    <th scope='col'>Sticker</th>
                                    <th scope='col'>Vigencia</th>
                                  </tr>
                              </thead>
                              <tbody id='detalle'>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                            $p = $p+1;
                                             $html_en.="<tr>                                                
                                                <td style='width:60%'> ".$row["nombre"]."</td>                                                
                                                <td style='width:5%'> ".$row["fecha"]."</td>
                                                <td style='width:10%'>".$row["calificacion"]."</td>
                                                <td style='width:5%'> ".$row["folio_sti"]."</td>
                                                <td style='width:10%'> ".$row["expira"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                echo ("$html_en");      
                ?>
                <br/><br/>    
             </div>  
             <?            
           }
       ?>       
       <br/>
       <br/>
       <br/>
       <?
     } // Si estás conectado
    ?>
    
       
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

