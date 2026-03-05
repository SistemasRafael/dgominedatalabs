
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
                <i class="fa fa-building-o fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        EMPRESA
                     </button>
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="empresa.php?tipo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Datos generales</h5></a> </li>
                            <li class="divider"></li> 
                            <li> <a href="doc_personas.php?u_id=<?echo $u_id;?>"><h5>Documentación de Personas</h5></a> </li>    
                            <li> <a href="doc_vehiculos.php"><h5>Documentación de Vehículos</h5></a> </li>   
                        </ul>
                   </div>
              </div>
          </div>  
          
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-bars fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        BITÁCORA
                     </button>
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
                <i class="fa fa-play-circle-o fa-2x"></i>
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
                            <li> <a  href="calificaciones.php?uid_califica=<?echo $u_id?>&trn_id=1"><h5>Mis cursos</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-warning o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-medkit fa-2x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        C O V I D   19
                     </button>
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="#"><h5>Información</h5></a> </li>   
                        </ul>
                   </div>
                
              </div>
          </div> 
       
      </div>
    </div>  
    <?           
       $u_id = $_SESSION['u_id'];     
       
        $check_id = $mysqli->query("SELECT division, org_id FROM arg_usuarios WHERE u_id = ".$u_id);
    	$row_uid = $check_id->fetch_array(MYSQLI_ASSOC);
    	$u_id_tipo = $row_uid['division'];
        $org_id = $row_uid['org_id'];
    	
        //echo $u_id_tipo;
        ?>
         <br /> <br />
        <div class="container" >
            <div class="col-md-9 col-lg-9">
                <div class="col-md-3 col-lg-3">
                    <a href="empresa.php?tipo=0&unidad=<?echo $unidad_mina_sel;?>">Empresas</a>
                 </div>
                 
                <div class="col-md-3 col-lg-3">
                    <a href="empresa.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>">Personas</a>
                </div>
                <div class="col-md-3 col-lg-3">
                    <a href="empresa.php?tipo=2&unidad=<?echo $unidad_mina_sel;?>">Vehículos</a>
                </div>
                <div class="col-md-3 col-lg-3">
                    <a href="empresa.php?tipo=3&unidad=<?echo $unidad_mina_sel;?>">Herramientas</a>
                </div>
            </div>
        </div>
       <?
        
        //echo $org_id;
        //if ($u_id_tipo == 'empleado'){
           $tipo = $_GET['tipo'];
           if ($tipo == 0){  //Lista de empresas
                mysqli_multi_query ($mysqli, "CALL visor_empresas (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                    <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>RFC</th>
                                    <th scope='col'>Calle</th>
                                    <th scope='col'>Colonia</th>
                                    <th scope='col'>Localidad</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                             $html_en.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["rfc"]."</td>
                                                <td> ".$row["calle"]."</td>
                                                <td> ".$row["colonia"]."</td>
                                                <td> ".$row["localidad"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                echo ("$html_en");      
                ?>
             </div>  
             <?            
           }
           if ($tipo == 1){
                mysqli_multi_query ($mysqli, "CALL visor_personas (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                  <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Email</th>
                                    <th scope='col'>INE</th>
                                    <th scope='col'>IMSS</th>
                                    <th scope='col'>Licencia</th>
                                    <th scope='col'>Cursos</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td><a href=\"doc_personas.php?u_id=".$row["u_id"]."&unidad=".$unidad_mina_sel."\" ><font color=blue><b>".$row["nombre"]."</a></td> 
                                                <td> ".$row["email"]."</td>
                                                <td> ".$row["ine"]."</td>
                                                <td> ".$row["seguro"]."</td>
                                                <td> ".$row["licencia"]."</td>
                                                <td> ".$row["cursos"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                echo ("$html_per");  
                ?>
             </div>  
             <? 
           }
           // Vehículos
            if ($tipo == 2){
                mysqli_multi_query ($mysqli, "CALL visor_vehiculos (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                  <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Placas</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Color</th>
                                    <th scope='col'>Póliza</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>
                                                <td> ".$row["empresa"]."</td>                                                
                                                <td><a href=\"doc_vehiculos.php?placas=".$row["placas"]."&unidad=".$unidad_mina_sel."\" ><font color=blue><b>".$row["placas"]."</a></td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                                <td> ".$row["color"]."</td>
                                                <td> ".$row["poliza"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                echo ("$html_per");  
                ?>
             </div>  
             <? 
           }
           
           // Herramientas
            if ($tipo == 3){
                mysqli_multi_query ($mysqli, "CALL visor_herramientas (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                  <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["nombre"]."</td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                echo ("$html_per");  
                ?>
             </div>  
             <? 
           }
            
              
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
         
          

