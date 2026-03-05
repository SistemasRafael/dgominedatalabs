
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
//$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> ''){
    $unidad_mina_sel = $_GET['unidad'];
?>
   
 <div class="container">
    <div class="row">
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-building-o fa-3x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        EMPRESA
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="empresa.php?tipo=0"><h5>Datos generales</h5></a> </li>
                            <li class="divider"></li> 
                            <li> <a href="empresa.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>"><h5>Personas</h5></a> </li>    
                            <li> <a href="empresa.php?tipo=2&unidad=<?echo $unidad_mina_sel;?>"><h5>Vehículos</h5></a></li>                                                                      
                            <li> <a href="empresa.php?tipo=2&unidad=<?echo $unidad_mina_sel;?>"><h5>Herramientas</h5></a> </li>
                        </ul>
                   </div>
              </div>
          </div>  
          
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-bars fa-3x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        BITÁCORA
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php"><h5>Crear Visita</h5></a> </li>
                            <li class="divider"></li>                   
                            <li> <a  href="dashboard.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>"><h5>Visitas</h5></a> </li>
                            <li> <a  href="#"><h5>Cerradas</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>
          
           <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-success o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-play-circle-o fa-3x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        CURSOS
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                           <li> <a href="cursos.php?tipo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Visita General</h5></a> </li>                                              
                            <li> <a  href="cursos.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>"><h5>Trabajos de altos riesgos</h5></a> </li>
                            <li> <a  href="calendario.php?motivo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Calendarios</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div>
       
      </div>
    </div>  
    <?           
       $u_id = $_SESSION['u_id'];
       
        $calendario = $_GET['cal_id'];    
        $check_id = $mysqli->query("SELECT division, org_id FROM arg_usuarios WHERE u_id = ".$u_id);
    	$row_uid = $check_id->fetch_array(MYSQLI_ASSOC);
    	$u_id_tipo = $row_uid['division']; 
       
        //echo $org_id;
        //if ($u_id_tipo == 'empleado'){
           $tipo = $_GET['tipo'];
           if ($u_id_tipo == 'empleado'){  //Lista de empresas
                mysqli_multi_query ($mysqli, "CALL visor_inscritos (".$calendario.", 0".")") OR DIE (mysqli_error($mysqli));
                ?>
                     <br /> <br />
                    <div class="container" >
                        <div class="col-md-9 col-lg-9">
                            <div class="col-md-3 col-lg-3">
                                <a href="inscritos.php?cal_id=11">Mes</a>
                             </div>
                             
                            <div class="col-md-3 col-lg-3">
                                <a href="inscritos.php?cal_id=0">Todos</a>
                            </div>
                          
                        </div>
                    </div>
                   <?
           }
           else{           
                mysqli_multi_query ($mysqli, "CALL visor_inscritos (".$calendario.",".$u_id.")") OR DIE (mysqli_error($mysqli));
           }
           
                ?>
                    <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Tipo Inducción</th>
                                    <th scope='col'>Fecha Programada</th>
                                    <th scope='col'>Fecha de solicitud</th>
                                    <th scope='col'>Estado</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                             $html_en.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["nombre"]."</td>
                                                <td> ".$row["tipo_induccion"]."</td>
                                                <td> ".$row["fecha_curso"]."</td>
                                                <td> ".$row["fecha_solicitud"]."</td>";
                                                 if ($u_id_tipo == 'empleado'){
                                                $html_en .= "<td> 
                                                    <div class='btn-group'>
                                                    <button type='button' class='btn btn-info btn-block dropdown-toggle' data-toggle='dropdown'>
                                                        ".$row["estado"]."
                                                    </button>";
                                                                                                      
                                                    $html_en.="<ul class='dropdown-menu' role='menu'>
                                                        <li> <a href='cambiar_estado_inscr.php?trn_id=".$row['trn_id']."&estado_id=".$row['estado_id_1']."'><h5>".$row['estado_n_1']."</h5></a> </li>
                                                        <li> <a href='cambiar_estado_inscr.php?trn_id=".$row['trn_id']."&estado_id=".$row['estado_id_2']."'><h5>".$row['estado_n_2']."</h5></a> </li>
                                                    </ul>";
                                                    }
                                                    else{
                                                        if ($row["estado"] == 'Calificado'){
                                                            $html_en .= "<td> 
                                                            <div class='btn-group'>
                                                            <button type='button' class='btn btn-info btn-block dropdown-toggle' data-toggle='dropdown'>
                                                                ".$row["estado"]."
                                                            </button>";
                                                            $html_en.=  "<ul class='dropdown-menu' role='menu'>
                                                                        <li> <a href='calificaciones.php?uid_califica=".$u_id."&trn_id=".$row['trn_id']."'><h5>".$row['estado_n_1']."</h5></a> </li>
                                                                        </ul>";
                                                            }
                                                         else{
                                                            $html_en .= "<td> 
                                                            <div class='btn-group'>
                                                            <button type='button' class='btn btn-info btn-block dropdown-toggle' data-toggle='dropdown'>
                                                                ".$row["estado"]."
                                                            </button>";
                                                         }     
                                                    }
                                                    
                                                    $html_en .= "</div> 
                                                </td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                echo ("$html_en");      
                ?>
             </div>  
             <?            
           
           
              
         //}
       ?>
       
       <br/>
       <br/>
       <br/>
       <?
     } // Si estás conectado
    ?>
    
       
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

